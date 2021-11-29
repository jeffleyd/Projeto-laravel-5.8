@extends('gree_sac_client.panel.layout')

@section('content')
<style>
    .select2-selection__choice__remove {
        color: white !important;
        font-size: 15px !important;
    }
</style>
<div class="row gutters-tiny push" >
    <div class="col-12 col-md-12 col-xl-12" onclick="document.location.href='/suporte/painel';">
        <a class="block block-rounded block-bordered block-link-shadow text-center" href="javascript:void(0)">
            <div class="block-content">
                <p class="mt-5">
                    <i class="si si-action-undo fa-3x text-muted"></i>
                </p>
                <p class="font-w600">Voltar</p>
            </div>
        </a>
    </div>
</div>
<div class="row">
    <div class="col-md-12">
        <div class="alert alert-warning alert-dismissable" role="alert">
            <h3 class="alert-heading font-size-h4 font-w400">Atenção</h3>
            <p class="mb-0">Para abertura de atendimento em garantia é obrigatório o comprovante de instalação para produtos que foram comprados na data: <?= date('d/m/Y', strtotime('- 91 days')) ?> ou inferior</p>
        </div>
    </div>
</div>
<div class="row">
    <div class="col-md-12">
        <div class="block">
            <div class="block-header block-header-default">
                <h3 class="block-title">Novo atendimento</h3>
            </div>
            <div class="block-content">
                <form action="/suporte/novo/atendimento_do" method="post" id="createProtocol" enctype="multipart/form-data">
                    <input type="hidden" name="latitude" id="latitude">
                    <input type="hidden" name="longitude" id="longitude">
                    <input type="hidden" name="json_data" id="json_data" value="[]">
                    <div class="row">
                        <div class="col-md-12">
                            <div class="form-group">
                                <label for="type">Tipo de atendimento</label>
                                <select class="form-control" id="type" name="type">
                                    <option value=""></option>
                                    <option value="1" @if (Session::get('sac_client_type') == 1) selected @endif>Reclamação</option>
                                    <option value="2" @if (Session::get('sac_client_type') == 2) selected @endif>Assistência técnica</option>
                                    <option value="3" @if (Session::get('sac_client_type') == 4) selected @endif>Dúvida técnica</option>
                                </select>
                            </div>
                        </div>
                        <div class="col-md-12">
                            <div class="form-group">
                                <input type="checkbox" name="not_exist" id="not_exist"> Declaro que o modelo não existe na listagem abaixo.
                            </div>
                        </div>
                        <div class="col-md-12">
                            <div class="row listmodels" style="margin-left: 2px;margin-bottom: 20px;margin-top: 15px;">
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="shop">Loja que comprou</label>
                                <input type="text" class="form-control" id="shop" name="shop">
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="buy_date">Data da compra</label>
                                <input type="text" class="form-control" id="buy_date" name="buy_date" placeholder="__/__/____">
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="form-group">
                                <label for="number_nf">Número da nota</label>
                                <input type="text" class="form-control" id="number_nf" name="number_nf">
                            </div>
                        </div>
                        <div class="col-md-8">
                            <label for="nf_file">Anexar nota fiscal (Max 2mb de arquivo)</label>
                            <div class="form-group">
                                <input type="file" id="nf_file" accept="image/jpeg,image/jpg,image/gif,image/png,application/pdf" name="nf_file">
                            </div>
                        </div>
                        <div class="col-md-12">
                            <label for="c_install_file">Comprovante de Instalação (Max 2mb de arquivo)</label>
                            <div class="form-group">
                                
                                <input type="file" id="c_install_file" accept="image/jpeg,image/jpg,image/gif,image/png,application/pdf" name="c_install_file"> 
                            </div>
                        </div>
                        <div class="col-md-12">
                            <label for="tag_file">Etiqueta do produto (Max 2mb de arquivo)</label>
                            <div class="form-group">
                                
                                <input type="file" id="tag_file" accept="image/jpeg,image/jpg,image/gif,image/png,application/pdf" name="tag_file">
                            </div>
                        </div>
                        <div class="col-md-12">
                            <div class="form-group">
                                <label for="address">Local do ocorrido (RUA e NÚMERO)</label>
                                <input type="text" class="form-control" id="address" name="address">
                            </div>
                        </div>
                        <div class="col-md-12">
                            <div class="form-group">
                                <label for="complement">Complemento</label>
                                <input type="text" class="form-control" id="complement" name="complement"> 
                            </div>
                        </div>
                        <div class="col-md-12">
                            <div class="form-group">
                                <label for="description">Motivo</label>
                                <textarea class="form-control" id="description" name="description" rows="4"></textarea>
                            </div>  
                        </div>

                        <div class="col-md-12">
                            <div class="form-group">
                                <button type="submit" class="btn btn-primary min-width-125">Criar atendimento</button>
                            </div>
                        </div>
                </form>
            </div>
        </div>
    </div>
