<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use App\Enum\EmailConsumer as EmailConsumerEnum;
use App\Console\Commands\Consumer\EmailConsumer;

class EmailConsumerServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     *
     * @return void
     */
    public function register()
    {
        $queueName = $this->defineQueueName();
        $maxRetries = $this->defineMaxRetries();

        $queueNameWhen = $this->app->when(EmailConsumer::class);
        $queueNameNeeds = $queueNameWhen->needs('$queueName');
        $queueNameNeeds->give($queueName);

        $maxRetriesWhen = $this->app->when(EmailConsumer::class);
        $maxRetriesNeeds = $maxRetriesWhen->needs('$maxRetries');
        $maxRetriesNeeds->give($maxRetries);
    }

    private function defineQueueName(): string
    {
        return env('RABBITMQ_QUEUE', EmailConsumerEnum::DEFAULT_QUEUE);
    }

    private function defineMaxRetries(): int
    {
        return (int)(env('RABBITMQ_MAXIMUM_RETRIES', EmailConsumerEnum::DEFAULT_MAX_RETRIES));
    }

    /**
     * Bootstrap services.
     *
     * @return void
     */
    public function boot()
    {
        //
    }
}
