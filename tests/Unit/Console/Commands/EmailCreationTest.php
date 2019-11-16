<?php

namespace Tests\Unit\Console\Commands;

use Tests\TestCase;
use App\Enum\EmailCreation;

class EmailCreationTest extends TestCase
{
    /**
     * Email Creation Console test
     *
     * @return void
     */
    public function testEmailCreation() {

        $this->artisan('create:email')
            ->expectsOutput(EmailCreation::START_MESSAGE)
            ->expectsQuestion(EmailCreation::FROM_EMAIL, 'fellipecapelli@gmail.com')
            ->expectsQuestion(EmailCreation::FROM_NAME, 'Fellipe')
            ->expectsQuestion(EmailCreation::TO_EMAIL, 'fellipe.capelli@outlook.com')
            ->expectsQuestion(EmailCreation::TO_NAME, 'Fellipe Capelli')
            ->expectsQuestion(EmailCreation::SUBJECT, 'hello - subject')
            ->expectsQuestion(EmailCreation::TEXT_PART, 'hello - text part')
            ->expectsQuestion(EmailCreation::HTML_PART, '<br>Hello<br><br>Html part')
            ->expectsOutput(EmailCreation::END_MESSAGE);
    }
}
