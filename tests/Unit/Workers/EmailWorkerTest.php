<?php

namespace Tests\Unit\Workers;

use Tests\TestCase;
use App\Connectors\SendgridConnector;
use App\Transactors\SendgridTransactor;
use App\Workers\EmailWorker;
use App\Connectors\MailjetConnector;
use App\Transactors\MailjetTransactor;
use Tests\Unit\EmailDataCreator;

class EmailWorkerTest extends TestCase
{
    use EmailDataCreator;

    private $worker;

    protected function setUp(): void
    {
        parent::setUp();

        $mailjetConfig = config('services.mailjet');
        $mailjetConnector = new MailjetConnector($mailjetConfig);
        $mailjetTransactor = new MailjetTransactor($mailjetConnector);

        $sendgridConfig = config('services.sendgrid');
        $sendgridConnector = new SendgridConnector($sendgridConfig);
        $sendgridTransactor = new SendgridTransactor($sendgridConnector);

        $this->worker = new EmailWorker([$mailjetTransactor, $sendgridTransactor]);
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
        $emailDTO = $this->setEmailData($email);
        $send = $this->worker->sendEmail($emailDTO);

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
        $send = $this->worker->sendEmail($emailDTO);

        $this->assertFalse($send);
    }
}
