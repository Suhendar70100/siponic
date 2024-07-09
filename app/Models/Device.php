<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Device extends Model
{
    use HasFactory;

    protected $table = 'device';
    protected $primaryKey = 'id';
    protected $keyType = 'int';
    public $timestamps = true;
    public $incrementing = false;

    protected $fillable = [
        'guid',
        'garden_id',
        'max_ppm',
        'min_ppm',
        'status',
        'note',
    ];

    public function garden()
    {
        return $this->belongsTo(Garden::class);
    }

    public function sensors()
    {
        return $this->hasMany(Sensor::class);
    }

    public function information()
    {
        return $this->hasMany(Information::class, 'device_id');
    }
}
