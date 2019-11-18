<?php

namespace App\Publishers;

use App\Enum\EmailPublisher as EmailPublisherEnum;
use App\Enum\LogMessages;

final class EmailPublisher implements Publisher
{
    private $routingKeyName;
    private $exchangeName;
    private $queueName;

    /**
     * Define object properties based on env variables
     *
     */
    public function __construct()
    {
        $this->routingKeyName = env(
            'RABBITMQ_ROUTING_KEY_NAME',
            EmailPublisherEnum::DEFAULT_ROUTING_KEY
        );
        $this->exchangeName = env(
            'RABBITMQ_EXCHANGE_NAME',
            EmailPublisherEnum::DEFAULT_EXCHANGE
        );
        $this->queueName = env(
            'RABBITMQ_QUEUE',
            EmailPublisherEnum::DEFAULT_QUEUE
        );
    }

    /**
     * Execute the job.
     *
     * @param array  $emailData
     * @param int  $retries
     * @return void
     */
    public function handle(array $emailData, int $retries = EmailPublisherEnum::DEFAULT_RETRY): void
    {
        $publisherData = [
            'data'      => $emailData,
            'retries'   => $retries,
        ];

        $stringMessage = json_encode($publisherData);

        \Amqp::publish(
            $this->routingKeyName,
            $stringMessage,
            [
                'queue'     => $this->queueName,
                'exchange'  => $this->exchangeName
            ]
        );

        $logMessage = sprintf(
            LogMessages::MESSAGE_PUBLISHED,
            $stringMessage,
            $retries
        );

        \Log::channel('publisher')->info($logMessage);
    }
}
