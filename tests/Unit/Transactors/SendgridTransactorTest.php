<?php

namespace Tests\Unit\Transactors;

use Tests\TestCase;
use Tests\Unit\EmailDataCreator;
use App\Connectors\SendgridConnector;
use App\Transactors\SendgridTransactor;

class SendgridTransactorTest extends TestCase
{
    use EmailDataCreator;

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
        $emailDTO = $this->setEmailData($email);
        $send = $this->transactor->send($emailDTO);

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
        $emailDTO = $this->setEmailData($email);
        $send = $this->transactor->send($emailDTO);

        $this->assertFalse($send);
    }
}
