<?php

namespace Tests\Unit\Workers;

use Tests\TestCase;
use App\Connectors\SendgridConnector;
use App\Transactors\SendgridTransactor;
use App\Workers\EmailWorker;
use App\Connectors\MailjetConnector;
use App\Transactors\MailjetTransactor;

class EmailWorkerTest extends TestCase
{
    private $worker;

    protected function setUp(): void
    {
        parent::setUp();

        $mailjetConfig = config('services.mailjet');
        $mailjetConnector = new MailjetConnector($mailjetConfig);

        $sendgridConfig = config('services.sendgrid');
        $sendgridConnector = new SendgridConnector($sendgridConfig);
        $sendgridTransactor = new SendgridTransactor($sendgridConnector);

        $transactor = new MailjetTransactor($mailjetConnector, $sendgridTransactor);

        $this->worker = new EmailWorker($transactor);
    }

    /**
     * Instance Type Return test
     *
     * @return void
     */
    public function testReturnInstanceType(): void
    {
        $this->assertInstanceOf(EmailWorker::class, $this->worker);
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
        $send = $this->worker->sendEmail($email);
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
        $send = $this->worker->sendEmail($email);
        $this->assertFalse($send);
    }
}
