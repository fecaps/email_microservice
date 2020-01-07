<?php

namespace Tests\Unit\Publishers;

use Tests\TestCase;
use Tests\Unit\EmailDataCreator;
use App\Publishers\EmailPublisher;

class EmailPublisherTest extends TestCase
{
    use EmailDataCreator;

    /**
     * Email Publisher test
     *
     * @dataProvider \Tests\Unit\DataProviders\ValidEmailsDataProvider::emails()
     * @param array  $email
     * @return void
     */
    public function testEmailTransactions(array $email): void {
        $emailDTO = $this->setEmailData($email);

        $testName = 'test';
        $publisher = new EmailPublisher($testName, $testName, $testName);
        $publisher->handle($emailDTO);

        $this->assertInstanceOf(EmailPublisher::class, $publisher);
    }
}
