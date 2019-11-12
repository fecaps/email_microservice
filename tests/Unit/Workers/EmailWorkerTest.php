<?php

namespace Tests\Unit\Transactors;

use App\Connectors\SendgridConnector;
use App\Transactors\SendgridTransactor;
use App\Workers\EmailWorker;
use Tests\TestCase;
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
    public function testReturnInstanceType()
    {
        $this->assertInstanceOf(EmailWorker::class, $this->worker);
    }

    /**
     * Email Transactions test
     *
     * @dataProvider \Tests\Unit\Transactors\ValidEmailsDataProvider::emails()
     * @param array  $email
     * @return void
     */
    public function testEmailTransactions(array $email) {
        $send = $this->worker->sendEmail($email);

        $this->assertTrue($send);
    }

    /**
     * Email Invalid Transactions test
     *
     * @dataProvider \Tests\Unit\Transactors\InvalidEmailsDataProvider::invalidEmails()
     * @param array  $email
     * @return void
     */
    public function testInvalidEmailTransactions(array $email) {
        $send = $this->worker->sendEmail($email);

        $this->assertFalse($send);
    }
}