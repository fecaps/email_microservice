<?php

namespace App\Providers;

use App\Connectors\MailjetConnector;
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
            $config = $app->make('config');

            $maijetConfig = $config->get('services.mailjet', []);

            return new MailjetConnector($maijetConfig);
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
