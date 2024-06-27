<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TargetValue extends Model
{
    protected $table = 'target_values';
    protected $primaryKey = 'value_id';
    protected $guarded = [];
    public $timestamps = false;
    protected $hidden = [];

    public function container(){
        return $this->belongsTo(Container::class, 'container_id', 'container_id');
    }
}
