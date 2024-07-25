<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;

class CheckMqttConnection extends Command
{
    protected $signature = 'mqtt:check-connection';
    protected $description = 'Check the MQTT connection';

    public function __construct()
    {
        parent::__construct();
    }

    public function handle()
    {
        $mqtt = app('mqtt');

        if ($mqtt->connect()) {
            $this->info('Successfully connected to MQTT broker');
            $mqtt->close();
        } else {
            $this->error('Could not connect to MQTT broker');
        }
    }
}
