<?php

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up()
    {
        DB::unprepared('
            CREATE EVENT IF NOT EXISTS delete_old_sensor_data
            ON SCHEDULE EVERY 1 MONTH
            STARTS CURRENT_TIMESTAMP + INTERVAL 1 MONTH
            DO
            BEGIN
                DELETE FROM sensors
                WHERE created_at < DATE_SUB(CURDATE(), INTERVAL 2 MONTH);
            END;
        ');
    }

    /**
     * Reverse the migrations.
     */
    public function down()
    {
        DB::unprepared('DROP EVENT IF EXISTS delete_old_sensor_data');
    }
};
