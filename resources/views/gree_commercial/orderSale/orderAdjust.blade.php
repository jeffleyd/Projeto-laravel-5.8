@extends('gree_commercial.layout')

@section('breadcrumb')
<ul class="breadcrumb">
    <li><a href="#">Home</a></li>
    <li><a href="/commercial/order/programation/month/list">Reajuste mensal</a></li>
    <li class="active">Todos</li>
</ul><!-- End .breadcrumb -->
@endsection
@section('content')

<style>
    .select-group {
        background-color: #eeeeee;
        border-color: #eeeeee;
        color: #555555;
        font-weight: 500;
    }
</style>

<header id="header-sec">
    <div class="inner-padding">
        <div class="pull-left">
            <div class="btn-group">
                <a class="btn btn-default" onclick="action()" href="#">
                    <i class="fa fa-plus"></i>&nbsp; Criar novo
                </a>
                <a class="btn btn-primary" href="#" data-toggle="modal" data-target="#filterModal">
                    <i class="fa fa-filter" style="color: #ffffff;"></i>&nbsp; Filtrar
                </a>
            </div>
        </div>
    </div><!-- End .inner-padding -->
</header>
<div class="window">
    <div class="inner-padding">
        <div class="row">
            <div class="col-sm-12">
                <div class="table-wrapper">
                    <header>
                        <h3>Ajuste mensal</h3>
                    </header>
                    <table class="table table-bordered table-striped" id="tb1" data-rt-breakpoint="600">
                        <thead>
                            <tr>
                                <th scope="col" data-rt-column="ID">ID</th>
                                <th scope="col" data-rt-column="Mês">Mês</th>
                                <th scope="col" data-rt-column="Ano">Ano</th>
                                <th scope="col" data-rt-column="Fator">Fator</th>
                                <th scope="col" data-rt-column="Vinculo">Vinculo</th>
                                <th scope="col" data-rt-column="Usuário">Usuário</th>
                                <th scope="col" data-rt-column="Ações">Ações</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($months as $key)
                            <tr>
                                <td>{{$key->id}}</td>
                                <td>{{$month[date('n', strtotime($key->date))]}}</td>
                                <td>{{date('Y', strtotime($key->date))}}</td>
                                <td>{{$key->factor}}%</td>
                                <td>
                                    @if ($key->type_apply == 1)
                                        <b>Grupo</b>
										@if ($key->group()->first())
                                        <br>{{$key->group()->first()->name}}
										@endif
                                    @elseif ($key->type_apply == 2)
                                        <b>Quente/Frio</b>
                                    @elseif ($key->type_apply == 3)
                                        <b>Frio</b>
                                    @elseif ($key->type_apply == 4)
                                        <b>Baixa Cap/Grupo</b>
                                        @if ($key->group()->first())
                                        <br>{{$key->group()->first()->name}}
										@endif
                                    @elseif ($key->type_apply == 5)
                                        <b>Alta Cap/Grupo</b>
                                        @if ($key->group()->first())
                                        <br>{{$key->group()->first()->name}}
										@endif
                                    @elseif ($key->type_apply == 6)
                                        <b>Todos</b>
									@elseif ($key->type_apply == 7)
                                        <b>Produto</b>
                                    @endif
                                </td>
                                <td><a target="_blank" href="/user/view/<?= $key->r_code ?>"><?= getENameF($key->r_code); ?></a></td>
                                <td>
                                    <select json-data="<?= htmlspecialchars(json_encode($key), ENT_QUOTES, 'UTF-8') ?>" onchange="action(this)" class="simpleselect form-control">
                                        <option></option>
                                        <option value="1">Editar</option>
                                        <option value="2">Deletar</option>
                                    </select>
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
                <div class="pull-right" style="margin-top: 20px;">
                    <ul class="pagination">
                        <?= $months->appends(getSessionFilters()[0]->toArray())->links(); ?>
                    </ul>
                </div>
                <div class="spacer-50"></div>
            </div>
        </div>

    </div>
    <!-- End .inner-padding -->
</div>

