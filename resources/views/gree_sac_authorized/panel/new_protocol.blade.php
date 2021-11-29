@extends('gree_sac_authorized.panel.layout')

@section('content')
<style>
    .select2-selection__choice__remove {
        color: white !important;
        font-size: 15px !important;
    }
    .select2-selection__choice {
        background: #3F51B5 !important;

    }
</style>
<style>
input::-webkit-outer-spin-button,
input::-webkit-inner-spin-button {
    /* display: none; <- Crashes Chrome on hover */
    -webkit-appearance: none;
    margin: 0; /* <-- Apparently some margin are still there even though it's hidden */
}

input[type=number] {
    -moz-appearance:textfield; /* Firefox */
}
</style>
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
        <div class="alert alert-danger alert-dismissable" role="alert">
            <h3 class="alert-heading font-size-h4 font-w400">Informação</h3>
            <p class="mb-0">Será gerado uma OS para cada modelo. Os conjuntos (Unidade interna e Externa) só será gerado 1 Os com ambos cadastrado.</p>
        </div>
    </div>
</div>
<div class="row">
    <form action="/autorizada/atendimento_do" method="post" id="createProtocol" enctype="multipart/form-data">
        <input type="hidden" name="latitude" id="latitude">
        <input type="hidden" name="longitude" id="longitude">
        <input type="hidden" name="json_data" id="json_data" value="[]">
        <input type="hidden" name="json_data_visit" id="json_data_visit" value="[]">
        <div class="col-md-12">
            <div class="block">
                <div class="block-header block-header-default">
                    <h3 class="block-title">Novo atendimento</h3>
                </div>
                <div class="block-content">
                <div class="row">
                    <div class="col-md-4">
                        <div class="form-group">
                            <label for="c_tyoe_people">Tipo de pessoa</label>
                            <select class="form-control" id="c_type_people" name="c_type_people">
                                <option value="1">Fisíca (CPF)</option>
                                <option value="2">Jurídica (CNPJ)</option>
                            </select>
                        </div>
                    </div>
                    <div class="col-md-8">
                        <div class="form-group">
                            <label for="identity">CPF/CNPJ</label>
                            <input type="text" class="form-control" id="identity" name="identity">
                        </div>
                    </div>
                    <div class="col-md-12">
                        <div class="form-group">
                            <label for="c_name">Nome completo do cliente</label>
                            <input type="text" class="form-control" id="c_name" name="c_name">
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-group">
                            <label for="c_phone">Telefone</label>
                            <input type="text" class="form-control" id="c_phone" name="c_phone">
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-group">
                            <label for="c_phone_2">Telefone</label>
                            <input type="text" class="form-control" id="c_phone_2" name="c_phone_2">
                        </div>
                    </div>
                    <div class="col-md-12">
                        <div class="form-group">
                            <label for="c_email">Email</label>
                            <input type="text" class="form-control" id="c_email" name="c_email">
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
                    <div class="col-md-12">
                        <div class="form-group">
                            <label for="number_nf">Número da nota</label>
                            <input type="text" class="form-control" id="number_nf" name="number_nf">
                        </div>
                    </div>
					<div class="col-md-12">
						<div class="form-group">
							<label for="problem_category">Categoria de Problema</label>
							<select class="form-control" id="sac_problem_category" name="sac_problem_category">
								<option value=""></option>
								@foreach ($sac_problem_category as $key)
								<option value="{{ $key->id }}" @if ($key->id == '') selected @endif>{{ $key->description }}</option>
								@endforeach
							</select>
						</div>
					</div>
                    <div class="col-md-12">
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

                    {{-- Lista visitas --}}

                </div>
            </div>
        </div>
    </div>
        <div class="col-md-12 sendpart" style="display: none">
            <div class="block">
                <div class="block-header block-header-default">
                    <h3 class="block-title">Pedido de peças</h3>
                </div>
                <div class="block-content">
                    <div class="row p-20">
                        
                        <div class="row repeater-default loadlayout">
                    
                        </div>

                        <div class="col-12 col-sm-12 col-lg-12 text-center">
                            <label for="users-list-verified">Relatório técnico (Obrigatório)</label>
                            <fieldset class="form-group">
                                <input type="file" name="report">
                            </fieldset>
                            <p><a href="{{ Request::root() }}/area_tecnica/report_tech.pdf" target="_blank">Baixar modelo padrão</a></p>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="row ml-1">
            <div class="col-md-12">
                <div class="form-group">
                    <button type="submit" style="width: 100%" class="btn btn-primary min-width-125">Criar atendimento</button>
                </div>
            </div>
        </div>
    </form>
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

            <div class="col-md-8">
                <div class="form-group">
                    <label for="date">Data da visita</label>
                    <input type="text" class="form-control" id="date" name="date" placeholder="__/__/____">
                </div>
            </div>
            <div class="col-md-4">
                <div class="form-group">
                    <label for="hour">Horário</label>
                    <input type="text" class="js-flatpickr form-control" data-enable-time="true" data-no-calendar="true" data-date-format="H:i" data-time_24hr="true" id="hour" name="hour" placeholder="12:00" value="12:00">
                </div>
            </div>
            <div class="col-md-6">
                <div class="form-group">
                    <label for="expert_name">Nome do técnico</label>
                    <input type="text" class="form-control" id="expert_name" name="expert_name" placeholder="Jhon Doe">
                </div>
            </div>
            <div class="col-md-6">
                <div class="form-group">
                    <label for="expert_phone">Telefone</label>
                    <input type="text" class="form-control" id="expert_phone" name="expert_phone" placeholder="(99) 99999-9999">
                </div>
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
    var o_id = 0;
    var i = 0;
    function loadPart(obj) {
        var item = $(obj).attr('name');
        var value = $(obj).val();
		var serial_number_selected = $(obj).find(':selected').attr('data-serial');
		var serial_number_field = item.replace("[p_model]", "[serial_number]");
		$('input[name="'+ serial_number_field +'"]').val(serial_number_selected);
        var res = item.replace("[p_model]", "[part]");
        if (value != "") {
            $('select[name="'+ res +'"]').load("/misc/part/list/" + value, function (response, status, request) {
                if ( status == "error" ) {
                    
                    return alert('Ocorreu um erro na conexão, tente novamente!');
                }
            });
        }
    }
