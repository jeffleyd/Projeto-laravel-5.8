@extends('gree_sac_authorized.panel.layout')

@section('content')
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
<div class="row js-appear-enabled animated fadeIn" data-toggle="appear">
    <div class="col-12 col-xl-12">
        <div class="block">
            <div class="block-content block-content-full">
                Modelo padrão de relatório de análise técnica: <a href="{{ Request::root() }}/area_tecnica/report_tech.pdf" target="_blank">Baixe aqui</a>
            </div>
        </div>
    </div>
</div>

<div class="row js-appear-enabled animated fadeIn" data-toggle="appear">
    <div class="col-12 col-xl-12">
        <div class="block">
            <div class="block-content block-content-full">
                <form action="/autorizada/os" method="get">
                    <div class="row">
                        <div class="col-4">
                            <label for="model">OS</label>
                            <input type="text" class="form-control" value="{{ Session::get('filter_os') }}" id="os" name="os" placeholder="Ex: W20201">
                            <div class="form-text text-muted">Digite a OS que deseja pesquisar.</div>
                        </div>
						<div class="col-4">
                            <label for="model">Protocolo</label>
                            <input type="text" class="form-control" value="{{ Session::get('filter_protocol') }}" id="protocol" name="protocol" placeholder="Ex: G2020124554">
                            <div class="form-text text-muted">Digite o protocolo que deseja pesquisar.</div>
                        </div>
                        <div class="col-4" style="display: flex;flex-direction: column;justify-content: center;">
                            <button class="btn btn-primary" type="submit">Buscar</button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<div class="row js-appear-enabled animated fadeIn" data-toggle="appear">
    <div class="col-12 col-xl-12">
        <div class="block">
            <div class="block-header block-header-default">
                <h3 class="block-title">Todas as suas O.S</h3>
                <div class="block-options">
                    <a href="/autorizada/atendimento">
                        <button type="button" class="btn btn-sm btn-info">Cadastrar nova ordem de serviço</button>
                    </a>
                    <button type="button" style="display: none" class="btn-block-option" data-toggle="block-option" data-action="state_toggle">
                        <i class="si si-refresh"></i>
                    </button>
                </div>
            </div>
            <div class="block-content block-content-full">
                <div class="table-responsive">
                <table class="table table-bordered table-striped table-vcenter js-dataTable-full">
                    <thead>
                        <tr>
                            <th class="text-center">#Os</th>
							<th class="text-center">Protocolo</th>
                            <th>Endereço</th>
                            <th>Complemento</th>
                            <th>Cliente</th>
                            <th>Telefone</th>
                            <!--<th>Ganho</th>-->
                            <th class="text-center">Ação</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($os as $key)
                        <tr>
                            <td class="text-center">{{ $key->code }}</td>
							<td>{{ $key->sacProtocol->code }}</td>
                            <td>{{ $key->sacProtocol->address }}</td>
                            <td>{{ $key->sacProtocol->complement }}</td>
                            <td>{{ $key->sacProtocol->clientProtocol->name }}</td>
                            <td>
                                {{ $key->sacProtocol->clientProtocol->phone }}
                                @if ($key->sacProtocol->clientProtocol->phone_2)
                                <br>{{ $key->sacProtocol->clientProtocol->phone_2 }}
                                @endif
                            </td>
                            {{--<td class="font-w600">{{ number_format($key->total, 2, '.', ',') }}</td> --}}
                            <td class="text-center">
                                @if ($key->is_paid == 1)
                                <button type="button" class="btn btn-sm btn-success">Concluído</button>
                                @elseif ($key->is_cancelled == 1)
                                <button type="button" class="btn btn-sm btn-danger">Cancelado</button>
                                @elseif ($key->sacProtocol->pending_completed == 1 or $key->sacProtocol->is_completed)
                                <button type="button" class="btn btn-sm btn-warning">Aguardando Pag.</button>
                                @else
                                <button type="button" onclick="menu({{ $key->id }})" class="btn btn-sm btn-info">Ver mais</button>
                                @endif
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
                <nav aria-label="Page navigation">
                    <ul class="pagination justify-content-end">
                        <?= $os->render(); ?>
                    </ul>
                </nav>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="modal fade" style="overflow: auto !important;" id="modal-os" role="dialog" aria-labelledby="modal-os" aria-hidden="true">
    <div class="modal-dialog modal-dialog-popout modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalScrollableTitle">Atualizar atendimento</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                  <span aria-hidden="true">&times;</span>
                </button>
              </div>
              <div class="modal-body">
                <form action="/autorizada/atualizar/atendimento" method="post" id="updateOs" enctype="multipart/form-data">
                <input type="hidden" name="id" id="upd_id">
                <div class="row">
                    <div class="m-15 p-10 border">
                        <b class="mb-5">Informações do cliente</b>
                        <div class="row">
                            <div class="col-md-12">
                                <b>Nome:</b> <span id="c_name"></span>
                             </div>
                             <div class="col-md-12">
                                 <b>Telefone:</b> <span id="c_phones"></span>
                             </div>
                             <div class="col-md-12">
                                 <b>Modelo(s):</b> <span id="p_model"></span>
                             </div>
                             <div class="col-md-12">
                                 <b>Reclamação:</b> <span id="p_description"></span>
                             </div>

                             <div class="col-md-12 mt-10">
                                 <b>Endereço:</b> <span id="p_address"></span>
                             </div>
                             <div class="col-md-12 ">
                                 <b>Complemento:</b> <span id="p_complement"></span>
                             </div>
                        </div>
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
                            <input type="text" class="js-flatpickr form-control" data-allow-input="true" data-enable-time="true" data-no-calendar="true" data-date-format="H:i" data-time_24hr="true" id="hour" name="hour" placeholder="00:00">
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
                    <div class="col-md-12">
                        <div class="form-group row">
                            <div class="col-12">
                                <div class="custom-control custom-checkbox mb-5">
                                    <input class="custom-control-input" type="checkbox" name="has_completed" id="has_completed" value="1">
                                    <label class="custom-control-label" for="has_completed">Marque essa opção para declarar que concluíu o serviço.</label>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-12 os_signature" style="display: none">
                        <label for="os_signature">Anexe OS Assinada pelo cliente.</label>
                        <div class="form-group">
                            <input type="file" id="os_signature" name="os_signature">
                        </div>
                    </div>
                    <div class="col-md-12 diagnostic_test" style="display: none">
                        <label for="diagnostic_test">Anexe relatório técnico.</label>
                        <div class="form-group">
                            <input type="file" id="diagnostic_test" name="diagnostic_test">
                        </div>
                    </div>
                    <div class="col-md-12 description" style="display: none">
                        <div class="form-group">
                            <label for="description_1">Conclusão</label>
                            <textarea class="form-control" id="description_1" name="description_1" placeholder="Descreva o que foi resolvido aqui..."></textarea>
                        </div>
                    </div>
                </div>

              </div>
              <div class="modal-footer">
                <button type="button" class="btn btn-alt-secondary" onclick="backMenu();" data-dismiss="modal">
                    Fechar
                </button>
                <button type="submit" class="btn btn-alt-primary">
                    Enviar informações
                </button>
            </div>
            </form>
        </div>
    </div>
