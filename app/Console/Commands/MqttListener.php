<?php

namespace App\Console\Commands;

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
        $mqtt = app('mqtt');

        if ($mqtt->connect()) {
            // Define topics with QoS as an associative array
            $topics = [
                'sensor/data' => ['qos' => 0] // QoS as associative array
            ];

            // Subscribe with topics and callback function
            $mqtt->subscribe($topics, function ($topic, $msg) {
                try {
                    $data = json_decode($msg, true);

                    // Verify the received data
                    if (isset($data['device_id']) && isset($data['water_ph']) && isset($data['temperature']) && isset($data['humidity']) && isset($data['ppm']) && isset($data['send_at'])) {
                        // Find the device by device_id
                        $device = DB::table('device')->where('id', $data['device_id'])->first();

                        if ($device) {
                            DB::table('sensors')->insert([
                                'device_id' => $device->id,
                                'water_ph' => $data['water_ph'],
                                'temperature' => $data['temperature'],
                                'humidity' => $data['humidity'],
                                'ppm' => $data['ppm'],
                                'send_at' => $data['send_at'],
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
            });

            while ($mqtt->proc()) {
                // Keep the connection alive
            }

            $mqtt->close();
        } else {
            $this->error('Could not connect to MQTT server.');
        }
    }
}
