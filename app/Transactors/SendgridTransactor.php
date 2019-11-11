<?php
declare(strict_types=1);

namespace App\Transactors;

use App\Connectors\SendgridConnector;
use App\Enum\Email;
use Exception;
use SendGrid\Mail\Mail;

final class SendgridTransactor implements Transactor
{
    private const SUCCESSFULL_HTTP_CODE = 202;
    private $client;
    private $email;

    public function __construct(SendgridConnector $sendgridConnector)
    {
        $this->client = $sendgridConnector->getClient();
        $this->email = new Mail();
    }

    public function preparePayload(array $inputData): void
    {
        try {
            foreach ($inputData as $key => $value) {
                if ($key === Email::FROM_KEY) {
                    $this->prepareFromPayload($value);
                    continue;
                }

                if ($key === Email::TO_KEY) {
                    $this->prepareToPayload($value);
                    continue;
                }

                if ($key === Email::SUBJECT_KEY) {
                    $this->prepareSubjectPayload($value);
                    continue;
                }

                $this->prepareContentPayload($key, $value);
            }
        } catch (Exception $exception) {
            echo $exception->getMessage();
        }
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

    private function prepareContentPayload(string $key, string $value): void
    {
        if ($key === Email::TEXTPART_KEY) {
            $this->email->addContent(
                'text/plain',
                $value
            );
            return;
        }

        $this->email->addContent(
            'text/html',
            $value
        );
    }

    public function send(): bool
    {
        try {
            $response = $this->client->send($this->email);
            return ($response->statusCode() === self::SUCCESSFULL_HTTP_CODE);
        } catch (Exception $exception) {
            return false;
        }
    }
}
