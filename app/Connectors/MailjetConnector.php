<?php
declare(strict_types=1);

namespace App\Connectors;

use Mailjet\Client;

final class MailjetConnector implements Connector
{
    private $client;

    public function __construct(array $config)
    {
        $this->client = new Client(
            $config['key'],
            $config['secret'],
            $config['performer'],
            [
                'version' => $config['version']
            ]
        );
    }

    public function getClient(): Client
    {
        return $this->client;
    }
}
