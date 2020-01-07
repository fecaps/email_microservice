<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use App\Connectors\MailjetConnector;
use App\Connectors\SendgridConnector;

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

        $this->app->singleton(SendgridConnector::class, function ($app) {
            $config = $app->make('config');
            $sendgridConfig = $config->get('services.sendgrid', []);

            return new SendgridConnector($sendgridConfig);
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
