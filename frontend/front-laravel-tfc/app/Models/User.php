<?php

namespace App\Models;
use Illuminate\Contracts\Auth\Authenticatable;
use Illuminate\Database\Eloquent\Model;

use App\Session;
class User extends Model 
{
  protected $table = 'users';
  protected $primaryKey = 'user_id';
  protected $guarded = [];

  private static $current = null;
  public static function getCurrent(){
    if(self::$current) return self::$current;
    $id = Session::get(Session::USER);
    if($id == null) return null;
    self::$current = self::where('user_id', $id)->first();
    return self::$current;
  }
  public static function setCurrent($curr){
    Session::put(Session::USER, $curr->user_id);
    return self::$current = $curr;
  }
  public static function logout(){
    Session::pull(Session::USER);
    self::$current = null;
  }

  public function canSee($perm){
    if($this->user_super == 1) return true;
    return $this->perm->relations()->where('perm_name', $perm)->exists();
  }

  public function perm(){
    return $this->hasOne('App\Models\Perm', 'perm_id', 'perm_id');
  }
  public function containers(){
    return $this->hasMany('App\Models\Container', 'user_id', 'user_id');
  }
  
  public function arduinos(){
    return $this->hasManyThrough('App\Models\Arduino', 'App\Models\Container', 'user_id', 'container_id', 'user_id', 'container_id');
  }
}
