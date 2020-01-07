<?php
declare(strict_types=1);

namespace App\Console\Commands\Consumer;

use App\Queue;
use App\DTO\Email;
use App\Enum\LogMessages;
use App\Enum\EmailConsumer as EmailConsumerEnum;

trait EmailConsumerCancel
{
    protected function cancelMessage(Email $emailDTO, Queue $queue, $resolver, $message): void
    {
        $this->info(EmailConsumerEnum::CONSUMER_REMOVED);
        $resolver->reject($message);
        $this->logCancel($emailDTO);

        if ($emailDTO->getId()) {
            $queue->updateStatusToFailed($emailDTO->getId());
        }
    }

    private function logCancel(Email $emailDTO): void
    {
        $logMessage = sprintf(
            LogMessages::MESSAGE_REMOVED,
            json_encode($emailDTO->get())
        );

        \Log::consumer('consumer')->info($logMessage);
    }
}
