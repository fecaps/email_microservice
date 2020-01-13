<?php

namespace Tests\Unit\Console\Commands\Creator;

use Tests\TestCase;
use App\Enum\EmailCreation;

class EmailCreationTest extends TestCase
{
    /**
     * Invalid Email Creation Console test
     *
     * @return void
     */
    public function testInvalidEmailCreation(): void
    {
        $this->artisan('create:email')
            ->expectsOutput(EmailCreation::START_MESSAGE)
            ->expectsQuestion(EmailCreation::FROM_EMAIL_MESSAGE, '')
            ->expectsQuestion(EmailCreation::FROM_NAME_MESSAGE, '')
            ->expectsQuestion(EmailCreation::TO_EMAIL_MESSAGE, '')
            ->expectsQuestion(EmailCreation::TO_NAME_MESSAGE, '')
            ->expectsQuestion(EmailCreation::SUBJECT_MESSAGE, '')
            ->expectsQuestion(EmailCreation::TEXT_PART_MESSAGE, '')
            ->expectsQuestion(EmailCreation::HTML_PART_MESSAGE, '')
            ->expectsQuestion(EmailCreation::MARKDOWN_PART_MESSAGE, '')
            ->expectsOutput(EmailCreation::INVALID_MESSAGE)
            ->expectsOutput('The from email field is required.')
            ->expectsOutput('The from name field is required.')
            ->expectsOutput('The to email field is required.')
            ->expectsOutput('The to name field is required.')
            ->expectsOutput('The subject field is required.');
    }

    /**
     * Email Creation with Text Part - Console test
     *
     * @return void
     */
    public function testEmailCreationWithText(): void
    {
        $this->artisan('create:email')
            ->expectsOutput(EmailCreation::START_MESSAGE)
            ->expectsQuestion(EmailCreation::FROM_EMAIL_MESSAGE, 'fellipecapelli@gmail.com')
            ->expectsQuestion(EmailCreation::FROM_NAME_MESSAGE, 'Fellipe')
            ->expectsQuestion(EmailCreation::TO_EMAIL_MESSAGE, 'fellipe.capelli@outlook.com')
            ->expectsQuestion(EmailCreation::TO_NAME_MESSAGE, 'Fellipe Capelli')
            ->expectsQuestion(EmailCreation::SUBJECT_MESSAGE, 'hello - subject')
            ->expectsQuestion(EmailCreation::TEXT_PART_MESSAGE, 'hello - text part')
            ->expectsQuestion(EmailCreation::HTML_PART_MESSAGE, '')
            ->expectsQuestion(EmailCreation::MARKDOWN_PART_MESSAGE, '')
            ->expectsOutput(EmailCreation::END_MESSAGE);
    }

    /**
     * Email Creation with Html Part - Console test
     *
     * @return void
     */
    public function testEmailCreationWithHtml(): void
    {
        $this->artisan('create:email')
            ->expectsOutput(EmailCreation::START_MESSAGE)
            ->expectsQuestion(EmailCreation::FROM_EMAIL_MESSAGE, 'fellipecapelli@gmail.com')
            ->expectsQuestion(EmailCreation::FROM_NAME_MESSAGE, 'Fellipe')
            ->expectsQuestion(EmailCreation::TO_EMAIL_MESSAGE, 'fellipe.capelli@outlook.com')
            ->expectsQuestion(EmailCreation::TO_NAME_MESSAGE, 'Fellipe Capelli')
            ->expectsQuestion(EmailCreation::SUBJECT_MESSAGE, 'hello - subject')
            ->expectsQuestion(EmailCreation::TEXT_PART_MESSAGE, '')
            ->expectsQuestion(EmailCreation::HTML_PART_MESSAGE, '<br>Hello<br><br>Html part')
            ->expectsQuestion(EmailCreation::MARKDOWN_PART_MESSAGE, '')
            ->expectsOutput(EmailCreation::END_MESSAGE);
    }

    /**
     * Email Creation with Markdown Part - Console test
     *
     * @return void
     */
    public function testEmailCreationWithMarkdown(): void
    {
        $this->artisan('create:email')
            ->expectsOutput(EmailCreation::START_MESSAGE)
            ->expectsQuestion(EmailCreation::FROM_EMAIL_MESSAGE, 'fellipecapelli@gmail.com')
            ->expectsQuestion(EmailCreation::FROM_NAME_MESSAGE, 'Fellipe')
            ->expectsQuestion(EmailCreation::TO_EMAIL_MESSAGE, 'fellipe.capelli@outlook.com')
            ->expectsQuestion(EmailCreation::TO_NAME_MESSAGE, 'Fellipe Capelli')
            ->expectsQuestion(EmailCreation::SUBJECT_MESSAGE, 'hello - subject')
            ->expectsQuestion(EmailCreation::TEXT_PART_MESSAGE, '')
            ->expectsQuestion(EmailCreation::HTML_PART_MESSAGE, '')
            ->expectsQuestion(EmailCreation::MARKDOWN_PART_MESSAGE, 'Hello, markdown part')
            ->expectsOutput(EmailCreation::END_MESSAGE);
    }
}
