<?php

namespace Tests\Unit\Transactors;

use Tests\TestCase;
use App\Connectors\MailjetConnector;
use App\Transactors\MailjetTransactor;

class MailjetTransactorTest extends TestCase
{
    private const MESSAGES_PATH =  'Messages';
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
    public function testReturnInstanceType()
    {
        $this->assertInstanceOf(MailjetTransactor::class, $this->transactor);
    }

    /**
     * Payload Preparation test
     *
     * @dataProvider emails
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
     * @dataProvider emails
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
     * @dataProvider emails
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
     * @dataProvider emails
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
     * @dataProvider emails
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
     * @dataProvider emails
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
     * @dataProvider emails
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
     * @dataProvider invalidEmails
     * @param array  $email
     * @return void
     */
    public function testInvalidEmailTransactions(array $email) {
        $this->transactor->preparePayload($email);
        $send = $this->transactor->send();

        $this->assertFalse($send);
    }

    public function emails(): array
    {
        return [
            [
                [
                    'from' => [
                        'email' => 'fellipecapelli@gmail.com',
                        'name' => 'Fellipe Capelli'
                    ],
                    'to' => [
                        [
                            'email' => 'fellipe.capelli@outlook.com',
                            'name' => 'Fellipe C. Fregoneze'
                        ]
                    ],
                    'subject' => 'Mailjet Transactor 1',
                    'textPart' => 'Hello, text part 1',
                    'htmlPart' => 'Hello, HTML part 1',
                ]
            ],
            [

                [
                    'from' => [
                        'email' => 'fellipecapelli@gmail.com',
                        'name' => 'Fellipe Capelli'
                    ],
                    'to' => [
                        [
                            'email' => 'fellipe.capelli@outlook.com',
                            'name' => 'Fellipe C. Fregoneze'
                        ]
                    ],
                    'subject' => 'Mailjet Transactor 2',
                    'textPart' => 'Hello, text part 2',
                    'htmlPart' => 'Hello, HTML part 2',
                ]
            ]
        ];
    }

    public function invalidEmails(): array
    {
        return [
            [
                [
                    'from' => [
                        'email' => 'fellipe',
                        'name' => 'Fellipe Capelli'
                    ],
                    'to' => [
                        [
                            'email' => 'fellipe.capelli@outlook.com',
                            'name' => 'Fellipe C. Fregoneze'
                        ]
                    ],
                    'subject' => 'Mailjet Transactor 1',
                    'textPart' => 'Hello, text part 1',
                    'htmlPart' => 'Hello, HTML part 1',
                ]
            ],
            [

                [
                    'from' => [
                        'email' => 'fellipecapelli@gmail.com',
                        'name' => 'Fellipe Capelli'
                    ],
                    'to' => [
                        [
                            'email' => 'fellipe',
                            'name' => 'Fellipe C. Fregoneze'
                        ]
                    ],
                    'subject' => 'Mailjet Transactor 2',
                    'textPart' => 'Hello, text part 2',
                    'htmlPart' => 'Hello, HTML part 2',
                ]
            ]
        ];
    }
}
