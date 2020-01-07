<?php
declare(strict_types=1);

namespace App\Console\Commands\Consumer;

use App\DTO\Email;
use App\Publishers\Publisher;
use App\Enum\EmailConsumer as EmailConsumerEnum;

trait EmailConsumerRetry
{
    protected function retryMessage(Publisher $publisher, Email $emailDTO, int $retries, $resolver, $message): void
    {
        $resolver->reject($message);

        $this->info(EmailConsumerEnum::CONSUMER_NACK);

        $retriesPerformedMessage = sprintf(
            '%s: %s',
            EmailConsumerEnum::CONSUMER_RETRIES,
            $retries
        );

        $this->info($retriesPerformedMessage);

        $publisher->handle($emailDTO, ++$retries);
    }
}
