<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Publishers\EmailPublisher;
use App\Enum\EmailCreation as EmailCreationEnum;

final class EmailCreation extends Command
{
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
     * @return string
     */
    public function handle(EmailPublisher $publisher): string
    {
        $this->info(EmailCreationEnum::START_MESSAGE);

        $fromEmail = $this->ask(EmailCreationEnum::FROM_EMAIL);
        $fromName =  $this->ask(EmailCreationEnum::FROM_NAME);
        $toEmail = $this->ask(EmailCreationEnum::TO_EMAIL);
        $toName = $this->ask(EmailCreationEnum::TO_NAME);
        $subject = $this->ask(EmailCreationEnum::SUBJECT);
        $textPart = $this->ask(EmailCreationEnum::TEXT_PART);
        $htmlPart = $this->ask(EmailCreationEnum::HTML_PART);

        $emailData = $this->prepareEmailPayload(
            $fromEmail, $fromName, $toEmail, $toName, $subject, $textPart, $htmlPart
        );

        $publisher->handle($emailData);

        $this->info(EmailCreationEnum::END_MESSAGE);
        return EmailCreationEnum::END_MESSAGE;
    }

    private function prepareEmailPayload(
        string $fromEmail,
        string $fromName,
        string $toEmail,
        string $toName,
        string $subject,
        string $textPart,
        string $htmlPart
    ): array {
        return [
            'from' => [
                'email' => $fromEmail,
                'name' => $fromName
            ],
            'to' => [
                [
                    'email' => $toEmail,
                    'name' => $toName
                ]
            ],
            'subject' => $subject,
            'textPart' => $textPart,
            'htmlPart' => $htmlPart
        ];
    }
}
