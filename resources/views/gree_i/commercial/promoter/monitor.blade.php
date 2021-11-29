@extends('gree_i.layout')
<link rel="stylesheet" type="text/css" href="/admin/app-assets/vendors/css/pickers/pickadate/pickadate.css">
@section('content')
<style>
.cardbox {
    box-shadow: -8px 12px 18px 0 rgba(25,42,70,.13);
    -webkit-transition: all .3s ease-in-out;
    transition: all .3s ease-in-out;
    min-height: 155px;
    display: flex;
    justify-content: center;
    flex-direction: column;
    text-align: center;
    object-fit: cover;
}
</style>
<div class="content-overlay"></div>
<div class="content-wrapper">
    <div class="content-header row">
        <div class="content-header-left col-12 mb-2 mt-1">
          <div class="row breadcrumbs-top">
            <div class="col-12">
              <h5 class="content-header-title float-left pr-1 mb-0">Comercial</h5>
              <div class="breadcrumb-wrapper col-12">
                Monitoramento de promotores
              </div>
            </div>
          </div>
        </div>
      </div>
    <div class="content-header row">
    </div>
    <div class="content-body">
        <div class="users-list-filter px-1">
            <form action="#" id="searchNow" method="GET">
                <div class="row border rounded py-2 mb-2">
                    <div class="col-6 col-sm-6 col-lg-4">
                        <label for="users-list-verified">Usuário</label>
                        <fieldset class="form-group">
                            <select class="form-control" id="user" name="user">
                                <option value="">Todos</option>
                                @foreach ($userall as $key)
                                <option value="{{$key->id}}">{{$key->name}}</option>
                                @endforeach
                            </select>
                        </fieldset>
                    </div>

                    <div class="col-12 col-sm-12 col-lg-3">
                        <label for="date_start">Data inicial</label>
                        <fieldset class="form-group">
                            <input type="text" class="form-control date-mask" placeholder="yyyy-mm-dd" id="date_start" name="date_start" />
                        </fieldset>
                    </div>

                    <div class="col-12 col-sm-12 col-lg-1">
                        <label for="hour_start">Hora inicial</label>
                        <fieldset class="form-group">
                            <input type="text" class="form-control hour-mask" id="hour_start" name="hour_start" />
                        </fieldset>
                    </div>

                    <div class="col-12 col-sm-12 col-lg-3">
                        <label for="date_end">Data final</label>
                        <fieldset class="form-group">
                            <input type="text" class="form-control date-mask" placeholder="yyyy-mm-dd" id="date_end" name="date_end" />
                        </fieldset>
                    </div>

                    <div class="col-12 col-sm-12 col-lg-1">
                        <label for="hour_end">Hora final</label>
                        <fieldset class="form-group">
                            <input type="text" class="form-control hour-mask" id="hour_end" name="hour_end" />
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
                        </div>
                    </div>
                </div>
            </div>
        </section>
    </div>
</div>

