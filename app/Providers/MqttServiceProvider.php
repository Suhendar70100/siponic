<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use PhpMqtt\Client\MqttClient;

class MqttServiceProvider extends ServiceProvider
{
    public function register()
    {
        $this->app->singleton(MqttClient::class, function ($app) {
            return new MqttClient(env('MQTT_HOST'), env('MQTT_PORT'), env('MQTT_CLIENT_ID'));
        });
    }

    public function boot()
    {
        //
    }
}
