<?php
declare(strict_types=1);

namespace App\Console\Commands\Creator;

use App\Enum\EmailCreation as EmailCreationEnum;

trait EmailCreationConverter
{
    protected function prepareEmailPayload(array $emailData): array
    {
        $payload = [
            'from' => [
                'email' => $emailData[EmailCreationEnum::FROM_EMAIL_KEY],
                'name' => $emailData[EmailCreationEnum::FROM_NAME_KEY],
            ],
            'to' => [
                [
                    'email' => $emailData[EmailCreationEnum::TO_EMAIL_KEY],
                    'name' => $emailData[EmailCreationEnum::TO_NAME_KEY],
                ],
            ],
            EmailCreationEnum::SUBJECT_KEY => $emailData[EmailCreationEnum::SUBJECT_KEY],
        ];

        $payload = $this->addContentKeyToPayload($payload, $emailData, EmailCreationEnum::TEXT_PART_KEY);
        $payload = $this->addContentKeyToPayload($payload, $emailData, EmailCreationEnum::HTML_PART_KEY);

        return $this->addContentKeyToPayload($payload, $emailData, EmailCreationEnum::MARKDOWN_PART_KEY);
    }

    private function addContentKeyToPayload(array $payload, array $emailData, string $key): array
    {
        if ($emailData[$key]) {
            $payload = array_merge($payload, [ $key => $emailData[$key] ]);
        }

        return $payload;
    }
}
