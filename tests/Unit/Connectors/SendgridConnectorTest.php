<?php

namespace Tests\Unit\Connectors;

use Tests\TestCase;
use SendGrid;
use App\Connectors\SendgridConnector;

class SendgridConnectorTest extends TestCase
{
    /**
     * Instance Type Return test
     *
     * @return void
     */
    public function testReturnInstanceType(): void
    {
        $config = config('services.sendgrid');
        $sendgrid = new SendgridConnector($config);
        $instanceType = $sendgrid->getClient();

        $this->assertInstanceOf(SendGrid::class, $instanceType);
    }
}
