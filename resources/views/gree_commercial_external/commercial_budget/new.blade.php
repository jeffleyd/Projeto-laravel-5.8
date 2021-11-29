@extends('gree_commercial_external.layout')

@section('page-css')
    <link href="/js/plugins/datatables/dataTables.bootstrap4.css" rel="stylesheet" type="text/css">
    <link href="/elite/assets/node_modules/bootstrap-datepicker/bootstrap-datepicker.min.css" rel="stylesheet" type="text/css">
    <link href="/elite/assets/node_modules/clockpicker/dist/jquery-clockpicker.min.css" rel="stylesheet">
    <link href="/elite/assets/node_modules/select2/dist/css/select2.min.css" rel="stylesheet" type="text/css" />
    <link rel="stylesheet" type="text/css" href="/admin/app-assets/vendors/css/extensions/sweetalert2.min.css">
    <style>
        .isblock {
            opacity: 0.4;
        }reportVPC
		.my-dialog .modal-header {
            display: block !important;
        }
    </style>
@endsection
@section('page-breadcrumb')
    <div class="row page-titles">
        <div class="col-md-5 align-self-center">
            <h4 class="text-themecolor">Nova Verba Comercial</h4>
        </div>
        <div class="col-md-7 align-self-center text-right">
            <div class="d-flex justify-content-end align-items-center">
                <a class="btn btn-info d-none d-lg-block m-l-15" onclick="saveVerb()" href="#">
                    <i class="fa fa-check"></i> Criar verba comercial
                </a>
            </div>
        </div>
    </div>
    <style>
        .input-td {
            border: none;
            font-size: 12px;
        }
    </style>
@endsection


