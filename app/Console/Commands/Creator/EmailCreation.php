<?php

namespace App\Console\Commands\Creator;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Validator;
use App\Model\Email;
use App\Enum\LogMessages;
use App\Enum\EmailCreation as EmailCreationEnum;

final class EmailCreation extends Command
{
    use EmailCreationValidator;
    use EmailCreationConverter;

    private $emailModel;

    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'create:email';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Create emails';

    /**
     * Create a new command instance.
     *
     * @param Email  $emailModel
     * @return void
     */
    public function __construct(Email $emailModel)
    {
        parent::__construct();

        $this->emailModel = $emailModel;
    }

    /**
     * Execute the console command.
     *
     * @return string
     */
    public function handle(): string
    {
        $this->info(EmailCreationEnum::START_MESSAGE);

        $fromEmail = $this->ask(EmailCreationEnum::FROM_EMAIL_MESSAGE);
        $fromName =  $this->ask(EmailCreationEnum::FROM_NAME_MESSAGE);
        $toEmail = $this->ask(EmailCreationEnum::TO_EMAIL_MESSAGE);
        $toName = $this->ask(EmailCreationEnum::TO_NAME_MESSAGE);
        $subject = $this->ask(EmailCreationEnum::SUBJECT_MESSAGE);
        $textPart = $this->ask(EmailCreationEnum::TEXT_PART_MESSAGE);
        $htmlPart = $this->ask(EmailCreationEnum::HTML_PART_MESSAGE);
        $markdownPart = $this->ask(EmailCreationEnum::MARKDOWN_PART_MESSAGE);

        $validationData = $this
            ->createArrayData($fromEmail, $fromName, $toEmail, $toName, $subject, $textPart, $htmlPart, $markdownPart);

        return $this->validateEmail($validationData);
    }

    private function createArrayData(
        $fromEmail,
        $fromName,
        $toEmail,
        $toName,
        $subject,
        $textPart,
        $htmlPart,
        $markdownPart
    ): array {
        return [
            EmailCreationEnum::FROM_EMAIL_KEY       => $fromEmail,
            EmailCreationEnum::FROM_NAME_KEY        => $fromName,
            EmailCreationEnum::TO_EMAIL_KEY         => $toEmail,
            EmailCreationEnum::TO_NAME_KEY          => $toName,
            EmailCreationEnum::SUBJECT_KEY          => $subject,
            EmailCreationEnum::TEXT_PART_KEY        => $textPart,
            EmailCreationEnum::HTML_PART_KEY        => $htmlPart,
            EmailCreationEnum::MARKDOWN_PART_KEY    => $markdownPart,
        ];
    }

    private function validateEmail(array $emailData)
    {
        $validatorData = $this->defineValidatorData($emailData);
        $validatorRules = $this->defineValidatorRules();

        $validator = Validator::make($validatorData, $validatorRules);

        if ($validator->fails()) {
            return $this->showErrors($validator);
        }

        \Log::channel('publisher')->info(LogMessages::START_CONSOLE);
        return $this->createEmail($emailData);
    }

    private function createEmail(array $emailData): string
    {
        $data = $this->prepareEmailPayload($emailData);
        $this->emailModel->storeEmail($data);

        $this->info(EmailCreationEnum::END_MESSAGE);
        return EmailCreationEnum::END_MESSAGE;
    }
}
