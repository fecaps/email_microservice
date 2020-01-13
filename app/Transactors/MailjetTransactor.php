<?php
declare(strict_types=1);

namespace App\Transactors;

use Parsedown;
use Mailjet\Resources;
use App\DTO\Email;
use App\Enum\Email as EmailEnum;
use App\Enum\LogMessages;
use App\Enum\MailjetEmail;
use App\Connectors\MailjetConnector;

final class MailjetTransactor implements MailerTransactor
{
    use LogError;

    private $client;
    private $parsedown;
    private $messageBody = [];
    private $emailDTO;

    /**
     * Config Mailjet Transactor
     *
     * @param MailjetConnector  $connector
     */
    public function __construct(MailjetConnector $connector)
    {
        $this->client = $connector->getClient();
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
            $response = $this->client->post(Resources::$Email, ['body' => $this->messageBody]);

            return $response->success();
        } catch (\Exception $exception) {
            $this->logError(LogMessages::MAILJET_SEND_ERROR, $exception->getMessage());

            return false;
        }
    }

    private function preparePayload(array $inputData): array
    {
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
        $payloadData = $this->{$payloadOption}($value);

        $this->messageBody[MailjetEmail::MESSAGES_KEY_MAILJET][0] =
            array_merge($this->messageBody[MailjetEmail::MESSAGES_KEY_MAILJET][0], $payloadData);
    }

    private function prepareFromPayload(array $userData): array
    {
        return [
            MailjetEmail::FROM_KEY_MAILJET => [
                MailjetEmail::EMAIL_KEY_MAILJET  => $userData[EmailEnum::EMAIL_KEY],
                MailjetEmail::NAME_KEY_MAILJET   => $userData[EmailEnum::NAME_KEY],
            ]
        ];
    }

    private function prepareToPayload(array $userData): array
    {
        $userPayload = [];

        foreach ($userData as $value) {
            $userPayload[] = [
                MailjetEmail::EMAIL_KEY_MAILJET  => $value[EmailEnum::EMAIL_KEY],
                MailjetEmail::NAME_KEY_MAILJET   => $value[EmailEnum::NAME_KEY],
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
}