<div id="filterModal" class="modal fade" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog" style="max-width: 350px; margin: 30px auto;">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
                <h4 class="modal-title">Filtrar dados</h4>
            </div>
            <div class="modal-body">
                <form action="{{Request::url()}}" id="filterData">
                    <div class="row">
                        <div class="col-sm-12">
                            <label for="filter_month">Mês</label>
                            <select name="filter_month" id="filter_month" class="form-control">
                                <option value=""></option>
                                @for ($i = 1; $i < 13; $i++)
                                <option value="{{$i}}" @if(date('n', strtotime(Session::get('f_filter_date'))) == $i and Request::get('filter_month')) selected @endif>
                                    {{$month[$i]}}</option>
                                @endfor
                                </select>
                        </div>
                    </div>
                    <div class="spacer-10"></div>
                    <div class="row">
                        <div class="col-sm-12">
                            <label for="filter_year">Ano</label>
                            <select name="filter_year" id="filter_year" class="form-control">
                                <option value=""></option>
                                <option value="{{date('Y')}}" @if(date('Y', strtotime(Session::get('f_filter_date'))) == date('Y')) selected @endif>
                                    {{date('Y')}}</option>
                                @for($i = date('Y', strtotime('+1 Year')); $i < date('Y', strtotime('+11 Year')); $i++)
                                    <option value="{{$i}}" @if(date('Y', strtotime(Session::get('f_filter_date'))) == $i) selected @endif>{{$i}}</option>
                                @endfor
                            </select>
                        </div>
                    </div>
                    <div class="spacer-10"></div>
                    <div class="row">
                        <div class="col-sm-12">
                            <label for="filter_type_apply">Vinculo</label>
                            <select name="filter_type_apply" id="filter_type_apply" class="form-control">
                                <option value=""></option>
                                <option value="6" @if(Session::get('f_filter_type_apply') == 6) selected @endif>Todos</option>
								<option value="7" @if(Session::get('f_filter_type_apply') == 7) selected @endif>Produto</option>
                                <option value="1" @if(Session::get('f_filter_type_apply') == 1) selected @endif>Grupo</option>
                                <option value="2" @if(Session::get('f_filter_type_apply') == 2) selected @endif>Quente/Frio</option>
                                <option value="3" @if(Session::get('f_filter_type_apply') == 3) selected @endif>Frio</option>
                                <option value="4" @if(Session::get('f_filter_type_apply') == 4) selected @endif>Baixa Cap/Grupo</option>
                                <option value="5" @if(Session::get('f_filter_type_apply') == 5) selected @endif>Alta Cap/Grupo</option>
                            </select>
                        </div>
                    </div>
                    <div class="spacer-10"></div>
                    <div class="row">
                        <div class="col-sm-12">
                            <label for="filter_group_id_apply">Grupo</label>
                            <select name="filter_group_id_apply" id="filter_group_id_apply" class="form-control js-select2" style="width:100%;" multiple></select>
                        </div>
                    </div>
                </form>
                <div class="clear"></div>
            </div>
            <div class="modal-footer" style="padding: 0;height: 76px;">
                <div data-dismiss="modal" style="float: left;width: 170px;text-align: center;position: relative;top: 0px;font-weight: bold;color: #ff0000;height: 76px;cursor: pointer; font-size: 16px;">
                    <span style="position: relative;top: 25px;">Fechar</span>
                </div>
            <div style="position: absolute;height: 76px;border-right: solid 1px #bbb;left: 170px;right: 0;width: 1px;"></div>
                <div  id="filterNow" style="float: right;width: 178px;text-align: center;position: relative;height:76px;font-weight: bold;color: #18b202;cursor: pointer;font-size: 16px;">
                    <span style="position: relative;top: 25px;">Filtrar</span>
                </div>
            </div>
        </div>
    </div>
</div>

<div id="newModal" class="modal fade" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog" style="margin: 30px auto;">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
                <h4 class="modal-title">Atualizar ajuste mensal</h4>
            </div>
            <div class="modal-body">
                <form action="/commercial/order/programation/month_do" id="editForm">
                    <input type="hidden" id="id" name="id" value="0">
                    <div class="row">
                        <div class="col-sm-6">
                            <label for="month">Mês</label>
                            <select name="month" id="month" class="form-control">
                                <option value="1" selected>Janeiro</option>
                                <option value="2">Fevereiro</option>
                                <option value="3">Março</option>
                                <option value="4">Abril</option>
                                <option value="5">Maio</option>
                                <option value="6">Junho</option>
                                <option value="7">Jullho</option>
                                <option value="8">Agosto</option>
                                <option value="9">Setembro</option>
                                <option value="10">Outubro</option>
                                <option value="11">Novembro</option>
                                <option value="12">Dezembro</option>
                            </select>
                        </div>
                        <div class="col-sm-6">
                            <label for="year">Ano</label>
                            <select name="year" id="year" class="form-control">
                                <option value="{{date('Y')}}">{{date('Y')}}</option>
                                @for($i = date('Y', strtotime('+1 Year')); $i < date('Y', strtotime('+11 Year')); $i++)
                                    <option value="{{$i}}">{{$i}}</option>
                                @endfor
                            </select>
                        </div>
                    </div>
                    <div class="spacer-10"></div>
                    <div class="row">
                        <div class="col-sm-12">
                            <label for="factor">Porcentagem</label>
                            <input type="text" id="factor" class="form-control" name="factor" />
                        </div>
                    </div>
                    <div class="spacer-10"></div>
                    <div class="row">
                        <div class="col-sm-12">
                            <label for="type_apply">Vinculo</label>
                            <select name="type_apply" id="type_apply" class="form-control">
                                <option value="6">Todos</option>
								<option value="7">Produto</option>
                                <option value="1">Grupo</option>
                                <option value="2">Quente/Frio</option>
                                <option value="3">Frio</option>
                                <option value="4">Baixa Cap/Grupo</option>
                                <option value="5">Alta Cap/Grupo</option>
                            </select>
                        </div>
                    </div>
                    <div class="spacer-10"></div>
                    <div class="row group_id_apply">
                        <div class="col-sm-12">
                            <label for="group_id_apply">Grupo</label>
                            <select name="group_id_apply" id="group_id_apply" class="form-control">
                                <option value=""></option>
                                @foreach($groups as $group)
                                    <option value="{{$group->id}}">{{$group->name}}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>
					<div class="spacer-10"></div>
                    <div class="row product_id" style="display:none">
                        <div class="col-sm-12">
                            <label for="product_id">Produto</label>
                            <select name="product_id[]" id="product_id" style="width:100%" class="form-control js-select22" multiple>
                                @foreach($setProducts as $setproduct)
                                    <option value="{{$setproduct->id}}">{{$setproduct->code}} ({{$setproduct->resume}})</option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                </form>
                <div class="clear"></div>
            </div>
            <div class="modal-footer">
                <button class="btn btn-default" data-dismiss="modal">Fechar</button>
                <button class="btn btn-primary pull-right" id="editSave">Salvar</button>
            </div>
        </div>
    </div>
