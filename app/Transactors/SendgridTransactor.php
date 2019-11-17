<?php
declare(strict_types=1);

namespace App\Transactors;

use Exception;
use SendGrid\Mail\Mail;
use Parsedown;
use App\Connectors\SendgridConnector;
use App\Enum\Email;
use App\Enum\SendgridEmail;

final class SendgridTransactor extends Transactor
{
    private $client;
    private $parsedown;
    private $email;

    public function __construct(SendgridConnector $sendgridConnector)
    {
        $this->client = $sendgridConnector->getClient();
        $this->email = new Mail();
        $this->parsedown = new Parsedown();
    }

    public function preparePayload(array $inputData): void
    {
        try {
            foreach ($inputData as $key => $value) {
                $this->defineEmailPayload($key, $value);
            }
        } catch (Exception $exception) {
            echo $exception->getMessage();
        }
    }

    private function defineEmailPayload(string $key, $value): void
    {
        $payloadOptions = [
            Email::FROM_KEY             => 'prepareFromPayload',
            Email::TO_KEY               => 'prepareToPayload',
            Email::SUBJECT_KEY          => 'prepareSubjectPayload',
            Email::TEXT_PART_KEY        => 'prepareTextPartPayload',
            Email::HTML_PART_KEY        => 'prepareHtmlPartPayload',
            Email::MARKDOWN_PART_KEY    => 'prepareMarkdownPartPayload',
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
            $userData[Email::EMAIL_KEY],
            $userData[Email::NAME_KEY]
        );
    }

    private function prepareToPayload(array $userData): void
    {
        foreach ($userData as $value) {
            $this->email->addTo(
                $value[Email::EMAIL_KEY],
                $value[Email::NAME_KEY]
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

    public function send(): bool
    {
        try {
            $response = $this->client->send($this->email);
            $success = $response->statusCode() === SendgridEmail::SUCCESSFUL_HTTP_CODE;

            return $success ?: $this->sendTrigger();
        } catch (Exception $exception) {
            return $this->sendTrigger();
        }
    }

    public function sendTrigger(): bool
    {
        return false;
    }
}
