@extends('gree_commercial.layout')

@section('breadcrumb')
    <ul class="breadcrumb">
        <li><a href="/commercial/order/list">Home</a></li>
        <li class="active">Configurações</li>
    </ul><!-- End .breadcrumb -->
@endsection

@php
    function getValue($settings, $command, $is_arr = 0) {

        if ($is_arr == 1) {
            if (!empty($settings->where('command', $command)->first()->value))
                return explode(',', $settings->where('command', $command)->first()->value);
            else
                return null;
        } else {
            return $settings->where('command', $command)->first()->value;
        }
    }
@endphp

@section('content')
    <style>
        .block-price {
            padding: 10px;
            margin: 15px;
            border: solid 1px;
            text-align: center;
            max-width: 160px;
        }

        .bfr {
            background: #d9e6f6;
        }

        .bqf {
            background: #f5f5f0;
        }

        .padding-left-block {
            padding: 0px;
            padding-left: 35px;
        }

        .padding-right-block {
            padding: 0px;
            padding-right: 35px;
        }

        @media only screen and (max-width: 600px) {
            .padding-left-block {
                padding-right: 35px;
            }

            .padding-right-block {
                padding-left: 35px;
            }

            .block-price {
                max-width: none;
            }
        }

        .select2-container--default .select2-selection--multiple {
            border-radius: 0px;
        }
		
        .perm_name {
            font-weight: bold;
            text-transform: uppercase;
            width: 300px;
            float: left;
            position: relative;
            top: 4px;
        }
        .perm_edit {
            float: left;
            width: 120px;
        }
        .perm_view {
            float: left;
            width: 65px;
        }
        .perm_header {
            background: black;
            color: white;
            padding: 10px;
            height: 62px;
            margin-bottom: 10px;
        }
        .ph_name {
            float: left;
            margin-right: 258px;
        }
        .ph_edit {
            text-align:center;
            float: left;
            margin-right: 75px;
        }
        .ph_view {
            text-align:center;
            float: left;
            margin-right: 10px;
        }
    </style>
    <header id="header-sec">
        <div class="inner-padding">
            <div class="pull-left">
                <div class="btn-group">
                    <a class="btn btn-default" id="editSave" href="#">
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
                        <a href="#content-tab-1">Cliente</a>
                    </li>
                    <li>
                        <a href="#content-tab-3">Programação</a>
                    </li>
                    <li>
                        <a href="#content-tab-2">Pedido de vendas</a>
                    </li>
					<li>
                        <a href="#content-tab-4">Permissões</a>
                    </li>
                </ul><!-- End .ext-tabs -->
            </div>
        </div>
        <form action="/commercial/settings_do" id="formSend" method="post">
            <div class="tab-content">
                <div id="content-tab-1" class="tab-pane active">
                    <div class="inner-padding">
                        <div class="row">
                            <div class="col-sm-12">
                                <fieldset>
                                    <legend>Cabeçalho da solicitação</legend>
                                    <div class="row">
                                        <div class="col-sm-3">
                                            <label>PCM</label>
                                        </div>
                                        <div class="col-sm-9">
                                            <div class="input-group input-group">
                                                <span class="input-group-addon"><i class="fa fa-barcode"></i></span>
                                                <input type="text" name="client_pcm_rev" value="{{getValue($settings, 'client_pcm_rev')}}" class="form-control">
                                            </div>
                                        </div>
                                    </div>
                                    <div class="spacer-10"></div>
                                    <div class="row">
                                        <div class="col-sm-3">
                                            <label>ID</label>
                                        </div>
                                        <div class="col-sm-9">
                                            <div class="input-group input-group">
                                                <span class="input-group-addon"><i class="fa fa-barcode"></i></span>
                                                <input type="text" name="client_id_rev" value="{{getValue($settings, 'client_id_rev')}}" class="form-control">
                                            </div>
                                        </div>
                                    </div>
                                    <div class="spacer-10"></div>
                                    <div class="row">
                                        <div class="col-sm-3">
                                            <label>Revisão</label>
                                        </div>
                                        <div class="col-sm-5">
                                            <div class="input-group input-group">
                                                <span class="input-group-addon"><i class="fa fa-calendar"></i></span>
                                                <input type="text" name="client_date_rev" value="{{getValue($settings, 'client_date_rev')}}" class="form-control">
                                            </div>
                                        </div>
                                        <div class="col-sm-4">
                                            <div class="input-group input-group">
                                                <span class="input-group-addon"><i class="fa fa-plus-square"></i></span>
                                                <input type="text" name="client_number_rev" value="{{getValue($settings, 'client_number_rev')}}" class="form-control">
                                            </div>
                                        </div>
                                    </div>
                                    <div class="spacer-10"></div>
                                    <div class="row">
                                        <div class="col-sm-3">
                                            <label>Qtd de folhas</label>
                                        </div>
                                        <div class="col-sm-9">
                                            <div class="input-group input-group">
                                                <span class="input-group-addon"><i class="fa fa-book"></i></span>
                                                <input type="text" name="client_qtd_paper" value="{{getValue($settings, 'client_qtd_paper')}}" class="form-control">
                                            </div>
                                        </div>
                                    </div>
                                    <div class="spacer-10"></div>
                                    <div class="row">
                                        <div class="col-sm-3">
                                            <label>Elaborador</label>
                                        </div>
                                        <div class="col-sm-9">
                                            <div class="input-group input-group">
                                                <span class="input-group-addon"><i class="fa fa-user"></i></span>
                                                <input type="text" name="client_user_creater" value="{{getValue($settings, 'client_user_creater')}}" class="form-control">
                                            </div>
                                        </div>
                                    </div>
                                    <div class="spacer-10"></div>
                                    <div class="row">
                                        <div class="col-sm-3">
                                            <label>Verificador</label>
                                        </div>
                                        <div class="col-sm-9">
                                            <div class="input-group input-group">
                                                <span class="input-group-addon"><i class="fa fa-user"></i></span>
                                                <input type="text" name="client_user_verify" value="{{getValue($settings, 'client_user_verify')}}" class="form-control">
                                            </div>
                                        </div>
                                    </div>
                                    <div class="spacer-10"></div>
                                    <div class="row">
                                        <div class="col-sm-3">
                                            <label>Aprovador</label>
                                        </div>
                                        <div class="col-sm-9">
                                            <div class="input-group input-group">
                                                <span class="input-group-addon"><i class="fa fa-user"></i></span>
                                                <input type="text" name="client_user_approval" value="{{getValue($settings, 'client_user_approval')}}" class="form-control">
                                            </div>
                                        </div>
                                    </div>
                                    <div class="spacer-10"></div>
                                </fieldset>
                            </div>
                            <div class="col-sm-12">
                                <fieldset>
                                    <legend>Configurações das notificações</legend>
                                    <div class="row">
                                        <div class="col-sm-3">
                                            <label>Criação de um novo cadastro</label>
                                        </div>
                                        <div class="col-sm-9">
                                            <div class="input-group input-group">
                                                <span class="input-group-addon"><i class="fa fa-envelope"></i></span>
                                                <select name="client_new_register[]" class="js-select2 form-control" multiple>
                                                    @if (is_array(getValue($settings, 'client_new_register', 1)))
                                                        @foreach (getValue($settings, 'client_new_register', 1) as $value)
                                                            <option value="{{$value}}" selected>{{$value}}</option>
                                                        @endforeach
                                                    @endif
                                                </select>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="spacer-10"></div>
                                    <div class="row">
                                        <div class="col-sm-3">
                                            <label>Atualização do cadastro</label>
                                        </div>
                                        <div class="col-sm-9">
                                            <div class="input-group input-group">
                                                <span class="input-group-addon"><i class="fa fa-envelope"></i></span>
                                                <select name="client_update_register[]" class="js-select2 form-control" multiple>
                                                    @if (is_array(getValue($settings, 'client_update_register', 1)))
                                                        @foreach (getValue($settings, 'client_update_register', 1) as $value)
                                                            <option value="{{$value}}" selected>{{$value}}</option>
                                                        @endforeach
                                                    @endif
                                                </select>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="spacer-10"></div>
                                    <div class="row">
                                        <div class="col-sm-3">
                                            <label>Aprovação do cadastro</label>
                                        </div>
                                        <div class="col-sm-9">
                                            <div class="input-group input-group">
                                                <span class="input-group-addon"><i class="fa fa-envelope"></i></span>
                                                <select name="client_approval[]" class="js-select2 form-control" multiple>
                                                    @if (is_array(getValue($settings, 'client_approval', 1)))
                                                        @foreach (getValue($settings, 'client_approval', 1) as $value)
                                                            <option value="{{$value}}" selected>{{$value}}</option>
                                                        @endforeach
                                                    @endif
                                                </select>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="spacer-10"></div>
                                </fieldset>
                            </div>
                            <div class="col-sm-12">
                                <fieldset>
                                    <legend>Prazo de atualização documental</legend>
                                    <div class="row">
                                        <div class="col-sm-3">
                                            <label>Balanço patrimonial/DRE/Fluxo de caixa</label>
                                        </div>
                                        <div class="col-sm-9">
                                            <div class="input-group input-group">
                                                <span class="input-group-addon">Meses</span>
                                                <input type="text" name="client_balance_month" value="{{getValue($settings, 'client_balance_month')}}" class="form-control">
                                            </div>
                                        </div>
                                    </div>
                                    <div class="spacer-10"></div>
                                </fieldset>
                            </div>
                        </div>
                    </div>
                    <!-- End .inner-padding -->
                    <div class="spacer-20"></div>
                </div>
                <div id="content-tab-2" class="tab-pane">
                    <div class="inner-padding">
                        <div class="row">
                            <div class="col-sm-12">
                                <fieldset>
                                    <legend>Cabeçalho da solicitação</legend>
                                    <div class="row">
                                        <div class="col-sm-3">
                                            <label>PCM</label>
                                        </div>
                                        <div class="col-sm-9">
                                            <div class="input-group input-group">
                                                <span class="input-group-addon"><i class="fa fa-barcode"></i></span>
                                                <input type="text" name="order_pcm_rev" value="{{getValue($settings, 'order_pcm_rev')}}" class="form-control">
                                            </div>
                                        </div>
                                    </div>
                                    <div class="spacer-10"></div>
                                    <div class="row">
                                        <div class="col-sm-3">
                                            <label>ID</label>
                                        </div>
                                        <div class="col-sm-9">
                                            <div class="input-group input-group">
                                                <span class="input-group-addon"><i class="fa fa-barcode"></i></span>
                                                <input type="text" name="order_id_rev" value="{{getValue($settings, 'order_id_rev')}}" class="form-control">
                                            </div>
                                        </div>
                                    </div>
                                    <div class="spacer-10"></div>
                                    <div class="row">
                                        <div class="col-sm-3">
                                            <label>Revisão</label>
                                        </div>
                                        <div class="col-sm-5">
                                            <div class="input-group input-group">
                                                <span class="input-group-addon"><i class="fa fa-calendar"></i></span>
                                                <input type="text" name="order_date_rev" value="{{getValue($settings, 'order_date_rev')}}" class="form-control">
                                            </div>
                                        </div>
                                        <div class="col-sm-4">
                                            <div class="input-group input-group">
                                                <span class="input-group-addon"><i class="fa fa-plus-square"></i></span>
                                                <input type="text" name="order_number_rev" value="{{getValue($settings, 'order_number_rev')}}" class="form-control">
                                            </div>
                                        </div>
                                    </div>
                                    <div class="spacer-10"></div>
                                    <div class="row">
                                        <div class="col-sm-3">
                                            <label>Qtd de folhas</label>
                                        </div>
                                        <div class="col-sm-9">
                                            <div class="input-group input-group">
                                                <span class="input-group-addon"><i class="fa fa-book"></i></span>
                                                <input type="text" name="order_qtd_paper" value="{{getValue($settings, 'order_qtd_paper')}}" class="form-control">
                                            </div>
                                        </div>
                                    </div>
                                </fieldset>
                            </div>
                            <div class="col-sm-12">
                                <fieldset>
                                    <legend>Configurações das notificações</legend>
                                    <div class="row">
                                        <div class="col-sm-3">
                                            <label>Criação de um novo pedido</label>
                                        </div>
                                        <div class="col-sm-9">
                                            <div class="input-group input-group">
                                                <span class="input-group-addon"><i class="fa fa-envelope"></i></span>
                                                <select name="order_new_register[]" style="width: 100%" class="js-select2 form-control" multiple>
                                                    @if (is_array(getValue($settings, 'order_new_register', 1)))
                                                        @foreach (getValue($settings, 'order_new_register', 1) as $value)
                                                            <option value="{{$value}}" selected>{{$value}}</option>
                                                        @endforeach
                                                    @endif
                                                </select>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="spacer-10"></div>
                                    <div class="row">
                                        <div class="col-sm-3">
                                            <label>Atualização do pedido</label>
                                        </div>
                                        <div class="col-sm-9">
                                            <div class="input-group input-group">
                                                <span class="input-group-addon"><i class="fa fa-envelope"></i></span>
                                                <select name="order_update_register[]" style="width: 100%" class="js-select2 form-control" multiple>
                                                    @if (is_array(getValue($settings, 'order_update_register', 1)))
                                                        @foreach (getValue($settings, 'order_update_register', 1) as $value)
                                                            <option value="{{$value}}" selected>{{$value}}</option>
                                                        @endforeach
                                                    @endif
                                                </select>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="spacer-10"></div>
                                    <div class="row">
                                        <div class="col-sm-3">
                                            <label>Aprovação do pedido</label>
                                        </div>
                                        <div class="col-sm-9">
                                            <div class="input-group input-group">
                                                <span class="input-group-addon"><i class="fa fa-envelope"></i></span>
                                                <select name="order_approval[]" style="width: 100%" class="js-select2 form-control" multiple>
                                                    @if (is_array(getValue($settings, 'order_approval', 1)))
                                                        @foreach (getValue($settings, 'order_approval', 1) as $value)
                                                            <option value="{{$value}}" selected>{{$value}}</option>
                                                        @endforeach
                                                    @endif
                                                </select>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="spacer-10"></div>
                                </fieldset>
                            </div>
                            <div class="col-sm-12">
                                <fieldset>
                                    <legend>Prazo para condição comercial/Programação/Pedido</legend>
                                    <div class="row">
                                        <div class="col-sm-3">
                                            <label>Dia limite p/ editar ou criar</label>
                                        </div>
                                        <div class="col-sm-9">
                                            <div class="form-group">
                                                <input type="text" name="programation_last_day" value="{{getValue($settings, 'programation_last_day')}}" class="form-control">
                                            </div>
                                        </div>
                                    </div>
                                </fieldset>
                            </div>
                        </div>
                    </div>
                    <!-- End .inner-padding -->
                    <div class="spacer-10"></div>
                </div>
                <div id="content-tab-3" class="tab-pane">
                    <div class="inner-padding">
                        <div class="row">
                            <div class="col-sm-12">
                                <fieldset>
                                    <legend>Configurações</legend>
                                    <div class="row">
                                        <div class="col-sm-3">
                                            <label>Aviso importante</label>
                                        </div>
                                        <div class="col-sm-9">
                                            <div class="form-group">
                                                <textarea name="programation_alert" id="editor1" class="form-control">{{getValue($settings, 'programation_alert')}}</textarea>
                                            </div>
                                        </div>
                                    </div>
                                </fieldset>
                            </div>
                            <div class="col-sm-12">
                                <fieldset>
                                    <legend>Configurações das notificações</legend>
                                    <div class="row">
                                        <div class="col-sm-3">
                                            <label>Atualização da programação</label>
                                        </div>
                                        <div class="col-sm-9">
                                            <div class="input-group input-group">
                                                <span class="input-group-addon"><i class="fa fa-envelope"></i></span>
                                                <select name="programation_update_register[]" style="width: 100%" class="js-select2 form-control" multiple>
                                                    @if (is_array(getValue($settings, 'programation_update_register', 1)))
                                                        @foreach (getValue($settings, 'programation_update_register', 1) as $value)
                                                            <option value="{{$value}}" selected>{{$value}}</option>
                                                        @endforeach
                                                    @endif
                                                </select>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="spacer-10"></div>
                                    <div class="row">
                                        <div class="col-sm-3">
                                            <label>Aprovação da programação</label>
                                        </div>
                                        <div class="col-sm-9">
                                            <div class="input-group input-group">
                                                <span class="input-group-addon"><i class="fa fa-envelope"></i></span>
                                                <select name="programation_approval[]" style="width: 100%" class="js-select2 form-control" multiple>
                                                    @if (is_array(getValue($settings, 'programation_approval', 1)))
                                                        @foreach (getValue($settings, 'programation_approval', 1) as $value)
                                                            <option value="{{$value}}" selected>{{$value}}</option>
                                                        @endforeach
                                                    @endif
                                                </select>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="spacer-10"></div>
                                </fieldset>
                            </div>
                        </div>
                    </div>
                    <!-- End .inner-padding -->
                    <div class="spacer-10"></div>
                </div>
				<div id="content-tab-4" class="tab-pane">
                    <div class="inner-padding">
                        <div class="row">
                            <div class="col-sm-12">
                                <fieldset>
                                    <legend>Escolha o colaborador</legend>
                                    <div class="row">
                                        <div class="col-sm-12">
                                            <select id="sel_collaborator" class="form-control js-select22" style="width: 100%;"></select>
                                        </div>
                                    </div>
                                    <div class="spacer-20"></div>
                                    <div class="row">
                                        <div class="col-sm-12">
                                            <div class="table-wrapper">
                                                <header>
                                                    <h3>COLABORADORES COM PERMISSÕES</h3>
                                                </header>
                                                <table class="table table-bordered table-striped" id="table_state" data-rt-breakpoint="600">
                                                    <thead>
                                                    <tr>
                                                        <th scope="col" data-rt-column="Nome">Nome</th>
                                                        <th scope="col" data-rt-column="Setor">Setor</th>
                                                        <th scope="col" data-rt-column="Última atualização">Última atualização</th>
                                                        <th scope="col" class="th-4-action-btn">Ações</th>
                                                    </tr>
                                                    </thead>
                                                    <tbody>
                                                    @if ($usersOnPermissions->count() > 0)
                                                        @foreach($usersOnPermissions as $key)
                                                        <tr>
                                                            <td>
                                                                <ul class="mini-list">
                                                                    <li>
                                                                        <img src="{{$key->user->picture}}" alt="{{$key->user->full_name}}" class="avatar">
                                                                        <ul>
                                                                            <li><a href="#"><b>{{$key->user->full_name}}</b></a></li>
                                                                            <li><a href="#">{{$key->user->email}}</a></li>
                                                                        </ul>
                                                                    </li>
                                                                </ul>
                                                            </td>
                                                            <td style="vertical-align: middle;">{{sectorName($key->user->sector_id)}}</td>
                                                            <td style="vertical-align: middle;">{{date('d/m/Y H:i', strtotime($key->created_at))}}</td>
                                                            <td style="vertical-align: middle; text-align: center">
                                                                <a json-data="<?= htmlspecialchars(json_encode($key), ENT_QUOTES, 'UTF-8') ?>" onclick="action(this)" data-value="1" class='btn-less'><i class='fa fa-edit'></i></a>
                                                                <a json-data="<?= htmlspecialchars(json_encode($key), ENT_QUOTES, 'UTF-8') ?>" onclick="action(this)" data-value="2" data-id='' class='btn-less'><i class='fa fa-trash-o'></i></a>
                                                            </td>
                                                        </tr>
                                                        @endforeach
                                                    @else
                                                        <tr>
                                                            Não há usuários com permissão para uso no sistema.
                                                        </tr>
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
            </div>
        </form>
    </div>