</div>

<div class="modal fade" id="addmodel" tabindex="-1" role="dialog" aria-labelledby="addmodel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-dialog-centered modal-dialog-scrollable" role="document">
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title">Novo modelo</h5>
          <button type="button" class="close" data-dismiss="modal" aria-label="Close">
            <i class="bx bx-x"></i>
          </button>
        </div>
        <div class="modal-body">
          <div class="row">
            <div class="col-md-12">
                <fieldset class="form-group">
                    <label for="model">Modelo do equipamento</label>
                    <select class="form-control js-select2" style="width: 100%" id="model" name="model[]" multiple>
                    </select>
                </fieldset>
            </div>
            <div class="col-md-12">
                <fieldset class="form-group">
                    <label for="serie">Número de série</label>
                    <input type="text"class="form-control" style="width: 100%" id="serie" name="serie">
                </fieldset>
            </div>
          </div>
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-light-secondary" data-dismiss="modal">
            <i class="bx bx-x d-block d-sm-none"></i>
            <span class="d-none d-sm-block">Fechar</span>
          </button>
          <button type="button" class="btn btn-primary ml-1" onclick="addModel_do()">
            <i class="bx bx-check d-block d-sm-none"></i>
            <span class="d-none d-sm-block">Adicionar</span>
          </button>
        </div>
      </div>
    </div>
  </div>
<script>
    var models = new Array();

    function reloadModels() {
        var list = "";
        for(var i = 0; i < models.length; i++) {
            var arrayObj = models[i];
            list += '<div class="col-3 mt-1 bg-secondary bg-lighten-1 text-white cursor-pointer" style="padding: 15px;margin-right: 25px;border-radius: 10px; height: 74px;" onclick="deleteModel('+ i +');">';
            list += '<i class="si si-close" style="position: absolute;right: 10px;top: 7px;"></i>';
            list += '<p style="margin: 0;"><b>Modelo:</b> '+ arrayObj.model_name +'';
            list += '<br><b>N Série:</b> '+ arrayObj.serial +' </p>';
            list += '</div>';
        }

        list += '<div class="col-3 mt-1 bg-secondary bg-lighten-1 text-center text-white cursor-pointer" style="display: flex;justify-content: center;flex-direction: column; border-radius: 10px; height: 74px;" onclick="addModel();">';
        list += '<p style="margin: 0;">Novo modelo';
        list += '<br><i style="font-size: 18px" class="si si-plus"></i>';
        list += '</div>';

        $(".listmodels").html(list);
        $("#json_data").val(JSON.stringify(models));
    }
    function deleteModel(index) {
        Swal.fire({
                title: 'Tem certeza disso?',
                text: "Você irá remover o modelo em anexo!",
                type: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#3085d6',
                cancelButtonColor: '#d33',
                confirmButtonText: 'Confirmar!',
                cancelButtonText: 'Cancelar',
                confirmButtonClass: 'btn btn-primary',
                cancelButtonClass: 'btn btn-danger ml-1',
                buttonsStyling: false,
                }).then(function (result) {
                if (result.value) {
                    models.splice(index, 1);
                    reloadModels();
                    Swal.fire(
                    {
                        type: "success",
                        title: 'Removido',
                        text: 'Modelo foi removido com sucesso!',
                        confirmButtonClass: 'btn btn-success',
                    }
                    )
                }
                })
    }
    function addModel() {
        $("#addmodel").modal();
    }
    function addModel_do() {
        $("#addmodel").modal('toggle');

        var _model = $('#model').select2('data');
        if (_model[0] == undefined) {

            return error('Você precisa escolher o modelo');
        } else if ($('#serie').val() == "") {

            return error('Você precisa informar o número de série');
        }
        for(var i = 0; i < models.length; i++) {
            var arrayObj = models[i];
            if (arrayObj.serial == $('#serie').val()) {

                error('Você já adicionou esse número de série com esse modelo.');
                $('#serie').val('');
                return;
            }
        }

        models.push({
            "product_id" : _model[0].id,
            "model_name" : _model[0].text,
            "serial" : $('#serie').val(),
        });
        $('#model').val(0).trigger('change');
        $('#serie').val('');
        reloadModels();

    }
    $(document).ready(function () {

        var list = "";
        list += '<div class="col-3 mt-1 bg-secondary bg-lighten-1 text-center text-white cursor-pointer" style="display: flex;justify-content: center;flex-direction: column; border-radius: 10px; height: 74px;" onclick="addModel();">';
        list += '<p style="margin: 0;">Novo modelo';
        list += '<br><i style="font-size: 18px" class="si si-plus"></i>';
        list += '</div>';

        $(".listmodels").html(list);
        

        $('#buy_date').mask('00/00/0000', {reverse: false});
        $("#createProtocol").submit(function (e) {
            if ($("#type").val() == "") {

                e.preventDefault();
                return error('Escolha o tipo de atendimento.');
            } else if ($("#json_data").val() == "[]" || $("#json_data").val() == "") {

                e.preventDefault();
                return error('Por favor, adicione pelos menos 1 modelo.');
            } else if ($("#shop").val() == "") {

                e.preventDefault();
                return error('Por favor, informe a loja de compra do equipamento.');
            } else if ($("#buy_date").val() == "") {

                e.preventDefault();
                return error('Por favor, informe a data de compra do equipamento.');
            } else if ($("#number_nf").val() == "" && $("#type").val() != 3) {

                e.preventDefault();
                return error('Por favor, informe o número da nota fiscal.');
            } else if ($("#nf_file").val() == "" && $("#type").val() != 3) {

                e.preventDefault();
                return error('Por favor, anexe sua nota fiscal.');
            } else if ($("#address").val() == "") {

                e.preventDefault();
                return error('Por favor, digite o endereço com número.');
            } else if ($("#complement").val() == "") {

                e.preventDefault();
                return error('Por favor, informe o complemento do endereço.');
            } else if ($("#description").val() == "") {

                e.preventDefault();
                return error('Por favor, diga o motivo do atendimento.');
            }

            Codebase.loader('show', 'bg-gd-sea');
            
            
        });
        
        setTimeout(() => {

            $('.js-select2').on('select2:select', function (e) {
                $("#not_exist").removeAttr('checked');
            });

            $(".js-select2").select2({
                maximumSelectionLength: 1,
                language: {
                    noResults: function () {
                        return 'Nada foi encontrado...';
                    }
                },
                ajax: {
                    url: '/suporte/products',
                    data: function (params) {
                    var query = {
                        search: params.term,
                        page: params.page || 1
                    }

                    // Query parameters will be ?search=[term]&page=[page]
                    return query;
                    }
                }
            });

            $(".js-select22").select2({
                maximumSelectionLength: 1,
                tags: true,
                language: {
                    noResults: function () {
                        return 'Informe o(s) número(s) de serie(s) seguindo a ordem do modelo.';
                    }
                }
            });
            
        }, 300);
    });
