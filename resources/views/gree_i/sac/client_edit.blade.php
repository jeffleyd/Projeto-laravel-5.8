@extends('gree_i.layout')

@section('content')
<div class="content-overlay"></div>
    <div class="content-wrapper">
        <div class="content-header row">
            <div class="content-header-left col-12 mb-2 mt-1">
              <div class="row breadcrumbs-top">
                <div class="col-12">
                  <h5 class="content-header-title float-left pr-1 mb-0">Cliente</h5>
                  <div class="breadcrumb-wrapper col-12">
                    @if ($id == 0)
                    Novo cliente
                    @else
                    Atualizar cliente
                    @endif
                  </div>
                </div>
              </div>
            </div>
          </div>
        <div class="content-header row">
        </div>
        <div class="content-body">
            <section>
                <div class="card">
                    <div class="card-header">
                      <h4 class="card-title">Consultar cliente</h4>
                    </div>
                    <div class="card-content">
                      <div class="card-body">
                        <div class="row">
                            <div class="col-12 col-sm-12 col-lg-2">
                                <label for="type_people1">Tipo de pesquisa</label>
                                <fieldset class="form-group">
                                    <select class="form-control" id="type_people1" name="type_people1" style="width: 100%;">
                                        <option value="0" selected>Livre</option>
                                        <option value="1">Física (CPF)</option>
                                        <option value="2">Jurídica (CNPJ)</option>
                                    </select>
                                </fieldset>
                            </div>
                            <div class="col-12 col-sm-12 col-lg-10">
                                <label for="users-list-verified">Cliente</label>
                                <fieldset class="form-group">
                                    <select class="js-select21 form-control" id="client" name="client" style="width: 100%;" multiple>
                                    </select>
                                </fieldset>
                            </div>
                        </div>
                      </div>
                    </div>
                </div>
            </section>
            <form class="needs-validation" action="/sac/client/edit_do" id="submitEdit" method="post" enctype="multipart/form-data">
            <input type="hidden" name="id" id="id" value="<?= $id ?>">
            <input type="hidden" name="latitude" id="latitude" value="<?= $latitude ?>">
            <input type="hidden" name="longitude" id="longitude" value="<?= $longitude ?>">
            <section>
                <div class="card">
                    <div class="card-header">
                      <h4 class="card-title">Preencha todos os dados com atenção</h4>
                    </div>
                    <div class="card-content">
                      <div class="card-body">
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
                                    <label for="name">Nome</label>
                                    <input type="text" class="form-control" name="name" value="{{ $name }}" required>
                                </fieldset>
                            </div>
                            
                            <div class="col-md-2">
                                <fieldset class="form-group">
                                    <label for="identity">Tipo de pessoa</label>
                                    <select name="type_people" id="type_people" class="form-control">
                                        <option value="1" @if ($type_people == 1) selected @endif>Pessoa física (CPF)</option>
                                        <option value="2" @if ($type_people == 2) selected @endif>Pessoa Juridica (CNPJ)</option>
                                    </select>
                                </fieldset>
                            </div>

                            <div class="col-md-10">
                                <fieldset class="form-group">
                                    <label for="identity">CPF/CNPJ</label>
                                    <input type="text" class="form-control" id="identity" name="identity" value="{{ $identity }}" required>
                                </fieldset>
                            </div>

                            <div class="col-md-6">
                                <fieldset class="form-group">
                                    <label for="phone">Telefone</label>
                                    <input type="text" class="form-control" id="phone" name="phone" value="{{ $phone }}" required>
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
                                    <label for="email">Email</label>
                                    <input type="text" class="form-control" id="reg_email" name="email" value="{{ $email }}">
									Caso o cliente não tenha email, deixe em branco!
                                </fieldset>
                            </div>

                            {{-- <div class="col-md-12">
                                <fieldset class="form-group">
                                    <label for="password">Senha (max 4 digitos)</label>
                                    <input type="text" class="form-control" maxlength="4" name="password" value="">
                                </fieldset>
                            </div> --}}

                            <div class="col-md-9">
                                <fieldset class="form-group">
                                    <label for="address">Digite a rua e o número</label>
                                    <input type="text" class="form-control" id="address" name="address" value="{{ $address }}" placeholder="Digite o endereço e número.">
                                </fieldset>
                            </div>

                            <div class="col-md-3">
                                <fieldset class="form-group">
                                    <label for="complement">Complemento</label>
                                    <input type="text" class="form-control" name="complement" value="{{ $complement }}" placeholder="Bloco D apto 108">
                                </fieldset>
                            </div>
                            
                        </div>
                      </div>
                    </div>
                  </div>
                
            </section>

            <button type="submit" id="NewRequest" class="btn btn-primary">@if ($id == 0)
                Criar cliente  
                @else
                Atualizar cliente
                @endif
            </button>

        </form>
        </div>
    </div>
    <script>
		function isEmail(email) {
		  var regex = /^([a-zA-Z0-9_.+-])+\@(([a-zA-Z0-9-])+\.)+([a-zA-Z0-9]{2,4})+$/;
		  return regex.test(email);
		}
        $(document).ready(function () {

            $("#NewRequest").click(function (e) {
                
                $("#submitEdit").submit(function (e) {
					if ($("#reg_email").val() != "") {
						if (!isEmail($("#reg_email").val())) {
							e.preventDefault();
							return $error('Informe um email válido ou deixe em branco.');
						}
					}
                    var form = $(".needs-validation");
                    if (form[0].checkValidity() === false) {
                        e.preventDefault();
                    } else {
                        block();
                    }
                });
                
            });

            $(".js-select21").select2({
                maximumSelectionLength: 1,
                language: {
                    noResults: function () {

                        var url = "'/sac/client/edit/0'";
                        return $('<button type="submit" style="width: 100%" onclick="document.location.href='+ url +'" class="btn btn-primary">Novo cliente</button>');
                    }
                },
                ajax: {
                    url: '/misc/sac/client/',
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

            $("#type_people").change(function (e) { 
                if($("#type_people").val() == 1) {

                    $('#identity').mask('000.000.000-00', {reverse: false});
                } else {
                    $('#identity').mask('00.000.000/0000-00', {reverse: false});
                }
                
            });

            $("#type_people1").change(function (e) { 
                if ($("#type_people1").val() == 0) {
                    $('.select2-search__field').unmask();
                } else if ($("#type_people1").val() == 1) {

                    $('.select2-search__field').mask('000.000.000-00', {reverse: false});

                } else if ($("#type_people1").val() == 2) {

                    $('.select2-search__field').mask('00.000.000/0000-00', {reverse: false});
                }
                
            });
            
            $('#phone').mask('(00) 00000-0000', {reverse: false});
            $('#phone_2').mask('(00) 00000-0000', {reverse: false});
            @if ($type_people == 1)
            $('#identity').mask('000.000.000-00', {reverse: false});
            @else
            $('#identity').mask('00.000.000/0000-00', {reverse: false});
            @endif

            setInterval(() => {
            $("#mAfterSales").addClass('sidebar-group-active active');
            $("#mSac").addClass('sidebar-group-active active');
            $("#mSacClient").addClass('active');
        }, 100);
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
                    alert('Erro ao converter endereço: ' + status);
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