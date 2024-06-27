<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PermRelation extends Model{
    protected $table = 'perms_relations';
    protected $primaryKey = 'perm_relation_id';
    protected $guarded = [];
    public $timestamps = false;
    protected $hidden = [];
}
