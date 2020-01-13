<?php
declare(strict_types=1);

namespace App\Console\Commands\Consumer;

use App\DTO\Email;
use App\Enum\Email as EmailEnum;
use App\Enum\EmailConsumer as EmailConsumerEnum;

trait EmailConsumerData
{
    protected function saveEmailData(Email $emailDTO, array $data): void
    {
        $emailData = $data[EmailConsumerEnum::DATA_KEY];

        if (array_key_exists(EmailEnum::ID_KEY, $emailData)) {
            $emailDTO->defineId($emailData[EmailEnum::ID_KEY]);
        }

        $emailDTO->defineFrom($emailData[EmailEnum::FROM_KEY]);
        $emailDTO->defineTo($emailData[EmailEnum::TO_KEY]);
        $emailDTO->defineSubject($emailData[EmailEnum::SUBJECT_KEY]);

        $this->defineTextPart($emailDTO, $emailData);
        $this->defineHtmlPart($emailDTO, $emailData);
        $this->defineMarkdownPart($emailDTO, $emailData);
    }

    private function defineTextPart(Email $emailDTO, array $email): void
    {
        if (array_key_exists(EmailEnum::TEXT_PART_KEY, $email)) {
            $emailDTO->defineTextPart($email[EmailEnum::TEXT_PART_KEY]);
        }
    }

    private function defineHtmlPart(Email $emailDTO, array $email): void
    {
        if (array_key_exists(EmailEnum::HTML_PART_KEY, $email)) {
            $emailDTO->defineHtmlPart($email[EmailEnum::HTML_PART_KEY]);
        }
    }

    private function defineMarkdownPart(Email $emailDTO, array $email): void
    {
        if (array_key_exists(EmailEnum::MARKDOWN_PART_KEY, $email)) {
            $emailDTO->defineMarkdownPart($email[EmailEnum::MARKDOWN_PART_KEY]);
        }
    }
}
