<?php

namespace App\Console\Commands;

use App\Enum\LogMessages;
use Illuminate\Console\Command;
use App\Enum\EmailConsumer as EmailConsumerEnum;
use App\Publishers\EmailPublisher;
use App\Queue;
use App\Workers\EmailWorker;

final class EmailConsumer extends Command
{
    private $queue;
    private $queueName;
    private $maxRetries;
    private $publisher;
    private $worker;

    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'consumer:emails';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Consumers emails from queue';

    /**
     * Create a new command instance.
     *
     * @param EmailPublisher  $publisher
     * @param EmailWorker  $worker
     * @param Queue  $queue
     * @return void
     */
    public function __construct(
        EmailPublisher $publisher,
        EmailWorker $worker,
        Queue  $queue
    ) {
        parent::__construct();

        $this->queueName = env(
            'RABBITMQ_QUEUE',
            EmailConsumerEnum::DEFAULT_QUEUE
        );

        $this->maxRetries = (int) (env(
            'RABBITMQ_MAXIMUM_RETRIES',
            EmailConsumerEnum::DEFAULT_MAX_RETRIES
        ));

        $this->publisher = $publisher;
        $this->worker = $worker;
        $this->queue = $queue;
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle(): void
    {
        try {
            $this->info(EmailConsumerEnum::CONSUMER_START);

            \Amqp::consume($this->queueName, function ($message, $resolver) {
                $this->consumeMessage($resolver, $message);
            });
        } catch (\Exception $exception) {
            $this->logConsumerError($exception);
        }
    }

    private function consumeMessage($resolver, $message): void
    {
        $arrayData = $this->defineArrayData($message);

        $this->queue->updateStatusToBounced($arrayData['data']['id']);

        try {
            $data = $arrayData[EmailConsumerEnum::DATA_KEY];
            $retries = $arrayData[EmailConsumerEnum::RETRIES_KEY];

            if (isset($retries) && $retries >= $this->maxRetries) {
                $this->cancelMessage($resolver, $message, $arrayData);
                return;
            }

            $this->resolveMessage($resolver, $message, $data, $arrayData);
        } catch (\Exception $exception) {
            $this->retryMessage($resolver, $message);
        }
    }

    private function cancelMessage($resolver, $message, array $arrayData): void
    {
        $this->info(EmailConsumerEnum::CONSUMER_REMOVED);
        $resolver->reject($message);

        $logMessage = sprintf(
            LogMessages::MESSAGE_REMOVED,
            json_encode($arrayData)
        );
        \Log::consumer('consumer')->info($logMessage);

        $this->queue->updateStatusToFailed($arrayData['data']['id']);
    }

    private function logConsumerError(\Exception $exception): void
    {
        $error = sprintf(
            '%s: %s',
            EmailConsumerEnum::CONSUMER_ERROR,
            $exception->getMessage()
        );

        $this->error($error);
    }

    private function resolveMessage($resolver, $message, array $data, array $arrayData): void
    {
        $messageSent = $this->worker->sendEmail($data);

        if (!$messageSent) {
            $this->retryMessage($resolver, $message);
            return;
        }

        $resolver->acknowledge($message);

        $logMessage = sprintf(
            LogMessages::MESSAGE_RESOLVED,
            json_encode($arrayData)
        );
        \Log::channel('consumer')->info($logMessage);

        $this->queue->updateStatusToDelivered($arrayData['data']['id']);
        $this->info(EmailConsumerEnum::CONSUMER_ACK);
    }

    private function retryMessage($resolver, $message): void
    {
        $resolver->reject($message);

        $this->info(EmailConsumerEnum::CONSUMER_NACK);

        $arrayData = $this->defineArrayData($message);

        $retriesPerformedMessage = sprintf(
            '%s: %s',
            EmailConsumerEnum::CONSUMER_RETRIES,
            $arrayData[EmailConsumerEnum::RETRIES_KEY]
        );

        $this->info($retriesPerformedMessage);

        $this->republishMessage($arrayData);
    }

    private function republishMessage(array $arrayData): void
    {
        $data = $arrayData[EmailConsumerEnum::DATA_KEY];
        $retries = $arrayData[EmailConsumerEnum::RETRIES_KEY];

        $this->publisher->handle($data, ++$retries);
    }

    private function defineArrayData($message): array
    {
        $body = (array)($message->body);
        return json_decode($body[0], true);
    }
}
