<?php

namespace NotificationChannels\Gcm;

use Zend\Http\Client\Adapter\Curl;
use ZendService\Google\Gcm\Client;
use Illuminate\Support\ServiceProvider;

class GcmServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap the application services.
     */
    public function register()
    {
        $this->app->when(GcmChannel::class)
            ->needs(Client::class)
            ->give(function () {
                $gcmConfig = config('broadcasting.connections.gcm');

                $client = new Client();
                $client->setApiKey($gcmConfig['key']);
                $client->getHttpClient()->setAdapter(new Curl());

                return $client;
            });
    }
}
