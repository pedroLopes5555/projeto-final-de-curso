<?php
  $format = "yyyy-mm-dd";
  $attributeString = "";
  if(isset($attributes['months_only']))
    $format = "yyyy-mm";

  if(isset($attributes['max-relative-date']))
    $attributeString .= ' max-relative-date="'.$attributes['max-relative-date'].'"';
  if(isset($attributes['min-relative-date']))
    $attributeString .= ' min-relative-date="'.$attributes['min-relative-date'].'"';

  if(!isset($class)) $class = true;
?>
<input type="text" {{$attributeString}} data-date-format="{{$format}}" datepicker autocomplete="off"
<?php
  foreach($attributes as $key => $value){
    if($key == "disabled" && !$value) continue;
    if($key=="months_only"){
      echo "data-start-mode='1' data-min-view-mode='1' ";
    }else{
      echo "$key='$value' ";
    }
  }
  if(gettype($class)=="string"){
    echo 'class="'.$class.'"';
  }else if($class){
    echo 'class="form-control"';
  }
?>
/>
