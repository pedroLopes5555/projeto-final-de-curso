@extends('_layouts.admin.normal',[
    'menu_open' => 'containers',
    'breadcrumbs' => [
        '/admin' => 'Admin',
        'Containers'
  ]
])

@section('buttons')
<button data-target="#modal-new" data-toggle="modal" class="btn btn-success">Criar</button>
@endsection

@section('body')

  <table id="dt" datatable ajax-url="/api/admin/table/containers" ajax-id="container_id" datatable-hide="-1">
  <thead>
    <tr>
      <th dt-name="container_name">Name</th>
        <th dt-name="container_location">Localização</th>
      <th>Opções</th>
    </tr>
  </thead>
  <tbody></tbody>
  </table>

  <script id="dt-template" type="text/template">
  <tr option-key="${container_id}">
    <td>${container_name}</td>
    <td>${container_location}</td>
    <td>
      <!-- check details of the container that links to route /admin/containers/${container_id} -->
      <a href="/admin/containers/${container_id}" style="color:black;"><i class="fas fa-eye"></i></a>
      <i class="fas fa-edit" option-key="${container_id}" option="edit"></i>
      <i class="fas fa-trash" option-key="${container_id}" modal="modal-delete" option="delete"></i>
    </td>
  </tr>
  </script>


  @component('_components.cardModal', [
    'id' => 'modal-new',
    'class' => 'modal-success',
    'title' => 'Criar',
    'close' => true
  ])
    <form id="form-new">
      @csrf
        <div class="form-group">
          <label>Nome</label>
          <input type="text" class="form-control" name="container_name" maxlength="64" required/>
        </div>
        <div class="form-group">
          <label>Localização</label>
          <input type="text" class="form-control" name="container_location" maxlength="64" required/>
        </div>
        <div class="form-group">
          <label>Ph Desejado(0-14)</label>
          <input type="number" step="0.01" class="form-control" name="value_ph" min="0" max="14" required/>
        </div>
        <div class="form-group">
          <label>Temperatura Desejada(ºC)</label>
          <input type="number" step="0.01" class="form-control" name="value_temp" required/>
        </div>
        <div class="form-group">
          <label>Conductividade Desejada(µS/cm)</label>
          <input type="number" step="0.01" class="form-control" name="value_electric_condutivity" required/>
        </div>
    </form>
    @slot('footer')
      <input type="submit" class="btn btn-success" value="Criar" form="form-new"/>
    @endslot
  @endcomponent


  @component('_components.cardModal', [
    'id' => 'modal-edit',
    'class' => 'modal-success',
    'title' => 'Editar',
    'close' => true
  ])
    <form id="form-edit">
      @csrf
      <input type="hidden" name="container_id"/>
      <div class="form-group">
        <label>Nome</label>
        <input type="text" class="form-control" name="container_name" maxlength="64" required/>
      </div>
      <div class="form-group">
        <label>Localização</label>
        <input type="text" class="form-control" name="container_location" maxlength="64" required/>
      </div>
      <div class="form-group">
        <label>Ph Desejado(0-14)</label>
        <input type="number" step="0.01" class="form-control" name="value_ph" min="0" max="14" required/>
      </div>
      <div class="form-group">
        <label>Temperatura Desejada(ºC)</label>
        <input type="number" step="0.01" class="form-control" name="value_temp" required/>
      </div>
      <div class="form-group">
        <label>Conductividade Desejada(µS/cm)</label>
        <input type="number" step="0.01" class="form-control" name="value_electric_condutivity" required/>
      </div>
    </form>
    @slot('footer')
      <input type="submit" form="form-edit" class="btn btn-success" value="Edit"/>
    @endslot
  @endcomponent

  @component('_components.cardModal', [
    'id' => 'modal-delete',
    'class' => 'modal-danger',
    'title' => 'Apagar',
    'close' => true
  ])
    <form id="form-delete">
      @csrf
      <input type="hidden" name="container_id"/>
      <div class="form-group">
        <label>Nome</label>
        <input type="text" class="form-control" name="container_name" disabled/>
      </div>
    </form>
    @slot('footer')
      <input type="submit" form="form-delete" class="btn btn-danger" value="Apagar"/>
    @endslot
  @endcomponent


@endsection

@section('scripts')
<script>
let dt = document.getElementById('dt');

let modalNew = document.getElementById('modal-new');
let $modalNew = $(modalNew);
let formNew = document.getElementById('form-new');
let $formNew = $(formNew);
$formNew.submit(e => {
  e.preventDefault();
  $.ajax({
    url: '/api/admin/container/new',
    method: 'POST',
    data: $formNew.serialize(),
    success: data => {
      $modalNew.modal('hide');
      dt.refresh();
      toastr.success('Contentor criado com sucesso!');
    },
    error: e => {
      toastr.error('Erro ao criar Contentor!');
    }
  });
});

let modalEdit = document.getElementById('modal-edit');
let $modalEdit = $(modalEdit);
let formEdit = document.getElementById('form-edit');
let $formEdit = $(formEdit);
$formEdit.submit(e => {
  e.preventDefault();

  $.ajax({
    url: '/api/admin/container/update',
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

let modalDelete = document.getElementById('modal-delete');
let $modalDelete = $(modalDelete);
let formDelete = document.getElementById('form-delete');
let $formDelete = $(formDelete);
$formDelete.submit(e => {
  e.preventDefault();

  $.ajax({
    url: '/api/admin/container/delete',
    method: 'POST',
    'data': $formDelete.serialize(),
    success: data => {
      $modalDelete.modal('hide');
      dt.refresh();
      toastr.success('Contentor apagado com sucesso!');
    },
    error: e => {
      toastr.error('Erro ao apagar Contentor!');
    }
  });
});

window.addEventListener('option-click', e => {
  let key = e.key;
  let option = e.option;
  let object = dt.ajaxJson.index[key];
  console.log(object);
  switch(option){
    case 'edit': {
      // fill id
      object.value_ph = object.target_value.value_ph;
      object.value_temp = object.target_value.value_temp;
      object.value_electric_condutivity = object.target_value.value_electric_condutivity;
      $formEdit.find('input[name="container_id"]').val(key);
      
      Utils.fill_form(formEdit, object);
      $modalEdit.modal('show');
      break;
    }
    case 'delete': {
      // fill id 
      $formDelete.find('input[name="container_id"]').val(key);
      Utils.fill_form(modalDelete, object);
      $modalDelete.modal('show');
      break;
    }
  }
});
</script>
@endsection
