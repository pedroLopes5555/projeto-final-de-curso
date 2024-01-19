<?php
	$_types = \App\Models\Image::TYPES;
	$__types = [];
	foreach($_types as $_key => $_value){
		$__types[] = ['key' => $_key, 'value' => implode("x",$_value)];
	}
	$__types[] = ['key' => 'ui', 'value' => 'UI Images'];
?>

@component('_components.cardModal', [
  'id' => 'image-manager',
  'title' => __('forms.choose_image'),
  'class' => 'modal-lg',
  'close' => true
])

  <!-- Nav tabs -->
  <ul class="nav nav-tabs nav-tabs-up" id="myTab" role="tablist">
    <li class="nav-item" role="presentation">
      <button class="nav-link active" id="image-tab" data-bs-toggle="pill" data-target="[id=&quot;im-tab:image&quot;]" type="button" role="tab" aria-controls="images" aria-selected="true">Imagens</button>
    </li>
    <li class="nav-item" role="presentation">
      <button class="nav-link" id="media-tab" data-bs-toggle="pill" data-target="[id=&quot;im-tab:media&quot;]" type="button" role="tab" aria-controls="media" aria-selected="false">Media</button>
    </li>
  </ul>

  <!-- Tab panes -->
  <div class="tab-content">
    <div class="tab-pane active" id="im-tab:image" role="tabpanel" aria-labelledby="image-tab">
      <div class="im-browser-buttons">
        <input class="im-browser-search form-control" type="text" placeholder="{{__('forms.search')}}" autocomplete="off"/>
        <a href="/panel/images?new" target="_blank" class="btn btn-success external">{{__('forms.add')}}</a>
      </div>
      <div class="im-list">
        <div class="im-list-inner"></div>
        <div intersect></div>
      </div>
    </div>
    <div class="tab-pane" id="im-tab:media" role="tabpanel" aria-labelledby="media-tab">
      <div class="im-browser-buttons">
        <input class="im-browser-search form-control" type="text" placeholder="{{__('forms.search')}}" autocomplete="off"/>
        <a href="/panel/media" target="_blank" class="btn btn-success external">{{__('forms.add')}}</a>
      </div>
      <div class="im-list">
        <div class="im-list-inner"></div>
        <div intersect></div>
      </div>
    </div>
  </div>

  @if(!isset($buttons) || $buttons)
  @slot("footer")
		<div style="margin-right: auto;width: 150px">
	    @component('_components.formSelect', [
	      'id' => 'image-manager-size',

	      'array' => $__types,
				'key' => 'key',
				'value' => 'value',
	    ])@endComponent
		</div>
    <button class="btn btn-light" data-bs-dismiss="modal">{{__('forms.cancel')}}</button>
    <button id="im-accept" class="btn btn-success">{{__('forms.confirm')}}</button>
  @endSlot
  @endif
@endComponent