</div>

<div class="modal fade" style="overflow: auto !important;" id="modal-part" role="dialog" aria-labelledby="modal-part" aria-hidden="true">
    <div class="modal-dialog modal-dialog-popout modal-xl" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalScrollableTitle">Nova solicitação de peças</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                  <span aria-hidden="true">&times;</span>
                </button>
              </div>
              <div class="modal-body">
                <form action="/autorizada/pecas/atendimento" method="post" id="updatePecas" enctype="multipart/form-data">
                <input type="hidden" name="id" id="updp_id">
                <div class="row repeater-default loadlayout">

                </div>
                <div class="row py-2 mb-2">
                    <div class="col-12 col-sm-12 col-lg-12 text-center">
                        <label for="users-list-verified">Relatório técnico (Obrigatório)</label>
                        <fieldset class="form-group">
                            <input type="file" name="report" required>
                        </fieldset>
                        <p><a href="{{ Request::root() }}/area_tecnica/report_tech.pdf" target="_blank">Baixar modelo padrão</a></p>
                    </div>
                </div>
              </div>
              <div class="modal-footer">
                <button type="button" class="btn btn-alt-secondary" onclick="backMenu();" data-dismiss="modal">
                    Fechar
                </button>
                <button type="submit" class="btn btn-alt-primary">
                    Enviar solicitação
                </button>
             </div>
            </form>
        </div>
    </div>
</div>

<div class="modal fade" style="overflow: auto !important;" id="modal-spart" role="dialog" aria-labelledby="modal-spart" aria-hidden="true">
    <div class="modal-dialog modal-dialog-popout modal-xl" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalScrollableTitle">Peças solicitadas</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                  <span aria-hidden="true">&times;</span>
                </button>
              </div>
              <div class="modal-body">
                <div class="col-md-12">
                    <table class="table table-striped table-vcenter">
                        <thead>
                            <tr>
                                <th class="text-center">Modelo</th>
                                <th>Peça</th>
                                <th>Quantidade</th>
                                <th>Comprovação</th>
                                <!--<th>Ganho</th>-->
                            </tr>
                        </thead>
                        <tbody id="listPartView">
                        </tbody>
                    </table>
                </div>
              </div>
              <div class="modal-footer">
                <button type="button" class="btn btn-alt-secondary" onclick="backMenu();" data-dismiss="modal">
                    Fechar
                </button>
            </div>
        </div>
    </div>
