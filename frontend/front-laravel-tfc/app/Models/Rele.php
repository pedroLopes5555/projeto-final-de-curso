<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Rele extends Model
{
    protected $table = 'reles';
    protected $primaryKey = 'rele_id';
    protected $guarded = [];
    public $timestamps = false;
    protected $hidden = [];

    public function arduino(){
        return $this->belongsTo(Arduino::class, 'arduino_id', 'arduino_id');
    }
    public function sensor(){
        return $this->belongsTo(Sensor::class, 'sensor_id', 'sensor_id');
    }
}
