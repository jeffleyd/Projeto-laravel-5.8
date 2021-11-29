@extends('gree_i.layout')

@section('content')
<div class="content-overlay"></div>
<div class="content-wrapper">
    <div class="content-header row">
        <div class="content-header-left col-12 mb-2 mt-1">
          <div class="row breadcrumbs-top">
            <div class="col-12">
              <h5 class="content-header-title float-left pr-1 mb-0">Pós venda</h5>
              <div class="breadcrumb-wrapper col-12">
                Mapa global
              </div>
            </div>
          </div>
        </div>
      </div>
    <div class="content-header row">
    </div>
    <div class="content-body">
        <div class="users-list-filter px-1">
            <form action="/sac/map/global" id="searchNow" method="GET">
                <div class="row border rounded py-2 mb-2">
                    <div @if (Session::get('filter_line') == 1) class="col-6 col-sm-6 col-lg-6" @else class="col-12 col-sm-12 col-lg-12" @endif>
                        <label for="users-list-verified">Status</label>
                        <fieldset class="form-group">
                            <select class="form-control" id="status" name="status" style="width: 100%;">
                                <option></option>
                                <option value="1">Ativo</option>
                                <option value="2">desativado</option>
                            </select>
                        </fieldset>
                    </div>

                    @if (Session::get('filter_line') == 1)
                    <div class="col-6 col-sm-6 col-lg-6">
                        <label for="users-list-verified">Tipo de linha</label>
                        <fieldset class="form-group">
                            <select class="form-control" id="type_line" name="type_line">
                                <option value="">Todos</option>
                                <option value="1" @if (Session::get('sacf_type_line') == '1') selected @endif>Residencial</option>
                                <option value="2" @if (Session::get('sacf_type_line') == '2') selected @endif>Comercial</option>
                            </select>
                        </fieldset>
                    </div>
                    @endif

                    <div class="col-12 col-sm-12 col-lg-2">
                        <label for="type_people">Tipo de pesquisa</label>
                        <fieldset class="form-group">
                            <select class="form-control" id="type_people" name="type_people" style="width: 100%;">
                                <option value="0" selected>Livre</option>
                                <option value="1">Física (CPF)</option>
                                <option value="2">Jurídica (CNPJ)</option>
                            </select>
                        </fieldset>
                    </div>
                    <div class="col-12 col-sm-12 col-lg-4">
                        <label for="users-list-verified">Autorizada/Credenciada</label>
                        <fieldset class="form-group">
                            <select class="js-select22 form-control" id="authorized" name="authorized" style="width: 100%;" multiple>
                            </select>
                        </fieldset>
                    </div>
					<div class="col-12 col-sm-12 col-lg-1">
                        <label for="users-list-verified">Estado</label>
                        <fieldset class="form-group">
                            <select class="form-control" id="state" name="state">
								<option value=""></option>
								@foreach (config('gree.states') as $key => $value)
									<option value="{{ $key }}">{{ $value }}</option>
								@endforeach
                            </select>
                        </fieldset>
                    </div>
					<div class="col-12 col-sm-12 col-lg-2">
                        <label for="users-list-verified">Cidade</label>
                        <fieldset class="form-group">
                            <input type="text" class="form-control" id="city" name="city">
                        </fieldset>
                    </div>
                    <div class="col-12 col-sm-12 col-lg-3">
                        <label for="users-list-verified">Habilidade</label>
                        <fieldset class="form-group">
                            <select class="form-control" id="skill" name="skill">
                                <option value=""></option>
                                @foreach ($sac_type as $key)
                                <option value="{{$key->id}}">{{$key->name}}</option>
                                @endforeach
                            </select>
                        </fieldset>
                    </div>
                    <div class="col-12 col-sm-12 col-lg-12 d-flex align-items-center">
                        <button type="submit" class="btn btn-primary btn-block glow users-list-clear mb-0">{{ __('news_i.lt_03') }}</button>
                    </div>
                </div>
            </form>
        </div>
        <section class="users-list-wrapper">
            <div class="users-list-table">
                <div class="card">
                    <div class="card-content">
                        <div class="card-body">
                            <div id="map" style="height: 700px; width:100%"></div>
                            <button type="button" id="authorized-total" class="btn btn-icon rounded-circle btn-primary glow" style="position: absolute;top: -0.8rem;right: -0.5rem;z-index: 9;width: 55px;height: 55px;">0</button>
                        </div>
                    </div>
                </div>
            </div>
        </section>
    </div>
