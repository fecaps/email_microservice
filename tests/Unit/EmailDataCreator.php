<?php
declare(strict_types=1);

namespace Tests\Unit;

use App\DTO\Email;
use App\DTO\EmailDTO;
use App\Enum\Email as EmailEnum;

trait EmailDataCreator
{
    protected function setEmailData(array $email): Email
    {
        $emailDTO = new EmailDTO();

        if (array_key_exists(EmailEnum::FROM_KEY, $email)) {
            $emailDTO->defineFrom($email[EmailEnum::FROM_KEY]);
        }

        if (array_key_exists(EmailEnum::TO_KEY, $email)) {
            $emailDTO->defineTo($email[EmailEnum::TO_KEY]);
        }

        if (array_key_exists(EmailEnum::SUBJECT_KEY, $email)) {
            $emailDTO->defineSubject($email[EmailEnum::SUBJECT_KEY]);
        }

        if (array_key_exists(EmailEnum::TEXT_PART_KEY, $email)) {
            $emailDTO->defineTextPart($email[EmailEnum::TEXT_PART_KEY]);
        }

        if (array_key_exists(EmailEnum::HTML_PART_KEY, $email)) {
            $emailDTO->defineHtmlPart($email[EmailEnum::HTML_PART_KEY]);
        }

        if (array_key_exists(EmailEnum::MARKDOWN_PART_KEY, $email)) {
            $emailDTO->defineMarkdownPart($email[EmailEnum::MARKDOWN_PART_KEY]);
        }

        return $emailDTO;
    }
}
