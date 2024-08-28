<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Device extends Model
{
    use HasFactory;

    protected $table = 'device';
    protected $primaryKey = 'id';
    protected $keyType = 'int';
    public $timestamps = true;
    public $incrementing = true;

    protected $fillable = [
        'guid',
        'garden_id',
        'max_ppm',
        'min_ppm',
        'status',
        'plants',
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

    public static function generateGuid($gardenId)
    {
        $latestDevice = self::where('garden_id', $gardenId)->orderBy('id', 'desc')->first();
        $deviceNumber = $latestDevice ? ($latestDevice->id + 1) : 1;
        $timestamp = Carbon::now()->format('Ymd');
        return "KEB-{$gardenId}-{$deviceNumber}-{$timestamp}";
    }
}
