<?php

namespace App\Providers;

use Bluerhinos\phpMQTT;
use Illuminate\Support\ServiceProvider;

class MqttServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     */
    public function register()
    {
        $this->app->singleton('mqtt', function () {
            $server = env('MQTT_SERVER', 'broker.emqx.io'); // Your MQTT server address
            $port = env('MQTT_PORT', 1883); // MQTT port
            $clientId = env('MQTT_CLIENT_ID', 'laravel-client'); // Client ID

            return new phpMQTT($server, $port, $clientId);
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
