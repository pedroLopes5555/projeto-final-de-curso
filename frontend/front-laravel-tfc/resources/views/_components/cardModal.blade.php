@if(!isset($show) || $show)
<div class="modal fade {{isset($open)?'show':''}}" id="{{$id}}" role="dialog" aria-hidden="true" tabindex="-1">
  <div class="modal-dialog @if(isset($class)) {{$class}} @endif" role="document">
    <div class="modal-content">
      @component('_components.card', [
        'title' => $title,
        'badge' => isset($badge)?$badge:null,
        'close' => isset($close) && $close,
        'top' => isset($top)?$top:null
      ])
        {{$slot}}
      @endcomponent
      @if(isset($footer))
      <div class="modal-footer">
        {{$footer}}
      </div>
      @endif
    </div>
  </div>
</div>
@endif
