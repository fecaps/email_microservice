<?php
declare(strict_types=1);

namespace App\Console\Commands\Creator;

use Illuminate\Contracts\Validation\Validator as ValidationValidator;
use App\Enum\EmailCreation as EmailCreationEnum;

trait EmailCreationValidator
{
    protected function defineValidatorRules(): array
    {
        $defaultEmailRule = 'required|email|max:255';
        $defaultStringRule = 'required|string|min:1|max:255';

        return [
            EmailCreationEnum::FROM_EMAIL_KEY       => $defaultEmailRule,
            EmailCreationEnum::FROM_NAME_KEY        => $defaultStringRule,
            EmailCreationEnum::TO_EMAIL_KEY         => $defaultEmailRule,
            EmailCreationEnum::TO_NAME_KEY          => $defaultStringRule,
            EmailCreationEnum::SUBJECT_KEY          => $defaultStringRule,
            EmailCreationEnum::TEXT_PART_KEY        => 'required_without_all:htmlPart,markdownPart|min:1|string',
            EmailCreationEnum::HTML_PART_KEY        => 'required_without_all:textPart,markdownPart|min:1|string',
            EmailCreationEnum::MARKDOWN_PART_KEY    => 'required_without_all:textPart,htmlPart|min:1|string',
        ];
    }

    protected function defineValidatorData(array $emailData): array
    {
        $payload = [
            EmailCreationEnum::FROM_EMAIL_KEY       => $emailData[EmailCreationEnum::FROM_EMAIL_KEY],
            EmailCreationEnum::FROM_NAME_KEY        => $emailData[EmailCreationEnum::FROM_NAME_KEY],
            EmailCreationEnum::TO_EMAIL_KEY         => $emailData[EmailCreationEnum::TO_EMAIL_KEY],
            EmailCreationEnum::TO_NAME_KEY          => $emailData[EmailCreationEnum::TO_NAME_KEY],
            EmailCreationEnum::SUBJECT_KEY          => $emailData[EmailCreationEnum::SUBJECT_KEY],
        ];

        $payload = $this->defineContentPartData($payload, $emailData, EmailCreationEnum::TEXT_PART_KEY);
        $payload = $this->defineContentPartData($payload, $emailData, EmailCreationEnum::HTML_PART_KEY);
        return $this->defineContentPartData($payload, $emailData, EmailCreationEnum::MARKDOWN_PART_KEY);
    }

    private function defineContentPartData(array $payload, array $emailData, string $key): array
    {
        if (!$emailData[$key]) {
            return $payload;
        }

        return array_merge($payload, [ $key => $emailData[$key] ]);
    }

    protected function showErrors(ValidationValidator $validator)
    {
        $this->info(EmailCreationEnum::INVALID_MESSAGE);

        $errors = $validator->errors();

        foreach ($errors->all() as $error) {
            $this->error($error);
        }

        return 1;
    }
}
