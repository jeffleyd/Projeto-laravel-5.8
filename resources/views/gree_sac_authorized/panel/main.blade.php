@extends('gree_sac_authorized.panel.layout')

@section('content')
<style>
    p {
        margin: 0px !important;
        padding: 0px !important;
    }
</style>
@if ($authorized->is_active == 0)
<div class="alert alert-warning alert-dismissable " role="alert">
    <p class="mb-0">Infelizmente estamos com dificuldades em saber seu local atual, por favor, atualize seu endereço. <a href="/autorizada/perfil">CLIQUE AQUI</a></p>
</div>
@endif
@if (empty($authorized->account) or empty($authorized->bank) or empty($authorized->agency))
<div class="alert alert-danger alert-dismissable " role="alert">
    <p class="mb-0">Sua conta bancária não está cadastrada, cadastre uma conta vinculada a sua empresa para que possamos realizar a transferência. <a href="/autorizada/perfil">CLIQUE AQUI</a></p>
</div>
@endif
@if (getConfig("sac_authorized_msg"))
<div class="alert alert-info alert-dismissable " role="alert">
    <p class="mb-0"><?= getConfig("sac_authorized_msg") ?></p>
</div>
@endif


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
    <div class="col-6 col-xl-4">
        <a class="block block-link-shadow text-right" href="javascript:void(0)">
            <div class="block-content block-content-full clearfix">
                <div class="float-left mt-10 d-none d-sm-block">
                    <i class="si si-clock fa-3x text-body-bg-dark"></i>
                </div>
                <div class="font-size-h3 font-w600 js-count-to-enabled" data-toggle="countTo" data-speed="1000" data-to="{{ $pending }}">{{ $pending }}</div>
                <div class="font-size-sm font-w600 text-uppercase text-muted">O.S Pendentes</div>
            </div>
        </a>
    </div>

    <div class="col-6 col-xl-4">
        <a class="block block-link-shadow text-right" href="javascript:void(0)">
            <div class="block-content block-content-full clearfix">
                <div class="float-left mt-10 d-none d-sm-block">
                    <i class="si si-check fa-3x text-body-bg-dark"></i>
                </div>
                <div class="font-size-h3 font-w600 js-count-to-enabled" data-toggle="countTo" data-speed="1000" data-to="{{ $done }}">{{ $done }}</div>
                <div class="font-size-sm font-w600 text-uppercase text-muted">O.S FEITAS</div>
            </div>
        </a>
    </div>
    <div class="col-12 col-xl-4">
        <a class="block block-link-shadow text-right" href="javascript:void(0)">
            <div class="block-content block-content-full clearfix">
                <div class="float-left mt-10 d-none d-sm-block">
                    <i class="si si-star fa-3x text-body-bg-dark"></i>
                </div>
                <div class="font-size-h3 font-w600">@if ($authorized->rate) {{ number_format($authorized->rate, 2) }} @else -- @endif</div>
                <div class="font-size-sm font-w600 text-uppercase text-muted">Avaliação do cliente</div>
            </div>
        </a>
    </div>
</div>
<div class="row js-appear-enabled animated fadeIn" data-toggle="appear">
    <div class="col-12 col-xl-12">
        <div class="block" id="listos">
            <div class="block-header block-header-default">
                <h3 class="block-title">O.S para serem aceitos</h3>
                <div class="block-options" style="display: none">
                    <button type="button" class="btn-block-option" data-toggle="block-option" data-action="state_toggle">
                        <i class="si si-refresh"></i>
                    </button>
                </div>
            </div>
            <div class="block-content block-content-full">
                <table class="table table-bordered table-striped table-vcenter js-dataTable-full">
                    <thead>
                        <tr>
                            <th class="text-center">#Protocolo</th>
                            <th>Endereço</th>
                            <th>Complemento</th>
                            <!--<th>Visita</th>-->
                            <th class="text-center">Ação</th>
                        </tr>
                    </thead>
                    <tbody class="loadprotocol">

                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

