<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Sensor extends Model
{
    use HasFactory;

    protected $table = 'sensors';
    protected $primaryKey = 'id';
    protected $keyType = 'int';
    public $timestamps = true;
    public $incrementing = true;

    protected $fillable = [
        'device_id',
        'water_ph',
        'temperature',
        'humidity',
        'ppm',
        'send_at',
    ];

    /**
     * Get the device that owns the sensor.
     */
    public function device()
    {
        return $this->belongsTo(Device::class);
    }

}
