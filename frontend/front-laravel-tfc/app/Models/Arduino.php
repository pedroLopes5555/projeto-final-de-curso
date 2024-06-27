<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Arduino extends Model
{
    protected $table = 'arduinos';
    protected $primaryKey = 'arduino_id';
    protected $guarded = [];
    public $timestamps = false;
    protected $hidden = [];


    public function container(){
        return $this->belongsTo(Container::class, 'container_id', 'container_id');
    }
    public function rele(){
        return $this->hasOne(Rele::class, 'arduino_id', 'arduino_id');
    }
}
