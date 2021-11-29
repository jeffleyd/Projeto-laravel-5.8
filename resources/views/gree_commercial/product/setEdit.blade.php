@extends('gree_commercial.layout')

@section('breadcrumb')
<ul class="breadcrumb">
    <li><a href="/commercial/order/list">Home</a></li>
    <li class="active">Produtos</li>
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
    <div class="inner-padding">
        <div class="row">
            <div class="col-sm-12">
                <form action="/commercial/product/set/edit_do" method="POST" id="sendForm">
                    <input type="hidden" id="id" name="id" value="{{$id}}">
                    <fieldset>
                        <legend>Conjunto</legend>
                        <div class="row">
                            <div class="col-sm-3">
                                <label>Status</label>
                            </div>
                            <div class="col-sm-9">
                                <div class="inline-labels">
                                    <label><input type="radio" name="is_active" value="1" @if ($is_active == 1) checked="" @endif><span></span> Ativo</label>
                                    <label><input type="radio" name="is_active" value="2" @if ($is_active == 2) checked="" @endif><span></span> Desativado</label>
                                </div>
                            </div>
                        </div>
                        <div class="spacer-10"></div>
                        <div class="row">
                            <div class="col-sm-3">
                                <label>Código</label>
                            </div>
                            <div class="col-sm-9">
                                <input type="text" name="code" readonly value="{{$code}}" class="form-control">
                            </div>
                        </div>
                        <div class="spacer-10"></div>
                        <div class="row">
                            <div class="col-sm-3">
                                <label>Posição</label>
                            </div>
                            <div class="col-sm-9">
                                <input type="number" name="position" value="{{$position}}" class="form-control">
                            </div>
                        </div>
                        <div class="spacer-10"></div>
                        <div class="row">
                            <div class="col-sm-3">
                                <label>Grupo</label>
                            </div>
                            <div class="col-sm-9">
                                <select name="group" class="form-control js-select2">
                                    @if (!empty($group))
                                    <option value="{{$group->id}}" selected>{{$group->name}} ({{$group->code}})</option>
                                    @endif
                                </select>
                            </div>
                        </div>
                        <div class="spacer-10"></div>
                        <div class="row">
                            <div class="col-sm-3">
                                <label>Tipo</label>
                            </div>
                            <div class="col-sm-9">
                                <select name="is_qf" class="form-control">
                                    <option value="2" @if ($is_qf == 0) selected @endif>FRIO</option>
                                    <option value="1" @if ($is_qf == 1) selected @endif>QUENTE E FRIO</option>
                                </select>
                            </div>
                        </div>
                        <div class="spacer-10"></div>
                        <div class="row">
                            <div class="col-sm-3">
                                <label>Resumo</label>
                            </div>
                            <div class="col-sm-5">
                                <input type="text" name="resume" value="{{$resume}}" class="form-control">
                            </div>
                            <div class="col-sm-4">
                                <select name="capacity" class="form-control">
                                    <option value="1" @if ($capacity == 1) selected @endif>Capacidade: ALTA</option>
                                    <option value="2" @if ($capacity == 2) selected @endif>Capacidade: BAIXA</option>
                                </select>
                            </div>
                        </div>
                        <div class="spacer-10"></div>
                        <div class="row">
                            <div class="col-sm-3">
                                <label>Evaporadora</label>
                            </div>
                            <div class="col-sm-5">
                                <select name="evap" class="form-control js-select22">
                                    @if (!empty($evap))
                                    <option value="{{$evap->id}}" selected>{{$evap->model}}</option>
                                    @endif
                                </select>
                            </div>
                            <div class="col-sm-4">
                                <div class="input-group input-group">
                                    <span class="input-group-addon">R$</span>
                                    <input type="text" name="evap_price" class="form-control" value="@if ($evap_product_price) {{number_format($evap_product_price, 2, '.', '')}} @endif">
                                    <span class="input-group-addon">Unid</span>
                                </div>
                            </div>
                        </div>
                        <div class="spacer-10"></div>
                        <div class="row">
                            <div class="col-sm-3">
                                <label>Condensadora</label>
                            </div>
                            <div class="col-sm-5">
                                <select name="cond" class="form-control js-select22">
                                    @if (!empty($cond))
                                    <option value="{{$cond->id}}" selected>{{$cond->model}}</option>
                                    @endif
                                </select>
                            </div>
                            <div class="col-sm-4">
                                <div class="input-group input-group">
                                    <span class="input-group-addon">R$</span>
                                    <input type="text" name="cond_price" class="form-control" value="@if ($cond_product_price) {{number_format($cond_product_price, 2, '.', '')}} @endif">
                                    <span class="input-group-addon">Unid</span>
                                </div>
                            </div>
                        </div>
						<div class="spacer-10"></div>
                        <div class="row">
                            <div class="col-sm-3">
                                <label>Btus</label>
                            </div>
                            <div class="col-sm-9">
                                <input type="number" name="btus" value="{{$btus}}" class="form-control">
                            </div>
                        </div>
                        <div class="spacer-10"></div>
                        <div class="row">
                            <div class="col-sm-3">
                                <label>Regra: Tipo de cliente</label>
                            </div>
                            <div class="col-sm-9">
                                <select name="has_type_client" class="form-control">
                                    <option value="1" @if ($has_type_client) selected @endif>Aplicado</option>
                                    <option value="0" @if (!$has_type_client) selected @endif>Não Aplicável</option>
                                </select>
                            </div>
                        </div>
						<div class="spacer-10"></div>
                        <div class="row">
                            <div class="col-sm-3">
                                <label>Esconder produto</label>
                            </div>
                            <div class="col-sm-9">
                                <select name="is_for_hide" id="is_for_hide" class="form-control">
                                    <option value="1" @if ($is_for_hide == 1) selected @endif>Sim</option>
                                    <option value="2" @if ($is_for_hide == 2) selected @endif>Não</option>
                                </select>
                            </div>
                        </div>
						<div class="spacer-10 show_for_salesmans" @if (!$is_for_hide) style="display:none" @endif></div>
                        <div class="row show_for_salesmans" @if (!$is_for_hide) style="display:none" @endif>
                            <div class="col-sm-3">
                                <label>Quem irá ver?</label>
                            </div>
                            <div class="col-sm-9">
                                <select name="show_for_salesmans[]" style="width:100%" class="form-control js-select-salesman" multiple>
									@if ($show_for_salesmans->count())
										@foreach($salesmans as $salesman)
											<option value="{{$salesman->id}}" @if ($show_for_salesmans->search($salesman->id) !== false) selected @endif>{{$salesman->full_name}}</option>
										@endforeach
									@else
										@foreach($salesmans as $salesman)
											<option value="{{$salesman->id}}">{{$salesman->full_name}}</option>
										@endforeach
									@endif
                                </select>
                            </div>
                        </div>
                    </fieldset>
                </form>
            </div>
        </div>
    </div>
    <!-- End .inner-padding -->
