<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Sensor extends Model
{
    protected $table = 'sensors';
    protected $primaryKey = 'sensor_id';
    protected $guarded = [];
    public $timestamps = false;
    protected $hidden = [];

    public function rele(){
        return $this->belongsTo(Rele::class, 'rele_id', 'rele_id');
    }
    public function sensorType(){
        return $this->belongsTo(SensorType::class, 'sensor_type_id', 'sensor_type_id');
    }
}