@section('content')
<form action="/comercial/operacao/verba-comercial/salvar" id="sendVerb" method="post" enctype="multipart/form-data">
<div class="row">
    <div class="col-12">
        <div class="alert alert-info">
            * Base de calculo de VPC será sempre valor líquido do faturamento ou pedido (Dedução de impostos, fretes e custos adicionais com clientes).
            <br>* Após o recebimento deste documento a Gree tem até 45 dias para análise liquidação do pagamento desta verba.
        </div>
    </div>
    <div class="col-12 col-sm-12">
        <div class="card">
            <div class="card-body">
                <ul class="nav nav-tabs" role="tablist">
                    <li class="nav-item"> <a class="nav-link active" data-toggle="tab" href="#client" role="tab"><span class="hidden-sm-up"><i class="ti-user"></i></span> <span class="hidden-xs-down">Beneficiário</span></a> </li>
                    <li class="nav-item"> <a class="nav-link" data-toggle="tab" href="#request" role="tab"><span class="hidden-sm-up"><i class="ti-package"></i></span> <span class="hidden-xs-down">Solicitação</span></a> </li>
                    <li class="nav-item duplicate" style="display: none"> <a class="nav-link" data-toggle="tab" href="#duplicate" role="tab"><span class="hidden-sm-up"><i class="ti-receipt"></i></span> <span class="hidden-xs-down">Duplicatas</span></a> </li>
                    <li class="nav-item transition_bank" style="display: none"> <a class="nav-link" data-toggle="tab" href="#transition_bank" role="tab"><span class="hidden-sm-up"><i class="ti-money"></i></span> <span class="hidden-xs-down">Dados bancários</span></a> </li>
                    <li class="nav-item"> <a class="nav-link" data-toggle="tab" href="#observation" role="tab"><span class="hidden-sm-up"><i class="ti-help"></i></span> <span class="hidden-xs-down">Observação</span></a> </li>
                </ul>
                <div class="tab-content tabcontent-border" style="border: 1px solid #ddd;border-top: 0px;">
                    <div class="tab-pane active" id="client" role="tabpanel">
                        <div class="row">
                            <div class="col-sm-12 p-4">
                                <div class="row">
                                    <div class="col-12 col-sm-6 form-group ">
                                        <label class="client_id">Informe o cliente</label>
                                        <select name="client_id" id="client_id" class="form-control select2-client" style="width: 100%">
                                            @foreach ($clients as $client)
                                                <option value="{{$client->id}}">{{$client->fantasy_name}} ({{$client->code}})
                                                    {{$client->identity}}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                    <div class="col-12 col-sm-6 form-group ">
                                        <label class="type_document">Tipo de documento</label>
                                        <select name="type_document" id="type_document" class="form-control">
                                            <option value=""></option>
                                            <option value="1">NF débito</option>
                                            <option value="2">NF Devolução</option>
                                            <option value="3">NF Produto</option>
                                            <option value="4">Pedido do cliente</option>
                                        </select>
                                    </div>
                                    <div class="col-sm-12 form-group">
                                        <label class="type_document_upload">Anexe os documentos</label>
                                        <input type="file" name="type_document_upload[]" id="type_document_upload" class="form-control" multiple>
                                        <p><small>Segure o control e vá clicando em cima dos arquivos para selecionar muitos.</small></p>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="tab-pane" id="transition_bank" role="tabpanel">
                        <div class="row">
                            <div class="col-sm-12 p-4">
                                <div class="row">
                                    <div class="col-12 col-sm-12 form-group ">
                                        <label class="nf">Nota fiscal</label>
                                        <input type="text" name="nf" id="nf" class="form-control">
                                    </div>
                                    <div class="col-12 col-sm-4 form-group ">
                                        <label class="bank">Banco</label>
                                        <input type="text" name="bank" id="bank" class="form-control">
                                    </div>
                                    <div class="col-12 col-sm-4 form-group ">
                                        <label class="agency">Agência</label>
                                        <input type="text" name="agency" id="agency" class="form-control">
                                    </div>
                                    <div class="col-12 col-sm-4 form-group ">
                                        <label class="account">Conta</label>
                                        <input type="text" name="account" id="account" class="form-control">
                                    </div>
                                    <div class="col-12 col-sm-6 form-group ">
                                        <label class="people_name">Títular da conta</label>
                                        <input type="text" name="people_name" id="people_name" class="form-control">
                                    </div>
                                    <div class="col-12 col-sm-6 form-group ">
                                        <label class="identity">CNPJ</label>
                                        <input type="text" name="identity" id="identity" class="form-control">
                                    </div>
                                    <div class="col-12 col-sm-6 form-group ">
                                        <label class="identity">Total bruto do pagamento</label>
                                        <input type="text" name="total_gross_payment" id="identity" class="form-control price">
                                    </div>
                                    <div class="col-12 col-sm-6 form-group ">
                                        <label class="identity">Total liquido do pagamento</label>
                                        <input type="text" name="total_liquid_payment" id="identity" class="form-control price">
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="tab-pane  p-20" id="request" role="tabpanel">
                        <div class="row">
                            <div class="col-12 col-sm-6 form-group ">
                                <label class="type_request">Tipo de Solicitação</label>
                                <select name="type_request" id="type_request" class="form-control">
                                    <option value=""></option>
                                    <option value="1">Produto</option>
                                    <option value="2">Desconto em duplicata/Título em aberto</option>
                                    <option value="3">Transação bancária</option>
                                </select>
                            </div>
                            <div class="col-12 col-sm-6 form-group">
                                <label class="type_budget">Tipo de Verba</label>
                                <select name="type_budget" id="type_budget" class="form-control">
                                    <option value=""></option>
                                    <option value="1">VPC</option>
                                    <option value="2">Rebate</option>
                                    <option value="3">Bonificação</option>
                                    <option value="4">Verbas contratuais</option>
                                    <option value="5">Desconto</option>
                                </select>
                            </div>
                            <div class="col-12 col-sm-12 form-group reportVPC" style="display: none">
                                <label for="reportVPC">Apuração VPC</label>
                                <select id="reportVPC" class="form-control" name="reportVPC">
                                    <option value="">Escolha</option>
                                    @foreach($reportVPC as $key)
                                        <option value="{{$key->id}}">({{$key->code}}) {{$key->client_group_name}}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="col-12 col-sm-12 form-group reportRebate" style="display: none">
                                <label for="reportRebate">Apuração Rebate</label>
                                <select id="reportRebate" class="form-control" name="reportRebate">
                                    <option value="">Escolha</option>
                                    @foreach($reportRebate as $key)
                                        <option value="{{$key->id}}">({{$key->code}}) {{$key->client_group_name}}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="col-sm-12">
                                <div class="table-responsive">
                                    <div style="width: 100%; display: flex; justify-content: center; position: absolute; bottom: -55px">
                                        <button type="button" onclick="addRow()" class="btn btn-info btn-circle">
                                            <i class="fa fa-plus"></i>
                                        </button>
                                        <button type="button" style="display: none; margin-left: 15px;" id="r_remove" onclick="removeRow()" class="btn btn-danger btn-circle">
                                            <i class="fa fa-times"></i>
                                        </button>
                                    </div>
                                    <table class="table table-bordered" style="margin-bottom: 0;">
                                        <tbody class="repeat">
                                        <tr>
                                            <td colspan="3" style="text-align: center">Descrição da solicitação</td>
                                            <td style="text-align: center">Quantidade</td>
                                            <td style="text-align: center">Unidade</td>
                                            <td style="text-align: center">Preço Unit.</td>
                                            <td style="text-align: center">Subtotal</td>
                                        </tr>
                                        <tr>
                                            <td colspan="3" style="text-align: center"><input style="width: 100%;" class="input-td" placeholder="Digite aqui..." type="text" name="request_col_1[1]"></td>
                                            <td class="text-center"><input style="width: 50px;" class="text-center input-td quantity" onkeyup="r_calc(this)" type="text" name="request_col_2[1]" placeholder="0" value=""></td>
                                            <td style="text-align: center"><input class="text-center input-td" placeholder="-" style="text-transform: uppercase" type="text" name="request_col_3[1]" value=""></td>
                                            <td style="text-align: center">R$ <input class="input-td price" placeholder="0,00" onkeyup="r_calc(this)" type="text" name="request_col_4[1]" value=""></td>
                                            <td style="text-align: center;width:210px" id="row_total_1">R$ 0,00</td>
                                            <input name="request_col_5[1]" type="hidden">
                                        </tr>
                                        </tbody>
                                    </table>
                                    <table class="table table-bordered">
                                        <tbody>
                                        <tr>
                                            <td style="text-align: center;background: #e4e4e4;"></td>
                                            <td style="text-align: center;background: #e4e4e4;"></td>
                                            <td style="text-align: center;background: #e4e4e4;"></td>
                                            <td style="text-align: center;background: #e4e4e4;"></td>
                                            <td style="text-align: center;background: #e4e4e4;"></td>
                                            <td style="width:210px;text-align: center;font-weight: 600;font-size: 15px;" id="total_general">R$ 0,00</td>
                                        </tr>
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="tab-pane  p-20" id="duplicate" role="tabpanel">
                        <div class="row">
                            <div class="col-sm-12">
                                <div class="table-responsive">
                                    <div style="width: 100%; display: flex; justify-content: center; position: absolute; bottom: -55px">
                                        <button type="button" onclick="addRowDuplicate()" class="btn btn-info btn-circle">
                                            <i class="fa fa-plus"></i>
                                        </button>
                                        <button type="button" style="display: none; margin-left: 15px;" id="d_remove" onclick="removeRowDuplicate()" class="btn btn-danger btn-circle">
                                            <i class="fa fa-times"></i>
                                        </button>
                                    </div>
                                    <table class="table table-bordered" style="margin-bottom: 0;">
                                        <tbody class="repeat_duplicate">
                                        <tr>
                                            <td style="text-align: center">Nº Nota fiscal Gree</td>
                                            <td style="text-align: center">Série da Nota fiscal</td>
                                            <td style="text-align: center">Nº da parcela</td>
                                            <td style="text-align: center">Data do vencimento</td>
                                            <td style="text-align: center">Valor da parcela</td>
                                            <td style="text-align: center">Valor do desconto</td>
                                        </tr>
                                        <tr>
                                            <td style="text-align: center"><input style="width: 100%;" class="input-td" placeholder="Digite aqui..." type="text" name="duplicate_col_1[1]"></td>
                                            <td style="text-align: center"><input style="width: 100%;" class="input-td" placeholder="Digite aqui..." type="text" name="duplicate_col_2[1]"></td>
                                            <td style="text-align: center"><input style="width: 100%;" class="input-td" placeholder="Digite aqui..." type="text" name="duplicate_col_3[1]"></td>
                                            <td style="text-align: center"><input style="width: 100%;" class="input-td date" placeholder="DIA/MES/ANO" type="text" name="duplicate_col_4[1]"></td>
                                            <td style="text-align: center">R$ <input class="input-td price" placeholder="0,00" type="text" name="duplicate_col_5[1]"></td>
                                            <td style="text-align: center">R$ <input class="input-td price" placeholder="0,00" type="text" name="duplicate_col_6[1]"></td>
                                        </tr>
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="tab-pane  p-20" id="observation" role="tabpanel">
                        <div class="row">
                            <div class="col-sm-12 form-group ">
                                <label>Informe sua observação</label>
                                <textarea name="observation" id="observation" class="form-control" rows="5" placeholder="Caso tenha alguma coisa a dizer, digite aqui..."></textarea>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