</script>
<script>
    function initAutocomplete() {    

        // Create the search box and link it to the UI element.
        var input = document.getElementById('address');
        var searchBox = new google.maps.places.SearchBox(input);
        

        $( "#address" ).blur(function() {
            if ($("#address").val() != "") {             
            
                var geocoder = new google.maps.Geocoder()
                var end = $("#address").val();
                var endereco = end;
                
                
                geocoder.geocode( { 'address': endereco}, function(resultado, status) {
                if (status == google.maps.GeocoderStatus.OK) {
                    
                var lat1 = resultado[0].geometry.location.lat();
                var long1 = resultado[0].geometry.location.lng();
                
                console.log( "Endereço: " + endereco + " - Latitude: " + lat1 + " Longitude: " + long1);
                document.getElementById("latitude").value = lat1;
                document.getElementById("longitude").value = long1;

                } else {
                alert('Ops, Seu endereço não está bom, digite novamente com número!');
                }
            });

            }
            
        });
   
            
        // Listen for the event fired when the user selects a prediction and retrieve
        // more details for that place.
        searchBox.addListener('places_changed', function() {
          var places = searchBox.getPlaces();
          

          if (places.length == 0) {
            return;
          }

          // For each place, get the icon, name and location.
          var bounds = new google.maps.LatLngBounds();
          places.forEach(function(place) {
            if (!place.geometry) {
              console.log("Returned place contains no geometry");
              return;
            }
            
            
            document.getElementById('latitude').value = place.geometry.location.lat();
            document.getElementById('longitude').value = place.geometry.location.lng();


          });
        });
        
        
        
      }
</script>
<script src="https://maps.googleapis.com/maps/api/js?v=3.exp&key={{ getConfig("google_key_web") }}&libraries=places&callback=initAutocomplete"></script>
    
@endsection