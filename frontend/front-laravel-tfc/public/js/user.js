Utils = {};


$(document).ready(function(){


  // Enable Selects
  let selects = document.querySelectorAll('[select2]');
  Utils.enableSelect = function(select){
    let data = {
      language: 'pt'
    };
    $select = $(select);

    let preprocess = select.preprocess = [];

    let modal = $select.closest(".modal-content");
    let isMultiple = select.hasAttribute("multiple");

    if(select.classList.contains('select-simple'))
      data["minimumResultsForSearch"] = -1;

    let placeholder = $select.data("placeholder");
    if(placeholder){
       data.placeholder = placeholder;
       data.allowClear = true;
    }
    if(modal[0]){
      data.dropdownParent = modal;
    }
    if($select.is('[create-new]')){
      let isMultiple = select.hasAttribute("multiple");
      let newName = select.name+"_new";
      if(isMultiple){
        newName = select.name.substring(0, select.name.length-2)+"_new";
      }
      function makeNewName(index){
        if(isMultiple)
          return newName+`[${index}]`;
        return newName;
      }
      select._newOptions = [];

      Object.assign(data, {
        tags: true,
        createTag: function(params){
          return {
            id: params.term,
            text: params.term,
            newOption: true
          }
        },
        insertTag: function(data, tag){
          data.push(tag);
        },
        templateSelection: function(data){
          let el = innerTemplateSelection(data);

          if(select._newOptions.length){
            select._newOptions.forEach(option => option.remove());
            select._newOptions = [];
          }
          let frag = document.createDocumentFragment();
          [...select.selectedOptions].forEach((option, index) => {
            let isNew = option.hasAttribute("data-select2-tag");
            if(isNew){
              let hidden = $(`<input type="hidden" name="${makeNewName(index)}" value="true"/>`)[0];
              select._newOptions.push(hidden);
              frag.appendChild(hidden);
            }
          });
          if(frag.lastChild)
            select.parentNode.insertBefore(frag, select);
          return el;
        },
        templateResult: function(data){
          let $result = innerTemplateResult(data);
          if(data.newOption){
            if(data.text=="") return;
            $result.append(" (Criar Novo)");
          }
          return $result;
        }
      });
    }else{
      Object.assign(data, {
        templateSelection: innerTemplateSelection,
        templateResult: innerTemplateResult
      })
    }
    function innerTemplateSelection(data){
      let select = data.element?data.element.parentNode:{};
      if(!select._templateSelection) return $(`<span>${data.text}</span>`);
      return select._templateSelection(data);
    }
    function innerTemplateResult(data){
      if(!select._templateResult) return $(`<span>${data.text}</span>`);
      return select._templateResult(data);
    }

    if($select.is('[ajax-url]')){
      Object.assign(data, {
        ajax: {
          url: $select.attr('ajax-url'),
          dataType: 'json',
          delay: 250,
          data: function (params) {
            return {
              q: params.term,
              page: params.page
            };
          },
          processResults: function (data, params) {
            params.page = params.page || 1;

            preprocess.forEach(p => p(data.results));

            return {
              results: data.results,
              pagination: {
                more: (params.page * data.paging) < data.total
              }
            };
          },
          cache: true
        },
        minimumInputLength: 0
      });
    }

    $select.select2(data);

    // This fixes the clear opening  the select
    $select.on('select2:unselecting', function() {
      $(this).data('unselecting', true);
    }).on('select2:opening', function(e) {
      if ($(this).data('unselecting')) {
        $(this).removeData('unselecting');
        e.preventDefault();
      }
    });

    // This fixes the label container selecting the HMTL Select Element
    let label = select.closest('label');
    if(label && !$select.is('[multiple]')){
      label.addEventListener('click', e => {
        e.stopImmediatePropagation();
        e.preventDefault();
      }, true);
    }

    // This fixes the select growing in size after clearing
    $select.change(e => {
      if(isMultiple){
        let removed = false;
        [...select.selectedOptions].forEach(option => {
          if(option.value==""){
            option.selected = false;
            removed = true;
          }
        });
        if(removed) $select.trigger('change');
      }else{

      }
    });
  };
  selects.forEach(Utils.enableSelect);


    // owl carousel script
    $('.carousel').owlCarousel({
        margin: 20,
        loop: true,
        autoplay: true,
        autoplayTimeOut: 2000,
        autoplayHoverPause: true,
        items: 3,
        loop: true,
        margin: 20,
        nav: true,
        navText: ["<i class='fa fa-chevron-left'></i>","<i class='fa fa-chevron-right'></i>"],
        dots: false,
        responsive: {
            0:{
                items: 1,
                nav: false
            },
            600:{
                items: 2,
                nav: false
            },
            1000:{
                items: 3,
                nav: false
            }
        }
    });


  let showMores = document.querySelectorAll('[show-more]');
  showMores.forEach(sm => {
    let btn = document.createElement('a');
    btn.href="#";
    btn.textContent = __('general.show_more');
    btn.addEventListener('click', e => {
      btn.textContent = sm.classList.toggle('show') ? __('general.show_less') : __('general.show_more');
    });

    sm.insertAdjacentElement('afterend', btn);
  });
});
