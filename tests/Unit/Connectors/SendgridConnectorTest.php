<?php

namespace Tests\Unit\Connectors;

use App\Connectors\SendgridConnector;
use Tests\TestCase;
use SendGrid;

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
