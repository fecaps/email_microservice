<?php
declare(strict_types=1);

namespace App\Transactors;

use Mailjet\Resources;
use App\Connectors\MailjetConnector;
use App\Enum\Email;
use App\Enum\MailjetEmail;

final class MailjetTransactor implements Transactor
{
    private $client;
    private $messageBody = [];

    public function __construct(MailjetConnector $maijetConnector)
    {
        $this->client = $maijetConnector->getClient();
    }

    public function preparePayload(array $inputData): array
    {
        $this->messageBody[MailjetEmail::MESSAGES_KEY_MAILJET] = [];

        foreach ($inputData as $key => $value) {
            if ($key === Email::FROM_KEY) {
                $this->messageBody[MailjetEmail::MESSAGES_KEY_MAILJET][0][MailjetEmail::FROM_KEY_MAILJET] =
                    $this->prepareFromPayload($value);
                continue;
            }

            if ($key === Email::TO_KEY) {
                $this->messageBody[MailjetEmail::MESSAGES_KEY_MAILJET][0][MailjetEmail::TO_KEY_MAILJET] =
                    $this->prepareToPayload($value);
                continue;
            }

            if ($key === Email::SUBJECT_KEY) {
                $this->messageBody[MailjetEmail::MESSAGES_KEY_MAILJET][0][MailjetEmail::SUBJECT_KEY_MAILJET] = $value;
                continue;
            }

            if ($key === Email::TEXTPART_KEY) {
                $this->messageBody[MailjetEmail::MESSAGES_KEY_MAILJET][0][MailjetEmail::TEXTPART_KEY_MAILJET] = $value;
                continue;
            }

            $this->messageBody[MailjetEmail::MESSAGES_KEY_MAILJET][0][MailjetEmail::HTMLPART_KEY_MAILJET] = $value;
        }

        return $this->messageBody;
    }

    private function prepareFromPayload(array $userData): array
    {
        return [
            MailjetEmail::EMAIL_KEY_MAILJET  => $userData[Email::EMAIL_KEY],
            MailjetEmail::NAME_KEY_MAILJET   => $userData[Email::NAME_KEY]
        ];
    }

    private function prepareToPayload(array $userData): array
    {
        $userPayload = [];

        foreach ($userData as $value) {
            $userPayload[] = [
                MailjetEmail::EMAIL_KEY_MAILJET  => $value[Email::EMAIL_KEY],
                MailjetEmail::NAME_KEY_MAILJET   => $value[Email::NAME_KEY]
            ];
        }

        return $userPayload;
    }

    public function send(): bool
    {
        $response = $this->client
            ->post(Resources::$Email, [ 'body' => $this->messageBody ]);

        return $response->success();
    }
}
