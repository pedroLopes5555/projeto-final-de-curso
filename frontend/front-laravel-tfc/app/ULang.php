<?php
namespace App;

class ULang{

  public static function get(){
    return \Lang::getLocale();
  }
  public static function is($lang){
    return strtolower(\Lang::getLocale()) == strtolower($lang);
  }
  public static function or($pt, $en){
    $fromLang = \Lang::getLocale();
    return $fromLang == 'pt' ? $pt : $en;
  }

}
