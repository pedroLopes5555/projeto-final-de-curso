@component('_components.cardModal', [
  'id' => 'media-upload-modal',
  'title' => 'Upload Media',
  'class' => 'modal-lg',
  'close' => true
])
<div id="media-upload-form" api-call="media.new" class="row">
  <div class="form-group col-lg-12">
    <label>Nome</label>
    <input type="text" class="form-control" name="media_name" />
  </div>
  <div class="form-group col-lg-12">
    <label>Imagem</label>
    <div>
      <img id="media-upload-image" style="max-width: 300px; max-width: 300px;" />
      <!-- <input type="file" name="file" style=""/> -->
    </div>
  </div>
</div>

@slot('footer')
<button class="btn btn-link" data-bs-dismiss="modal">Cancelar</button>
<button api-for="media-upload-form" class="btn btn-success">Upload</button>
@endslot
@endComponent