</script>
<script>
    var models = new Array();

    function reloadModels() {
        var list = "";
        for(var i = 0; i < models.length; i++) {
            var arrayObj = models[i];
            list += '<div class="col-3 mt-1 bg-secondary bg-lighten-1 text-white cursor-pointer" style="padding: 15px;margin-right: 25px;border-radius: 10px; height: 142px;" onclick="deleteModel('+ i +');">';
            list += '<i class="si si-close" style="position: absolute;right: 10px;top: 7px;"></i>';
            list += '<p style="margin: 0;"><b>Modelo:</b> '+ arrayObj.product_text +'';
            list += '<br><b>N Série:</b> '+ arrayObj.serial +'';
            list += '<br><b>Técnico:</b> '+ arrayObj.expert_name +'';
            list += '<br><b>Telefone:</b> '+ arrayObj.expert_phone +'';
            list += '<br><b>Data da visita:</b> '+ arrayObj.date +' '+ arrayObj.hour +' </p>';
            list += '</div>';
        }

        list += '<div class="col-3 mt-1 bg-secondary bg-lighten-1 text-center text-white cursor-pointer" style="display: flex;justify-content: center;flex-direction: column; border-radius: 10px; height: 142px;" onclick="addModel();">';
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
                    if (models.length > 0) {
                        $(".sendpart").show()
                        var options = "";
                        options += '<option></option>';
                        for (let index = 0; index < models.length; index++) {
                            const arrayObj = models[index];
                            options += '<option value="'+ arrayObj.product_id +'" data-serial="'+ arrayObj.serial +'">'+ arrayObj.product_text +'</option>';
                        }

                        $("#p_model").html(options);
                    } else {
                        $(".sendpart").hide();
                    }
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
        } else if ( $("#serie").val().length < 13 || $("#serie").val().length > 13) {


            return error('Por favor, informe o número de série válido do modelo.');
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
            "product_text" : _model[0].text,
            "serial" : $('#serie').val(),
            "date" : $('#date').val(),
            "hour" : $('#hour').val(),
            "expert_name" : $('#expert_name').val(),
            "expert_phone" : $('#expert_phone').val(),
        });
        

        reloadModels();

        $('#model').val(0).trigger('change');
        $('#serie').val('');

        $(".sendpart").show()
        var options = "";
        options += '<option></option>';
        for (let index = 0; index < models.length; index++) {
            const arrayObj = models[index];
            options += '<option value="'+ arrayObj.product_id +'" data-serial="'+ arrayObj.serial +'">'+ arrayObj.product_text +' ('+ arrayObj.serial +')</option>';
        }

        $("#p_model").html(options);

    }
	function validMax($this) {
		if ($($this).val() <= 0) { 
			$($this).val(0);
		}	
	}
    $(document).ready(function () {
        var list = "";
        list += '<div class="col-3 mt-1 bg-secondary bg-lighten-1 text-center text-white cursor-pointer" style="display: flex;justify-content: center;flex-direction: column; border-radius: 10px; height: 142px;" onclick="addModel();">';
        list += '<p style="margin: 0;">Novo modelo';
        list += '<br><i style="font-size: 18px" class="si si-plus"></i>';
        list += '</div>';

        $(".listmodels").html(list);

        $('#expert_phone').mask('(00) 00000-0000', {reverse: false});

        flatpickr($("#date"), {
            "locale": "pt" ,
        });

        $('#identity').mask('000.000.000-00', {reverse: false});
        $("#c_type_people").change(function (e) { 
            if ($("#c_type_people").val() == 1) {

                $('#identity').mask('000.000.000-00', {reverse: false});

            } else if ($("#c_type_people").val() == 2) {

                $('#identity').mask('00.000.000/0000-00', {reverse: false});
            }
            
        });

        $("#createProtocol").submit(function (e) {
            if ($("#identity").val() == "") {

                e.preventDefault();
                return error('Digite o documento do cliente.');
            } else if ($("#c_name").val() == "") {

                e.preventDefault();
                return error('Informe o nome completo do cliente.');
            }  else if ($("#c_phone").val() == "" && $("#c_phone_2").val() == "") {

                e.preventDefault();
                return error('Informe ao menos 1 telefone do cliente.');
            }  else if ($("#c_email").val() == "") {

                e.preventDefault();
                return error('Preciamos do email de contato do cliente.');
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
            } else if ($("#sac_problem_category").val() == "" && $("#type").val() != 3) {

                e.preventDefault();
                return error('Por favor, informe a categoria do problema.');
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

        $('#buy_date').mask('00/00/0000', {reverse: false});
        $("#navNewProtocol").addClass('active');
        $('#c_phone').mask('(00) 00000-0000', {reverse: false});
        $('#c_phone_2').mask('(00) 00000-0000', {reverse: false});

        var layout = "";

        layout += '<div class="col-md-12">';
        layout += '<div data-repeater-list="group">';
        layout += '<div data-repeater-item>';
        layout += '<input type="hidden" name="item_id" value="0">';
		layout += '<input type="hidden" name="serial_number" value="0">';
        layout += '<div class="row justify-content-between">';
        layout += '<div class="col-md-3 col-sm-12 form-group">';
        layout += '<label for="p_model">Modelo</label>';
        layout += '<select name="p_model" id="p_model" onchange="loadPart(this);" class="form-control">';
        layout += '</select>';
        layout += '</div>';
        layout += '<div class="col-md-6 col-sm-12 form-group">';
        layout += '<label for="part">Peça</label>';
        layout += '<select name="part" class="form-control">';
        layout += '<option value=""></option>';
        layout += '</select>';
        layout += '</div>';
        layout += '<div class="col-md-1 col-sm-12 form-group">';
        layout += '<label for="quantity">Quantidade</label>';
        layout += '<input type="number" class="form-control" onkeyup="validMax(this)" name="quantity" value="1">';
        layout += '</div>';
        layout += '<div class="col-md-2 col-sm-12 form-group d-flex align-items-center pt-2" style="margin-top: 15px; width: 100% !important;">';
        layout += '<button type="button" class="btn btn-danger" data-repeater-delete>';
        layout += 'Deletar';
        layout += '</button>';
        layout += '</div>';

        layout += '<div class="col-md-12 col-sm-12 form-group">';
        layout += '<label for="p_description">Motivo da peça</label>';
        layout += '<input type="text" class="form-control" id="p_description" name="p_description">';
        layout += '</div>';

        layout += '<div class="col-md-12 col-sm-12 form-group">';
        layout += '<label for="picture">Foto de comprovação <small>Max 2mb</small></label>';
        layout += '<br><input type="file" id="picture" name="picture" accept="image/png, image/jpeg, application/pdf">';
        layout += '</div>';

        layout += '</div>';
        layout += '<hr>';
        layout += '</div>';
        layout += '</div>';
        layout += '<div class="form-group">';
        layout += '<div class="col p-0">';
        layout += '<button type="button" class="btn btn-primary" id="newPart" data-repeater-create>';
        layout += 'Nova peça';
        layout += '</button>';
        layout += '</div>';
        layout += '</div>';
        layout += '</div>';

        $(".repeater-default").html(layout);        

        // form repeater jquery
        $('.file-repeater, .contact-repeater, .repeater-default').repeater({
            show: function () {
                i++;
                $('input[name="group['+ i +'][quantity]"]').val(1);
                var options = "";
                options += '<option></option>';
                for (let index = 0; index < models.length; index++) {
                    const arrayObj = models[index];
                    options += '<option value="'+ arrayObj.product_id +'" data-serial="'+ arrayObj.serial +'">'+ arrayObj.product_text +'</option>';
                }
                $('select[name="group['+ i +'][p_model]"]').html(options);
                $(this).slideDown();
            },
            hide: function (deleteElement) {
                i--;
                $(this).slideUp(deleteElement); 
            }
        });
        
        
        setTimeout(() => {
            $('#hour').val('12:00');
            $(".js-select2").select2({
                maximumSelectionLength: 1,
                ajax: {
                    url: '/suporte/products/protocol', // -> Troquei a rota apenas
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