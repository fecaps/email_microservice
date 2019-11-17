<?php

namespace Tests\Unit\Transactors;

use Tests\TestCase;
use App\Connectors\SendgridConnector;
use App\Transactors\SendgridTransactor;

class SendgridTransactorTest extends TestCase
{
    private $transactor;

    protected function setUp(): void
    {
        parent::setUp();

        $config = config('services.sendgrid');
        $connector = new SendgridConnector($config);

        $this->transactor = new SendgridTransactor($connector);
    }

    /**
     * Instance Type Return test
     *
     * @return void
     */
    public function testReturnInstanceType(): void
    {
        $this->assertInstanceOf(SendgridTransactor::class, $this->transactor);
    }

    /**
     * Email Transactions test
     *
     * @dataProvider \Tests\Unit\DataProviders\ValidEmailsDataProvider::emails()
     * @param array  $email
     * @return void
     */
    public function testEmailTransactions(array $email): void
    {
        $this->transactor->preparePayload($email);
        $send = $this->transactor->send();

        $this->assertTrue($send);
    }

    /**
     * Email Invalid Transactions test
     *
     * @dataProvider \Tests\Unit\DataProviders\InvalidEmailsDataProvider::invalidEmails()
     * @param array  $email
     * @return void
     */
    public function testInvalidEmailTransactions(array $email): void
    {
        $this->transactor->preparePayload($email);
        $send = $this->transactor->send();

        $this->assertFalse($send);
    }
}
