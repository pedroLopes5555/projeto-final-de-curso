@extends('_layouts.admin.normal',[
    'menu_open' => 'containers',
    'breadcrumbs' => [
        '/admin' => 'Admin',
        'Microcontroladores'
  ]
])


@section('buttons')
@endsection

@section('body')

  <table id="dt" datatable ajax-url="/api/admin/table/arduinos" ajax-id="arduino_id" datatable-hide="-1">
  <thead>
    <tr>
      <th dt-name="arduino_guid">GUID</th>
      <th dt-name="arduino_name">Name</th>
        <th dt-name="arduino.container.container_name">Container</th>
        <th dt-name="arduino_active">Ativo</th>
      <th>Opções</th>
    </tr>
  </thead>
  <tbody></tbody>
  </table>

  <script id="dt-template" type="text/template">
  <tr option-key="${arduino_id}">
    <td>${arduino_guid}</td>
    <td>${arduino_name}</td>
    <td>${container?container.container_name:'Nenhum'}</td>
    <td>${container?'<i class="fas fa-check" style="color:green"></i>':'<i class="fas fa-times" style="color:red"></i>'}</td>
    <td>
      <i class ="fas fa-exchange-alt" option-key="${arduino_id}" option="edit"></i>
    </td>
  </tr>
  </script>

  @component('_components.cardModal', [
    'id' => 'modal-edit',
    'class' => 'modal-success',
    'title' => 'Editar',
    'close' => true
  ])
    <form id="form-edit">
      @csrf
      <input type="hidden" name="arduino_id"/>
        <div class="form-group">
        <label><label>Contentor</label></label><br>
        @component('_components.formSelect', [
            'required' => true,
            'class' => '',
            'attributes' => 'ajax-url="/api/select/containers" fill="container:container_id|container_name"',
            'name' => 'container_id',
            'placeholder' => 'Escolhe um Contentor',
            'array' => [],
            'key' => 'id',
            'value' => 'title',
        ])@endComponent
        </div>
    </form>
    @slot('footer')
      <input type="submit" form="form-edit" class="btn btn-success" value="Edit"/>
    @endslot
  @endcomponent


@endsection

@section('scripts')
<script>


let dt = document.getElementById('dt');

let modalEdit = document.getElementById('modal-edit');
let $modalEdit = $(modalEdit);
let formEdit = document.getElementById('form-edit');
let $formEdit = $(formEdit);
$formEdit.submit(e => {
  e.preventDefault();

  $.ajax({
    url: '/api/admin/micro/update',
    method: 'POST',
    data: $formEdit.serialize(),
    success: data => {
      $modalEdit.modal('hide');
      dt.refresh();
      console.log(data);
      toastr.success('Contentor editado com sucesso!');
    },
    error: e => {
      toastr.error('Erro ao editar Contentor!');
    }
  });
});

window.addEventListener('option-click', e => {
  let key = e.key;
  let option = e.option;
  let object = dt.ajaxJson.index[key];

  switch(option){
    case 'edit': {
      console.log('This is the edit option!', key, object);
      $formEdit.find('input[name="arduino_id"]').val(key);
      Utils.fill_form(formEdit, object);
      $modalEdit.modal('show');
      break;
    }
  }
});

</script>
@endSection
