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

      'array' => $pacos,
      'key' => 'paco_id',
      'value' => 'paco_name'
    ])@endcomponent
  </div>
</div>

@slot('footer')
<button id="component-modal-success" class="btn btn-success">Selecionar</button>
<button id="component-modal-cancel" class="btn btn-link" data-dismiss="modal">Cancelar</button>
@endslot
@endComponent