</div>

<div class="modal fade" id="modal-menu" tabindex="-1" role="dialog" aria-labelledby="modal-menu" aria-hidden="true">
    <div class="modal-dialog modal-dialog-popout" role="document">
        <div class="modal-content">
            <div class="block block-themed block-transparent mb-0">
                <div class="block-header bg-primary-dark">
                    <h3 class="block-title">ESCOLHA ABAIXO</h3>
                    <div class="block-options">
                        <button type="button" class="btn-block-option" data-dismiss="modal" aria-label="Close">
                            <i class="si si-close"></i>
                        </button>
                    </div>
                </div>
                <div class="block-content">
                    <ul class="list-group push text-center" style="text-transform: uppercase;">
                        <a onclick="requestPartView()" data-dismiss="modal" class="list-group-item list-group-item-action d-flex justify-content-between align-items-center" href="javascript:void(0)">
                            <div style="width: 100%"><b>Ver peças enviadas</b></div>
                        </a>
                        <a onclick="requestPart()" data-dismiss="modal" class="list-group-item list-group-item-action d-flex justify-content-between align-items-center" href="javascript:void(0)">
                            <div style="width: 100%"><b>Nova solicitação de peças</b></div>
                        </a>
                        <a target="_blank" id="linkos" class="list-group-item list-group-item-action d-flex justify-content-between align-items-center">
                            <div style="width: 100%;"><b>Imprimir Ordem de serviço</b><br><i style="text-transform:lowercase">(Precisa estar assinada para finalizar)</i></div>
                        </a>
                        <a onclick="updateOs()" data-dismiss="modal" class="list-group-item list-group-item-action d-flex justify-content-between align-items-center" href="javascript:void(0)">
                            <div style="width: 100%"><b>Atualizar ou finalizar atendimento</b></div>
                        </a>
                        <a target="_blank" id="interactive_os" class="list-group-item list-group-item-action d-flex justify-content-between align-items-center">
                            <div style="width: 100%;"><b>Interagir com assistência</b></div>
                        </a>
                    </ul>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-alt-secondary" data-dismiss="modal">
                        Fechar
                    </button>
                </div>
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
		var serial_number_field = item.replace("[model]", "[serial_number]");
		$('input[name="'+ serial_number_field +'"]').val(serial_number_selected);
        var res = item.replace("[model]", "[part]");
        if (value != "") {
            $('select[name="'+ res +'"]').load("/misc/part/list/" + value, function (response, status, request) {
                if ( status == "error" ) {

                    return alert('Ocorreu um erro na conexão, tente novamente!');
                }
            });
        }
    }
	
	function validMax($this) {
		if ($($this).val() <= 0) { 
			$($this).val(0);
		}	
	}
	
    function menu(id) {
        $('.btn-block-option').click();

        $.ajax({
            type: "GET",
            url: "/autorizada/buscar/os/" + id,
            success: function (response) {
                i = 0;
                $('.block').removeClass("block-mode-loading");
                if (response.success) {

                    o_id = response.id;
                    $("#linkos").removeAttr('href');
                    $("#linkos").attr('href', '/autorizada/imprimir/os/' + response.id);
                    $("#interactive_os").removeAttr('href');
                    $("#interactive_os").attr('href', '/autorizada/os/interacao/' + response.id);
                    $("#updp_id").val(o_id);
                    $("#upd_id").val(o_id);
                    $("#c_name").html(response.name);
                    $("#c_phones").html(response.phones);
                    $("#p_model").html(response.model);
                    $("#p_description").html(response.description);
                    $("#p_address").html(response.address);
                    $("#p_complement").html(response.complement);

                    $("#date").val(response.date);
                    $("#hour").val(response.hour);
                    $("#expert_name").val(response.expert_name);
                    $("#expert_phone").val(response.expert_phone);

                    var list = "";
                    for (let index = 0; index < response.v_parts.length; index++) {
                        var obj = response.v_parts[index];
                        if (obj.status == 1) {
                            list += '<tr class="table-success">';
                        } else if (obj.status == 2) {
                            list += '<tr class="table-danger">';
                        } else {
                            list += '<tr>';
                        }

                        list += '<th class="text-center">'+ obj.model +'</th>';
                        list += '<td>'+ obj.part +'</td>';
                        list += '<td class="text-center">'+ obj.quantity +'</td>';
                        if (obj.attach){
                            list += '<td class="text-center"><a href="'+ obj.attach +'" target="_blank">VER IMAGEM</a></td>';
                        } else {
                            list += '<td class="text-center">--</td>';
                        }

                        /*if (obj.total == "0.00"){
                            list += '<td class="text-center">--</td>';
                        } else {
                            list += '<td class="text-center">'+ obj.total +'</td>';
                        }*/

                        list += '</tr>';
                    }

                    $("#listPartView").html(list);

                    var layout = "";

                    layout += '<div class="col-md-12">';
                    layout += '<div data-repeater-list="group">';
                    layout += '<div data-repeater-item>';
                    layout += '<input type="hidden" name="item_id" value="0">';
					layout += '<input type="hidden" name="serial_number" value="0">';
                    layout += '<div class="row justify-content-between">';
                    layout += '<div class="col-md-3 col-sm-12 form-group">';
                    layout += '<label for="title">Modelo</label>';
                    layout += '<select name="model" id="model" onchange="loadPart(this);" class="form-control" required>';
                    layout += '</select>';
                    layout += '</div>';
                    layout += '<div class="col-md-6 col-sm-12 form-group">';
                    layout += '<label for="part">Peça</label>';
                    layout += '<select name="part" class="form-control" required>';
                    layout += '<option value=""></option>';
                    layout += '</select>';
                    layout += '</div>';
                    layout += '<div class="col-md-1 col-sm-12 form-group">';
                    layout += '<label for="quantity">Quantidade</label>';
                    layout += '<input type="number" class="form-control" onkeyup="validMax(this)" name="quantity" value="1" required>';
                    layout += '</div>';
                    layout += '<div class="col-md-2 col-sm-12 form-group d-flex align-items-center pt-2" style="margin-top: 15px; width: 100% !important;">';
                    layout += '<button type="button" class="btn btn-danger" data-repeater-delete>';
                    layout += 'Deletar';
                    layout += '</button>';
                    layout += '</div>';

                    layout += '<div class="col-md-12 col-sm-12 form-group">';
                    layout += '<label for="description">Motivo da peça</label>';
                    layout += '<input type="text" class="form-control" id="description" name="description" required>';
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
                    $("#model").html(response.parts);

                    // form repeater jquery
                    $('.file-repeater, .contact-repeater, .repeater-default').repeater({
                        show: function () {
                            i++;
                            $('input[name="group['+ i +'][quantity]"]').val(1);
                            $(this).slideDown();
                        },
                        hide: function (deleteElement) {
                            i--;
                            $(this).slideUp(deleteElement);
                        }
                    });

                    $('#modal-menu').modal({
                        backdrop: 'static',
                        keyboard: false
                    });

                } else {
                    error(response.msg);
                }
            }
        });

    }

    function backMenu() {

        $('#modal-menu').modal({
            backdrop: 'static',
            keyboard: false
        });
    }

    function updateOs() {

        $('#modal-os').modal({
            backdrop: 'static',
            keyboard: false
        });
    }

    function requestPart() {

        $('#modal-part').modal({
            backdrop: 'static',
            keyboard: false
        });
    }

    function requestPartView() {

        $('#modal-spart').modal({
            backdrop: 'static',
            keyboard: false
        });
    }

    $(document).ready(function () {

        $("#updatePecas").submit(function (e) {
            Codebase.loader('show', 'bg-gd-sea');

        });

        $("#newPart").click(function (e) {
            e.preventDefault();

        });

        $("#updateOs").submit(function (e) {
            if ($("#date").val() == "") {

                e.preventDefault();
                return error('Preencha a data da visita ao cliente.');
            } else if ($("#expert_name").val() == "") {

                e.preventDefault();
                return error('Preencha o nome do técnico que irá fazer a visita.');
            } else if ($("#expert_phone").val() == "") {

                e.preventDefault();
                return error('Preencha o telefone de contato do técnico.');
            }
            if ($("#has_completed").prop("checked")) {

                if ($("#os_signature").val() == "") {

                    e.preventDefault();
                    return error('Você precisa os assinada pelo cliente.');
                } else if ($("#diagnostic_test").val() == "") {

                    e.preventDefault();
                    return error('Você precisa anexar o relatório técnico.');
                } else if ($("#description_1").val() == "") {

                    e.preventDefault();
                    return error('Fale um resumo do que você fez em sua visita.');
                }

            }

            Codebase.loader('show', 'bg-gd-sea');


        });

        flatpickr($("#date"), {
            "locale": "pt" ,
            minDate: "<?= date('Y-m-d') ?>",
            maxDate: "<?= date('Y-m-d', strtotime('+ 3 days')) ?>",
            "disable": [
                function(date) {
                    // return true to disable
                    return (date.getDay() === 0);

                }
            ],
        });

        $("#has_completed").click(function (e) {
            if ($("#has_completed").prop("checked")) {

                $(".description").show();
                $(".diagnostic_test").show();
                $(".os_signature").show();
            } else {
                $(".description").hide();
                $(".diagnostic_test").hide();
                $(".os_signature").hide();
            }

        });

        $("#navMyOs").addClass('active');
    });
</script>
@endsection
