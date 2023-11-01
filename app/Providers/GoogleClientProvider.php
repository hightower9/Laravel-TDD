<?php

namespace App\Providers;

use Google\Client;
use Illuminate\Support\ServiceProvider;

class GoogleClientProvider extends ServiceProvider
{
    /**
     * Register services.
     */
    public function register(): void
    {
        $this->app->bind(Client::class, function () {
            $client = new Client();
            $client->setClientId(config('services.google.client_id'));
            $client->setClientSecret(config('services.google.client_secret'));
            $client->setRedirectUri(config('services.google.redirect_uri'));

            return $client;
        });        
    }

    /**
     * Bootstrap services.
     */
    public function boot(): void
    {
        //
    }
}
