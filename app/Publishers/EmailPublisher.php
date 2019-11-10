<?php

namespace App\Publishers;

final class EmailPublisher
{
    private const DEFAULT_RETRY = 0;

    private $queueName;
    private $routingKeyName;

    public function __construct()
    {
        $this->queueName = env('RABBITMQ_QUEUE', 'default');
        $this->routingKeyName = env('RABBITMQ_ROUTING_KEY_NAME', 'default');
    }

    /**
     * Execute the job.
     *
     * @param array  $emailData
     * @return void
     */
    public function handle(array $emailData): void
    {
        $messageProperties = [
            'content_type'  => 'text/plain',
            'delivery_mode' => 2,
            'x-retries'     => self::DEFAULT_RETRY,
        ];

        $message = \Amqp::message(
            json_encode($emailData),
            $messageProperties
        );

        \Amqp::publish(
            $this->routingKeyName,
            $message,
            [
                'queue' => $this->queueName
            ]
        );
    }
}
