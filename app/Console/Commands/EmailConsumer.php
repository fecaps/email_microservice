<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Enum\EmailConsumer as EmailConsumerEnum;
use App\Publishers\EmailPublisher;
use App\Workers\EmailWorker;

final class EmailConsumer extends Command
{
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
     * @return void
     */
    public function __construct(EmailPublisher $publisher, EmailWorker $worker)
    {
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

        try {
            $data = $arrayData[EmailConsumerEnum::DATA_KEY];
            $retries = $arrayData[EmailConsumerEnum::RETRIES_KEY];

            if (isset($retries) && $retries >= $this->maxRetries) {
                $this->info(EmailConsumerEnum::CONSUMER_REMOVED);
                $resolver->reject($message);
                return;
            }

            $messageSent = $this->worker->sendEmail($data);

            if (!$messageSent) {
                $this->retryMessage($resolver, $message);
                return;
            }

            $resolver->acknowledge($message);
            $this->info(EmailConsumerEnum::CONSUMER_ACK);
        } catch (\Exception $exception) {
            $this->retryMessage($resolver, $message);
        }
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
