@extends('_layouts.admin.normal', [
  'menu_open' => 'admin',
  'breadcrumbs' => [
    '/admin' => 'Admin',        
    'Users' => 'users'
  ],
  'cantSee' => false
])

@section('buttons')
    <button data-target="#modal-new" data-toggle="modal" class="btn btn-success">Criar</button>
@endsection

@section('body')

  <table id="dt" datatable ajax-url="/api/admin/table/perms" ajax-id="perm_id" datatable-hide="-1">
  <thead>
    <tr>
      <th dt-name="perm_id">Id</th>
      <th dt-name="perm_name">Nome</th>
      <th>Opções</th>
    </tr>
  </thead>
  <tbody></tbody>
  </table>

  <script id="dt-template" type="text/template">
  <tr option-key="${perm_id}">
    <td>${perm_id}</td>
    <td>${perm_name}</td>
    <td>
      <i class="fas fa-edit" option-key="${perm_id}" option="edit"></i>
      <i class="fas fa-trash" option-key="${perm_id}" modal="modal-delete" option="delete"></i>
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
            <label for="user_name">Nome</label>
            <input type="text" class="form-control" id="perm_name" name="perm_name" required>
        </div>
        <div class="form-group">
        <label>Permissões</label>
        @component('_components.formSelect', [
        'multiple' => true,
        'required' => true,
        'class' => '',
        'attributes' => 'fill="relations:perm_name|perm_name"',
        'name' => 'perm_names[]',
        'placeholder' => 'Escolhe as Permissões',
        'array' => $perm_areas,
        ])@endComponent
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
      <input type="hidden" name="user_id" />
      <input type="hidden" name="perm_id" />
      <div class="form-group">
          <label>Nome</label>
          <input type="text" class="form-control" name="perm_name" required>
      </div>
      <div class="form-group">
          <label>Permissões</label>
          @component('_components.formSelect', [
          'multiple' => true,
          'required' => true,
          'class' => '',
          'attributes' => 'fill="relations:perm_name|perm_name"',
          'name' => 'perm_names[]',
          'placeholder' => 'Escolhe as Permissões',
          'array' => $perm_areas,
          ])@endComponent
      </div>
    </form>
    @slot('footer')
      <input type="submit"class="btn btn-success" value="Edit" form="form-edit"/>
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
      <input type="hidden" name="perm_id" />
        <div class="modal-body">
            <p>Tens a certeza que queres apagar esta Permissão?</p>
            <div class="form-group">
            <label>Nome:</label>
            <input type="text" class="form-control" name="perm_name" disabled>
        </div>
            <p class="text-danger"><small>Esta ação não pode ser revertida.</small></p>
        </div>
    </form>
    @slot('footer')
      <input type="submit" class="btn btn-danger" value="Apagar" form="form-delete"/>
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
    url: '/api/admin/perm/new',
    method: 'POST',
    data: $formNew.serialize(),
    success: data => {
      $modalNew.modal('hide');
      dt.refresh();
      toastr.success('Permissão criado com sucesso!');
    },
    error: e => {
      toastr.error('Erro ao criar Permissão!');
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
    url: '/api/admin/perm/update',
    method: 'POST',
    data: $formEdit.serialize(),
    success: data => {
      $modalEdit.modal('hide');
      dt.refresh();
      console.log(data);
      toastr.success('Permissão editado com sucesso!');
    },
    error: e => {
      toastr.error('Erro ao editar Permissão!');
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
    url: '/api/admin/perm/delete',
    method: 'POST',
    'data': $formDelete.serialize(),
    success: data => {
      $modalDelete.modal('hide');
      dt.refresh();
      toastr.success('Permissão apagado com sucesso!');
    },
    error: e => {
      toastr.error('Erro ao apagar Permissão!');
    }
  });
});

window.addEventListener('option-click', e => {
  let key = e.key;
  let option = e.option;
  let object = dt.ajaxJson.index[key];

  switch(option){
    case 'edit': {
      // fill id
      $formEdit.find('input[name="perm_id"]').val(key);
      Utils.fill_form(formEdit, object);
      $modalEdit.modal('show');
      break;
    }
    case 'delete': {
      // fill id 
      $formDelete.find('input[name="perm_id"]').val(key);
      Utils.fill_form(modalDelete, object);
      $modalDelete.modal('show');
      break;
    }
  }
});

</script>
@endSection

