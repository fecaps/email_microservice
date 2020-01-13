<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use App\Publishers\EmailPublisher;
use App\Enum\EmailPublisher as EmailPublisherEnum;

class EmailPublisherServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     *
     * @return void
     */
    public function register()
    {
        $routingKeyName = $this->defineRoutingKeyName();
        $exchangeName = $this->defineExchangeName();
        $queueName = $this->defineQueueName();

        $routingKeyWhen = $this->app->when(EmailPublisher::class);
        $routingKeyNeeds = $routingKeyWhen->needs('$routingKeyName');
        $routingKeyNeeds->give($routingKeyName);

        $exchangeWhen = $this->app->when(EmailPublisher::class);
        $exchangeNeeds = $exchangeWhen->needs('$exchangeName');
        $exchangeNeeds->give($exchangeName);

        $queueNameWhen = $this->app->when(EmailPublisher::class);
        $queueNameNeeds = $queueNameWhen->needs('$queueName');
        $queueNameNeeds->give($queueName);
    }

    private function defineRoutingKeyName(): string
    {
        return env('RABBITMQ_ROUTING_KEY_NAME', EmailPublisherEnum::DEFAULT_ROUTING_KEY);
    }

    private function defineExchangeName(): string
    {
        return env('RABBITMQ_EXCHANGE_NAME', EmailPublisherEnum::DEFAULT_EXCHANGE);
    }

    private function defineQueueName(): string
    {
        return env('RABBITMQ_QUEUE', EmailPublisherEnum::DEFAULT_QUEUE);
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
