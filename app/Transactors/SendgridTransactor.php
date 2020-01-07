<?php
declare(strict_types=1);

namespace App\Transactors;

use Exception;
use Parsedown;
use SendGrid\Mail\Mail;
use App\DTO\Email;
use App\Enum\Email as EmailEnum;
use App\Enum\LogMessages;
use App\Enum\SendgridEmail;
use App\Connectors\SendgridConnector;

final class SendgridTransactor implements MailerTransactor
{
    use LogError;

    private $client;
    private $parsedown;
    private $email;
    private $emailDTO;

    /**
     * Config SendGrid Transactor
     *
     * @param SendgridConnector  $connector
     */
    public function __construct(SendgridConnector $connector)
    {
        $this->client = $connector->getClient();
        $this->email = new Mail();
        $this->parsedown = new Parsedown();
    }

    /**
     * Send email
     *
     * @param Email  $email
     * @return bool
     */
    public function send(Email $email): bool
    {
        try {
            $inputData = $email->get();
            $this->preparePayload($inputData);
            $response = $this->client->send($this->email);

            return $response->statusCode() === SendgridEmail::SUCCESSFUL_HTTP_CODE;
        } catch (Exception $exception) {
            $this->logError(LogMessages::SENDGRID_SEND_ERROR, $exception->getMessage());

            return false;
        }
    }

    private function preparePayload(array $inputData): void
    {
        try {
            foreach ($inputData as $key => $value) {
                $this->defineEmailPayload($key, $value);
            }
        } catch (Exception $exception) {
            $this->logError(
                LogMessages::SENDGRID_PAYLOAD_ERROR,
                $exception->getMessage()
            );
        }
    }

    private function defineEmailPayload(string $key, $value): void
    {
        $payloadOptions = [
            EmailEnum::FROM_KEY             => 'prepareFromPayload',
            EmailEnum::TO_KEY               => 'prepareToPayload',
            EmailEnum::SUBJECT_KEY          => 'prepareSubjectPayload',
            EmailEnum::TEXT_PART_KEY        => 'prepareTextPartPayload',
            EmailEnum::HTML_PART_KEY        => 'prepareHtmlPartPayload',
            EmailEnum::MARKDOWN_PART_KEY    => 'prepareMarkdownPartPayload',
        ];

        if (!array_key_exists($key, $payloadOptions)) {
            return;
        }

        $payloadOption = $payloadOptions[$key];
        $this->{$payloadOption}($value);
    }

    private function prepareFromPayload(array $userData): void
    {
        $this->email->setFrom(
            $userData[EmailEnum::EMAIL_KEY],
            $userData[EmailEnum::NAME_KEY]
        );
    }

    private function prepareToPayload(array $userData): void
    {
        foreach ($userData as $value) {
            $this->email->addTo(
                $value[EmailEnum::EMAIL_KEY],
                $value[EmailEnum::NAME_KEY]
            );
        }
    }

    private function prepareSubjectPayload(string $value): void
    {
        $this->email->setSubject($value);
    }

    private function prepareTextPartPayload(string $value): void
    {
        $this->email->addContent(
            'text/plain',
            $value
        );
    }

    private function prepareHtmlPartPayload(string $value): void
    {
        $this->email->addContent(
            'text/html',
            $value
        );
    }

    private function prepareMarkdownPartPayload(string $value): void
    {
        $this->parsedown->setSafeMode(true);
        $this->parsedown->setMarkupEscaped(true);

        $markdownValue = $this->parsedown->text($value);

        $this->email->addContent(
            'text/html',
            $markdownValue
        );
    }
}