</div>

<script>
    function save() {
        if ($('select[name="group"]').val() == null) {

            return $error('Escolha um grupo para o conjunto.');
        } else if ($('input[name="resume"]').val() == "") {

            return $error('Informe um nome resumido do conjunto.');
        } else if ($('select[name="evap"]').val() == null) {

            return $error('Escolha a unidade evaporadora.');
        } else if ($('input[name="evap_price"]').val() == "") {

            return $error('Informe o preço da unidade evaporadora.');
        } else if ($('select[name="cond"]').val() == null) {

            return $error('Escolha a unidade condensadora.');
        } else if ($('input[name="cond_price"]').val() == "") {

            return $error('Informe o preço da unidade condensadora.');
        }

        block();
        $("#sendForm").submit();
    }
    $(document).ready(function () {
		
		$('#is_for_hide').change(function(){
			if ($('#is_for_hide').val() == 1) {
				$('.show_for_salesmans').show();
			} else {
				$('.show_for_salesmans').hide();
			}
		});
		
        $(".js-select2").select2({
            language: {
                noResults: function () {

                    return 'Grupo não existe...';
                }
            },
            ajax: {
                url: '/commercial/product/group/dropdown',
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
            language: {
                noResults: function () {

                    return 'Produto não cadastro na engenharia...';
                }
            },
            ajax: {
                url: '/suporte/products',
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
		
		$(".js-select-salesman").select2({
			placeholder: "Ninguém",
            language: {
                noResults: function () {
                    return 'Representante não existe...';
                }
            }
        });

        $('input[name="cond_price"], input[name="evap_price"]').mask('0000.00', {reverse: true});

        $("#product").addClass('menu-open');
        $("#productSet").addClass('menu-open');
        $("#productSetEdit").addClass('page-arrow active-page');
    });
</script>

@endsection
