<?php

namespace App\Console\Commands\Consumer;

use Illuminate\Console\Command;
use App\Queue;
use App\DTO\Email;
use App\Workers\Worker;
use App\Publishers\Publisher;
use App\Enum\EmailConsumer as EmailConsumerEnum;

final class EmailConsumer extends Command
{
    use EmailConsumerData;
    use EmailConsumerResolver;
    use EmailConsumerRetry;
    use EmailConsumerCancel;

    private $publisher;
    private $worker;
    private $queue;
    private $emailDTO;
    private $queueName;
    private $maxRetries;

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
     * @param Publisher  $publisher
     * @param Worker  $worker
     * @param Queue  $queue
     * @param Email  $emailDTO
     * @param string  $queueName
     * @param int  $maxRetries
     * @return void
     */
    public function __construct(
        Publisher $publisher,
        Worker $worker,
        Queue  $queue,
        Email $emailDTO,
        string $queueName,
        int $maxRetries
    ) {
        parent::__construct();

        $this->publisher = $publisher;
        $this->worker = $worker;
        $this->queue = $queue;
        $this->emailDTO = $emailDTO;
        $this->queueName = $queueName;
        $this->maxRetries = $maxRetries;
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
                $messageData = $this->getMessageData($message);
                $this->saveEmailData($this->emailDTO, $messageData);
                $retries = $messageData[EmailConsumerEnum::RETRIES_KEY];

                if ($this->emailDTO->getId()) {
                    $this->queue->updateStatusToBounced($this->emailDTO->getId());
                }

                $this->consumeMessage($resolver, $message, $retries);
            });
        } catch (\Exception $exception) {
            $this->logConsumerError($exception);
        }
    }

    private function consumeMessage($resolver, $message, int $retries): void
    {
        try {
            if (isset($retries) && $retries >= $this->maxRetries) {
                $this->cancelMessage($this->emailDTO, $this->queue, $resolver, $message);
                return;
            }

            $this->resolveMessage(
                $this->worker,
                $this->emailDTO,
                $this->queue,
                $this->publisher,
                $retries,
                $resolver,
                $message
            );
        } catch (\Exception $exception) {
            $this->retryMessage($this->publisher, $this->emailDTO, $retries, $resolver, $message);
        }
    }

    private function getMessageData($message): array
    {
        $body = (array)($message->body);
        return json_decode($body[0], true);
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
}
