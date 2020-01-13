<?php

namespace Tests\Unit\Transactors;

use Tests\TestCase;
use Tests\Unit\EmailDataCreator;
use App\Connectors\MailjetConnector;
use App\Transactors\MailjetTransactor;

class MailjetTransactorTest extends TestCase
{
    use EmailDataCreator;

    private $transactor;

    protected function setUp(): void
    {
        parent::setUp();

        $config = config('services.mailjet');
        $connector = new MailjetConnector($config);
        $this->transactor = new MailjetTransactor($connector);
    }

    /**
     * Instance Type Return test
     *
     * @return void
     */
    public function testReturnInstanceType(): void
    {
        $this->assertInstanceOf(MailjetTransactor::class, $this->transactor);
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