<div id="editModal" class="modal fade" tabindex="-1" role="dialog" aria-hidden="true">
        <div class="modal-dialog" style="width: 980px;">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
                    <h4 class="modal-title editTitle"></h4>
                </div>
                <div class="modal-body">
                    <form id="editForm" method="post" action="/commercial/permissions_do">
                        <input type="hidden" id="id" name="id" value="0">
                        <input type="hidden" id="r_code" name="r_code" value="0">

                        <div class="ext-tabs-vertical-wrapper ext-tabs-highlighted">
                            <ul class="ext-tabs-vertical">
                                @php
                                    $firstKey = array_key_first($permissions);
                                    $checki = 0;
                                @endphp
                                @foreach($permissions as $perm => $val)
                                <li @if ($firstKey == $perm) class="active" @endif>
                                    <a href="#{{$perm}}">{{$permissions[$perm]['page_name']}}</a>
                                </li>
                                @endforeach
                            </ul>
                            <div class="tab-content">
                                @foreach($permissions as $perm => $val)
                                <div id="{{$perm}}" class="tab-pane @if ($firstKey == $perm) active @endif">
                                    <div class="inner-padding">
                                        <div class="row">
                                            <div class="col-sm-12">
                                                <div class="perm_header">
                                                    <div class="ph_name">Nome</div>
                                                    <div class="ph_edit">Editar
                                                        <br><label><input onclick="checklistEdit(this, '{{$checki}}')" type="checkbox"><span></span> </label>
                                                    </div>
                                                    <div class="ph_view">Visualizar
                                                        <br><label><input onclick="checklistView(this, '{{$checki}}')" type="checkbox"><span></span> </label>
                                                    </div>
                                                </div>
                                            </div>
                                            @foreach($permissions[$perm] as $input => $name)
                                                @if ($input != 'page_name')
                                                <div style="margin-left: 10px;" class="col-sm-12">
                                                    <div class="perm_name">{{$name}}</div>
                                                    <div class="perm_edit">
                                                        <label><input name="{{$input}}[edit]" data-id="checkedit{{$checki}}" value="1" type="checkbox"><span></span> </label>
                                                    </div>
                                                    <div class="perm_view">
                                                        <label><input name="{{$input}}[view]" data-id="checkview{{$checki}}" value="1" type="checkbox"><span></span> </label>
                                                    </div>
                                                </div>
                                                @endif
                                            @endforeach
                                        </div>
                                    </div>
                                </div>
                                    @php
                                        $checki++;
                                    @endphp
                                @endforeach
                            </div>
                        </div>
                    </form>
                    <div class="clear"></div>
                </div>
                <div class="modal-footer">
                    <button class="btn btn-default" data-dismiss="modal">Fechar</button>
                    <button class="btn btn-primary pull-right" onclick="submitClick()">Salvar</button>
                </div>
            </div>
        </div>
    </div>

    <script>
		function checklistEdit($this, $indx) {
            $('input[data-id="checkedit'+$indx+'"]').each(function() {
                $(this).prop('checked', $($this).prop('checked'))
            });
        }
        function checklistView($this, $indx) {
            $('input[data-id="checkview'+$indx+'"]').each(function() {
                $(this).prop('checked', $($this).prop('checked'))
            });
        }
        function action($this = '') {

            $('#editForm').each (function(){
                this.reset();
            });

            var json = JSON.parse($($this).attr('json-data'));

            if ($($this).attr('data-value') == 1) {

                $(".editTitle").html('Atualizando permissão de: '+json.user.first_name);

                $("#id").val(json.id);
                $("#r_code").val(json.r_code);

                var permissions = Object.entries(JSON.parse(json.scheme));
                permissions.forEach(function ($key) {
                    if (typeof $key[1]['edit'] != 'undefined')
                        $('input[name="'+$key[0]+'[edit]"]').prop('checked', true);
                    if (typeof $key[1]['view'] != 'undefined')
                        $('input[name="'+$key[0]+'[view]"]').prop('checked', true);
                });

                $("#editModal").modal();
            } else if ($($this).attr('data-value') == 2) {

                bootbox.dialog({
                    message: "Você realmente quer remover as permissões do: '"+json.user.full_name+"'?",
                    title: "Remover permissões",
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
                                window.location.href = '/commercial/user/remover/permissions/'+json.id;
                            }
                        }
                    }
                });
            }

            $($this).val('');

        }
		
        function isEmail(email) {
            var regex = /^([a-zA-Z0-9_.+-])+\@(([a-zA-Z0-9-])+\.)+([a-zA-Z0-9]{2,4})+$/;
            return regex.test(email);
        }
		
		function submitClick() {
            $("#editModal").modal('toggle');
            block();
            $("#editForm").submit();
        }
        $(document).ready(function () {
            $(".js-select2").select2({
                tags: true,
                language: {
                    noResults: function () {

                        return 'Informe um email válido para receber as notícias.';
                    }
                },
            });
			
			$(".js-select22").select2({
                language: {
                    noResults: function () {

                        return 'Colaborador não existe...';
                    }
                },
                ajax: {
                    url: '/users/dropdown',
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

            $('.js-select22').on('select2:selecting', function (e) {
                $('#editForm').each (function(){
                    this.reset();
                });
                $("#id").val(0);
                $("#r_code").val(e.params.args.data.r_code);
                $(".editTitle").html('Permitindo um novo usuário');
                $("#editModal").modal('toggle');
                setTimeout(function () {
                    $(".js-select22").val(0).trigger('change');
                }, 100);
            });

            $('.js-select2').on('select2:selecting', function (e) {
                if (!isEmail(e.params.args.data.text)) {
                    e.preventDefault();
                    return $error('Informe um email válido.');
                }
            });
            $("#editSave").click(function (e) {

                block();
                $("#formSend").submit();

            });

            $('#multiplay').mask('000.00', {reverse: true});

            $("#settings").addClass('page-arrow active-page');
        });
    </script>

@endsection
