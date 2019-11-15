<?php

namespace App\Publishers;

use App\Enum\EmailPublisher as EmailPublisherEnum;

final class EmailPublisher
{
    private $routingKeyName;
    private $exchangeName;
    private $queueName;

    public function __construct()
    {
        $this->routingKeyName = env('RABBITMQ_ROUTING_KEY_NAME', EmailPublisherEnum::DEFAULT_ROUTING_KEY);
        $this->exchangeName = env('RABBITMQ_EXCHANGE_NAME', EmailPublisherEnum::DEFAULT_EXCHANGE);
        $this->queueName = env('RABBITMQ_QUEUE', EmailPublisherEnum::DEFAULT_QUEUE);
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
            'retries' => $retries
        ];

        \Amqp::publish(
            $this->routingKeyName,
            json_encode($publisherData),
            [
                'queue'     => $this->queueName,
                'exchange'  => $this->exchangeName
            ]
        );
    }
}
