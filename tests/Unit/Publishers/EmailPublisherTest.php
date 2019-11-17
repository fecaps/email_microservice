<?php

namespace Tests\Unit\Publishers;

use Tests\TestCase;
use App\Publishers\EmailPublisher;

class EmailPublisherTest extends TestCase
{
    private $publisher;

    protected function setUp(): void
    {
        parent::setUp();

        $this->publisher = new EmailPublisher();
    }

    /**
     * Email Publisher test
     *
     * @dataProvider \Tests\Unit\DataProviders\ValidEmailsDataProvider::emails()
     * @param array  $email
     * @return void
     */
    public function testEmailTransactions(array $email): void {
        $this->publisher->handle($email);
        $this->assertInstanceOf(EmailPublisher::class, $this->publisher);
    }
}
