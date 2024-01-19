<?php
  $name = isset($name)?'name='.$name:'';
  $disabled = isset($disabled)?$disabled:false;
  $required = isset($required)?$required:false;
  $multiple = isset($multiple)?$multiple:false;
  $select = isset($select)?$select:[];
  if(!is_array($select)) $select = [$select => true];
  // if($name == 'name=ld[eventStatus][]') dd($array, $select);
?>
<select {{$name}} select2 class="form-control {{isset($class)?$class:""}}"  autocomplete="off"
  {{isset($id)?'id='.$id:''}} {{$disabled?"disabled":""}}
  {{$required?"required":""}} {!!isset($attributes)?$attributes:""!!}
  {{$multiple?"multiple":""}}
  {!! isset($placeholder)?'data-placeholder="'.$placeholder.'"':"" !!}>
  @if(isset($placeholder))
    <option></option>
  @endif
  @if(isset($select_ajax))
  <option value="{{Utils::dot_notation($select_ajax, $key)}}" selected>{{Utils::dot_notation($select_ajax, $value)}}</option>
  @endif
  <?php
  if(isset($key)){
    foreach($array as $a){
      $_key = Utils::dot_notation($a, $key);
      $_value = Utils::dot_notation($a, $value);
      if(isset($key_fn))
        $_key = $key_fn($_key, $a);
      if(isset($value_fn))
        $_value = $value_fn($_value, $a);
      $selected = array_key_exists($_key, $select)?"selected":"";
      echo "<option $selected value='{$_key}'>{$_value}</option>";
    }
  }else if(isset($array[0])){
    foreach($array as $a){
      $selected = in_array($a, $select)?"selected":"";
      echo "<option $selected>$a</option>";
    }
  }else{
    foreach($array as $key => $value){
      $selected = array_key_exists($key, $select)?"selected":"";
      echo "<option $selected value='$key'>$value</option>";
    }
  }
  ?>
</select>
