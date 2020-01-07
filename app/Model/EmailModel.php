<?php
declare(strict_types=1);

namespace App\Model;

use App\Queue;
use App\Enum\LogMessages;
use App\Enum\Email as EmailEnum;
use App\DTO\Email as EmailDTO;
use App\Publishers\Publisher;

final class EmailModel implements Email
{
    private $emailDTO;
    private $queue;
    private $publisher;

    public function __construct(EmailDTO $emailDTO, Queue $queue, Publisher $publisher)
    {
        $this->emailDTO = $emailDTO;
        $this->queue = $queue;
        $this->publisher = $publisher;
    }

    public function storeEmail(array $email): array
    {
        \Log::channel('publisher')->info(LogMessages::START_WEB);

        $id = $this->queue->addToQueue($email);

        $this->emailDTO->defineId($id);
        $this->emailDTO->defineFrom($email[EmailEnum::FROM_KEY]);
        $this->emailDTO->defineTo($email[EmailEnum::TO_KEY]);
        $this->emailDTO->defineSubject($email[EmailEnum::SUBJECT_KEY]);

        $this->defineTextPart($email);
        $this->defineHtmlPart($email);
        $this->defineMarkdownPart($email);

        $this->publisher->handle($this->emailDTO);

        return $this->emailDTO->get();
    }

    private function defineTextPart(array $email): void
    {
        if (array_key_exists(EmailEnum::TEXT_PART_KEY, $email)) {
            $this->emailDTO->defineTextPart($email[EmailEnum::TEXT_PART_KEY]);
        }
    }

    private function defineHtmlPart(array $email): void
    {
        if (array_key_exists(EmailEnum::HTML_PART_KEY, $email)) {
            $this->emailDTO->defineHtmlPart($email[EmailEnum::HTML_PART_KEY]);
        }
    }

    private function defineMarkdownPart(array $email): void
    {
        if (array_key_exists(EmailEnum::MARKDOWN_PART_KEY, $email)) {
            $this->emailDTO->defineMarkdownPart($email[EmailEnum::MARKDOWN_PART_KEY]);
        }
    }
}
