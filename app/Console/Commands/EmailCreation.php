<?php

namespace App\Console\Commands;

use App\Enum\EmailCreation as EmailCreationEnum;
use App\Enum\LogMessages;
use App\Publishers\EmailPublisher;
use App\Queue;
use Illuminate\Console\Command;
use Illuminate\Contracts\Validation\Validator as ValidationValidator;
use Illuminate\Support\Facades\Validator;

final class EmailCreation extends Command
{
    private $queue;

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
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @param EmailPublisher  $publisher
     * @param Queue  $queue
     * @return string
     */
    public function handle(EmailPublisher $publisher, Queue $queue): string
    {
        $this->queue = $queue;
        $this->info(EmailCreationEnum::START_MESSAGE);

        $fromEmail = $this->ask(EmailCreationEnum::FROM_EMAIL_MESSAGE);
        $fromName =  $this->ask(EmailCreationEnum::FROM_NAME_MESSAGE);
        $toEmail = $this->ask(EmailCreationEnum::TO_EMAIL_MESSAGE);
        $toName = $this->ask(EmailCreationEnum::TO_NAME_MESSAGE);
        $subject = $this->ask(EmailCreationEnum::SUBJECT_MESSAGE);
        $textPart = $this->ask(EmailCreationEnum::TEXT_PART_MESSAGE);
        $htmlPart = $this->ask(EmailCreationEnum::HTML_PART_MESSAGE);
        $markdownPart = $this->ask(EmailCreationEnum::MARKDOWN_PART_MESSAGE);

        return $this->dispatchEmail([
            EmailCreationEnum::FROM_EMAIL_KEY       => $fromEmail,
            EmailCreationEnum::FROM_NAME_KEY        => $fromName,
            EmailCreationEnum::TO_EMAIL_KEY         => $toEmail,
            EmailCreationEnum::TO_NAME_KEY          => $toName,
            EmailCreationEnum::SUBJECT_KEY          => $subject,
            EmailCreationEnum::TEXT_PART_KEY        => $textPart,
            EmailCreationEnum::HTML_PART_KEY        => $htmlPart,
            EmailCreationEnum::MARKDOWN_PART_KEY    => $markdownPart,
        ], $publisher);
    }

    private function dispatchEmail(array $emailData, EmailPublisher $publisher): string
    {
        $validatorData = $this->defineValidatorData($emailData);

        $validatorRules = $this->defineValidatorRules();

        $validator = Validator::make($validatorData, $validatorRules);

        if ($validator->fails()) {
            return $this->showErrors($validator);
        }

        \Log::channel('publisher')->info(LogMessages::START_CONSOLE);
        return $this->createEmail($emailData, $publisher);
    }

    private function defineValidatorData(array $emailData): array
    {
        $payload = [
            EmailCreationEnum::FROM_EMAIL_KEY       => $emailData[EmailCreationEnum::FROM_EMAIL_KEY],
            EmailCreationEnum::FROM_NAME_KEY        => $emailData[EmailCreationEnum::FROM_NAME_KEY],
            EmailCreationEnum::TO_EMAIL_KEY         => $emailData[EmailCreationEnum::TO_EMAIL_KEY],
            EmailCreationEnum::TO_NAME_KEY          => $emailData[EmailCreationEnum::TO_NAME_KEY],
            EmailCreationEnum::SUBJECT_KEY          => $emailData[EmailCreationEnum::SUBJECT_KEY],
        ];

        return $this->addContentToPayload($payload, $emailData);
    }

    private function defineValidatorRules(): array
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

    private function showErrors(ValidationValidator $validator): string
    {
        $this->info(EmailCreationEnum::INVALID_MESSAGE);

        $errors = $validator->errors();

        foreach ($errors->all() as $error) {
            $this->error($error);
        }

        return 1;
    }

    private function createEmail(array $emailData, EmailPublisher $publisher): string
    {
        $data = $this->prepareEmailPayload($emailData);

        $id = $this->queue->addToQueue($data);
        $fullData = array_merge($data, [ 'id' => $id ]);

        $publisher->handle($fullData);

        $this->info(EmailCreationEnum::END_MESSAGE);
        return EmailCreationEnum::END_MESSAGE;
    }

    private function prepareEmailPayload(array $emailData): array
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

        return $this->addContentToPayload($payload, $emailData);
    }

    private function addContentToPayload(array $payload, array $emailData): array
    {
        if ($emailData[EmailCreationEnum::TEXT_PART_KEY]) {
            $payload = array_merge(
                $payload,
                [ EmailCreationEnum::TEXT_PART_KEY => $emailData[EmailCreationEnum::TEXT_PART_KEY] ]
            );
        }

        if ($emailData[EmailCreationEnum::HTML_PART_KEY]) {
            $payload = array_merge(
                $payload,
                [ EmailCreationEnum::HTML_PART_KEY => $emailData[EmailCreationEnum::HTML_PART_KEY] ]
            );
        }

        if ($emailData[EmailCreationEnum::MARKDOWN_PART_KEY]) {
            $payload = array_merge(
                $payload,
                [ EmailCreationEnum::MARKDOWN_PART_KEY => $emailData[EmailCreationEnum::MARKDOWN_PART_KEY] ]
            );
        }

        return $payload;
    }
}
