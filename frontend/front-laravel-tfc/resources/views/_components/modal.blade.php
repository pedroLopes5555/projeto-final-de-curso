@if(!isset($show) || $show)
<div class="modal fade simple-modal {{isset($open)?'show':''}}" id="{{$id}}" role="dialog" aria-hidden="true">
  <div class="modal-dialog @if(isset($class)) {{$class}} @endif" role="document">
    <div class="modal-content">
      {{$slot}}
    </div>
  </div>
</div>
@endif
