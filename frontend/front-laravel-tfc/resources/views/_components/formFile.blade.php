<?php
  if(!isset($form)) $form = true;
  $required = isset($required)?'required':'';
  $multiple = isset($multiple)?'multiple':'';
  $id_name_edit = isset($id_name_edit)?'my-file-input-edit': 'my-file-input';
?>

@if($form)<div class="form-group">@endif
  <div class="custom-file">
    <input name="{{isset($name)?$name:'file'}}" type="file" class="custom-file-input" id={{$id_name_edit}}
      {{$required}} {{$multiple}}
      @if(isset($attributes))
      {!! $attributes !!}
      @endif
    >
    <label class="custom-file-label" for={{$id_name_edit}}>{{__('forms.choose_file')}}</label>
  </div>
@if($form)</div>@endif
