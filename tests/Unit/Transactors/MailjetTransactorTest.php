<?php

namespace Tests\Unit\Transactors;

use Tests\TestCase;
use App\Connectors\SendgridConnector;
use App\Transactors\SendgridTransactor;
use App\Connectors\MailjetConnector;
use App\Transactors\MailjetTransactor;

class MailjetTransactorTest extends TestCase
{
    private const MESSAGES_PATH =  'Messages';
    private $transactor;

    protected function setUp(): void
    {
        parent::setUp();

        $mailjetConfig = config('services.mailjet');
        $mailjetConnector = new MailjetConnector($mailjetConfig);

        $sendgridConfig = config('services.sendgrid');
        $sendgridConnector = new SendgridConnector($sendgridConfig);
        $sendgridTransactor = new SendgridTransactor($sendgridConnector);

        $this->transactor = new MailjetTransactor($mailjetConnector, $sendgridTransactor);
    }

    /**
     * Instance Type Return test
     *
     * @return void
     */
    public function testReturnInstanceType()
    {
        $this->assertInstanceOf(MailjetTransactor::class, $this->transactor);
    }

    /**
     * Payload Preparation test
     *
     * @dataProvider \Tests\Unit\DataProviders\ValidEmailsDataProvider::emails()
     * @param array  $email
     * @return void
     */
    public function testPayloadIsPrepared(array $email)
    {
        $payload = $this->transactor->preparePayload($email);

        $this->assertIsArray($payload);
        $this->assertArrayHasKey(self::MESSAGES_PATH, $payload);
        $this->assertIsArray($payload[self::MESSAGES_PATH]);
        $this->assertCount(5, $payload[self::MESSAGES_PATH][0]);
    }

    /**
     * From Payload Preparation test
     *
     * @dataProvider \Tests\Unit\DataProviders\ValidEmailsDataProvider::emails()
     * @param array  $email
     * @return void
     */
    public function testFromPropertyFromPayload(array $email) {
        $payload = $this->transactor->preparePayload($email);
        $fromPayload = $payload[self::MESSAGES_PATH][0];

        $this->assertArrayHasKey('From', $fromPayload);
    }

    /**
     * To Payload Preparation test
     *
     * @dataProvider \Tests\Unit\DataProviders\ValidEmailsDataProvider::emails()
     * @param array  $email
     * @return void
     */
    public function testToPropertyFromPayload(array $email) {
        $payload = $this->transactor->preparePayload($email);
        $toPayload = $payload[self::MESSAGES_PATH][0];

        $this->assertArrayHasKey('To', $toPayload);
    }

    /**
     * Subject Payload Preparation test
     *
     * @dataProvider \Tests\Unit\DataProviders\ValidEmailsDataProvider::emails()
     * @param array  $email
     * @return void
     */
    public function testSubjectPropertyFromPayload(array $email) {
        $payload = $this->transactor->preparePayload($email);
        $subjectPayload = $payload[self::MESSAGES_PATH][0];

        $this->assertArrayHasKey('Subject', $subjectPayload);
    }

    /**
     * TextPart Payload Preparation test
     *
     * @dataProvider \Tests\Unit\DataProviders\ValidEmailsDataProvider::emails()
     * @param array  $email
     * @return void
     */
    public function testTextPartPropertyFromPayload(array $email) {
        $payload = $this->transactor->preparePayload($email);
        $textPartPayload = $payload[self::MESSAGES_PATH][0];

        $this->assertArrayHasKey('TextPart', $textPartPayload);
    }

    /**
     * HtmlPart Payload Preparation test
     *
     * @dataProvider \Tests\Unit\DataProviders\ValidEmailsDataProvider::emails()
     * @param array  $email
     * @return void
     */
    public function testHtmlPartPropertyFromPayload(array $email) {
        $payload = $this->transactor->preparePayload($email);
        $htmlPartPayload = $payload[self::MESSAGES_PATH][0];

        $this->assertArrayHasKey('HTMLPart', $htmlPartPayload);
    }

    /**
     * Email Transactions test
     *
     * @dataProvider \Tests\Unit\DataProviders\ValidEmailsDataProvider::emails()
     * @param array  $email
     * @return void
     */
    public function testEmailTransactions(array $email) {
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
    public function testInvalidEmailTransactions(array $email) {
        $this->transactor->preparePayload($email);
        $send = $this->transactor->send();

        $this->assertFalse($send);
    }
}
