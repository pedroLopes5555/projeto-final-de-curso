<?php
namespace App;

class Snowflake{

  // 2021-08-01 00:00:00 (milliseconds)
  const EPOCH = 1627772400000;
  const DATACENTER = 0;
  const WORKER = 0;

  // 48 bits - timestamp ms (around 8925 years)
  // 04 bits - datacenter
  // 04 bits - worker
  // 08 bits - sequence

  private static $last_pre_id = 0;
  private static $last_seq = 0;
  public static function id(){
    $ms = self::milliseconds();
    $epoch = $ms - self::EPOCH;
    $pre_id = $epoch << 16;
    $pre_id += (self::DATACENTER & 0xf) << 12;
    $pre_id += (self::WORKER & 0xf) << 8;
    // TODO
    // $pre_id = $pre_id & 0x7fffffff; // Turn the sign bit off

    if($pre_id == self::$last_pre_id){
      self::$last_seq++;
    }else{
      self::$last_seq = 0;
      self::$last_pre_id = $pre_id;
    }

    if(self::$last_seq > 0xff) throw "SnowflakeSequenceOverflow";

    $id = $pre_id + self::$last_seq;
    return "" . $id; // Return string to support non 64bit systems
  }

  private static function milliseconds(){
    $mt = explode(' ', microtime());
    return ((int)$mt[1]) * 1000 + ((int)round($mt[0] * 1000));
  }

}
