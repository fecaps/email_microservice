<?php
declare(strict_types=1);

namespace App\Connectors;

use Mailjet\Client;

final class MailjetConnector implements Connector
{
    private $client;

    /**
     * Create Mailjet client connection.
     *
     * @param array  $config
     */
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

    /**
     * Get Mailjet client connection.
     *
     * @return Client
     */
    public function getClient(): Client
    {
        return $this->client;
    }
}
