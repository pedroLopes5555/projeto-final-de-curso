@extends('_layouts.admin.normal',[
    'menu_open' => 'containers',
    'breadcrumbs' => [
        '/admin' => 'Admin',
        'Containers'
  ]
])

@section('buttons')
<button data-target="#modal-merge" data-toggle="modal" class="btn btn-success">Update</button>
<button data-target="#modal-new" data-toggle="modal" class="btn btn-success">Create</button>
@endsection

@section('body')

  <table id="dt" datatable ajax-url="/api/admin/table/containers" ajax-id="container_id" datatable-hide="-1">
  <thead>
    <tr>
      <th dt-name="container_name">Name</th>
      <th dt-name="container_dimension">Capacity</th>
        <th dt-name="container_location">Container</th>
      <th>Opções</th>
    </tr>
  </thead>
  <tbody></tbody>
  </table>

  <script id="dt-template" type="text/template">
  <tr option-key="${container_id}">
    <td>${container_name}</td>
    <td>${container_dimension}</td>
    <td>${container_location}</td>
    <td>
      <i class="fas fa-pencil-alt" option="edit"></i>
      <i class="fas fa-trash-alt" option="delete"></i>
    </td>
  </tr>
  </script>

  @component('_components.cardModal', [
    'id' => 'modal-new',
    'class' => 'modal-success',
    'title' => 'Criar',
    'close' => true
  ])
    <div id="form-new" api-call="countryCity.new">
      <div class="form-group">
        <label>Name</label>
        <input type="text" class="form-control" name="container_name" maxlength="64" required/>
      </div>
      <div class="form-group">
        <label>Capacity</label>
        <input type="number" class="form-control" name="container_dimension" required/>
      </div>
      <div class="form-group">
        <Label>Location</label>
        <input type="text" class="form-control" name="container_location" maxlength="64" required/>
      </div>
    </div>
    @slot('footer')
      <input type="submit" api-submit api-for="form-new" class="btn btn-success" value="Criar"/>
    @endslot
  @endcomponent

  @component('_components.cardModal', [
    'id' => 'modal-edit',
    'class' => 'modal-success',
    'title' => 'Editar',
    'close' => true
  ])
    <div id="form-edit" api-call="">
      <div class="form-group">
        <label>Name</label>
        <input type="text" class="form-control" name="container_name" maxlength="64" required/>
      </div>
      <div class="form-group">
        <label>Capacity</label>
        <input type="number" class="form-control" name="container_dimension" required/>
      </div>
      <div class="form-group">
        <label>Location</label>
        <input type="text" class="form-control" name="container_location" maxlength="64" required/>
      </div>
    </div>
    @slot('footer')
      <input type="submit" api-submit api-for="form-edit" class="btn btn-success" value="Edit"/>
    @endslot
  @endcomponent

  @component('_components.cardModal', [
    'id' => 'modal-delete',
    'class' => 'modal-danger',
    'title' => 'Apagar',
    'close' => true
  ])
    <div id="form-delete" api-empty api-call="">
      @method('delete')
      <div class="form-group">
        <label>Nome</label>
        <input type="text" class="form-control" name="country_city_name" disabled/>
      </div>
      <div class="form-group">
        <label>Nome EN</label>
        <input type="text" class="form-control" name="country_city_name_en" disabled/>
      </div>
    </div>
    @slot('footer')
      <input type="submit" api-submit api-for="form-delete" class="btn btn-danger" value="Apagar"/>
    @endslot
  @endcomponent


@endsection

@section('scripts')
<script>
let dt = document.getElementById('dt');

let modalMerge = document.getElementById('modal-merge');
let $modalMerge = $(modalMerge);
let formMerge = modalMerge.querySelector('[api-call]');
formMerge.addEventListener('api-response', e => {
	if(!e.isOK) return;

	dt.refresh();
	$modalMerge.modal('hide');
});
let selectMerge = formMerge.querySelector('[select2]');

let modalNew = document.getElementById('modal-new');
let $modalNew = $(modalNew);
let formNew = modalNew.querySelector('[api-call]');
formNew.addEventListener('api-response', e => {
	if(!e.isOK) return;

	dt.refresh();
	$modalNew.modal('hide');
});

let modalEdit = document.getElementById('modal-edit');
let $modalEdit = $(modalEdit);
let formEdit = modalEdit.querySelector('[api-call]');
formEdit.addEventListener('api-response', e => {
	if(!e.isOK) return;

	dt.refresh();
	$modalEdit.modal('hide');
});

let modalDelete = document.getElementById('modal-delete');
let $modalDelete = $(modalDelete);
let formDelete = modalDelete.querySelector('[api-call]');
formDelete.addEventListener('api-response', e => {
	if(!e.isOK) return;

	dt.refresh();
	$modalDelete.modal('hide');
});

window.addEventListener('option-click', e => {
  let key = e.key;
  let option = e.option;
  let object = dt.ajaxJson.index[key];

  switch(option){
    case 'edit': {
      formEdit.setApiCall(`countryCity.edit(${key})`);
      Utils.fill_form(formEdit, object);
      $modalEdit.modal('show');
      break;
    }
    case 'delete': {
      formDelete.setApiCall(`countryCity.delete(${key})`);
      Utils.fill_form(modalDelete, object);
      $modalDelete.modal('show');
      break;
    }
  }
});

</script>
@endSection
