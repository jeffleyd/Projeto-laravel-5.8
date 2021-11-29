@extends('gree_sac_authorized.panel.layout')

@section('content')
@if ($is_active == 0)
<div class="alert alert-warning alert-dismissable " role="alert">
    <p class="mb-0">Preencha seu endereço abaixo para que possamos saber aonde estar localizado.</p>
</div>
@endif

<div class="col-md-12">
    <div class="block">
        <div class="block-header block-header-default">
            <h3 class="block-title">Informações de cadastro</h3>
        </div>
        <div class="block-content">
            <form action="/autorizada/perfil_do" id="submitForm" method="post">
                <input type="hidden" name="latitude" value="{{ $latitude }}" id="latitude">
                <input type="hidden" name="longitude" value="{{ $longitude }}" id="longitude">
                <div class="row">
                    <div class="col-md-12">
                        <div class="form-group">
                            <label for="name_contact">Nome do responsável para contato</label>
                            <input type="text" class="form-control" id="name_contact" value="{{ $name_contact }}" name="name_contact">
                        </div>
                    </div>
                    <div class="col-md-8">
                        <div class="form-group">
                            <label for="address">Digite seu endereço e número</label>
                            <input type="text" class="form-control" id="address" @if ($is_active == 1) value="{{ $address }}" @endif name="address" placeholder="Exempo: Rua joaquim de nazaré 123">
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="form-group">
                            <label for="complement">Comeplemento</label>
                            <input type="text" class="form-control" id="complement" value="{{ $complement }}" name="complement">
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-group">
                            <label for="zipcode">CEP</label>
                            <input type="text" class="form-control" id="zipcode" value="{{ $zipcode }}" name="zipcode">
                        </div>
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
                            <input type="text" class="form-control" name="state" id="state" value="{{ $state }}" placeholder="Digite o estado.">
                        </fieldset>
                    </div>
                    <div class="col-md-6">
                        <div class="form-group">
                            <label for="phone_1">Telefone</label>
                            <input type="text" class="form-control" id="phone_1" value="{{ $phone_1 }}" name="phone_1" placeholder="(00) 0000-0000">
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-group">
                            <label for="phone_2">Telefone</label>
                            <input type="text" class="form-control" id="phone_2" value="{{ $phone_2 }}" name="phone_2" placeholder="(00) 0000-0000">
                        </div>
                    </div>
                    <div class="col-md-12">
                        <div class="form-group">
                            <label for="email">Email</label>
                            <input type="email" class="form-control" id="email" value="{{ $email }}" name="email" placeholder="empresa@empresa.com.br">
                        </div>
                    </div>
					<div class="col-md-12">
                        <div class="form-group">
                            <label for="email">Email em cópia</label>
                            <input type="email" class="form-control" id="email_copy" value="{{ $email_copy }}" name="email_copy" placeholder="empresa@empresa.com.br">
                        </div>
                    </div>
                    <div class="col-md-12">
                        <div class="form-group">
                            <label for="password">Senha</label>
                            <input type="text" class="form-control" id="password" name="password" placeholder="******">
                        </div>
                    </div>

                    <hr class="mt-20">
                    <div class="col-md-4">
                        <div class="form-group">
                            <label for="agency">Agência</label>
                            <input type="agency" class="form-control" id="agency" value="{{ $agency }}" name="agency" placeholder="0000">
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="form-group">
                            <label for="account">Conta</label>
                            <input type="text" class="form-control" id="account" name="account" value="{{ $account }}" placeholder="0000000">
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="form-group">
                            <label for="bank">Banco</label>
                            <input type="text" class="form-control" id="bank" name="bank" value="{{ $bank }}" placeholder="Bradesco SA">
                        </div>
                    </div>
                </div>

                <div class="form-group">
                    <button type="submit" class="btn btn-alt-primary">Atualizar informações</button>
                </div>
            </form>
        </div>
    </div>
</div>
<script>
    $(document).ready(function () {
        $('#zipcode').mask('00000-000', {reverse: false});
        $('#phone_1').mask('(00) 00000-0000', {reverse: false});
        $('#phone_2').mask('(00) 00000-0000', {reverse: false});
        $("#submitForm").submit(function (e) {
            if ($("#name_contact").val() == "") {

                e.preventDefault();
                return error('É necessário o preenchimento do nome de contato.');
            } else if ($("#address").val() == "") {

                e.preventDefault();
                return error('Preencha o endereço com número.');
            } else if ($("#latitude").val() == 0.00000000 && $("#longitude").val() == 0.00000000 ) {

                e.preventDefault();
                return error('Preencha novamente seu endereço, pois não conseguimos te localizar.');
            } else if ($("#complement").val() == "") {

                e.preventDefault();
                return error('Preencha o complemento do seu endereço.');
            } else if ($("#phone_1").val() == "" && $("#phone_2").val() == "") {

                e.preventDefault();
                return error('Preencha ao menos 1 telefone para contato.');
            } else if ($("#email").val() == "") {

                e.preventDefault();
                return error('O email é extramamente obrigatório para contato.');
            } else if ($("#agency").val() == "") {

                e.preventDefault();
                return error('Preencha sua agência bancária.');
            } else if ($("#account").val() == "") {

                e.preventDefault();
                return error('Preencha sua conta bancária.');
            } else if ($("#bank").val() == "") {

                e.preventDefault();
                return error('Preencha o nome do seu banco.');
            }
            
            Codebase.loader('show', 'bg-gd-sea');
            
        });
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