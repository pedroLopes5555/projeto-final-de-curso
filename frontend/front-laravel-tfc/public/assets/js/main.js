// Prototypes
HTMLElement.prototype.trigger = function(eventName){
    let event = new Event(eventName);
    this.dispatchEvent(event);
  }
  
  HTMLScriptElement.prototype.template_type = 'text/template';
  HTMLScriptElement.prototype.new = function(object, place, id){
    if(this.type != HTMLScriptElement.prototype.template_type)
      throw `This script is not of type '${HTMLScriptElement.prototype.template_type}'.`;
  
    let html = this.textContent;
    let parsed = "";
  
    let level = 0;
    let last_index = 0;
    for(let i=0;i<html.length;i++){
      if(level != 0 && html[i] == '}'){
        if(--level == 0){
          let str = html.substring(last_index+2, i); // Get code without the tokens ${}
          let fn = new Function(`return (${str});`);
          try{
            parsed += fn.inject(object)();
          }catch(e){
            // Do nothing
          }
          last_index = i+1;
        }
      }else if(html.substr(i, 2) == '${'){
        if(level == 0){
          parsed += html.substring(last_index, i);
          last_index = i;
        }
        level++;
        i++; // do this to not go through a meaningless loop
      }
    }
    parsed += html.substring(last_index, html.length);
  
    // HTML parse through template element
    var t = document.createElement('template');
    t.innerHTML = parsed;
    let content = t.content;
  
    // Remove whitespace
    content.childNodes.forEach(child => {
      if(child instanceof Text && child.textContent.trim() == '') child.remove();
    });
    // Return single element if possible
    if(content.childNodes.length == 1){
      content = content.childNodes[0];
      if(id) content.__template_id = Utils.dot_notation(object, id);
    }
  
    if(place) place.appendChild(content);
    return content;
  }
  HTMLScriptElement.prototype.get = function(object, place, id){
    let thisId = Utils.dot_notation(object, id);
  
    let old;
    [...place.childNodes].some(e => {
      if(e.__template_id == thisId){
        old = e;
        return true;
      }
    });
  
    return thisId;
  }
  HTMLScriptElement.prototype.edit = function(object, place, id){
    let element = this.new(object, place, id);
    let thisId = element.__template_id;
  
    let old;
    [...place.childNodes].some(e => {
      if(e.__template_id == thisId){
        old = e;
        return true;
      }
    });
  
    if(old) old.replaceWith(element);
    else place.appendChild(element);
  
    return element;
  }
  HTMLScriptElement.prototype.delete = function(object, place, id){
    let thisId = Utils.dot_notation(object, id);
  
    let old;
    [...place.childNodes].some(e => {
      if(e.__template_id == thisId){
        old = e;
        return true;
      }
    });
  
    if(old) old.remove();
  
    return old;
  }
  HTMLScriptElement.prototype.forEach = function(objects, place, id){
    let frag = document.createDocumentFragment();
    objects.forEach(object => {
      let element = this.new(object, null, id);
      frag.appendChild(element);
    });
    place.appendChild(frag);
  }
  
  Function.prototype.inject = function(args){
    let body = [];
    for(let i in args){
      body.push(`let ${i} = __args['${i}'];`);
    }
    body.push(`return ${this.toString()};`);
    let fn = new Function("__args", body.join('\n'));
    return fn(args);
  };
  
  
  // Utils
  Utils = {};
  
  Utils.dot_notation = function(object, dot){
    if(dot == "this") return object;
    let index;
    let dots = dot.split(".");
    while(index = dots.shift()){
      object = object[index];
      if(object==undefined) return undefined;
    }
    return object;
  };
  
  Utils.empty_form = function(form, clear_hidden=true){
    let fields;
    if(form instanceof NodeList || form instanceof HTMLCollection)
      fields = form;
    else
      fields = form.querySelectorAll("[name]");
  
    fields.forEach(field => {
      let type = (field.matches("select")&&"select") || (field.matches("input")&&field.type);
      switch(type){
        case "select": {
          [...field.options].forEach(option => {
            option.selected = false;
          });
  
          // Forced to use JQuery to support select2
          $(field).trigger("change");
          break;
        }
        case "checkbox":
        case "radio": {
          field.checked = false;
          break;
        }
        case "hidden": {
          if(clear_hidden)
            field.value = "";
          break;
        }
        default: {
          field.value = "";
        }
      }
      field.classList.remove('input-error');
    });
  };
  Utils.fill_form = function(form, object, change_hidden=false){
    let fields;
    if(form instanceof NodeList || form instanceof HTMLCollection)
      fields = form;
    else
      fields = form.querySelectorAll("[name]");
  
    fields.forEach(field => {
      let name = field.getAttribute("name");
      let indexName = name.endsWith("[]")?name.substring(0, name.length-2):name;
      if(!name || (field.type=='hidden' && !change_hidden)) return;
      let type = (field.matches("select")&&"select") || (field.matches("input")&&field.type);
      switch(type){
        case "select": {
          let value = object[indexName];
          let isNew = object[indexName+"_new"];
          let fill = field.getAttribute('fill');
          if(fill){
            let parts = fill.split(':');
            let fill_name = parts[0];
            value = object[fill_name];
            fill = parts[1].split('|');
            fill = {value: fill[0], text: fill[1]};
          }
  
          let alreadyExists = [...field.options].reduce((cum, option) => {
            cum[option.value] = true;
            return cum;
          }, {});
  
          if(Array.isArray(value)){
            if(field.hasAttribute('ajax-url')){
              if(fill){
                for(let i in value){
                  let v = value[i];
                  if(alreadyExists[v[fill.value]]) continue;
                  let newOption = new Option(v[fill.text], v[fill.value], true, true);
                  $(field).append(newOption).trigger('change');
                }
              }else{
                for(let i in value){
                  if(alreadyExists[value[i]]) continue;
                  let newOption = new Option(value[i], value[i], true, true);
                  $(field).append(newOption).trigger('change');
                }
              }
            }
            [...field.options].forEach(option => {
              if(fill){
                if(option.value && value.findIndex(v=>v[fill.value]==option.value) != -1)
                  option.selected = true;
                else
                  option.selected = false;
              }else{
                if(option.value && (value.indexOf(option.value) != -1 || value.indexOf(+option.value) != -1))
                  option.selected = true;
                else
                  option.selected = false;
              }
  
              if(option.hasAttribute('new-option')) option.remove();
            });
            if(isNew){
              for(let i in isNew){
                let newOption = new Option(value[i], value[i], true, true);
                newOption.setAttribute('new-option', true);
                $(field).append(newOption).trigger('change');
              }
            }
          } else {
            if(fill && value){
              if(!alreadyExists[value[fill.value]]){
                let newOption = new Option(value[fill.text], value[fill.value], true, true);
                $(field).find('[value]').remove();
                $(field).append(newOption).trigger('change');
              }
            }else if(isNew){
              let newOption = new Option(isNew, isNew, true, true);
              newOption.setAttribute('new-option', true);
              $(field).append(newOption).trigger('change');
            }else{
              field.value = value;
            }
          }
  
          // Forced to use JQuery to support select2
          $(field).trigger("change");
          break;
        }
        case "checkbox":
        case "radio": {
          let objVal = object[indexName];
          let value = field.value;
          if(value === "on") value = true;
          if(value === "false" || value === "true") value = JSON.parse(value); // Convert boolean string to boolean
          if(objVal === 0 || objVal === 1) objVal = !!objVal; // Convert boolean string to boolean
          value = (objVal == value);
          field.checked = value;
          field.trigger('change');
  
          // if(typeof value != 'boolean' && !isNaN(+value)){
          //   field.checked = value==object[indexName];
          //   field.trigger("change");
          //   break;
          // }
          // if(!isNaN(+value)) value = !!+value; // Convert numbers to boolean (might remove)
          // if(value === "false" || value === "true") value = JSON.parse(value); // Convert boolean string to boolean
          // value = (object[indexName] == value);
          // field.checked = value;
          // field.trigger("change");
  
          /*
          let value = object[indexName];
          if(value !== null && String(object[name]) == value) field.checked = true;
          else if(value === null && object[name]) field.checked = true;
          else field.checked = false;
          */
          break;
        }
        case "file": {
          let fileLink = field.fileLink;
          if(!fileLink){
            fileLink = document.createElement('div');
            field.insertAdjacentElement('beforebegin', fileLink);
            field.fileLink = fileLink;
          }
          fileLink.innerHTML = `Ficheiro Atual: <a target="_blank" href="${object[name]}">${object[name+":name"]}</a>`;
          break;
        }
        default: {
          if(object[name] === undefined) field.value = "";
          else field.value = object[name];
          field.trigger('change'); // trigger from prototype.js
  
          if(field.hasAttribute('datepicker')){
            $(field).datepicker("setDate", object[name]);
          }
        }
      }
    });
  };
  Utils.inputs_values = function(fields, callback, send_empty = false){
    let errors = [];
    let explored = {};
    fields.forEach(field => {
      /*
      if(field.selectedOptions && !field.selectedOptions.length) return; // Multi select placeholder
      if(field.selectedOptions && field.selectedOptions[0].disabled) return; // Single select placeholder
      */
  
      if(explored[field.name]) return;
      if(field.matches('[type="radio"], [type="checkbox"]') && !field.checked) return;
      explored[field.name] = true;
  
      if(field.hasAttribute('mde')){
        callback(field.name, field.mde.value());
        return;
      }
  
      if(field.selectedOptions){
        // let isPlaceholder = (field.selectedOptions.length == 1 && !field.selectedOptions[0].hasAttribute('value'));
        let isPlaceholder = false;
        if(field.selectedOptions.length && !isPlaceholder){
          [...field.selectedOptions].forEach(option => {
            callback(field.name, option.value);
          });
        }else if(field.required){
          errors.push(field);
        }
      }else{
        let value = field.value;
        if(field.type == "file")
          value = field.files[0]; // TODO: multiple files
        if(value || send_empty){
          callback(field.name, value);
        }else if(field.required){
          errors.push(field);
        }
      }
    });
    return errors;
  };
  
  // Main
  (function(){
  
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
  
    // Enable DataTables
    $.fn.dataTable.ext.errMode = 'none';
    Utils.buildDataTable = function(dataTable){
      let data = {
        autoWidth:false,
        columnDefs:[],
        language: {
          url: "/lib/datatables/pt.json"
        },
        createdRow: function(tr, data, index, children){
          if(!dataTable.ajaxJson) return;
          let id = data.id || tr.dtID;
          let d = dataTable.ajaxJson.index[id];
          let el = dataTable.ajaxRender(d, tr);
  
  
          tr.innerHTML = "";
          // Copy Children
          [...el.children].forEach(c => tr.appendChild(c));
          // Copy Attributes
          [...el.attributes].forEach(a => {
            tr.setAttribute(a.nodeName, a.nodeValue);
          });
          // Copy Properties
          for(let i in el){
            if(el.hasOwnProperty(i)){
              tr[i] = el[i];
            }
          }
  
          let event = new CustomEvent('created-row', {
            bubbles: true,
          });
          event.row = tr;
          dataTable.dispatchEvent(event);
        }
        // initComplete: init
      };
  
      // let dtNames = dataTable.querySelectorAll('[dt-name]');
      let dtNames = dataTable.querySelectorAll('th');
      if(dtNames.length){
        data.columns = [...dtNames].map(name => ({name: name.getAttribute('dt-name')||null}));
      }
  
      let ajaxUrl = dataTable.getAttribute('ajax-url');
      let ajaxID = dataTable.getAttribute('ajax-id');
      if(ajaxUrl){
        data.serverSide = true;
        data.ajax = {
          url: ajaxUrl,
          type: 'POST',
          dataSrc: function (json){
            if(!dataTable.ajaxRender) throw "There is no ajax renderer.";
  
            if(!dataTable.ajaxJson || +dataTable.ajaxJson.draw < +json.draw){
              dataTable.ajaxJson = json;
              json.index = json.data.reduce((cum, i) => {
                cum[i[ajaxID]] = i;
                return cum;
              }, {});
            }
  
            let ret = json.data.map(d => {
              let array = data.columns.map(c => "");
              array.id = d[ajaxID];
              return array;
            });
            return ret;
          }
        };
      }
  
      // Convert to number and wrap around if negative
      let n = dataTable.rows[0].cells.length;
      function convertIndex(x){
        x = parseInt(x);
        return x<0?n+x:x;
      }
  
      let noOrder = dataTable.getAttribute("datatable-no-order");
      if(noOrder !== null){
        noOrder = noOrder.replace(/\s+/, "").split(",");
        noOrder = noOrder.map(convertIndex);
  
        data.columnDefs.push(
          {
            targets: noOrder,
            orderable: false
          }
        );
      }
  
      let hide = dataTable.getAttribute("datatable-hide");
      if(hide){
        hide = hide.replace(/\s+/, "").split(",");
        hide = hide.map(convertIndex);
        data.columnDefs.push(
          {
            targets: hide,
            searchable: false,
            orderable: false
          }
        );
      }
  
      let number = dataTable.getAttribute("datatable-number");
      if(number){
        number = number.replace(/\s+/, "").split(",");
        number = number.map(convertIndex);
  
        data.columnDefs.push(
          {
            targets: number,
            type: "num"
          }
        );
      }
  
      let order = dataTable.getAttribute("datatable-order");
      if(order){
        order = order.replace(/\s+/, "").split(",");
        data.order = order.map(o => {
          let matches = o.match(/(\d+)(\((.+)\))?/);
          return [matches[1], matches[3]?matches[3].toLowerCase():"asc"];
        });
      }
  
      let $dataTable = $(dataTable);
      $dataTable.on('error.dt', (e, settings, techNote, message) => {
        console.error(message);
      });
      let dt = $dataTable.DataTable(data);
      dataTable.datatable = dt;
  
      function init(){
        // Filter
        let parent = dataTable.parentNode;
        let input = parent.querySelector('.dataTables_filter input');
        input.classList.add('n2-input');
        let inputNodes = input.parentNode.childNodes;
        inputNodes[0].remove();
        input.placeholder = "Filtrar resultados";
  
        // Pagination Select
        let select = parent.querySelector('.dataTables_length select');
        select.classList.add('n2-select');
        select.classList.add('select-simple');
  
        let selectNodes = select.parentNode.childNodes;
        selectNodes[0].remove();
        selectNodes[1].remove();
        [...select.options].forEach(option => {
          option.textContent += " resultados";
        });
        // Utils.n2Select(select);
      }
    }
    let dataTables = document.querySelectorAll("[datatable]");
    dataTables.forEach(Utils.buildDataTable);
  
  
    // Enable option-click
    window.addEventListener('click', e => {
      let opt = e.target.closest('[option]');
      if(opt) clickOption(opt);
  
      function clickOption(target){
        let keyElement = target.closest('[option-key]');
        let key = null;
        if(keyElement) key = keyElement.getAttribute('option-key');
        let option = target.getAttribute('option');
  
        let event = new Event('option-click', {bubbles: true});
        event.key = key;
        event.option = option;
        target.dispatchEvent(event);
      }
    });
  
  })();
  
  (function(){
    let dt = document.getElementById('dt');
    let dtTemplate = document.getElementById('dt-template');
    if(dt && dt.hasAttribute('ajax-url') && dtTemplate){
      let id = dt.getAttribute('ajax-id');
      dt.ajaxRender = function(d, tr){
        return dtTemplate.new(d, null, id);
      };
  
      dt.newRow = function(obj){
        let el = dtTemplate.new(obj, null, id);
        el.dtID = obj[id];
        dt.ajaxJson.index[obj[id]] = obj;
        dt.datatable.row.add(el).draw('page');
        return el;
      };
      dt.editRow = function(obj){
        let el = dtTemplate.edit(obj, dt.tBodies[0], id);
        dt.ajaxJson.index[obj[id]] = obj;
        return el;
      };
      dt.deleteRow = function(obj){
        let el = dtTemplate.get(obj, dt.tBodies[0], id);
        delete dt.ajaxJson.index[obj[id]];
        dt.datatable.row(el).remove().draw('page');
        return el;
      };
      dt.refresh = function(){
        dt.datatable.draw('page');
      };
    }
  })();
  
  (function(){
    Utils.toast = function(status, message) {
      let toast = document.createElement('div');
      toast.innerHTML = `
        <div class="toast" role="alert" aria-live="assertive" aria-atomic="true">
          <div class="toast-header">
            <strong class="mr-auto">${status}</strong>
            <button type="button" class="ml-2 mb-1 close" data-dismiss="toast" aria-label="Close">
              <span aria-hidden="true">&times;</span>
            </button>
          </div>
          <div class="toast-body">
            ${message}
          </div>
        </div>
        <style>
        .toast {
          opacity: 1 !important;
          position: fixed;
          bottom: 0;
          right: 0;
          background-color: #333;
          color: #fff;
          font-size: 1rem;
          padding: 0.5rem;
          z-index: 9999;
        }
        .toast .toast-header {
          padding: 0.25rem 0.5rem;
        }
        .toast .toast-body {
          padding: 0.25rem 0.5rem;
        }
        </style>
        
      `;
      document.body.appendChild(toast);
  
      // Make it disappear after 2 seconds and fade out after 1.5 seconds and make it disappear if the user clicks on it
      let $toast = $(toast.children[0]);
      $toast.toast({delay: 2000});
      $toast.toast('show');
      $toast.on('hidden.bs.toast', function () {
        toast.remove();
      });
  
      
      
      return toast.children[0];
    };  
  })();

  /**
* Template Name: Gp
* Updated: Sep 18 2023 with Bootstrap v5.3.2
* Template URL: https://bootstrapmade.com/gp-free-multipurpose-html-bootstrap-template/
* Author: BootstrapMade.com
* License: https://bootstrapmade.com/license/
*/
(function() {
  "use strict";

  /**
   * Easy selector helper function
   */
  const select = (el, all = false) => {
    el = el.trim()
    if (all) {
      return [...document.querySelectorAll(el)]
    } else {
      return document.querySelector(el)
    }
  }

  /**
   * Easy event listener function
   */
  const on = (type, el, listener, all = false) => {
    let selectEl = select(el, all)
    if (selectEl) {
      if (all) {
        selectEl.forEach(e => e.addEventListener(type, listener))
      } else {
        selectEl.addEventListener(type, listener)
      }
    }
  }

  /**
   * Easy on scroll event listener 
   */
  const onscroll = (el, listener) => {
    el.addEventListener('scroll', listener)
  }

  /**
   * Navbar links active state on scroll
   */
  let navbarlinks = select('#navbar .scrollto', true)
  const navbarlinksActive = () => {
    let position = window.scrollY + 200
    navbarlinks.forEach(navbarlink => {
      if (!navbarlink.hash) return
      let section = select(navbarlink.hash)
      if (!section) return
      if (position >= section.offsetTop && position <= (section.offsetTop + section.offsetHeight)) {
        navbarlink.classList.add('active')
      } else {
        navbarlink.classList.remove('active')
      }
    })
  }
  window.addEventListener('load', navbarlinksActive)
  onscroll(document, navbarlinksActive)

  /**
   * Scrolls to an element with header offset
   */
  const scrollto = (el) => {
    let header = select('#header')
    let offset = header.offsetHeight

    let elementPos = select(el).offsetTop
    window.scrollTo({
      top: elementPos - offset,
      behavior: 'smooth'
    })
  }

  /**
   * Toggle .header-scrolled class to #header when page is scrolled
   */
  let selectHeader = select('#header')
  if (selectHeader) {
    const headerScrolled = () => {
      if (window.scrollY > 100) {
        selectHeader.classList.add('header-scrolled')
      } else {
        selectHeader.classList.remove('header-scrolled')
      }
    }
    window.addEventListener('load', headerScrolled)
    onscroll(document, headerScrolled)
  }

  /**
   * Back to top button
   */
  let backtotop = select('.back-to-top')
  if (backtotop) {
    const toggleBacktotop = () => {
      if (window.scrollY > 100) {
        backtotop.classList.add('active')
      } else {
        backtotop.classList.remove('active')
      }
    }
    window.addEventListener('load', toggleBacktotop)
    onscroll(document, toggleBacktotop)
  }

  /**
   * Mobile nav toggle
   */
  on('click', '.mobile-nav-toggle', function(e) {
    select('#navbar').classList.toggle('navbar-mobile')
    this.classList.toggle('bi-list')
    this.classList.toggle('bi-x')
  })

  /**
   * Mobile nav dropdowns activate
   */
  on('click', '.navbar .dropdown > a', function(e) {
    if (select('#navbar').classList.contains('navbar-mobile')) {
      e.preventDefault()
      this.nextElementSibling.classList.toggle('dropdown-active')
    }
  }, true)

  /**
   * Scrool with ofset on links with a class name .scrollto
   */
  on('click', '.scrollto', function(e) {
    if (select(this.hash)) {
      e.preventDefault()

      let navbar = select('#navbar')
      if (navbar.classList.contains('navbar-mobile')) {
        navbar.classList.remove('navbar-mobile')
        let navbarToggle = select('.mobile-nav-toggle')
        navbarToggle.classList.toggle('bi-list')
        navbarToggle.classList.toggle('bi-x')
      }
      scrollto(this.hash)
    }
  }, true)

  /**
   * Scroll with ofset on page load with hash links in the url
   */
  window.addEventListener('load', () => {
    if (window.location.hash) {
      if (select(window.location.hash)) {
        scrollto(window.location.hash)
      }
    }
  });

  /**
   * Preloader
   */
  let preloader = select('#preloader');
  if (preloader) {
    window.addEventListener('load', () => {
      preloader.remove()
    });
  }

  /**
   * Clients Slider
   */
  new Swiper('.clients-slider', {
    speed: 400,
    loop: true,
    autoplay: {
      delay: 5000,
      disableOnInteraction: false
    },
    slidesPerView: 'auto',
    pagination: {
      el: '.swiper-pagination',
      type: 'bullets',
      clickable: true
    },
    breakpoints: {
      320: {
        slidesPerView: 2,
        spaceBetween: 40
      },
      480: {
        slidesPerView: 3,
        spaceBetween: 60
      },
      640: {
        slidesPerView: 4,
        spaceBetween: 80
      },
      992: {
        slidesPerView: 6,
        spaceBetween: 120
      }
    }
  });

  /**
   * Porfolio isotope and filter
   */
  window.addEventListener('load', () => {
    let portfolioContainer = select('.portfolio-container');
    if (portfolioContainer) {
      let portfolioIsotope = new Isotope(portfolioContainer, {
        itemSelector: '.portfolio-item'
      });

      let portfolioFilters = select('#portfolio-flters li', true);

      on('click', '#portfolio-flters li', function(e) {
        e.preventDefault();
        portfolioFilters.forEach(function(el) {
          el.classList.remove('filter-active');
        });
        this.classList.add('filter-active');

        portfolioIsotope.arrange({
          filter: this.getAttribute('data-filter')
        });
        portfolioIsotope.on('arrangeComplete', function() {
          AOS.refresh()
        });
      }, true);
    }

  });

  /**
   * Initiate portfolio lightbox 
   */
  const portfolioLightbox = GLightbox({
    selector: '.portfolio-lightbox'
  });

  /**
   * Portfolio details slider
   */
  new Swiper('.portfolio-details-slider', {
    speed: 400,
    loop: true,
    autoplay: {
      delay: 5000,
      disableOnInteraction: false
    },
    pagination: {
      el: '.swiper-pagination',
      type: 'bullets',
      clickable: true
    }
  });

  /**
   * Testimonials slider
   */
  new Swiper('.testimonials-slider', {
    speed: 600,
    loop: true,
    autoplay: {
      delay: 5000,
      disableOnInteraction: false
    },
    slidesPerView: 'auto',
    pagination: {
      el: '.swiper-pagination',
      type: 'bullets',
      clickable: true
    }
  });

  /**
   * Animation on scroll
   */
  window.addEventListener('load', () => {
    AOS.init({
      duration: 1000,
      easing: "ease-in-out",
      once: true,
      mirror: false
    });
  });

  /**
   * Initiate Pure Counter 
   */
  new PureCounter();

})()