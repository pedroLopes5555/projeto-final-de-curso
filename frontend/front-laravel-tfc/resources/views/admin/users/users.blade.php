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

  <table id="dt" datatable ajax-url="/api/admin/table/users" ajax-id="user_id" datatable-hide="-1">
  <thead>
    <tr>
      <th dt-name="user_id">Id</th>
      <th dt-name="user_name">Nome</th>
      <th dt-name ="user_super">Admin</th>
      <th dt-name="user_super">Permissão</th>
      <th>Opções</th>
    </tr>
  </thead>
  <tbody></tbody>
  </table>

  <script id="dt-template" type="text/template">
  <tr option-key="${user_id}">
    <td>${user_id}</td>
    <td>${user_name}</td>
    <td>${user_super?'Sim':'Não'}</td>
    <td>${perm.perm_name}</td>
    <td>
      <i class="fas fa-plus" option-key="${user_id}" option="plus"></i>
      <i class="fas fa-edit" option-key="${user_id}" option="edit"></i>
      <i class="fas fa-trash" option-key="${user_id}" modal="modal-delete" option="delete"></i>
    </td>
  </tr>
  </script>

  @component('_components.cardModal', [
    'id' => 'modal-plus',
    'class' => 'modal-success',
    'title' => 'Criar Microcontrolador',
    'close' => true
  ])
    <form id="form-plus">
      @csrf
      <input type="hidden" name="user_id" />
      <div class="form-group">
        <label>Nome</label>
        <input type="text" class="form-control" name="microcontroller_name" required>
      </div>
      <div class="form-group">
        <label>MAC Address</label>
        <input type="text" class="form-control" name="microcontroller_guid" required>
      </div>
    </form>
    @slot('footer')
      <input type="submit" class="btn btn-success" value="Criar" form="form-plus"/>
    @endslot
  @endcomponent


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
          <input type="text" class="form-control" id="user_name" name="user_name" required>
        </div>
        <div class="form-group">
          <label for="user_pass">Palavra-Passe</label>
          <input type="text" class="form-control" id="user_pass" name="user_pass" required>
        </div>
        <div class="form-group">
                        <div><label>Permissão</label></div>
                        @component('_components.formSelect', [
                        'required' => true,
                        'class' => '',
                        'attributes' => 'ajax-url="/api/select/perms"',
                        'name' => 'perm_id',
                        'placeholder' => 'Escolhe a Permissão',
                        'array' => [],
                        'key' => 'perm_id',
                        'value' => 'perm_name',
                        ])@endComponent
                    </div>
        <div class="form-group">
        <label>Administrador</label>
        <div><input type="checkbox" name="user_super" ></div>
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
					<div class="form-group">
						<label>Nome</label>
						<input type="text" class="form-control" name="user_name" required>
					</div>
					<div class="form-group">
						<label>Palavra-Passe</label>
						<input type="text" class="form-control" name="user_pass" placeholder="(unchanged)" disabled>
					</div>
					<div class="form-group">
              <div><label>Permissão</label></div>
              @component('_components.formSelect', [
              'required' => true,
              'class' => '',
              'attributes' => 'ajax-url="/api/select/perms" fill="perm:perm_id|perm_name"',
              'name' => 'perm_id',
              'placeholder' => 'Escolhe a Permissão',
              'array' => [],
              'key' => 'perm_id',
              'value' => 'perm_name',
              ])@endComponent
          </div>
					<div class="form-group">
						<label>Administrador</label>
						<div><input type="checkbox" name="user_super" ></div>
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
      <input type="hidden" name="user_id" />
					<div class="modal-body">
						<p>Tens a certeza que queres apagar este Utilizador?</p>
						<div class="form-group">
						<label>Nome:</label>
						<input type="text" class="form-control" name="user_name" disabled>
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

let modalPlus = document.getElementById('modal-plus');
let $modalPlus = $(modalPlus);
let formPlus = document.getElementById('form-plus');
let $formPlus = $(formPlus);
$formPlus.submit(e => {
  e.preventDefault();
  $.ajax({
    url: '/api/admin/microcontroller/new',
    method: 'POST',
    data: $formPlus.serialize(),
    success: data => {
      $modalPlus.modal('hide');
      dt.refresh();
      toastr.success('Microcontrolador criado com sucesso!');
    },
    error: e => {
      toastr.error('Erro ao criar microcontrolador!');
    }
  });
});

let modalNew = document.getElementById('modal-new');
let $modalNew = $(modalNew);
let formNew = document.getElementById('form-new');
let $formNew = $(formNew);
$formNew.submit(e => {
  e.preventDefault();
  $.ajax({
    url: '/api/admin/user/new',
    method: 'POST',
    data: $formNew.serialize(),
    success: data => {
      $modalNew.modal('hide');
      dt.refresh();
      toastr.success('Utilizador criado com sucesso!');
    },
    error: e => {
      toastr.error('Erro ao criar utilizador!');
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
    url: '/api/admin/user/update',
    method: 'POST',
    data: $formEdit.serialize(),
    success: data => {
      $modalEdit.modal('hide');
      dt.refresh();
      console.log(data);
      toastr.success('Utilizador editado com sucesso!');
    },
    error: e => {
      toastr.error('Erro ao editar utilizador!');
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
    url: '/api/admin/user/delete',
    method: 'POST',
    'data': $formDelete.serialize(),
    success: data => {
      $modalDelete.modal('hide');
      dt.refresh();
      toastr.success('Utilizador apagado com sucesso!');
    },
    error: e => {
      toastr.error('Erro ao apagar utilizador!');
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
      $formEdit.find('input[name="user_id"]').val(key);
      Utils.fill_form(formEdit, object);
      $modalEdit.modal('show');
      break;
    }
    case 'delete': {
      // fill id 
      $formDelete.find('input[name="user_id"]').val(key);
      Utils.fill_form(modalDelete, object);
      $modalDelete.modal('show');
      break;
    }
    case 'plus': {
      // fill id
      $formPlus.find('input[name="user_id"]').val(key);
      $modalPlus.modal('show');
      break;
    }	
  }
});

</script>
@endSection

