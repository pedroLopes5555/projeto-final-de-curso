<nav aria-label="breadcrumb">
  <ol class="breadcrumb {{isset($container_class)?$container_class:''}}">
    <li class="breadcrumb-item"><a class="n2-override" href="/">{{__('general.home')}}</a></li>
    @foreach($links as $url => $link)
      @if(is_numeric($url))
      <li class="breadcrumb-item active" aria-current="page">{{$link}}</li>
      @else
      <li class="breadcrumb-item"><a class="n2-override" href="{{$url}}">{{$link}}</a></li>
      @endif
    @endforeach
  </ol>
</nav>
