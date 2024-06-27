<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Container extends Model
{
    protected $table = 'containers';
    protected $primaryKey = 'container_id';
    protected $guarded = [];
    public $timestamps = false;
    protected $hidden = [];


    public function values(){
        return $this->hasMany(Value::class, 'container_id', 'container_id');
    }
    public function arduinos(){
        return $this->hasMany(Arduino::class, 'container_id', 'container_id');
    }
    public function targetValue(){
        return $this->hasOne(TargetValue::class, 'container_id', 'container_id');
    }
    public function container(){
        return $this->hasOne(Container::class, 'container_id', 'container_id');
    }
}