</div>

<script>
    function action($this = '') {
        $('#editForm').each (function(){
            this.reset();
        });

        if ($this == '') {
            $("#newModal").modal();
            $("#id").val(0);
            $(".group_id_apply").hide();
			$(".product_id").hide();
            return
        }

        var json = JSON.parse($($this).attr('json-data'));

        if ($($this).val() == 1) {

            var $date = new Date(json.date);

            $("#id").val(json.id);
            $("#month").val($date.getMonth()+1);
            $("#year").val($date.getFullYear());
            $("#factor").val(json.factor);
            $("#type_apply").val(json.type_apply);
            $("#group_id_apply").val(json.group_id_apply);
			
			var arrs = [];
			if (json.type_apply == 7) {
				if (json.p_ids) {
					var p_ids = json.p_ids;
					arrs = p_ids.split(',');
				}
			}
			
			$('#product_id').val(arrs);
			$('#product_id').trigger('change');

            if (json.type_apply == 1 || json.type_apply == 4 || json.type_apply == 5)
                $(".group_id_apply").show();
            else
                $(".group_id_apply").hide();
			
			if (json.type_apply == 7)
                $(".product_id").show();
            else
                $(".product_id").hide();

            $("#newModal").modal();
        } else if ($($this).val() == 2) {

            bootbox.dialog({
                message: "Você realmente quer deletar? Essa ação poderá ser irreversível.",
                title: "Deletar ajuste mensal",
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
                            window.location.href = '/commercial/order/programation/month/delete/'+json.id;
                        }
                    }
                }
            });
        }

        $($this).val('');

    }

    $(document).ready(function () {
		
		$(".js-select22").select2({
			placeholder: "Todos",
            language: {
                noResults: function () {
                    return 'Produto não existe...';
                }
            }
        });
		
		$(".js-select2").select2({
            maximumSelectionLength: 1,
            language: {
                noResults: function () {
                    return 'Grupo não existe...';
                },
                maximumSelected: function (e) {
                    return 'você só pode selecionar 1 item';
                }
            },
            ajax: {
                url: '/commercial/product/group/dropdown',
                data: function (params) {
                    var query = {
                        search: params.term,
                        page: params.page || 1
                    }
                    return query;
                }
            }
        });

        $("#filterNow").click(function (e) {
            $("#filterModal").modal('toggle');
            block();
            $("#filterData").submit();
        });

        $("#editSave").click(function (e) {
            $("#newModal").modal('toggle');
            block();
            $("#editForm").submit();
        });

        $("#type_apply").change(function () {
            if ($("#type_apply").val() == 1 || $("#type_apply").val() == 4 || $("#type_apply").val() == 5)
                $(".group_id_apply").show();
            else
                $(".group_id_apply").hide();
			
			if ($("#type_apply").val() == 7)
                $(".product_id").show();
            else
                $(".product_id").hide();
        });

        $('table').responsiveTables({
            columnManage: false,
            exclude: '.table-collapsible, .table-collapsible-open',
            menuIcon: '<i class="fa fa-bars"></i>',
            startBreakpoint: function(ui){
                //ui.item(element)
                ui.item.find('label').parents('.rt-responsive-row').hide();
            },
            endBreakpoint: function(ui){
                //ui.item(element)
                ui.item.find('label').parents('.rt-responsive-row').show();
            },
            onColumnManage: function(){}
        });

        $("#factor").mask('00000.00', {reverse: true});

        $("#orderSale").addClass('menu-open');
        $("#orderAdjust").addClass('page-arrow active-page');
    });
</script>

@endsection