</div>

    <script>
    $(document).ready(function () {
        $(".js-select2").select2({
            maximumSelectionLength: 1,
        });

		createMarker();
		
        $("#searchNow").submit(function (e) { 
            e.preventDefault();

            createMarker();
            
        });

        $("#type_people").change(function (e) { 
            if ($("#type_people").val() == 0) {
                $('.select2-search__field').unmask();
            } else if ($("#type_people").val() == 1) {

                $('.select2-search__field').mask('000.000.000-00', {reverse: false});

            } else if ($("#type_people").val() == 2) {

                $('.select2-search__field').mask('00.000.000/0000-00', {reverse: false});
            }
            
        });

        $('#list-datatable').DataTable( {
            searching: false,
            paging: false,
            ordering:false,
            lengthChange: false,
            language: {
                search: "{{ __('layout_i.dtbl_search') }}",
                zeroRecords: "{{ __('layout_i.dtbl_zero_records') }}",
                info: "{{ __('layout_i.dtbl_info') }}",
                infoEmpty: "{{ __('layout_i.dtbl_info_empty') }}",
                infoFiltered: "{{ __('layout_i.dtbl_info_filtred') }}",
            }
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

            $(".js-select22").select2({
                maximumSelectionLength: 1,
                language: {
                    noResults: function () {

                        var url = "'/sac/authorized/edit/0'";
                        return $('<button type="submit" style="width: 100%" onclick="document.location.href='+ url +'" class="btn btn-primary">Nova Autorizada</button>');
                    }
                },
                ajax: {
                    url: '/misc/sac/authorized/',
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

            setInterval(() => {
            $("#mAfterSales").addClass('sidebar-group-active active');
            $("#mSacMap").addClass('active');
        }, 100);

    });
    </script>
    <script>
    // Drag Event
    var gmarkers = [];
    var markers = [];
    var markern = [];
    var pin_client = '/media/pin.png';
    var pin_authorized = '/media/pin_ath.png'
    var map;
    var infoWindow;

    function initAutocomplete() {
        map = new google.maps.Map(document.getElementById('map'), {
          center: {lat: -3.119028, lng: -60.021732},
          zoom: 13,
          mapTypeId: 'roadmap'
        });
		
		
		infoWindow = new google.maps.InfoWindow;
      }
      // Sets the map on all markers in the array.
		function setMapOnAll(map) {
				for (var i = 0; i < markers.length; i++) {
				  markers[i].setMap(map);
				}
		}

    function bindInfoWindow(marker, map, infoWindow, html) {
        google.maps.event.addListener(marker, 'click', function () {
            infoWindow.setContent(html);
            infoWindow.open(map, marker);
        });
    }
    // Removes the markers from the map, but keeps them in the array.
    function clearMarkers() {
        setMapOnAll(null);
    }
      function createMarker(lat, long) {
        clearMarkers();
        block();
        // Create a marker for each place.
        $.ajax({
                type: "GET",
                url: "/sac/warranty/get/authorizeds/all",
                data: {status: $("#status").val(), authorized: $("#authorized").val(), skill: $("#skill").val(), type_line: $("#type_line").val(), state:$("#state").val(), city: $("#city").val()},
                success: function (response) {
                    unblock();
                    if (response.success) {

                        console.log(response);

                        $("#authorized-total").html(response.authorizeds.length);
                        for (i = 0; i < response.authorizeds.length; i++) {
                            var marker_ll = new google.maps.LatLng(response.authorizeds[i]['latitude'], response.authorizeds[i]['longitude']);
                            var result = new google.maps.Marker({
                                position: marker_ll,
                                map: map,
                                zIndex: 1,
                                icon: pin_authorized,
                                title: response.authorizeds[i]['name'],
                            });
                            markers.push(result);
                            html = '<h6>Informações de contato</h6><p><small class="text-muted"><b>Nome fantasia:</b> '+ response.authorizeds[i]['name'] +'</small><br><small class="text-muted"><b>Nome do contato:</b> '+ response.authorizeds[i]['name_contact'] +'</small><br><small class="text-muted"><b>CNPJ:</b> '+ response.authorizeds[i]['identity'] +'</small><br><small class="text-muted"><b>Telefone:</b> '+ response.authorizeds[i]['phone_1'] +'</small><br><small class="text-muted"><b>Telefone:</b> '+ response.authorizeds[i]['phone_2'] +'</small><br><small class="text-muted"><b>Email:</b> '+ response.authorizeds[i]['email'] +'</small></p>';
                            bindInfoWindow(result, map, infoWindow, html);

							var bounds = new google.maps.LatLngBounds();
		  					bounds.extend(marker_ll);
                        }
						
						google.maps.event.addListener(map, 'zoom_changed', function() {
						zoomChangeBoundsListener = 
								google.maps.event.addListener(map, 'bounds_changed', function(event) {
								if (this.getZoom() > 13 && this.initialZoom == true) {
									// Change max/min zoom here
									this.setZoom(4);
									this.initialZoom = false;
								}
								google.maps.event.removeListener(zoomChangeBoundsListener);
							});
						});
						map.initialZoom = true;
						map.fitBounds(bounds);
						
                    }
                }
            });
		  
        }
</script>

<script src="https://maps.googleapis.com/maps/api/js?v=3.exp&key={{ getConfig("google_key_web") }}&libraries=places&callback=initAutocomplete"></script>

@endsection