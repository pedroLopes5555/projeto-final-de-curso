@component('_components.cardModal', [
  'id' => 'image-manager',
  'title' => __('forms.choose_image'),
  'class' => 'modal-lg',
  'close' => true
])

  <div id="im-browser-buttons">
    <button id="im-browser-new" class="btn btn-success">{{__('forms.add')}}</button>
    <input id="im-browser-search" class="form-control" type="text" placeholder="{{__('forms.filter')}}" autocomplete="off" style="display: none"/>
  </div>
  <div id="im-browser">
    <div id="im-edit" class="im-form">
      <div class="im-form-img" style=""></div>
      <div class="w-100">
        <div id="im-edit-body"></div>
        <button class="im-form-cancel btn btn-light">{{__('forms.cancel')}}</button>
        <button class="im-form-confirm btn btn-success">{{__('forms.confirm')}}</button>
      </div>
    </div>
    <div id="im-delete" class="im-form">
      <div class="im-form-img" style=""></div>
      <div class="w-100">
        <div class="form-group">
          <label>{{__('forms.name')}}</label>
          <input class="form-control" placeholder="{{__('forms.add_name')}}" disabled/>
        </div>
        <!-- <div class="form-group">
          <label><%- __('n_of_articles') %>: <span class="n_of_articles"></span></label>
        </div> -->
        <button class="im-form-cancel btn btn-light">{{__('forms.cancel')}}</button>
        <button class="im-form-confirm btn btn-danger">{{__('forms.delete')}}</button>
      </div>
    </div>
    <div id="im-list"></div>
  </div>

  @if(!isset($buttons) || $buttons)
  @slot("footer")
    <button class="btn btn-light" data-dismiss="modal">{{__('forms.cancel')}}</button>
    <button id="im-accept" class="btn btn-success">{{__('forms.confirm')}}</button>
  @endSlot
  @endif
@endComponent
