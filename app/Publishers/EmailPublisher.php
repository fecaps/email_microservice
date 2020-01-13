<?php

namespace App\Publishers;

use App\DTO\Email;
use App\Enum\LogMessages;
use App\Enum\EmailPublisher as EmailPublisherEnum;

final class EmailPublisher implements Publisher
{
    private $routingKeyName;
    private $exchangeName;
    private $queueName;

    /**
     * Define object properties based on env variables
     *
     * @param string  $routingKeyName
     * @param string  $exchangeName
     * @param string  $queueName
     */
    public function __construct(string $routingKeyName, string $exchangeName, string $queueName)
    {
        $this->routingKeyName = $routingKeyName;
        $this->exchangeName = $exchangeName;
        $this->queueName = $queueName;
    }

    /**
     * Execute the job.
     *
     * @param Email $emailDTO
     * @param int $retries
     * @return void
     */
    public function handle(Email $emailDTO, int $retries = EmailPublisherEnum::DEFAULT_RETRY): void
    {
        $publisherData = [
            'data' => $emailDTO->get(),
            'retries' => $retries,
        ];

        $stringMessage = json_encode($publisherData);

        \Amqp::publish(
            $this->routingKeyName,
            $stringMessage,
            [
                'queue' => $this->queueName,
                'exchange' => $this->exchangeName
            ]
        );

        $this->logPublish($stringMessage, $retries);
    }

    private function logPublish(string $stringMessage, int $retries): void
    {
        $logMessage = sprintf(
            LogMessages::MESSAGE_PUBLISHED,
            $stringMessage,
            $retries
        );

        \Log::channel('publisher')->info($logMessage);
    }
}
