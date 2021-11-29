@extends('gree_i.layout')

@section('content')

<style>
    pre {
        background-color: transparent;
        font-size: 1em;
        color: #26282a;
        font-family: "IBM Plex Sans", Helvetica, Arial, serif;
    }

    .alert-shadow {
       box-shadow: 0 3px 8px 0 rgb(130 140 158 / 58%);
    }
</style>    

<div class="content-overlay"></div>
<div class="content-wrapper">
    <div class="content-header row">
        <div class="content-header-left col-12 mb-2 mt-1">
            <div class="row breadcrumbs-top">
                <div class="col-12">
                    <h5 class="content-header-title float-left pr-1 mb-0">Autorizada</h5>
                    <div class="breadcrumb-wrapper col-12">
                        @if ($id == 0)
                        Nova autorizada
                        @else
                        Atualizar autorizada: {{ $code }}
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="content-header row"></div>
    <div class="content-body">
        <form class="needs-validation" action="/sac/authorized/edit_do" id="submitEdit" method="post" enctype="multipart/form-data">
            <input type="hidden" name="id" id="id" value="<?= $id ?>">
            <input type="hidden" name="latitude" id="latitude" value="<?= $latitude ?>">
            <input type="hidden" name="longitude" id="longitude" value="<?= $longitude ?>">
            <section>
                <div class="row text-center bg-danger text-white cursor-pointer" onclick="window.open('/sac/authorized/view/<?= $id ?>', '_blank')" style=" margin: 0; padding: 7px;">
                    <div class="col-12">
                        APERTE AQUI PARA REALIZAR O LOGIN COMO ESSA AUTORIZADA/CREDENCIADA
                    </div>
                </div>
                <div class="card">
                    <div class="card-header">
                      <h4 class="card-title">Preencha todos os dados com atenção</h4>
                    </div>
                    <div class="card-content">
                        <div class="card-body">
                            <ul class="nav nav-tabs mb-2" role="tablist" id="tabMenu">
                                <li class="nav-item">
                                    <a class="nav-link d-flex align-items-center active" id="account-tab" data-toggle="tab" href="#account" aria-controls="account" role="tab" aria-selected="true">
                                        <i class="bx bx-user mr-25"></i><span class="d-none d-sm-block">Dados da autorizada</span>
                                    </a>
                                </li>
                                <li class="nav-item">
                                    <a class="nav-link d-flex align-items-center" id="notes-tab" data-toggle="tab" href="#notes" aria-controls="notes" role="tab" aria-selected="true">
                                        <i class="bx bx-info-circle mr-25"></i><span class="d-none d-sm-block">Histórico de notas</span>
                                    </a>
                                </li>
                            </ul>
                            <div class="tab-content">
                                <div class="tab-pane active fade show" id="account" aria-labelledby="account-tab" role="tabpanel">
                                    <div class="row">
                                        <div class="col-md-12">
                                            <ul class="list-unstyled mb-0 border p-2 text-center mb-2">
                                                <li class="d-inline-block mr-2">
                                                <fieldset>
                                                    <div class="custom-control custom-radio">
                                                    <input type="radio" class="custom-control-input" value="1" <?php if ($is_active == 1) { ?> checked=""<?php } else { ?><?php } ?> name="is_active" id="active" checked="">
                                                    <label class="custom-control-label" for="active">Ativo</label>
                                                    </div>
                                                </fieldset>
                                                </li>
                                                <li class="d-inline-block mr-2">
                                                <fieldset>
                                                    <div class="custom-control custom-radio">
                                                    <input type="radio" class="custom-control-input" value="0" <?php if ($is_active == 0) { ?> checked=""<?php } else { ?><?php } ?> name="is_active" id="desactive">
                                                    <label class="custom-control-label" for="desactive">Desativado</label>
                                                    </div>
                                                </fieldset>
                                                </li>
                                            </ul>
                                        </div>
                                        <div class="col-md-12">
                                            <fieldset class="form-group">
                                                <label for="only_repair_sell">Atende apenas o que vendeu?</label>
                                                <select class="form-control" id="only_repair_sell" name="only_repair_sell">
                                                    <option value="1" @if ($only_repair_sell == 1) selected @endif>Sim</option>
                                                    <option value="0" @if ($only_repair_sell == 0) selected @endif>Não</option>
                                                </select>
                                            </fieldset>
                                        </div>
                                        <div class="col-md-6">
                                            <fieldset class="form-group">
                                                <label for="code">Código</label>
                                                <input type="text" class="form-control" name="code" value="{{ $code }}" required>
                                            </fieldset>
                                        </div>
                                        <div class="col-md-3">
                                            <fieldset class="form-group">
                                                <label for="type">Tipo da empresa</label>
                                                <select class="form-control" id="type" name="type">
                                                    <option value="1" @if ($type == 1) selected @endif>Autorizada</option>
                                                    <option value="2" @if ($type == 2) selected @endif>Tercerizado</option>
                                                    <option value="3" @if ($type == 3) selected @endif>Revenda</option>
                                                </select>
                                            </fieldset>
                                        </div>
										<div class="col-md-3">
                                            <fieldset class="form-group">
                                                <label for="type">Tem remessa de peça</label>
                                                <select class="form-control" id="remittance" name="remittance">
													<option value="0">Não</option>
                                                    <option value="1" @if ($remittance == 1) selected @endif>Sim</option>
                                                </select>
                                            </fieldset>
                                        </div>
                                        <div class="col-md-6">
                                            <fieldset class="form-group">
                                                <label for="type_people">Tipo de pessoal</label>
                                                <select class="form-control" id="type_people" name="type_people">
                                                    <option value="1" @if ($type_people == 1) selected @endif>Pessoa física (CPF)</option>
                                                    <option value="2" @if ($type_people == 2) selected @endif>Pessoa jurídica (CNPJ)</option>
                                                </select>
                                            </fieldset>
                                        </div>
                                        <div class="col-md-6">
                                            <fieldset class="form-group">
                                                <label for="identity">CPF/CNPJ</label>
                                                <input type="text" class="form-control" id="identity" name="identity" value="{{ $identity }}" required>
                                            </fieldset>
                                        </div>
                                        <div class="col-md-6">
                                            <fieldset class="form-group">
                                                <label for="name">Nome fantasia</label>
                                                <input type="text" class="form-control" name="name" value="{{ $name }}" required>
                                            </fieldset>
                                        </div>
                                        <div class="col-md-6">
                                            <fieldset class="form-group">
                                                <label for="name_contact">Nome do contato</label>
                                                <input type="text" class="form-control" name="name_contact" value="{{ $name_contact }}" required>
                                            </fieldset>
                                        </div>
                                        <div class="col-md-6">
                                            <fieldset class="form-group">
                                                <label for="phone_1">Telefone</label>
                                                <input type="text" class="form-control" id="phone_1" name="phone_1" value="{{ $phone_1 }}" required>
                                            </fieldset>
                                        </div>
                                        <div class="col-md-6">
                                            <fieldset class="form-group">
                                                <label for="phone_2">Telefone</label>
                                                <input type="text" class="form-control" id="phone_2" name="phone_2" value="{{ $phone_2 }}">
                                            </fieldset>
                                        </div>
                                        <div class="col-md-12">
                                            <fieldset class="form-group">
                                                <label for="password">Senha</label>
                                                <input type="text" class="form-control" name="password">
                                            </fieldset>
                                        </div>
                                        <div class="col-md-6">
                                            <fieldset class="form-group">
                                                <label for="email">Email</label>
                                                <input type="text" class="form-control" id="reg_email" name="email" value="{{ $email }}">
                                            </fieldset>
                                        </div>
										<div class="col-md-6">
                                            <fieldset class="form-group">
                                                <label for="email_copy">Email em copia</label>
                                                <input type="text" class="form-control" id="email_copy" name="email_copy" value="{{ $email_copy }}">
                                            </fieldset>
                                        </div>
                                        <div class="col-md-9">
                                            <fieldset class="form-group">
                                                <label for="address">Digite a rua e o número</label>
                                                <input type="text" class="form-control" name="address" id="address" value="{{ $address }}" placeholder="Digite o endereço e número." required>
                                            </fieldset>
                                        </div>
                                        <div class="col-md-3">
                                            <fieldset class="form-group">
                                                <label for="complement">Comeplemento</label>
                                                <input type="text" class="form-control" name="complement" value="{{ $complement }}" placeholder="Bloco D apto 108">
                                            </fieldset>
                                        </div>
                                        <div class="col-md-12">
                                            <div id="map" style="height: 300px; width:100%"></div>
                                        </div>
                                        <div class="col-md-6">
                                            <fieldset class="form-group">
                                                <label for="zipcode">CEP</label>
                                                <input type="text" class="form-control" name="zipcode" id="zipcode" value="{{ $zipcode }}" placeholder="Digite o cep.">
                                            </fieldset>
                                        </div>
                                        <div class="col-md-3">
                                            <fieldset class="form-group">
                                                <label for="city">Cidade</label>
                                                <input type="text" class="form-control" name="city" id="city" value="{{ $city }}" placeholder="Digite a cidade.">
                                            </fieldset>
                                        </div>
                                        <div class="col-md-3">
                                            <fieldset class="form-group">
                                                <label for="state">Estado</label>
                                                <select class="form-control" id="state" name="state" style="width: 100%;">
                                                    <option></option>      
                                                    @foreach (config('gree.states') as $key => $value)
                                                        <option value="{{ $key }}" @if ($key == $state) selected @endif>{{ $value }}</option>
                                                    @endforeach
                                                </select>
                                            </fieldset>
                                        </div>
                                        <div class="col-md-12 p-1">
                                            <div class="row border">
                                                <div class="col-md-6">
                                                    <fieldset class="form-group">
                                                        <label for="agency">Agência</label>
                                                        <input type="text" class="form-control" name="agency" value="{{ $agency }}">
                                                    </fieldset>
                                                </div>
                                                <div class="col-md-6">
                                                    <fieldset class="form-group">
                                                        <label for="account">Conta</label>
                                                        <input type="text" class="form-control" name="account" value="{{ $account }}">
                                                    </fieldset>
                                                </div>
                                                <div class="col-md-12">
                                                    <fieldset class="form-group">
                                                        <label for="bank">Banco</label>
                                                        <input type="text" class="form-control" name="bank" value="{{ $bank }}">
                                                    </fieldset>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-12">    
                                            <label for="bank">Tipos</label>
                                            <ul class="list-unstyled mb-0">
                                                <?php foreach ($sac_type as $index => $key) { ?>
                                                <li class="d-inline-block mr-2 mb-1">
                                                    <fieldset>
                                                    <div class="checkbox">
                                                        <input type="checkbox" class="checkbox-input" id="sac_type<?= $index ?>" value="<?= $key->id ?>" name="sac_type[]"
															   @if ($id != 0)
															   	@foreach($authorized->sacTypes as $type)
																	@if ($type->id_sac_type == $key->id)
																		checked=""
																	@endif
																@endforeach
															   @endif
															   >
                                                        <label for="sac_type<?= $index ?>"><?= $key->name ?></label>
                                                    </div>
                                                    </fieldset>
                                                </li>
                                                <?php } ?>
                                            </ul>    
                                        </div>
                                    </div>
                                </div>
                                <div class="tab-pane fade show" id="notes" aria-labelledby="notes-tab" role="tabpanel">
                                    <div class="row" style="margin-bottom:20px;">
                                        <div class="col-md-12">
                                            <button type="button" id="btnHistoric" class="btn btn-primary float-right" data-toggle="modal" data-target="#modal-historic">Adicionar nota</button>
                                        </div>
                                    </div>    
                                    <div class="row">    
                                        <div class="col-md-12">
                                            @if ($id > 0)
                                            @if ($authorized->historic()->orderBy('id', 'DESC')->count() > 0)
                                            @foreach($authorized->historic()->orderBy('id', 'DESC')->get() as $note)
                                                <div class="alert @if($note->priority == 1) bg-rgba-warning @elseif($note->priority == 2) bg-rgba-primary @elseif($note->priority == 3) bg-rgba-danger @endif mb-2 alert-shadow" role="alert" >
                                                    <button type="button" class="close note_delete" aria-label="Close" value="<?= $note->id?>">
                                                        <span aria-hidden="true">×</span>
                                                    </button>
                                                    <p class="mb-0">
                                                        <span class="text-muted">Nota criada por<span>
                                                        <span style="color:#26282a;">{{ $note->owner()->first_name }} {{ $note->owner()->last_name }}</span> as <span class="text-muted">{{ $note->created_at }}</span>
                                                        @if($note->priority == 1) 
                                                            <span class="badge badge-warning mr-10 mb-1"><small>Prioridade Baixa</small></span>
                                                        @elseif($note->priority == 2)    
                                                            <span class="badge badge-primary mr-10 mb-1"><small>Prioridade Média</small></span>
                                                        @elseif($note->priority == 3)    
                                                            <span class="badge badge-danger mr-10 mb-1"><small>Prioridade Alta</small></span>
                                                        @endif
                                                    </p>
                                                    <pre><?= $note->description?></pre>
                                                </div>
                                            @endforeach
                                            @endif
                                            @endif
                                        </div>    
                                    </div>    
                                </div>
                            </div>
                        </div>
                    </div>
                </div>    
            </section>
            <button type="submit" id="NewRequest" class="btn btn-primary">@if ($id == 0)
                Criar autorizada/credenciada  
                @else
                Atualizar autorizada/credenciada
                @endif
            </button>
        </form>
    </div>
