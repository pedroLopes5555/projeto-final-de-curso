<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SensorType extends Model
{
    protected $table = 'sensor_types';
    protected $primaryKey = 'sensor_type_id';
    protected $guarded = [];
    public $timestamps = false;
    protected $hidden = [];

    public function sensors(){
        return $this->hasMany(Sensor::class, 'sensor_type_id', 'sensor_type_id');
    }
}