<div class="modal fade text-left w-100" id="modal-generic" tabindex="-1" role="dialog" aria-labelledby="myModalLabel16" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-xl" role="document">
      <div class="modal-content">
        <div class="modal-header">
          <h4 class="modal-title"></h4>
          <button type="button" class="close" data-dismiss="modal" aria-label="Close">
            <i class="bx bx-x"></i>
          </button>
        </div>
        <div class="modal-body">
            <div class="table-responsive">
                <table id="list-datatable" class="table">
                    <thead>
                        <tr>
                            <th>#</th>
                            <th>Realizar em</th>
                            <th>Endereço</th>
                            <th>Complemento</th>
                            <th>Status</th>
                        </tr>
                    </thead>
                    <tbody class="listroutes">
                    </tbody>
                </table>
                <nav aria-label="Page navigation">
                    <ul class="pagination justify-content-end">
                                                            </ul>
                </nav>
            </div>
        </div>
        <div class="modal-footer actiondetail">
            <button type="button" class="btn btn-light-secondary ml-1" data-dismiss="modal">
                <span class="d-sm-block">FECHAR</span>
            </button>
        </div>
        </form>
      </div>
    </div>
  </div>

  <div id="reportModal" class="modal fade" tabindex="-1" role="dialog" aria-labelledby="reportModal" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-lg">
        <div class="modal-content">
            <div class="modal-header" style="border-bottom: none;">
                <div class="modal-title">RELATÓRIO DE FINALIZAÇÃO</div>
            </div>
            <div class="modal-body">
                <div class="row">
                    <div class="col-md-12">
                        <div class="form-group border p-1">
                            <span id="reporttext" class="form-control-static"></span>
                        </div>
                    </div>
                    <div class="col-md-12">
                        <div class="row loadimage m-0">
                        </div>
                    </div>
                </div>
            </div>
            <div class="modal-footer" style="border-top: none;">
                <button type="button" class="btn btn-secondary waves-effect" data-dismiss="modal">Fechar</button>
            </div>
        </div>
        <!-- /.modal-content -->
    </div>
    <!-- /.modal-dialog -->
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

            $(".js-select2").select2({
                maximumSelectionLength: 1,
            });

            $('.date-mask').pickadate({
                formatSubmit: 'yyyy-mm-dd',
                format: 'yyyy-mm-dd',
                today: 'Hoje',
                clear: 'Limpar',
                close: 'Fechar',
                monthsFull: ['Janeiro', 'Fevereiro', 'Março', 'Abril', 'Maio', 'Junho', 'Julho', 'Agosto', 'Setembro', 'Outubro', 'Novembro', 'Dezembro'],
                monthsShort: ['Jan', 'Fev', 'Mar', 'Abr', 'Mai', 'Jun', 'Jul', 'Ago', 'Set', 'Out', 'Nov', 'Dez'],
                weekdaysFull: ['Domingo', 'Segunda-Feira', 'Terça-Feira', 'Quarta-Feira', 'Quinta-Feira', 'Sexta-Feira', 'Sábado'],
                weekdaysShort: ['Dom', 'Seg', 'Ter', 'Qua', 'Qui', 'Sex', 'Sab'],
            });

            setTimeout(() => {
                socket.on('new position', function(data){
                    if (!is_route) {
                        var index = markers.findIndex(x => x.store_id == data.promoter_id);
                        if (index != -1) {
                            markers[index].setPosition(new google.maps.LatLng(data.latitude, data.longitude));

                            var bounds = new google.maps.LatLngBounds();
                            for (let index = 0; index < markers.length; index++) {
                                const obj = markers[index];
                                bounds.extend(obj.getPosition());
                            }
                            
                            map.fitBounds(bounds);
                        } else {
                            createMarker();
                        }
                    }                    
                });
            }, 1000);

            setInterval(() => {
                if (is_route == false) {
                    createMarker();
                }
            }, 600000);

            $('.hour-mask').mask('00:00', { placeholder: "HH:ss" });

            setInterval(() => {
                $("#mCommercial").addClass('sidebar-group-active active');
                $("#mCommercialPromoter").addClass('sidebar-group-active active');
                $("#mCommercialPromoterMonitor").addClass('active');
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
    var is_route = false;
    var poly;
    var json_row;

    function reloadImg(index, indx) {

        var obj = json_row.routes[index]
        $("#reporttext").html(obj.route_history[indx].report);

        var list = '';
        for (let i = 0; i < obj.route_history[indx].images.length; i++) {
            const img = obj.route_history[indx].images[i];

            list += '<div class="col-md-4 cardbox cursor-pointer">';
            list += '<a target="_blank" href="'+img.image+'"><img id="image" height="100" class="img-fluid" src="'+img.image+'" alt=""></a>';
            list += '</div>';
            
        }

        $(".loadimage").html(list);
        $("#reportModal").modal();

    }
    function showRoutes($this) {
        json_row = JSON.parse($($this).attr("json-data"));
        $(".modal-title").html(json_row.name);
        
        var list = '';

        for (let index = 0; index < json_row.routes.length; index++) {
            const obj = json_row.routes[index];

            list += '<tr class="cursor-pointer showDetails">';
            list += '    <td style="width: 1%">';
            list += '        <i class="row_expand bx bx-minus-circle cursor-pointer bx-plus-circle"></i>';
            list += '   </td>';
            list += '    <td class="date" style="width: 220px;"> <small> <b>começar em:</b> '+ obj.date_start +' <br><b>Termina em:</b> '+obj.date_end+'</small></td>';
            list += '    <td class="address">'+ obj.address +'</td>';
            list += '    <td class="complement">'+ obj.complement +'</td>';
            list += '     <td class="status">';

            if (obj.is_cancelled == 1)
            list += '        <span class="badge badge-light-danger">Cancelado</span>';
            else if (obj.is_completed == 1)
            list += '        <span class="badge badge-light-success">Concluído</span>';
            else
            list += '        <span class="badge badge-light-warning">Pendente</span>';

            list += '        </td>';
            list += '</tr>';

            list += '<tr style="display: none;" class="group">';
            list += '    <td colspan="7">';
            list += '       <div class="card m-0">';
            list += '          <div class="card-content">';
            list += '              <div class="card-body">';
            list += '              <p class="card-text">';
            
            list += '<div class="row">';
            list += '<div class="col-md-4">';
            list += '<div class="form-group">';
            list += '<label>Criado em:</label>';
            list += '<span class="created_at"> '+ obj.created_at +'</span>';
            list += '</div>';
            list += '</div>';
            list += '<div class="col-md-4">';
            list += '    <div class="form-group">';
            list += '<label>CheckIn:</label>';
            list += '<span class="created_at"> '+ obj.checkin +'</span>';
            list += '</div>';
            list += '</div>';
            list += '<div class="col-md-4">';
            list += '<div class="form-group">';
            list += '        <label>Checkout:</label>';
            list += '        <span class="created_at"> '+ obj.checkout +'</span>';
            list += '    </div>';
            list += '</div>';
            list += '<div class="col-md-12">';
            list += '    <div class="form-group">';
            list += '         <label>Descrição:</label>';
            list += '        <span class="description">'+ obj.description +'</span>';
            list += '    </div>';
            list += '</div>';
            list += '</div>';

            // tasks
            if (obj.route_history.length > 0) {
                list += '<hr>';
                list += '<h5>TAREFAS</h5>';
            }

            for (let indx = 0; indx < obj.route_history.length; indx++) {
                const elm = obj.route_history[indx];

                var i_pos = indx + 1;
                list += '<div class="row" style="border: solid 1px;padding: 10px;margin-bottom: 10px;margin-left: 3px;margin-right: 3px;">';
                list += '    <div class="col-md-1" style="display: flex;justify-content: center;flex-direction: column;text-align: center;">';
                list += '      <div class="number-pos" style="font-size: 20px;font-weight: 600;">';
                list += '          <span>'+ i_pos +'</span>';
                list += '      </div>';
                list += '   </div>';
                list += '  <div class="col-md-11">';
                list += '      <div class="row">';
                list += '         <div class="col-md-6">';
                list += '            <div class="form-group">';

                if (elm.attach_date == null || elm.attach_date == "")
                list += '                <label>Comprovação:</label>';
                else
                list += '                <label>Comprovação: <a onclick="reloadImg('+index+', '+indx+')" href="javascript:void(0)">Clique aqui</a></label>';

                list += '              <span></span>';
                list += '          </div>';
                list += '       </div>';
                list += '      <div class="col-md-3">';
                list += '         <div class="form-group">';
                list += '           <label>Feito em: '+ elm.attach_date +'</label>';
                list += '          <span></span>';
                list += '      </div>';
                list += '   </div>';
                list += '    <div class="col-md-3">';
                list += '        <div class="form-group">';
                    
                if(elm.attach_date)
                list += '           Status:  <span class="badge badge-light-success">Concluído</span>';
                else
                list += '           Status:  <span class="badge badge-light-warning">Pendente</span>';

                list += '       </div>';
                list += '   </div>';
                list += '   <div class="col-md-12">';
                list += '   <div class="form-group">';
                list += '        <label>Descrição:</label>';
                list += '        <span> '+ elm.description +'</span>';
                list += '     </div>';
                list += '   </div>';
                list += '  </div>';
                list += ' </div>';
                list += ' </div>';
                
            }

            list += '</p>';
            list += '</div>';
            list += '</div>';
            list += '</div>';
            list += '</td>';
            list += '</tr>';
        
        }
        
        $(".listroutes").html(list);
        $('.showDetails td').click(function (e) { 
            e.preventDefault();
            $(this).parent().next().toggle();
            $(this).parent().find('.row_expand').toggleClass('bx-plus-circle');
            
        });
        $("#modal-generic").modal();
    }
    
    function initAutocomplete() {
        map = new google.maps.Map(document.getElementById('map'), {
          center: {lat: -3.119028, lng: -60.021732},
          zoom: 10,
          mapTypeId: 'terrain'
        });
        poly = new google.maps.Polyline({
            strokeColor: '#0C4391',
            strokeOpacity: 1.0,
            strokeWeight: 3
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
        markers = new Array();
    }    
    function clearOverlays(arr) {
        poly.setMap(null);
        var path = poly.getPath();
    }

      function createMarker(lat, long) {
        clearMarkers();
        block();
        ajaxSend('/commercial/promoter/monitor/filter', $("#searchNow").serialize()).then(function(response){
            unblock();

            // For each place, get the icon, name and location.
            var bounds = new google.maps.LatLngBounds();
            is_route = response.is_route;
            if (response.is_route) {
                clearOverlays();
                poly.setMap(map);
                var path = poly.getPath();
                for (i = 0; i < response.track.length; i++) {
                    if ($("#user").val() == response.track[i].id) {
                        for (let index = 0; index < response.track[i].positions.length; index++) {
                            const element = response.track[i].positions[index];
                            
                            var marker_ll = new google.maps.LatLng(element.latitude, element.longitude);
                            path.push(marker_ll);
                            if (index == 0) {

                                var result = new google.maps.Marker({
                                    position: marker_ll,
                                    map: map,
                                    zIndex: 2,
                                    icon: '/media/start_directions.png',
                                    title: 'COMEÇOU AQUI',
                                });

                                markers.push(result);
                                var checkin = element.has_checkin == 1 ? 'FEITO' : 'NÃO';
                                var checkout = element.has_checkout == 1 ? 'FEITO' : 'NÃO';

                                if (element.promoter_route_id == 0)
                                html = '<h6>Iniciou a rota</h6><p><small class="text-muted" style="font-size: 18px;line-height: 1.8;"><b>Atualizado em:</b><br> '+ element.created_at +'</small></p>';
                                else
                                html = '<h6>Iniciou a rota</h6><p><small class="text-muted"><b>Descrição da rota:</b> '+ element.route.description +'</small><br><small class="text-muted"><b>Fez checkin?:</b> '+ checkin +'</small><br><small class="text-muted"><b>Fez checkout?:</b> '+ checkout +'</small><br><small class="text-muted"><b>Atualizado em:</b> '+ element.created_at +'</small></p>';
                                
                                bindInfoWindow(result, map, infoWindow, html);

                            } else if (index == response.track[i].positions.length-1) {

                                var result = new google.maps.Marker({
                                    position: marker_ll,
                                    map: map,
                                    zIndex: 2,
                                    icon: '/media/end_directions.png',
                                    title: 'TERMINOU AQUI',
                                });

                                markers.push(result);
                                if (element.promoter_route_id == 0)
                                html = '<h6>Terminou a rota</h6><p><small class="text-muted" style="font-size: 18px;line-height: 1.8;"><b>Atualizado em:</b><br> '+ element.created_at +'</small></p>';
                                else
                                html = '<h6>Terminou a rota</h6><p><small class="text-muted"><b>Rota id:</b> '+ element.promoter_route_id +'</small><br><small class="text-muted"><b>Fez checkin?:</b> '+ element.has_checkin +'</small><br><small class="text-muted"><b>Fez checkout?:</b> '+ element.has_checkout +'</small><br><small class="text-muted"><b>Atualizado em:</b> '+ element.created_at +'</small></p>';
                                
                                bindInfoWindow(result, map, infoWindow, html);
                                

                            } else {

                                var result = new google.maps.Marker({
                                    position: marker_ll,
                                    map: map,
                                    zIndex: 1,
                                    icon: pin_client,
                                    title: 'ROTA',
                                });

                                markers.push(result);
                                if (element.promoter_route_id == 0)
                                html = '<h6>A caminho</h6><p><small class="text-muted" style="font-size: 18px;line-height: 1.8;"><b>Atualizado em:</b><br> '+ element.created_at +'</small></p>';
                                else
                                html = '<h6>A caminho</h6><p><small class="text-muted"><b>Rota id:</b> '+ element.promoter_route_id +'</small><br><small class="text-muted"><b>Fez checkin?:</b> '+ element.has_checkin +'</small><br><small class="text-muted"><b>Fez checkout?:</b> '+ element.has_checkout +'</small><br><small class="text-muted"><b>Atualizado em:</b> '+ element.created_at +'</small></p>';
                                
                                bindInfoWindow(result, map, infoWindow, html);

                            }

                            

                            bounds.extend(marker_ll);
                        }
                    }
                }

                map.fitBounds(bounds);

            } else {

                for (i = 0; i < response.track.length; i++) {

                    var marker_ll = new google.maps.LatLng(response.track[i].lastposition.latitude, response.track[i].lastposition.longitude);
                    var picicon;
                    if (response.track[i].icon == null) {
                        picion = '/media/avatars/round10.png';
                    } else {
                        picion = response.track[i].icon;
                    }
                    var result = new google.maps.Marker({
                        position: marker_ll,
                        map: map,
                        zIndex: 1,
                        icon: picion,
                        title: response.track[i].name,
                        store_id: response.track[i].id,
                    });
                    markers.push(result);
                    var button = response.track[i].routes.length > 0 ? `<button class='btn btn-sm btn-outline-warning mt-1' json-data='${JSON.stringify(response.track[i])}' onclick='showRoutes(this)''>Ver rotas pendentes</button>` : '';
                    html = '<div class="text-center"><h6>Informações de contato</h6><p class="text-left"><small class="text-muted"><b>Nome:</b> '+ response.track[i].name +'</small><br><small class="text-muted"><b>Telefones:</b> '+ response.track[i].phone_1 +' / '+ response.track[i].phone_2 +'</small><br><small class="text-muted"><b>Email:</b> '+ response.track[i].email +'</small><br><small class="text-muted"><b>Documento:</b> '+ response.track[i].identity +'</small><br></p>'+button+'</div>';
                    bindInfoWindow(result, map, infoWindow, html);

                    bounds.extend(marker_ll);
                }

                map.fitBounds(bounds);

            }
            

        }).catch(function(err){
            unblock();
            $error(err.message)
        })
        // Create a marker for each place.

        }
</script>
<script src="https://maps.googleapis.com/maps/api/js?v=3.exp&key={{ getConfig("google_key_web") }}&libraries=places&callback=initAutocomplete"></script>

@endsection