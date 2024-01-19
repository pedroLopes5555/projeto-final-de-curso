<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Value extends Model
{
    protected $table = 'values';
    protected $primaryKey = 'value_id';
    protected $guarded = [];
    public $timestamps = false;
    protected $hidden = [];

    public function container(){
        return $this->belongsTo(Container::class, 'container_id', 'container_id');
    }
}
