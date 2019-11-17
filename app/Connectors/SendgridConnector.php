<?php
declare(strict_types=1);

namespace App\Connectors;

use SendGrid;

final class SendgridConnector implements Connector
{
    private $client;

    /**
     * Create Sendgrid client connection.
     *
     * @param array  $config
     */
    public function __construct(array $config)
    {
        $this->client = new SendGrid($config['key']);
    }

    /**
     * Get SendGrid client connection.
     *
     * @return SendGrid
     */
    public function getClient(): SendGrid
    {
        return $this->client;
    }
}
