<?php
  $img = "";
  if(isset($url)){
    $img = "style=\"background-image: url('".$full_url."');\"";
  }
  $square = isset($square)?'image-input-square':'';
?>
<div class="form-group col-sm-12">
  <label>{{$label}}</label>
  <label class="image-input-label {{$square}}">
    <input type="file" class="input-hidden" name="{{$name}}"/>
    @if(isset($url))
      <input type="hidden" name="{{$name}}" value="{{$url}}" @if(!isset($required) || $required != false ) required @endif/>
    @endif
    <div class="image-input" {!! $img !!} {{isset($id)?'id='.$id:''}}></div>
  </label>
</div>
