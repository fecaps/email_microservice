<?php
declare(strict_types=1);

namespace App\Console\Commands\Consumer;

use App\Queue;
use App\DTO\Email;
use App\Workers\Worker;
use App\Enum\LogMessages;
use App\Publishers\Publisher;
use App\Enum\EmailConsumer as EmailConsumerEnum;

trait EmailConsumerResolver
{
    protected function resolveMessage(
        Worker $worker,
        Email $emailDTO,
        Queue $queue,
        Publisher $publisher,
        int $retries,
        $resolver,
        $message
    ): void {
        $messageSent = $worker->sendEmail($emailDTO);

        if (!$messageSent) {
            $this->retryMessage($publisher, $emailDTO, $retries, $resolver, $message);
            return;
        }

        $resolver->acknowledge($message);
        $this->logResolver($emailDTO);

        if ($emailDTO->getId()) {
            $queue->updateStatusToDelivered($emailDTO->getId());
        }

        $this->info(EmailConsumerEnum::CONSUMER_ACK);
    }

    private function logResolver(Email $emailDTO): void
    {
        $logMessage = sprintf(
            LogMessages::MESSAGE_RESOLVED,
            json_encode($emailDTO->get())
        );

        \Log::channel('consumer')->info($logMessage);
    }
}
