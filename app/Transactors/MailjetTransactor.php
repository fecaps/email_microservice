<?php
declare(strict_types=1);

namespace App\Transactors;

use Mailjet\Resources;
use App\Connectors\MailjetConnector;
use App\Enum\MailjetMessages;

final class MailjetTransactor implements Transactor
{
    private $client;
    private $messageBody = [ MailjetMessages::MESSAGES_KEY ];

    public function __construct(MailjetConnector $maijetConnector)
    {
        $this->client = $maijetConnector->getClient();
    }

    public function preparePayload(array $inputData): self
    {
        foreach ($inputData as $key => $value) {
            if ($key === MailjetMessages::FROM_KEY) {
                $this->messageBody[MailjetMessages::MESSAGES_KEY][] = $this->prepareFromPayload($value);
            }

            if ($key === MailjetMessages::TO_KEY) {
                $this->messageBody[MailjetMessages::MESSAGES_KEY][] = $this->prepareToPayload($value);
            }

            if ($key === MailjetMessages::SUBJECT_KEY) {
                $this->messageBody[MailjetMessages::MESSAGES_KEY][MailjetMessages::SUBJECT_KEY_MAILJET] = $value;
            }

            $this->messageBody[MailjetMessages::MESSAGES_KEY][] = $key === MailjetMessages::TEXTPART_KEY
                ? [MailjetMessages::TEXTPART_KEY_MAILJET => $value ]
                : [MailjetMessages::HTMLPART_KEY_MAILJET => $value ];
        }

        return $this;
    }

    private function prepareFromPayload(array $userData): array
    {
        return [
            MailjetMessages::FROM_KEY_MAILJET => [
                MailjetMessages::EMAIL_KEY_MAILJET  => $userData[MailjetMessages::EMAIL_KEY],
                MailjetMessages::NAME_KEY_MAILJET   => $userData[MailjetMessages::NAME_KEY]
            ]
        ];
    }

    private function prepareToPayload(array $userData): array
    {
        $userPayload = [ MailjetMessages::TO_KEY_MAILJET ];

        foreach ($userData as $value) {
            $userPayload[MailjetMessages::TO_KEY_MAILJET][] = [
                MailjetMessages::EMAIL_KEY_MAILJET  => $value[MailjetMessages::EMAIL_KEY],
                MailjetMessages::NAME_KEY_MAILJET   => $value[MailjetMessages::NAME_KEY]
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
