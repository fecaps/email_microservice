<?php

namespace App\Providers;

use App\Connectors\MailjetConnector;
use Illuminate\Contracts\Support\DeferrableProvider;
use Illuminate\Support\ServiceProvider;

class EmailServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     *
     * @return void
     */
    public function register()
    {
        $this->app->singleton(MailjetConnector::class, function ($app) {
            $config = $app->make('config')->get('services.mailjet', []);
            return new MailjetConnector($config);
        });
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
