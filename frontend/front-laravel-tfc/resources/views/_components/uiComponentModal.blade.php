@component('_components.cardModal', [
  'id' => 'component-modal',
  'title' => 'Escolher Componente',
  'class' => 'modal-lg',
  'close' => true
])
<div id="component-modal-form">
  <div class="form-group">
    @component('_components.formSelect', [
      'id' => 'component-modal-select',
      'name' => 'paco_id',
      'placeholder' => 'Escolhe o componente',

      'array' => $uiComponents,
      'key' => 'name',
      'value' => 'name'
    ])@endcomponent
  </div>
</div>

@slot('footer')
<button id="component-modal-success" class="btn btn-success">Selecionar</button>
<button id="component-modal-cancel" class="btn btn-link" data-dismiss="modal">Cancelar</button>
@endslot
@endComponent

@foreach($uiComponents as $_ui)
@uiTemplate($_ui['name'])
@endforeach

<script>
  window.addEventListener('load', e => {
    let uiComponents = @json($uiComponents);
    window.showComponentModal.beforeSucc = function(name){
      return uiComponents[name].example;
    }
  });
</script>
