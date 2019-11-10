<?php
declare(strict_types=1);

namespace App\Connectors;

use SendGrid;

final class SendgridConnector implements Connector
{
    private $client;

    public function __construct(array $config)
    {
        $this->client = new SendGrid($config['key']);
    }

    public function getClient(): SendGrid
    {
        return $this->client;
    }
}
