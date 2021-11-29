@extends('gree_commercial.layout')

@section('breadcrumb')
<ul class="breadcrumb">
    <li><a href="#">Home</a></li>
    <li><a href="/commercial/salesman/list">Representantes</a></li>
    <li class="active">Novo</li>
</ul><!-- End .breadcrumb -->
@endsection

@section('content')
<header id="header-sec">
    <div class="inner-padding">
        <div class="pull-left">
            <div class="btn-group">
                <a class="btn btn-default" onclick="save()" href="#">
                    <i class="fa fa-floppy-o"></i>&nbsp; Salvar
                </a>
            </div>
        </div>
    </div><!-- End .inner-padding -->
</header>
<div class="window">

    <div class="actionbar">
        <div class="pull-left">
            <ul class="ext-tabs">
                <li class="active">
                    <a href="#content-tab-1">Representante</a>
                </li>
                <li>
                    <a href="#content-tab-2">Imediato</a>
                </li>
                <li>
                    <a href="#content-tab-3">Subordinados</a>
                </li>
                <li class="is_mng" style="display:none">
                    <a href="#content-tab-4">Estados Representativos (Gerência)</a>
                </li>
            </ul>
        </div>
    </div>
    <div class="tab-content">
        <div id="content-tab-1" class="tab-pane active">
            <div class="inner-padding">
				@if($id != 0)
				<div class="alert alert-danger" onclick="window.open('/commercial/salesman/view/<?= $id ?>', '_blank')" 
                    style="text-align: center; background-color: #FF5B5C !important; color: #fff; cursor: pointer;">
                    APERTE AQUI PARA REALIZAR O LOGIN COMO REPRESENTANTE
                </div>
				@endif
                <div class="row">
                    <div class="col-sm-12">
                        <form action="/commercial/salesman/edit_do" method="POST" id="sendForm" enctype="multipart/form-data">
                            <input type="hidden" id="id" name="id" value="{{$id}}">
                            <input type="hidden" id="immediate" name="immediate" value="">
                            <input type="hidden" id="states" name="states" value="">
                            <fieldset>
                                <legend>Preencha todos dados com atenção</legend>
                                <div class="row">
                                    <div class="col-sm-3">
                                        <label>Status</label>
                                    </div>
                                    <div class="col-sm-9">
                                        <div class="inline-labels">
                                            <label><input type="radio" name="is_active" value="1" @if ($is_active == 1) checked="" @endif><span></span> Ativo</label>
                                            <label><input type="radio" name="is_active" value="0" @if ($is_active == 0) checked="" @endif><span></span> Desativado</label>
                                        </div>
                                    </div>
                                </div>
                                <div class="spacer-10"></div>
                                <div class="row">
                                    <div class="col-sm-3">
                                        <label>Código</label>
                                    </div>
                                    <div class="col-sm-9">
                                        <input type="text" name="code" id="code" value="{{$code}}" class="form-control">
                                    </div>
                                </div>
                                @if ($picture)
                                <div class="spacer-10"></div>
                                <div class="row">
                                    <div class="col-sm-3">
                                    </div>
                                    <div class="col-sm-9">
                                        <img src="{{$picture}}" height="100">
                                    </div>
                                </div>
                                @endif
                                <div class="spacer-10"></div>
                                <div class="row">
                                    <div class="col-sm-3">
                                        <label>Foto de perfil</label>
                                    </div>
                                    <div class="col-sm-9">
                                        <input type="file" name="picture" id="picture" class="form-control">
                                    </div>
                                </div>
                                <div class="spacer-10"></div>
                                <div class="row">
                                    <div class="col-sm-3">
                                        <label>Tipo</label>
                                    </div>
                                    <div class="col-sm-9">
                                        <select name="is_direction" id="is_direction" class="form-control">
                                            <option value="0" @if ($is_direction == 0) selected @endif>Representante externo</option>
                                            <option value="1" @if ($is_direction == 1) selected @endif>Representante interno</option>
                                            <option value="3" @if ($is_direction == 3) selected @endif>Gerente</option>
                                            <option value="2" @if ($is_direction == 2) selected @endif>Diretor</option>
                                        </select>
                                    </div>
                                </div>
                                <div class="spacer-10 div_r_code" style="display:none;"></div>
                                <div class="row div_r_code" style="display:none;">
                                    <div class="col-sm-3">
                                        <label>Matrícula</label>
                                    </div>
                                    <div class="col-sm-9">
                                        <input type="text" name="r_code" id="r_code" value="{{$r_code}}" class="form-control" placeholder="matrícula do funcionário">
                                    </div>
                                </div>
                                <div class="spacer-10"></div>
                                <div class="row">
                                    <div class="col-sm-3">
                                        <label>CNPJ / CPF</label>
                                    </div>
                                    <div class="col-sm-9">
                                        <input type="text" id="identity" name="identity" value="{{$identity}}" class="form-control" placeholder="00.000.000/0000-00">
                                    </div>
                                </div>

                                <div class="spacer-10"></div>
                                <div class="row">
                                    <div class="col-sm-3">
                                        <label>Cargo</label>
                                    </div>
                                    <div class="col-sm-9">
                                        <input type="text" id="office" name="office" value="{{$office}}" class="form-control" placeholder="Digite o cargo">
                                    </div>
                                </div>

                                <div class="spacer-10"></div>
                                <div class="row">
                                    <div class="col-sm-3">
                                        <label>Nome fantasia</label>
                                    </div>
                                    <div class="col-sm-9">
                                        <input type="text" name="company_name" value="{{$company_name}}" class="form-control">
                                    </div>
                                </div>
                                <div class="spacer-10"></div>
                                <div class="row">
                                    <div class="col-sm-3">
                                        <label>Nome do contato</label>
                                    </div>
                                    <div class="col-sm-9">
                                    <input type="text" name="contact_name" id="contact_name" value="{{$full_name}}" class="form-control">
                                    </div>
                                </div>
                                <div class="spacer-10"></div>
                                <div class="row">
                                    <div class="col-sm-3">
                                        <label>Telefone</label>
                                    </div>
                                    <div class="col-sm-9">
                                        <input type="text" id="phone1" name="phone1" value="{{$phone1}}" class="form-control" placeholder="(00) 00000-0000">
                                    </div>
                                </div>
                                <div class="spacer-10"></div>
                                <div class="row">
                                    <div class="col-sm-3">
                                        <label>Telefone</label>
                                    </div>
                                    <div class="col-sm-9">
                                        <input type="text" id="phone2" name="phone2" value="{{$phone2}}" class="form-control" placeholder="(00) 00000-0000">
                                    </div>
                                </div>
                                <div class="spacer-10"></div>
                                <div class="row">
                                    <div class="col-sm-3">
                                        <label>Email</label>
                                    </div>
                                    <div class="col-sm-9">
                                        <input type="email" name="email" value="{{$email}}" class="form-control" placeholder="junior@gree-am.com.br">
                                    </div>
                                </div>
                                <div class="spacer-10"></div>
                                <div class="row">
                                    <div class="col-sm-3">
                                        <label>CEP</label>
                                    </div>
                                    <div class="col-sm-9">
                                        <input type="text" id="zipcode" name="zipcode" value="{{$zipcode}}" class="form-control" placeholder="00000-000">
                                    </div>
                                </div>
                                <div class="spacer-10"></div>
                                <div class="row">
                                    <div class="col-sm-3">
                                        <label>Endereço</label>
                                    </div>
                                    <div class="col-sm-9">
                                        <input type="text" name="address" value="{{$address}}" class="form-control" placeholder="Digite o endereço e número.">
                                    </div>
                                </div>
                                <div class="spacer-10"></div>
                                <div class="row">
                                    <div class="col-sm-3">
                                        <label>Cidade</label>
                                    </div>
                                    <div class="col-sm-9">
                                        <input type="text" name="city" value="{{$city}}" class="form-control" placeholder="Digite a cidade.">
                                    </div>
                                </div>
                                <div class="spacer-10"></div>
                                <div class="row">
                                    <div class="col-sm-3">
                                        <label>Estado</label>
                                    </div>
                                    <div class="col-sm-9">
                                        <select name="state" id="state" class="form-control">
                                            <option value="">Escolha o estado</option>
                                            <option value="">----</option>
                                            @foreach (config('gree.states') as $key => $value)
                                                <option value="{{ $key }}" @if ($key == $state) selected @endif>{{ $value }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>
                                <div class="spacer-10"></div>
                                <div class="row">
                                    <div class="col-sm-3">
                                        <label>Região</label>
                                    </div>
                                    <div class="col-sm-9">
                                        <select name="region" id="regiono" class="form-control">
                                            <option value="">Escolha a região</option>
                                            <option value="">----</option>
                                            @foreach (config('gree.region') as $key => $value)
                                                <option value="{{ $key }}" @if ($key == $region) selected @endif>{{ $value }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>
                                <div class="spacer-10"></div>
                                <div class="row">
                                    <div class="col-sm-3">
                                        <label>Complemento</label>
                                    </div>
                                    <div class="col-sm-9">
                                        <input type="text" name="complement" value="{{$complement}}" class="form-control">
                                    </div>
                                </div>
								<div class="spacer-10"></div>
                                <div class="row div_password">
                                    <div class="col-sm-3">
                                        <label>Senha</label>
                                    </div>
                                    <div class="col-sm-9">
                                        <input type="password" name="password" value="" class="form-control" placeholder="Informe uma senha">
                                    </div>
                                </div>
								<div class="spacer-10"></div>
                                <div class="row">
                                    <div class="col-sm-3">
                                        <label>Autenticação 2 fatores</label>
                                    </div>
                                    <div class="col-sm-9">
                                        <div class="inline-labels">
                                            <label><input type="radio" class="radio-auth" name="is_otpauth" value="1" @if ($otpauth != '') checked="" @endif disabled><span></span> Habilitado</label>
                                            <label><input type="radio" class="radio-auth" name="is_otpauth" value="2" @if ($otpauth == '') checked="" @endif><span></span>@if ($otpauth == '') Desabilitado @else Resetar @endif</label>
                                        </div>
                                    </div>
                                </div>
                                <div class="spacer-10"></div>
                            </fieldset>
                        </form>
                    </div>
                </div>
            </div>
        </div>
        <div id="content-tab-2" class="tab-pane">
            <div class="inner-padding">
                <div class="row">
                    <div class="col-sm-12">
                        <fieldset>
                            <legend>Selecione o(s) imediato(s)</legend>
                            <div class="row">
                                <div class="col-sm-10">
                                    <select id="sel_immediate" class="form-control js-select2" style="width: 100%;"></select>
                                </div>
                                <div class="col-sm-2">
                                    <button class="btn btn-default" id="btn_add_immediate">
                                        <i class="fa fa-plus"></i>&nbsp; Adicionar
                                    </button>
                                </div>
                            </div>
                            <div class="spacer-20"></div>
                            <div class="row">
                                <div class="col-sm-12">
                                    <div class="table-wrapper">
                                        <header>
                                            <h3>IMEDIATOS</h3>
                                        </header>
                                        <table class="table table-bordered table-striped" id="table_immediate" data-rt-breakpoint="600">
                                            <thead>
                                                <tr>
                                                    <th scope="col" data-rt-column="Nome">Nome</th>
                                                    <th scope="col" data-rt-column="Email">Email</th>
                                                    <th scope="col" class="th-4-action-btn">Ações</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                @if(!$immediate->isEmpty())
                                                    @foreach ($immediate as $key)
                                                    <tr>
                                                        <td>{{ $key->full_name }} ({{ $key->identity }})</td>
                                                        <td>{{ $key->email }}</td>
                                                        <td><a onclick='deleteImediate(this)' data-id='<?= $key->id ?>' class='btn-less'><i class='fa fa-trash-o'></i></a></td>
                                                    </tr>
                                                    @endforeach
                                                @else
                                                <tr class="not-immediate">
                                                    <td colspan="3">Não há imediatos vinculados.</td>
                                                <tr>
                                                @endif
                                            </tbody>
                                        </table>
                                    </div>
                                </div>
                            </div>
                        </fieldset>
                    </div>
                </div>
            </div>
        </div>
        <div id="content-tab-3" class="tab-pane">
            <div class="inner-padding">
                <div class="row">
                    <div class="col-sm-12">
                        <div class="table-wrapper">
                            <header>
                                <h3>SUBORDINADOS</h3>
                            </header>
                            <table class="table table-bordered table-striped" id="tb2" data-rt-breakpoint="600">
                                <thead>
                                    <tr>
                                        <th scope="col" data-rt-column="Nome">Nome</th>
                                        <th scope="col" data-rt-column="Email">Email</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @if(!$subordinate->isEmpty())
                                        @foreach ($subordinate as $key)
                                        <tr>
                                            <td>{{ $key->full_name }} ({{ $key->identity }})</td>
                                            <td>{{ $key->email }}</td>
                                        </tr>
                                        @endforeach
                                    @else
                                    <tr>
                                        <td colspan="2">Não há subordinados.</td>
                                    <tr>
                                    @endif
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        <div>
    </div>
</div>
        <div id="content-tab-4" class="tab-pane">
            <div class="inner-padding">
                <div class="row">
                    <div class="col-sm-12">
                        <fieldset>
                            <legend>Selecione o(s) estado(s)</legend>
                            <div class="row">
                                <div class="col-sm-10">
                                    <select id="sel_state" class="form-control" style="width: 100%;">
                                        <option value="">Escolha o estado</option>
                                        <option value="">----</option>
                                        @foreach (config('gree.states') as $key => $value)
                                            <option value="{{ $key }}">{{ $value }}</option>
                                        @endforeach
                                    </select>
                                </div>
                                <div class="col-sm-2">
                                    <button class="btn btn-default" id="btn_add_state">
                                        <i class="fa fa-plus"></i>&nbsp; Adicionar
                                    </button>
                                </div>
                            </div>
                            <div class="spacer-20"></div>
                            <div class="row">
                                <div class="col-sm-12">
                                    <div class="table-wrapper">
                                        <header>
                                            <h3>ESTADOS</h3>
                                        </header>
                                        <table class="table table-bordered table-striped" id="table_state" data-rt-breakpoint="600">
                                            <thead>
                                            <tr>
                                                <th scope="col" data-rt-column="Nome">Nome</th>
                                                <th scope="col" class="th-4-action-btn">Ações</th>
                                            </tr>
                                            </thead>
                                            <tbody>
                                            @if(!$states->isEmpty())
                                                @foreach ($states as $key)
                                                    <tr>
                                                        <td>{{config('gree.states')[$key->state]}} ({{ $key->state }})</td>
                                                        <td><a onclick='deleteState(this)' data-id='<?= $key->state ?>' class='btn-less'><i class='fa fa-trash-o'></i></a></td>
                                                    </tr>
                                                @endforeach
                                            @else
                                                <tr class="not-state">
                                                    <td colspan="2">Não há estados vinculados.</td>
                                                <tr>
                                            @endif
                                            </tbody>
                                        </table>
                                    </div>
                                </div>
                            </div>
                        </fieldset>
                    </div>
                </div>
            </div>
        </div>
<script>

    var list_table = [];
    var list_state = [];
    var arr = {!! $immediate !!},
        salesman_id = {!! $id !!}, states = {!! $states !!};

    if(arr.length != 0) {
        for(var i = 0; i < arr.length; i++) {
            list_table.push(arr[i].id);
        }
    }

    if(states.length != 0) {
        for(var i = 0; i < states.length; i++) {
            list_state.push(states[i].state);
        }
    }

    function save() {

        $("#immediate").val(JSON.stringify(list_table));
        $("#states").val(JSON.stringify(list_state));

        if ($('input[name="code"]').val() == "") {

            return $error('Informe o código do representante.');
		} else if ($("#r_code").val() == "" && ($("#is_direction").val() == 1 || $("#is_direction").val() == 2 || $("#is_direction").val() == 3)) {			
            return $error('Informe a matrícula do funcionário.');
        } else if ($('input[name="identity"]').val() == "") {

            return $error('Informe o CNPJ / CPF do representante.');
        } else if ($('input[name="office"]').val() == "") {

            return $error('Informe o cargo do representante.');
        } else if ($('input[name="contact_name"]').val() == "") {

            return $error('Informe o nome de contato.');
        } else if ($('input[name="phone1"]').val() == "") {

            return $error('Informe ao menos um telefone.');
        } else if ($('input[name="email"]').val() == "") {

            return $error('Imforme o email.');
        } else if ($('input[name="address"]').val() == "") {

            return $error('Informe o endereço.');
        } else if ($('input[name="city"]').val() == "") {

            return $error('Informe a cidade.');
        } else if ($('select[name="state"]').val() == "") {

            return $error('Escolha o estado.');
        } else if ($('select[name="region"]').val() == "") {

            return $error('Escolha a região.');
        } else if (list_table.length == 0 && $("#is_direction").val() != 2) {

            return $error('INFORME UM IMEDIATO.');
        }

        block();
        $("#sendForm").submit();
    }

    function deleteImediate($this) {

        var id = $($this).attr('data-id');
        var index = list_table.findIndex(x => x == id);

        list_table.splice(index, 1);

        $($this).parent().parent().remove();
    }

    function deleteState($this) {

        var id = $($this).attr('data-id');
        var index = list_state.findIndex(x => x == id);

        list_state.splice(index, 1);

        $($this).parent().parent().remove();
    }

    $(document).ready(function () {

        if(<?= $is_direction ?> == 1) {
            $(".div_r_code").show();
        } else {
            $(".div_r_code").hide();
        }

        if(<?= $is_direction ?> == 2 || <?= $is_direction ?> == 3) {
            $(".div_r_code").show();
            $(".div_password").hide();
        } else {
            $(".div_password").show();
        }

        if(<?= $is_direction ?> == 3) {
            $(".is_mng").show();
        } else {
            $(".is_mng").hide();
        }

        $(".js-select2").select2({
            language: {
                noResults: function () {

                    return 'Representante não existe...';
                }
            },
            ajax: {
                url: '/commercial/salesman/dropdown',
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

        $("#btn_add_immediate").click(function () {

            var data = $('#sel_immediate').select2('data');
            list_table.push(parseInt(data[0].id));

            $("#table_immediate tbody").append("<tr class='tr-rem'>"+
                                                "<td>" + data[0].text + "</td>"+
                                                "<td>" + data[0].email + "</td>"+
                                                "<td><a onclick='deleteImediate(this)' data-id='"+data[0].id+"' class='btn-less'><i class='fa fa-trash-o'></i></a></td>"+
                                                "</tr>");

            $('#sel_immediate').val(null).trigger('change');
            $(".not-immediate").hide();
        });

        $("#btn_add_state").click(function () {

            list_state.push($('#sel_state').val());

            $("#table_state tbody").append("<tr class='tr-rem'>"+
                "<td>" + $( "#sel_state option:selected" ).text() + " ("+ $('#sel_state').val() +")</td>"+
                "<td><a onclick='deleteState(this)' data-id='"+$('#sel_state').val()+"' class='btn-less'><i class='fa fa-trash-o'></i></a></td>"+
                "</tr>");

            $('#sel_state').val('');
            $(".not-state").hide();
        });

        var CpfCnpjMaskBehavior = function (val) {
			return val.replace(/\D/g, '').length <= 11 ? '000.000.000-009' : '00.000.000/0000-00';
		},
        cpfCnpjpOptions = {
                onKeyPress: function(val, e, field, options) {
                    field.mask(CpfCnpjMaskBehavior.apply({}, arguments), options);
            }
        };
        $('#identity').mask(CpfCnpjMaskBehavior, cpfCnpjpOptions);
        $('#zipcode').mask('00000-000', {reverse: false});
        $('#phone1').mask('(00) 00000-0000', {reverse: false});
        $('#phone2').mask('(00) 00000-0000', {reverse: false});

        $("#is_direction").change(function() {
            if($(this).val() == 1) {
                $(".div_r_code").show();
            } else {
                $(".div_r_code").hide();
                $("#r_code").val("");
            }

            if($(this).val() == 3 || $(this).val() == 2) {
                $(".div_r_code").show();
                $(".div_password").hide();
            } else {
                $(".div_password").show();
            }

            if($(this).val() == 3){
                $(".is_mng").show();
            } else {
                $(".is_mng").hide();
            }
        });

        $("#identity, #code").blur(function() {

            var name = $(this).attr("name");
            var value = $(this).val();

            if(value != "" && salesman_id == 0) {
                $.ajax({
                    url: "/commercial/salesman/verify/identity/ajax",
                    data: {name: name, value:value},
                    success: function (response) {
                        if (response.success) {

                            if(response.exists) {

                                $("#"+name+"").val("");

                                var title = '';
                                var message = '';
                                if(name == 'code') {
                                    title = 'Código já cadastrado'
                                    message = 'Códido '+value+' já cadastrado para representante <br><b>'+response.name+'</b> ('+response.identity+')';

                                } else {
                                    title = 'CNPJ / CPF já cadastrado'
                                    message = 'CNPJ / CPF: '+value+' <br>já cadastrado para representante <b>'+response.name+'</b>';
                                }

                                bootbox.dialog({
                                    message: message,
                                    title: title,
                                    buttons: {
                                        main: {
                                            label: "Fechar",
                                            className: "btn-primary",
                                            callback: function() {}
                                        }
                                    }
                                });
                            }
                        } else {
                            error('Peça não foi encontrada!');
                        }
                    }
                });
            }
        });
		
		$(".radio-auth").change(function(){

            var val = $(".radio-auth:checked").val();
            var name_contact = $("#contact_name").val();
            var id_salesman = $("#id").val();

            if(val == 2) {

                bootbox.dialog({
                    message: "Você realmente deseja resetar autenticação de 2 fatores de "+name_contact+" do painel de representantes?",
                    title: "Resetar autenticação de 2 fatores",
					closeButton: false,
                    buttons: {
                        danger: {
                            label: "Cancelar",
                            className: "btn-default",
                            callback: function(){
                                $("input[name=is_otpauth][value=1]").prop('checked', true);
                                $("input[name=is_otpauth][value=2]").prop('checked', false);
                            }
                        },
                        main: {
                            label: "Confirmar",
                            className: "btn-primary",
                            callback: function() {
                                block();
                                window.location.href = '/commercial/salesman/reset/auth/'+id_salesman;
                            }
                        }
                    }
                });
            }
        });

        $("#salesman").addClass('menu-open');
        $("#salesmanEdit").addClass('page-arrow active-page');
    });
</script>

@endsection
