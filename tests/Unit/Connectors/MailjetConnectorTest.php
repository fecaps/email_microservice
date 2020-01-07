<?php

namespace Tests\Unit\Connectors;

use Tests\TestCase;
use Mailjet\Client;
use App\Connectors\MailjetConnector;

class MailjetConnectorTest extends TestCase
{
    /**
     * Instance Type Return test
     *
     * @return void
     */
    public function testReturnInstanceType(): void
    {
        $config = config('services.mailjet');
        $mailjet = new MailjetConnector($config);
        $instanceType = $mailjet->getClient();

        $this->assertInstanceOf(Client::class, $instanceType);
    }
}