</form>

@endsection

@section('page-scripts')

    <script src="/js/plugins/mask/jquery.mask.min.js"></script>
    <script src="/elite/assets/node_modules/bootstrap-datepicker/bootstrap-datepicker.min.js"></script>
    <script src="/elite/assets/node_modules/clockpicker/dist/jquery-clockpicker.min.js"></script>
    <script src="/elite/assets/node_modules/select2/dist/js/select2.full.min.js" type="text/javascript"></script>
    <script src="/admin/app-assets/vendors/js/extensions/sweetalert2.all.min.js"></script>
	<script src="/karma/bootstrap/bootboxjs/bootboxjs.min.js"></script>
    <script type="text/javascript">
        var r_index = 2;
        var d_index = 2;

        function saveVerb() {
            if (!$('#client_id').val())
                return $error('Você precisa escolher informar o cliente!');
            else if (!$('#type_document').val())
                return $error('Você precisa escolher o tipo de documento!');
            else if (!$('#type_document_upload')[0]['files'].length)
                return $error('Você precisa anexar ao menos 1 documento.');
            else if (!$('#type_request').val())
                return $error('Você precisa escolher o tipo da solicitação!');
            else if (!$('#type_budget').val())
                return $error('Você precisa escolher o tipo de verba!');
            if ($('#type_budget').val() == "1") {
                if (!$('#reportVPC').val())
                    return $error('Você precisa escolher apuração');
            } else if ($('#type_budget').val() == "2") {
                if (!$('#reportRebate').val())
                    return $error('Você precisa escolher apuração');
            }

            bootbox.dialog({
                message: "Você irá gerar uma solicitação de verbas, mas para dar continuidade, terá que imprimir a solicitação e pegar assinatura do cliente, deseja continuar?",
                title: "Aviso importante",
                buttons: {
                    danger: {
                        label: "Cancelar",
                        className: "btn-default",
                        callback: function(){}
                    },
                    main: {
                        label: "Confirmar",
                        className: "btn-primary",
                        callback: function() {
                            block();
                            $('#sendVerb').submit();
                        }
                    }
                }
            });
        }

        $('#type_budget').change(function () {
           if ($(this).val() == "1") {
               $(".reportVPC").show();
               $(".reportRebate").hide();
           } else if ($(this).val() == "2") {
               $(".reportVPC").hide();
               $(".reportRebate").show();
           } else {
               $(".reportVPC").hide();
               $(".reportRebate").hide();
           }
        });

        function addRow() {
            var elem = '';
            elem += '<tr>';
            elem += '<td colspan="3" style="text-align: center"><input style="width: 100%;" class="input-td" placeholder="Digite aqui..." type="text" name="request_col_1['+r_index+']"></td>';
            elem += '<td class="text-center"><input style="width: 50px;" class="text-center input-td quantity" onkeyup="r_calc(this)" type="text" name="request_col_2['+r_index+']" placeholder="0" value=""></td>';
            elem += '<td style="text-align: center"><input class="text-center input-td" style="text-transform: uppercase" placeholder="-" type="text" name="request_col_3['+r_index+']" value=""></td>';
            elem += '<td style="text-align: center">R$ <input class="input-td price" placeholder="0,00" onkeyup="r_calc(this)" type="text" name="request_col_4['+r_index+']" value=""></td>';
            elem += '<td style="text-align: center; width:210px" id="row_total_'+r_index+'">R$ 0,00</td>';
            elem += '<input name="request_col_5['+r_index+']" type="hidden">';
            elem += '</tr>';
            r_index++;

            $('.repeat').append(elem);
            $('#r_remove').show();
            $('.price').mask('00.000.000,00', {reverse: true});
            $('.quantity').mask('0000', {reverse: true});
        }

        function removeRow() {

            if ($('.repeat tr').length > 2)
                $('.repeat tr:last').remove();

            if ($('.repeat tr').length == 2)
                $('#r_remove').hide();

            r_index--;
            r_calc();
        }

        function addRowDuplicate() {
            var elem = '';
            elem += '<tr>';
            elem += '<td style="text-align: center"><input style="width: 100%;" class="input-td" placeholder="Digite aqui..." type="text" name="duplicate_col_1['+d_index+']"></td>';
            elem += '<td style="text-align: center"><input style="width: 100%;" class="input-td" placeholder="Digite aqui..." type="text" name="duplicate_col_2['+d_index+']"></td>';
            elem += '<td style="text-align: center"><input style="width: 100%;" class="input-td" placeholder="Digite aqui..." type="text" name="duplicate_col_3['+d_index+']"></td>';
            elem += '<td style="text-align: center"><input style="width: 100%;" class="input-td date" placeholder="DIA/MES/ANO" type="text" name="duplicate_col_4['+d_index+']"></td>';
            elem += '<td style="text-align: center">R$ <input class="input-td price" placeholder="0,00" type="text" name="duplicate_col_5['+d_index+']"></td>';
            elem += '<td style="text-align: center">R$ <input class="input-td price" placeholder="0,00" type="text" name="duplicate_col_6['+d_index+']"></td>';
            elem += '</tr>';
            d_index++;

            $('.repeat_duplicate').append(elem);
            $('#d_remove').show();
            $('.price').mask('00.000.000,00', {reverse: true});
            $(".date").datepicker( {
                format: "dd/mm/yyyy",
            });
        }

        function removeRowDuplicate() {

            if ($('.repeat_duplicate tr').length > 2)
                $('.repeat_duplicate tr:last').remove();

            if ($('.repeat_duplicate tr').length == 2)
                $('#d_remove').hide();

            d_index--;
        }

        function r_calc() {
            var total = 0;
            var all_row = $('.repeat tr');
            for (index = 1; index < all_row.length; index++) {
                var row = $(all_row[index]);
                var r_quantity = row.find('.quantity').val();
                var r_price = row.find('.price').val();
                var r_row_total = row.find('#row_total_'+index);
                if (r_quantity != '' && r_quantity != '0') {
                    if (r_price != '' && r_price != '0,00') {

                        // Formate price
                        var price = r_price.replace(/[.,\s]/g, '');
                        // sum price
                        var row_total = price * r_quantity;
                        // Increscet in total general
                        total += row_total;

                        var view_price =convertIntToStringWithComma(row_total);
                        r_row_total.html(view_price.toLocaleString('pt-br',{style: 'currency', currency: 'BRL'}));
                        $('input[name="request_col_5['+index+']"]').val(view_price.toLocaleString('pt-br'));
                    }
                }
            }

            var view_price_total =convertIntToStringWithComma(total);
            $('#total_general').html(view_price_total.toLocaleString('pt-br',{style: 'currency', currency: 'BRL'}));

        }

        function convertIntToStringWithComma(int) {
            var price_s = String(int);
            var cents = price_s.substr(price_s.length -2);
            var price_without_cents = price_s.slice(0, -2);

            return parseFloat(price_without_cents+'.'+cents);
        }

        $(document).ready(function () {

            $('#type_request').change(function () {
                if ($(this).val() == 2) {
                    $('.duplicate').show();
                    $('.transition_bank').hide();
                } else if ($(this).val() == 3) {
                    $('.transition_bank').show();
                    $('.duplicate').hide();
                } else {
                    $('.duplicate').hide();
                    $('.transition_bank').hide();
                }
            });

			$(".select2-client").select2({
                language: {
                    noResults: function () {
                        return 'Cliente não existe...';
                    },
                    maximumSelected: function (e) {
                        return 'você só pode selecionar 1 item';
                    }
                }
            });

            $(".select2-report").select2({
                language: {
                    noResults: function () {
                        return 'Apuração não existe...';
                    },
                    maximumSelected: function (e) {
                        return 'você só pode selecionar 1 item';
                    }
                },
                ajax: {
                    url: '/comercial/operacao/client/dropdown',
                    data: function (params) {
                        var query = {
                            search: params.term,
                            page: params.page || 1
                        }
                        return query;
                    }
                }
            });

            $('#client_phone, #apm_phone').mask('(00) 0000-00009');
            $('#client_phone, #apm_phone').blur(function(event) {
                if($(this).val().length == 15){ // Celular com 9 dígitos + 2 dígitos DDD e 4 da máscara
                    $('#fone').mask('(00) 00000-0009');
                } else {
                    $('#fone').mask('(00) 0000-00009');
                }
            });
            $('.price').mask('0.000.000,00', {reverse: true});
            $('.quantity').mask('0000', {reverse: true});

            $.fn.datepicker.dates['en'] = {
                days: ["Domingo", "Segunda", "Terça", "Quarta", "Quinta", "Sexta", "Sábado"],
                daysShort: ["Dom", "Seg", "Ter", "Qua", "Qui", "Sex", "Sáb"],
                daysMin: ["Do", "Se", "Te", "Qu", "Qu", "Se", "Sa"],
                months: ["Janeiro", "Fevereiro", "Março", "Abril", "Maio", "Junho", "Julho", "Agosto", "Setembro", "Outubro", "Novembro", "Dezembro"],
                monthsShort: ["Jan", "Fev", "Mar", "Abr", "Mai", "Jun", "Jul", "Ago", "Set", "Out", "Nov", "Dez"],
                today: "Hoje",
                clear: "Limpar",
                format: "mm/dd/yyyy",
                titleFormat: "MM yyyy", /* Leverages same syntax as 'format' */
                weekStart: 0
            };
            $(".date").datepicker( {
                format: "dd/mm/yyyy",
            });
            $('.hour').clockpicker({
                autoclose: true,
            });

        });

        $('.buttons-copy, .buttons-csv, .buttons-print, .buttons-pdf, .buttons-excel').addClass('btn btn-primary mr-1');
    </script>

@endsection
