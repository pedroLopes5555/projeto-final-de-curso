<div {{isset($id)?'id='.$id:''}} class="card {{isset($close)?'with-close':''}} {{isset($class)?$class:''}} {{isset($badge)?'with-badge':''}} {{isset($back)?'with-back':''}}">
  <h3 class="card-header">
    @if(isset($back))
      <a href="{{$back}}" class="card-back">
        <i class="css-arrow back"></i>
      </a>
    @endif
    {{$title}}
    @if(isset($badge))
      <?php
        if(!is_array($badge)) $badge = [[$badge]];
        if(!is_array($badge[0])) $badge = [$badge];
        $colors = [];
        foreach($badge as $index => &$b){
          $colors[$index] = [];
          $keys = array_keys($b);
          foreach($keys as $key){
            if(is_string($key)){
              $colors[$index][$key] = $b[$key];
              unset($b[$key]);
            }
          }
          unset($b);
        }
        $last = count($badge)-1;

        foreach($colors as &$c){
          if(isset($c['background'])) $c['background'] = 'background-color: '.$c['background'].';';
          else $c['background'] = '';
          if(isset($c['color'])) $c['color'] = 'color: '.$c['color'].';';
          else $c['color'] = '';
        }
      ?>
      @foreach($badge as $index => $b)
      <span class="badge-group">@foreach($b as $bb)<span class="badge badge-dark card-badge"
          style="{{$colors[$index]['background']}}{{$colors[$index]['color']}}"
        >{{$bb}}</span>@endforeach</span>
      @endforeach
    @endif
    @if(isset($close) && $close)
      <a class="close-button" data-dismiss="modal" aria-label="Close"></a>
    @endif
  </h3>
  <div class="card-body">
    @if(isset($top))
      <div class="row card-top">
        {{$top}}
      </div>
    @endif
    {{$slot}}
  </div>
</div>
