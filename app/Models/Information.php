<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Information extends Model
{
    use HasFactory;

    protected $table = 'information';
    protected $primaryKey = 'id';
    protected $keyType = 'int';
    public $timestamps = true;
    public $incrementing = false;

    protected $fillable = [
        'seeding_start_date',
        'harvest_date',
        'harvest_yield',
        'device_id',
    ];

    public function device()
    {
        return $this->belongsTo(Device::class, 'device_id');
    }
}