<div class="modal fade" id="modal-os" tabindex="-1" role="dialog" aria-labelledby="modal-os" aria-hidden="true">
    <div class="modal-dialog modal-dialog-popout modal-lg" role="document">
        <div class="modal-content">
            <form action="/autorizada/agendar/atendimento" id="submitForm" method="post">
            <input type="hidden" id="p_id" name="id">
            <div class="block block-themed block-transparent mb-0">
                <div class="block-header bg-primary-dark">
                    <h3 class="block-title">INFORMAÇÕES</h3>
                </div>
                <div class="block-content">
                    <div class="alert alert-warning alert-dismissable mb-0" role="alert">
                        <p class="mb-0">
                            Entre em contato com o cliente para: Agendar um <b>dia</b> e <b>horário</b>, informar o técnico e seu telefone para contato.
                            <br>É obrigatório levar o modelo padrão de relatório técnico no atendimento:  <a href="{{ Request::root() }}/area_tecnica/report_tech.pdf" target="_blank">Baixe aqui</a>
                        </p>
                    </div>
                    <div class="alert alert-danger alert-dismissable mb-0 mt-3" role="alert">
                        <p class="mb-0" id="attetion"></p>
                    </div>
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
                                <input type="text" class="js-flatpickr form-control" data-allow-input="true" data-enable-time="true" data-no-calendar="true" data-date-format="H:i" data-time_24hr="true" id="hour" name="hour" placeholder="12:00" value="12:00">
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
                        <div class="col-md-12" style="margin: 14px 0px">
                            <i>Preencha as mesmas informações combinadas com o cliente acima.</i>
                        </div>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="submit" class="btn btn-alt-primary">
                    Enviar informações
                </button>
            </div>
            </form>
        </div>
    </div>
</div>
<script>
    var p_id = 0;
    function acceptOS(id) {
        Swal.fire({
            title: 'Atendimento',
            text: "Ao aceitar o atendimento, você está se comprometendo a conclui-lo. A não conclusão, poderá acarreta na desclassificação do seu cadastro.",
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

                $('.btn-block-option').click();

                $.ajax({
                    type: "POST",
                    url: "/autorizada/confirmar/atendimento",
                    data: {id: id},
                    success: function (response) {
                        $('#listos').removeClass("block-mode-loading");
                        $(".row-" + id).remove();
                        if (response.success) {

                            updateOs(response.id);

                        } else {

                            Swal.fire(
                            {
                                type: "info",
                                title: 'Atenção!',
                                text: response.msg,
                                confirmButtonClass: 'btn btn-success',
                            }
                            )
                        }
                    }
                });


            }
        })
    }
    function updateOs(id) {
        p_id = id;
        $("#p_id").val(id);
        $.ajax({
            type: "GET",
            url: "/autorizada/buscar/atendimento/" + id,
            success: function (response) {
                if (response.success) {

                    $("#c_name").html(response.name);
                    $("#c_phones").html(response.phones);
                    $("#p_model").html(response.model);
                    $("#p_description").html(response.description);
                    $("#p_address").html(response.address);
                    $("#p_complement").html((typeof response.complement == 'undefined' ? '' : response.complement));

                    $("#attetion").html("Você tem até <b>" + response.end_date + "</b> para agendar esse atendimento, caso contrário ele será cancelado automaticamente e você será desclassificado.");

                    $('#modal-os').modal({
                        backdrop: 'static',
                        keyboard: false
                    });

                } else {
                    $('#listos').removeClass("block-mode-loading");
                    error(response.msg);
                }
            }
        });


    }

    var interval;

    function loadProtocol() {
        clearInterval(interval);
        interval = setInterval(() => {
            loadProtocol();
        }, 600000);
        $.ajax({
            type: "get",
            url: "/autorizada/carregar/protocolos",
            success: function (response) {
                if (response.success) {


                    var list = "";
                    for (let index = 0; index < response.data.length; index++) {
                        const obj = response.data[index];
						var compl = (typeof obj.complement == 'undefined' ? '' : obj.complement);
                        list += '<tr class="row-'+ obj.id +'">';
                        list += '<td class="text-center">'+ obj.protocol +'</td>';
                        list += '<td>'+ obj.address +'</td>';
                        list += '<td class="font-w600">'+ compl +'</td>';
                        list += '<td class="text-center">';
                        list += '<button type="button" onclick="acceptOS('+ obj.id +')" class="btn btn-sm btn-success">Aceitar</button>';
                        list += '</td>';
                        list += '</tr>';

                    }

                    $(".loadprotocol").html(list);

                } else {
                    location.reload();
                }
            }
        });
    }

    $("#submitForm").submit(function (e) {

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


    });

    $(document).ready(function () {

        @if (Session::get('already_accept'))
        Swal.fire({
                type: "info",
                title: 'Atenção!',
                text: 'Essa O.S não precisa mais de atendimento, obrigado por sua manifestação.',
                confirmButtonClass: 'btn btn-success',
            });

        <?php Session::forget('already_accept') ?>
        @endif

        loadProtocol();

        $('#expert_phone').mask('(00) 00000-0000', {reverse: false});

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

        @if ($hasid > 0)
        updateOs({{ $hasid }});
        @endif
        $("#navDashboard").addClass('active');
    });
</script>
@endsection
