<!DOCTYPE html>
<html>
<head>
  @include('_layouts.admin.head')
</head>
<body id="layout">
  @include('_layouts.admin.header')
  @include('_layouts.admin.sidebar')
  <div id="layout-body">
    <div class="card">
      <div class="card-header">
        <?php
          if(!isset($breadcrumbs)) $breadcrumbs = [];
          $breadcrumb_title = $breadcrumbs[0] ?? '';
          if(isset($breadcrumbs[1])) unset($breadcrumbs[0]);
        ?>
        <h5>{{$breadcrumb_title}}</h5>
        <ul class="breadcrumb breadcrumb-card">
          @foreach($breadcrumbs as $href => $name)
          <li class="breadcrumb-item text-muted">
            @if(is_int($href))
            <span class="text-muted">{{$name}}</a>
            @else
            <a href="{{$href}}">{{$name}}</a>
            @endif
          </li>
          @endforeach
        </ul>
        <div id="toolbar-buttons">
          @hasSection('links')
          <div>
            @yield('links')
          </div>
          @endif
          <div>
            @yield('buttons')
          </div>
        </div>
      </div>
      <div class="card-body">
        @yield('body')
      </div>
      @hasSection('card-footer')
      <div class="card-footer">
        @yield('card-footer')
      </div>
      @endif
    </div>
  </div>

  @error('popup-error')
    @component('_components.cardModal', [
      'id' => 'modal-error',
      'class' => 'modal-danger show',
      'title' => 'Erro',
      'close' => true
    ])
      {{$message}}

      @slot('footer')
        <button class="btn btn-danger" data-dismiss="modal">OK</button>
      @endslot
    @endcomponent
    <script>
      window.addEventListener('load', e => {
        let modalError = document.getElementById('modal-error');
        $(modalError).modal('show');
      });
    </script>
  @enderror

	@component('_components.apiErrorModal')@endcomponent
  <script src="/lib/jquery/jquery.min.js"></script>
  <script src="/lib/bootstrap/js/bootstrap.bundle.min.js"></script>
  <script src="/lib/select2/select2.min.js"></script>
  <script src="/lib/sortable/sortable.min.js"></script>
  <script src="/lib/simplemde/simplemde.min.js"></script>
  <script src="/lib/datatables/datatables.min.js"></script>

  <script src="/js/admin.js?t=1684922036"></script>
	<script src="/js/api.js?t=1684922036"></script>
	@yield('scripts')
</body>
</html>
