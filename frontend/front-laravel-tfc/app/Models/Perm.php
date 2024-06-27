<?php

namespace App\Models;
use Illuminate\Database\Eloquent\Model;

class Perm extends Model{
    protected $table = 'perms';
    protected $primaryKey = 'perm_id';
    protected $guarded = [];
    public $timestamps = false;
    protected $hidden = [];

    public function relations(){
      return $this->hasMany('App\Models\PermRelation', 'perm_id', 'perm_id');
    }
}