</div>
<div class="modal fade text-left" id="modal-historic" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-dialog-scrollable" role="document">
        <div class="modal-content">
            <div class="modal-header bg-primary white">
                <span class="modal-title">Nova nota</span>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><i class="bx bx-x"></i></button>
            </div>
            <form class="push" id="a_update_form" method="POST" action="/sac/authorized/historic_do">
                <div class="modal-body">
                    <div class="row">
                        <input type="hidden" id="id" name="id">
                        <input type="hidden" name="authorized_id" id="authorized_id" value="<?= $id ?>">
                        <input type="hidden" name="tab" value="notes">
                        <div class="col-sm-12">
                            <label for="type">Descrição</label>
                            <fieldset class="form-group">
                                <textarea id="description" rows="5" class="form-control" name="description" required></textarea>
                            </fieldset>
                        </div>
                        <div class="col-sm-12">
                            <label for="users-list-verified">Prioridade</label>
                            <fieldset class="form-group">
                                <select class="form-control" id="priority" name="priority" style="width: 100%;">
                                    <option value="1">Baixa</option>
                                    <option value="2">Média</option>
                                    <option value="3">Alta</option>
                                </select>
                            </fieldset>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-light-secondary" data-dismiss="modal">
                        <i class="bx bx-x d-block d-sm-none"></i>
                        <span class="d-none d-sm-block">{{ __('news_i.lt_06') }}</span>
                    </button>
                    <button type="submit" class="btn btn-primary ml-1">
                        <i class="bx bx-check d-block d-sm-none"></i>
                        <span class="d-none d-sm-block">Adicionar nota</span>
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
    <script>
		function isEmail(email) {
		  var regex = /^([a-zA-Z0-9_.+-])+\@(([a-zA-Z0-9-])+\.)+([a-zA-Z0-9]{2,4})+$/;
		  return regex.test(email);
		}
        $(document).ready(function () {

            $(".note_delete").click(function(){
                var  value = $(this).val();
                Swal.fire({
                    title: '<?= __('news_i.la_11') ?>',
                    text: "<?= __('news_i.la_12') ?>",
                    type: 'warning',
                    showCancelButton: true,
                    confirmButtonColor: '#3085d6',
                    cancelButtonColor: '#d33',
                    confirmButtonText: '<?= __('trip_i.tn_fly_toast_yes') ?>',
                    cancelButtonText: '<?= __('trip_i.tn_fly_toast_no') ?>',
                    confirmButtonClass: 'btn btn-primary',
                    cancelButtonClass: 'btn btn-danger ml-1',
                    buttonsStyling: false,
                }).then(function (result) {
                    if (result.value) {
                        block();
                        window.location.href = "/sac/authorized/historic/delete/"+ value;
                    }
                })
            });

            $("#type_people").change(function (e) { 
                if($("#type_people").val() == 1) {

                    $('#identity').mask('000.000.000-00', {reverse: false});
                } else {
                    $('#identity').mask('00.000.000/0000-00', {reverse: false});
                }
                
            });
                
            $("#submitEdit").submit(function (e) { 
				if ($("#reg_email").val() != "") {
                    if (!isEmail($("#reg_email").val())) {
                        e.preventDefault();
                        return $error('Informe um email válido ou deixe em branco.');
                    }
                } else if ($("#email_copy").val() != "") {
                    if (!isEmail($("#email_copy").val())) {
                        e.preventDefault();
                        return $error('Informe um email em cópia válido ou deixe em branco.');
                    }
                }
                var form = $(".needs-validation");
                if (form[0].checkValidity() === false) {
                    e.preventDefault();
                } else {
                    block();
                }
            });

            $('#zipcode').mask('00000-000', {reverse: false});
            $('#identity').mask('00.000.000/0000-00', {reverse: false});
            $('#phone_1').mask('(00) 00000-0000', {reverse: false});
            $('#phone_2').mask('(00) 00000-0000', {reverse: false});

            setInterval(() => {
            $("#mAfterSales").addClass('sidebar-group-active active');
            $("#mSac").addClass('sidebar-group-active active');
            $("#mSacAuthorized").addClass('active');
        }, 100);
        });
    </script>
    <script>
        function initAutocomplete() {
            var map = new google.maps.Map(document.getElementById('map'), {
              @if ($address)
              center: {lat: {{ $latitude }}, lng: {{ $longitude }}},
              @else
              center: {lat: -23.005171, lng: -43.348923},
              @endif
              zoom: 16,
              draggable: true,
              mapTypeId: 'roadmap'
            });
            
            // Drag Event
            var gmarkers = [];
            var markers = [];
            var markern = [];
            var marker_cliente = '/media/pin.png';
        
            
                
    
            // Create the search box and link it to the UI element.
            var input = document.getElementById('address');
            var searchBox = new google.maps.places.SearchBox(input);
            
            // Bias the SearchBox results towards current map's viewport.
            map.addListener('bounds_changed', function() {
              searchBox.setBounds(map.getBounds());
            });
    
            $( "#address" ).blur(function() {
                if ($("#address").val() != "") {
    
                    clearMarkers();
                
                
                
                    var geocoder = new google.maps.Geocoder()
                    var end = $("#address").val();
                    var endereco = end;
                    
                    
                    geocoder.geocode( { 'address': endereco}, function(resultado, status) {
                    if (status == google.maps.GeocoderStatus.OK) {
                        
                    var lat1 = resultado[0].geometry.location.lat();
                    var long1 = resultado[0].geometry.location.lng();	
                        
                    var markern = new google.maps.Marker({
                    map: map,
                    icon: marker_cliente,
                    title: 'Local do serviço',
                    animation: google.maps.Animation.DROP,
                    zIndex: 2,
                    position: {lat: lat1, lng: long1},
                    draggable: true
                    });
                        markers.push(markern);
    
                    google.maps.event.addListener(markern, 'drag', function() {
                        console.log('posição Lat: ' + markern.position.lat());
                        console.log('posição lng: ' + markern.position.lng());
                        document.getElementById('latitude').value = markern.position.lat();
                        document.getElementById('longitude').value = markern.position.lng();
                    });
    
                    google.maps.event.addListener(markern, 'dragend', function() {
                        geocodeR(markern.position.lat(), markern.position.lng());
                    });
                    
                    console.log( "Endereço: " + endereco + " - Latitude: " + lat1 + " Longitude: " + long1);
                    document.getElementById("latitude").value = lat1;
                    document.getElementById("longitude").value = long1;
    
                    } else {
                        var r = confirm("Endereço não foi encontrado, deseja continuar assim mesmo?");
                        if (r == true) {
                            $("#not_address").click();
                        }
                    }
                });
    
                }
                
            });
    
            // Sets the map on all markers in the array.
            function setMapOnAll(map) {
                    for (var i = 0; i < markers.length; i++) {
                      markers[i].setMap(map);
                    }
            }
            
            function geocodeR(latitude, longitude) {
                var pos = {
                lat: latitude,
                lng: longitude
                };
    
                var geocoder = new google.maps.Geocoder();
                geocoder.geocode({ location: pos }, function(results, status) {
                    if (status === "OK") {
                    if (results[0]) {
                        $("#address").val(results[0].formatted_address);
                    } else {
                        window.alert("Não foi encontrado o endereço.");
                    }
                    } else {
                    window.alert("Geocoder failed due to: " + status);
                    }
                });
            }
    
            // Removes the markers from the map, but keeps them in the array.
             function clearMarkers() {
                    setMapOnAll(null);
                  }
            
            
             // Create a marker for each place.
                var marker = new google.maps.Marker({
                  map: map,
                  icon: marker_cliente,
                  title: 'Local do serviço',
                  zIndex: 2,
                  animation: google.maps.Animation.DROP,
                  @if ($address)
                  position: {lat: {{ $latitude }}, lng: {{ $longitude }}},
                @else
                position: {lat: -23.005171, lng: -43.348923},
                @endif
                  draggable: true,
                });
                
                
                google.maps.event.addListener(marker, 'drag', function() {
                    console.log('posição Lat: ' + marker.position.lat());
                    console.log('posição lng: ' + marker.position.lng());
                    document.getElementById('latitude').value = marker.position.lat();
                    document.getElementById('longitude').value = marker.position.lng();
                });
    
                google.maps.event.addListener(marker, 'dragend', function() {
                    geocodeR(marker.position.lat(), marker.position.lng());
                });
    
            // Listen for the event fired when the user selects a prediction and retrieve
            // more details for that place.
            searchBox.addListener('places_changed', function() {
              var places = searchBox.getPlaces();
              
    
              if (places.length == 0) {
                return;
              }
              
              
    
             clearMarkers();
    
              // For each place, get the icon, name and location.
              var bounds = new google.maps.LatLngBounds();
              places.forEach(function(place) {
                if (!place.geometry) {
                  console.log("Returned place contains no geometry");
                  return;
                }
                
                
                document.getElementById('latitude').value = place.geometry.location.lat();
                document.getElementById('longitude').value = place.geometry.location.lng();
    
                marker.setPosition(place.geometry.location);
                marker.setVisible(false);
    
    
                if (place.geometry.viewport) {
                  // Only geocodes have viewport.
                  bounds.union(place.geometry.viewport);
                } else {
                  bounds.extend(place.geometry.location);
                }
              });
              map.fitBounds(bounds);
            });
            
            
            
          }
    </script>
    <script src="https://maps.googleapis.com/maps/api/js?v=3.exp&key={{ getConfig("google_key_web") }}&libraries=places&callback=initAutocomplete"></script>
    
@endsection