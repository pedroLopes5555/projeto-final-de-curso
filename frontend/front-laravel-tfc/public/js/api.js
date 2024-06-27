(function(){
  function Api(){
    let _this = this;
    let endpoint = "/api/admin"

    /*********
     * Utils *
     *********/

    // Allows a more free attribution through 'this'
    function ApiGroup(fn){
      fn.call(this);
    }

    // HTTP Utils
    this.get = function(url, data){
      return _this.send("GET", url, data);
    }
    this.post = function(url, data){
      return _this.send("POST", url, data);
    }
    this.put = function(url, data){
      if(data) data.append('_method', 'PUT');
      return _this.send("POST", url, data);
    }
    this.delete = function(url, data){
      return _this.send("DELETE", url, data);
    }
    this.send = function(method, url, data){
      url = endpoint+url;
      if(data instanceof HTMLFormElement){
        data = new FormData(data);
      }else if(!(data instanceof FormData)){
        let formdata = new FormData();
        for(let key in data){
          let value = data[key];
          if(value === null || value === undefined) continue;
          if(value instanceof Array)
            value.forEach(v => formdata.append(key, v));
          else
            formdata.append(key, value);
        }
        data = formdata;
      }
      return new Ajax({method, url, data});
    }

    // Api Calls
    // Wrapped in 'calls' to prevent future collision with 'reserved' names like 'get', 'post', etc.
    this.calls = new ApiGroup(function(){

      this.books = new ApiGroup(function(){
        this.delete = function(id){
          return _this.delete("/admin/book/"+id);
        };
      });

      /* Cover Types */
      this.coverType = new ApiGroup(function(){
        this.new = function(data){
          return _this.put(`/coverType`, data);
        };
        this.edit = function(id, data){
          return _this.post(`/coverType/${id}`, data);
        };
        this.delete = function(id){
          return _this.delete(`/coverType/${id}`);
        };
        this.merge = function(id, data){
          return _this.post(`/coverType/${id}/merge`, data);
        };
      });
      /* Bind Types */
      this.bindType = new ApiGroup(function(){
        this.new = function(data){
          return _this.put(`/bindType`, data);
        };
        this.edit = function(id, data){
          return _this.post(`/bindType/${id}`, data);
        };
        this.delete = function(id){
          return _this.delete(`/bindType/${id}`);
        };
        this.merge = function(id, data){
          return _this.post(`/bindType/${id}/merge`, data);
        };
      });
      /* Extra Cover Types */
      this.extraCoverType = new ApiGroup(function(){
        this.new = function(data){
          return _this.put(`/extraCoverType`, data);
        };
        this.edit = function(id, data){
          return _this.post(`/extraCoverType/${id}`, data);
        };
        this.delete = function(id){
          return _this.delete(`/extraCoverType/${id}`);
        };
        this.merge = function(id, data){
          return _this.post(`/extraCoverType/${id}/merge`, data);
        };
      });
      /* Paper Types */
      this.paperType = new ApiGroup(function(){
        this.new = function(data){
          return _this.put(`/paperType`, data);
        };
        this.edit = function(id, data){
          return _this.post(`/paperType/${id}`, data);
        };
        this.delete = function(id){
          return _this.delete(`/paperType/${id}`);
        };
        this.merge = function(id, data){
          return _this.post(`/paperType/${id}/merge`, data);
        };
      });
      /* Print Types */
      this.printType = new ApiGroup(function(){
        this.new = function(data){
          return _this.put(`/printType`, data);
        };
        this.edit = function(id, data){
          return _this.post(`/printType/${id}`, data);
        };
        this.delete = function(id){
          return _this.delete(`/printType/${id}`);
        };
        this.merge = function(id, data){
          return _this.post(`/printType/${id}/merge`, data);
        };
      });
      /* Publication Types */
      this.publicationType = new ApiGroup(function(){
        this.new = function(data){
          return _this.put(`/publicationType`, data);
        };
        this.edit = function(id, data){
          return _this.post(`/publicationType/${id}`, data);
        };
        this.delete = function(id){
          return _this.delete(`/publicationType/${id}`);
        };
        this.merge = function(id, data){
          return _this.post(`/publicationType/${id}/merge`, data);
        };
      });
      /* Country Cities */
      this.countryCity = new ApiGroup(function(){
        this.new = function(data){
          return _this.put(`/countryCity`, data);
        };
        this.edit = function(id, data){
          return _this.post(`/countryCity/${id}`, data);
        };
        this.delete = function(id){
          return _this.delete(`/countryCity/${id}`);
        };
        this.merge = function(id, data){
          return _this.post(`/countryCity/${id}/merge`, data);
        };
      });
      /* People */
      this.person = new ApiGroup(function(){
        this.new = function(data){
          return _this.put(`/person`, data);
        };
        this.edit = function(id, data){
          return _this.post(`/person/${id}`, data);
        };
        this.delete = function(id){
          return _this.delete(`/person/${id}`);
        };
        this.merge = function(id, data){
          return _this.post(`/person/${id}/merge`, data);
        };
      });
      /* Publishers */
      this.publisher = new ApiGroup(function(){
        this.new = function(data){
          return _this.put(`/publisher`, data);
        };
        this.edit = function(id, data){
          return _this.post(`/publisher/${id}`, data);
        };
        this.delete = function(id){
          return _this.delete(`/publisher/${id}`);
        };
        this.merge = function(id, data){
          return _this.post(`/publisher/${id}/merge`, data);
        };
      });
      /* FunctionModels */
      this.function = new ApiGroup(function(){
        this.new = function(data){
          return _this.put(`/function`, data);
        };
        this.edit = function(id, data){
          return _this.post(`/function/${id}`, data);
        };
        this.delete = function(id){
          return _this.delete(`/function/${id}`);
        };
        this.merge = function(id, data){
          return _this.post(`/function/${id}/merge`, data);
        };
      });
      /* Tags */
      this.tag = new ApiGroup(function(){
          this.new = function(data){
            return _this.put(`/tag`, data);
          };
          this.edit = function(id, data){
            return _this.post(`/tag/${id}`, data);
          };
          this.delete = function(id){
            return _this.delete(`/tag/${id}`);
          };
          this.merge = function(id, data){
            return _this.post(`/tag/${id}/merge`, data);
          };
        });
      /* User */
      this.user = new ApiGroup(function(){
        this.new = function(data){
          return _this.put(`/user`, data);
        };
        this.edit = function(id, data){
          return _this.post(`/user/${id}`, data);
        };
        this.delete = function(id){
          return _this.delete(`/user/${id}`);
        };
      });
      /* Perms */
      this.perm = new ApiGroup(function(){
        this.new = function(data){
          return _this.put(`/perm`, data);
        };
        this.edit = function(id, data){
          return _this.post(`/perm/${id}`, data);
        };
        this.delete = function(id){
          return _this.delete(`/perm/${id}`);
        };
      });
      /* PermsRelations */
      this.permRelation = new ApiGroup(function(){
        this.new = function(data){
          return _this.put(`/permRelation`, data);
        };
        this.edit = function(id, data){
          return _this.post(`/permRelation/${id}`, data);
        };
        this.delete = function(id){
          return _this.delete(`/permRelation/${id}`);
        };
      });
        
        this.pages = new ApiGroup(function(){
          this.order = function(data){
            return _this.post(`/pages/order`, data);
          };
        });

    });

  }
  let api = new Api();
  api.initForm = handleApiForm;
  api.call = function(call){
    let args = [...arguments];
    args.shift(); // Get rid of call argument

    let fn;
    try{
      fn = Utils.dot_notation(api.calls, call);
    }catch(e){
      //Empty
    }
    if(!fn) throw "Api call '"+call+"' does not exist.";
    if(args.length != fn.length) throw "Api call expects "+fn.length+" arguments ("+args.length+" passed).";

    let progress = function(progress){};
    let promise = new Promise(function(succ, err){
      fn.apply(api, args).progress(e => {
        progress(e);
      }).then(function(e){
        let data = e.data;
        try{
          data = JSON.parse(e.data);
        }catch(e){
          e.data = e.data;
          return err(e);
        }
        e.data = data;
        e.d = data.data;
        e.isOK = data.error==0;
        succ(e);
      }).catch(err);
    });
    promise.progress = function(fn){
      progress = fn;
      return promise;
    }
    return promise;
  }
  window.api = api;

  let errorModal = document.getElementById('api-error-modal');
  let $errorModal = $(errorModal);
  let errorModalBody;
  if(errorModal){
    errorModalBody = errorModal.querySelector('.card-body');
  }
  api.showError = function(error){
    if(!errorModal) return;
    errorModalBody.innerHTML = error;
    $errorModal.modal('show');
  }

  let apiForms = document.querySelectorAll("[api-call]");
  let apiSubmitButtons = document.querySelectorAll("[api-for]");
  let buttons = {};
  apiSubmitButtons.forEach(b => {
    let id = b.getAttribute("api-for");
    if(buttons[id] == null){
      buttons[id] = [b];
    }else{
      buttons[id].push(b);
    }

  });

  apiForms.forEach(form => handleApiForm(form));
  function handleApiForm(form){
    let formButtons = form.querySelectorAll("[api-submit]");
    formButtons = [...formButtons];
    if(form.matches("[api-submit]")) formButtons.push(form);
    let extraButtons = buttons[form.id];
    if(extraButtons) formButtons = formButtons.concat(extraButtons);
    formButtons.forEach(button => {
      button.addEventListener("click", e => {
        let apiClear = form.hasAttribute("api-clear");
        let apiEmpty = (form.hasAttribute("api-empty") || button.hasAttribute("api-empty"));
        let apiSendEmpty = form.hasAttribute("api-send-empty");
        let apiCall = button.getAttribute("api-submit");
        if(!apiCall) apiCall = form.getAttribute("api-call");
        apiCall = apiCall.replace(/ /g, "");
        let match = apiCall.match(/([^(]+)(\(.+?\))?/);

        let call = match[1];
        let arguments = [];
        if(match[2]) arguments = match[2].substr(1, match[2].length-2).split(",");

        let body = {};
        let fields = form.querySelectorAll("[name]:not(:disabled)");
        if(!apiEmpty && fields.length){
          let formdata = new FormData();
          if(button.name) formdata.append(button.name, button.value);

          let errors = Utils.inputs_values(fields, (name, value) => {

            formdata.append(name, value);

            if(name.indexOf('[]') != -1){
              if(!body[name]) body[name] = [];
              body[name].push(value);
            }else{
              body[name] = value;
            }
          }, apiSendEmpty);
          if(errors.length){
            errors.forEach(field => {
              field.classList.add('input-error');
              if(!field.hasErrorEvent){
                field.addEventListener('input', errorEvent);
                $(field).on('change', errorEvent);
                field.hasErrorEvent = true;

                function errorEvent(e){
                  if(field.value) field.classList.remove('input-error');
                  else field.classList.add('input-error');
                }
              }
            });
            e.stopImmediatePropagation();
            e.preventDefault();
            return;
          }
          arguments.push(formdata);
        }

        let beforeEvent = new Event("api-request", {bubbles:true});
        beforeEvent.data = arguments;
        beforeEvent.apiSubmit = button;
        beforeEvent.cancel = function(){
          beforeEvent.isCancelled = true;
        }
        form.dispatchEvent(beforeEvent);
        if(beforeEvent.isCancelled) return;

        let fn;
        try{
          fn = Utils.dot_notation(api.calls, call);
        }catch(e){
          //Empty
        }
        if(!fn) throw "Api call '"+call+"' does not exist.";
        if(arguments.length != fn.length) throw "Api call expects "+fn.length+" arguments ("+arguments.length+" passed).";

        let event = new Event("api-response", {bubbles:true});
        let errorEvent = new Event("api-error");
        fn.apply(api, arguments).then(response => {
          let data = response.data;
          try{
            data = JSON.parse(response.data);
          }catch(e){
            // Setting event data
            errorEvent.call = {
              name:call,
              args: arguments
            };
            errorEvent.data = response.data;
            errorEvent.apiSubmit = button;
            errorEvent.body = body;
            console.error("Error calling '"+call+"'", errorEvent.data);
            let bubbled = form.dispatchEvent(errorEvent);
            if(!bubbled.defaultPrevented){
              api.showError("Erro a processar o JSON (client-side).");
            }
            return;
          }

          // Setting event data
          event.call = {
            name:call,
            args: arguments
          };
          event.data = data;
          event.d = data.data;
          event.apiSubmit = button;
          event.isOK = data.error==0;
          event.body = body;

          let bubbled = form.dispatchEvent(event);
          if(!bubbled.defaultPrevented && data.error){
            api.showError(data.data);
          }
        }, response => {

          // Setting event data
          errorEvent.call = call;
          errorEvent.data = response.data;
          errorEvent.apiSubmit = button;
          errorEvent.body = body;

          console.error("Error calling '"+call+"'", errorEvent.data);

          let bubbled = form.dispatchEvent(errorEvent);
          if(!bubbled.defaultPrevented){
            let xhr = response.xhr;
            api.showError(xhr.status + ' ' + xhr.statusText);
          }
        });
      });
    });

    form.addEventListener('submit', e => {
      if(formButtons.length == 1)
        formButtons[0].click();
      e.preventDefault();
    });
  }

  HTMLElement.prototype.setApiCall = function(call){
    this.setAttribute('api-call', call);
  }

})();
