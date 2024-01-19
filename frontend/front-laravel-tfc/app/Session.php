<?php

namespace app;

class Session{
  public static $session = null;
  const ERROR = 'error';
  const ADMIN = 'admin';
  const USER = 'user';

  public static function __callStatic($method, $args){
    if(self::$session == null) self::$session = request()->session();
    return self::$session->$method(...$args);
  }
}

?>
