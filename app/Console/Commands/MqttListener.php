<?php

namespace App\Console\Commands;

use PhpMqtt\Client\Exceptions\MqttClientException;
use PhpMqtt\Client\MqttClient;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class MqttListener extends Command
{
    protected $signature = 'mqtt:listen';
    protected $description = 'Listen for MQTT messages and store them in the database';

    public function __construct()
    {
        parent::__construct();
    }

    public function handle()
    {
        while (true) {
            try {
                $this->connectAndListen();
            } catch (MqttClientException $e) {
                Log::error('MQTT Client Exception: ' . $e->getMessage());
                sleep(5);
            } catch (\Exception $e) {
                Log::error('Error: ' . $e->getMessage());
                sleep(5);
            }
        }
    }

    private function connectAndListen()
    {
        $host = env('MQTT_HOST', 'broker.hivemq.com');
        $port = env('MQTT_PORT', 1883);
        $clientId = env('MQTT_CLIENT_ID', 'laravel-listener');
        $username = trim(env('MQTT_USERNAME', ''));
        $password = env('MQTT_PASSWORD', '');

        $client = new MqttClient($host, $port, $clientId);

        $connectionSettings = (new \PhpMqtt\Client\ConnectionSettings)
            ->setKeepAliveInterval(60)
            ->setConnectTimeout(10)
            ->setMaxReconnectAttempts(5);

        if (!empty($username)) {
            $connectionSettings->setUsername($username);
            if (!empty($password)) {
                $connectionSettings->setPassword($password);
            }
        }

        $client->connect($connectionSettings, true);
        Log::info("Connected to MQTT broker at {$host}:{$port} with client ID {$clientId}");

        $client->subscribe('siponic/dataSensor', function (string $topic, string $message) {
            $this->processMessage($message);
        }, 0);

        $client->loop(true);
        throw new MqttClientException('MQTT loop stopped unexpectedly.');
    }

    protected function processMessage($msg)
    {
        try {
            $data = json_decode($msg, true);

            if (isset($data['device_id']) && isset($data['water_ph']) && isset($data['temperature']) && isset($data['humidity']) && isset($data['ppm'])) {
                $device = DB::table('device')->where('id', $data['device_id'])->first();

                if ($device) {
                    DB::table('sensors')->insert([
                        'device_id' => $device->id,
                        'water_ph' => $data['water_ph'],
                        'temperature' => $data['temperature'],
                        'humidity' => $data['humidity'],
                        'ppm' => $data['ppm'],
                        'created_at' => now(),
                        'updated_at' => now(),
                    ]);
                } else {
                    Log::warning('Device with ID ' . $data['device_id'] . ' not found.');
                }
            } else {
                Log::warning('Invalid data received: ' . $msg);
            }
        } catch (\Exception $e) {
            Log::error('Error processing MQTT message: ' . $e->getMessage());
        }
    }
}
