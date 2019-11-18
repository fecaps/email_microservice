<?php
declare(strict_types=1);

namespace App\Transactors;

use App\Enum\LogMessages;
use Parsedown;
use Mailjet\Resources;
use App\Connectors\MailjetConnector;
use App\Enum\Email;
use App\Enum\MailjetEmail;

final class MailjetTransactor extends Transactor
{
    private $client;
    private $sendgridTransactor;
    private $parsedown;
    private $messageBody = [];
    private $inputData;

    /**
     * Config Mailjet Transactor
     *
     * @param MailjetConnector  $maijetConnector
     * @param SendgridTransactor  $sendgridTransactor
     */
    public function __construct(
        MailjetConnector $maijetConnector,
        SendgridTransactor $sendgridTransactor
    ) {
        $this->client = $maijetConnector->getClient();
        $this->sendgridTransactor = $sendgridTransactor;
        $this->parsedown = new Parsedown();
    }

    /**
     * Prepare email payload
     *
     * @param array  $inputData
     * @return array
     */
    public function preparePayload(array $inputData): array
    {
        $this->inputData = $inputData;

        $this->messageBody[MailjetEmail::MESSAGES_KEY_MAILJET] = [];
        $this->messageBody[MailjetEmail::MESSAGES_KEY_MAILJET][0] = [];

        foreach ($inputData as $key => $value) {
            $this->defineEmailPayload($key, $value);
        }

        return $this->messageBody;
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

        if (! array_key_exists($key, $payloadOptions)) {
            return;
        }

        $payloadOption = $payloadOptions[$key];
        $payloadData = $this->{$payloadOption}($value);

        $this->messageBody[MailjetEmail::MESSAGES_KEY_MAILJET][0] =
            array_merge($this->messageBody[MailjetEmail::MESSAGES_KEY_MAILJET][0], $payloadData);
    }

    private function prepareFromPayload(array $userData): array
    {
        return [
            MailjetEmail::FROM_KEY_MAILJET => [
                MailjetEmail::EMAIL_KEY_MAILJET  => $userData[Email::EMAIL_KEY],
                MailjetEmail::NAME_KEY_MAILJET   => $userData[Email::NAME_KEY],
            ]
        ];
    }

    private function prepareToPayload(array $userData): array
    {
        $userPayload = [];

        foreach ($userData as $value) {
            $userPayload[] = [
                MailjetEmail::EMAIL_KEY_MAILJET  => $value[Email::EMAIL_KEY],
                MailjetEmail::NAME_KEY_MAILJET   => $value[Email::NAME_KEY],
            ];
        }

        return [ MailjetEmail::TO_KEY_MAILJET => $userPayload ];
    }

    private function prepareSubjectPayload(string $value): array
    {
        return [ MailjetEmail::SUBJECT_KEY_MAILJET => $value ];
    }

    private function prepareTextPartPayload(string $value): array
    {
        return [ MailjetEmail::TEXT_PART_KEY_MAILJET => $value ];
    }

    private function prepareHtmlPartPayload(string $value): array
    {
        return [ MailjetEmail::HTML_PART_KEY_MAILJET => $value ];
    }

    private function prepareMarkdownPartPayload(string $value): array
    {
        $this->parsedown->setSafeMode(true);
        $this->parsedown->setMarkupEscaped(true);

        $markdownValue = $this->parsedown->text($value);

        return [ MailjetEmail::HTML_PART_KEY_MAILJET => $markdownValue ];
    }

    /**
     * Send email
     *
     * @return bool
     */
    public function send(): bool
    {
        try {
            $response = $this->client
                ->post(Resources::$Email, ['body' => $this->messageBody]);
            $status = $response->success();

            return $status ?: $this->sendTrigger();
        } catch (\Exception $exception) {
            $message = sprintf(
                LogMessages::MAILJET_SEND_ERROR,
                $exception->getMessage()
            );
            \Log::channel('consumer')->info($message);

            return $this->sendTrigger();
        }
    }

    /**
     * Send trigger email (next vendor)
     *
     * @return bool
     */
    public function sendTrigger(): bool
    {
        $this->sendgridTransactor->preparePayload($this->inputData);
        return $this->sendgridTransactor->send();
    }
}
