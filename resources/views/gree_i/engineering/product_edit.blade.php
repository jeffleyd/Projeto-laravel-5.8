@extends('gree_i.layout')

@section('content')
<link rel="stylesheet" href="/js/plugins/jquery-tags-input/jquery.tagsinput.min.css">
<style>
div.tagsinput span.tag {
    border: 1px solid #82300f !important;
    background: #e65012 !important;
    color: #f6f6f6 !important;
    padding: 2px !important;
    font-weight: 100;
}

div.tagsinput span.tag a {
    color: #ffffff !important;
}
</style>

<div class="content-overlay"></div>
    <div class="content-wrapper">
        <div class="content-header row">
            <div class="content-header-left col-12 mb-2 mt-1">
              <div class="row breadcrumbs-top">
                <div class="col-12">
                  <h5 class="content-header-title float-left pr-1 mb-0">Engenharia</h5>
                  <div class="breadcrumb-wrapper col-12">
                    @if ($id == 0)
                    Novo produto
                    @else
                    Atualizando produto
                    @endif
                  </div>
                </div>
              </div>
            </div>
          </div>
        <div class="content-header row">
        </div>
        <div class="content-body">
            <form class="needs-validation" action="/engineering/product/edit_do" id="submitEdit" method="post" enctype="multipart/form-data">
            <input type="hidden" name="id" id="id" value="<?= $id ?>">
            <section>
                <div class="card">
                    <div class="card-header">
                      <h4 class="card-title">DADOS DE SEGMENTO</h4>
                    </div>
                    <div class="card-content">
                      <div class="card-body">
                        <div class="row">

                            <div class="col-md-12">
                                <fieldset class="form-group">
                                    <label for="import">Fabricação</label>
                                    <select class="form-control" id="import" name="import">
                                        <option value="1" @if ($import == 1) selected="" @endif>Nacional</option>
                                        <option value="2" @if ($import == 2) selected="" @endif>Internacional</option>
                                    </select>
                                </fieldset>
                            </div>

                            <div class="col-md-12">
                                <fieldset class="form-group">
                                    <label for="product_category">Categoria do produto</label>
                                    <select class="form-control" id="product_category" name="product_category" required>
                                        <option value=""></option>
                                        @foreach ($product_category as $key)
                                        <option value="{{$key->id}}" @if ($product_category_id == $key->id) selected="" @endif>{{$key->name}}</option>
                                        @endforeach
                                    </select>
                                </fieldset>
                            </div>

                            <div class="col-md-12 sub_1" @if (!$product_sub_level_1_id) style="display:none;" @endif>
                                <fieldset class="form-group">
                                    <select class="form-control" id="product_sub_level_1" name="product_sub_level_1">
                                        <option value=""></option>
                                        @if ($product_sub_level_1_id)
                                        <option value="{{ $product_sub_level_1_id->id }}" selected="">{{ $product_sub_level_1_id->name }}</option>
                                        @endif
                                    </select>
                                </fieldset>
                            </div>

                            <div class="col-md-12 sub_2" @if (!$product_sub_level_2_id) style="display:none;" @endif>
                                <fieldset class="form-group">
                                    <select class="form-control" id="product_sub_level_2" name="product_sub_level_2">
                                        <option value=""></option>
                                        @if ($product_sub_level_2_id)
                                        <option value="{{ $product_sub_level_2_id->id }}" selected="">{{ $product_sub_level_2_id->name }}</option>
                                        @endif
                                    </select>
                                </fieldset>
                            </div>

                            <div class="col-md-12 sub_3" @if (!$product_sub_level_3_id) style="display:none;" @endif>
                                <fieldset class="form-group">
                                    <select class="form-control" id="product_sub_level_3" name="product_sub_level_3">
                                        <option value=""></option>
                                        @if ($product_sub_level_3_id)
                                        <option value="{{ $product_sub_level_3_id->id }}" selected="">{{ $product_sub_level_3_id->name }}</option>
                                        @endif
                                    </select>
                                </fieldset>
                            </div>

                            <div class="col-12">    
                                <label for="bank"></label>
                                <ul class="list-unstyled mb-0">
                                    <li class="d-inline-block mr-2 mb-1">
                                        <fieldset>
                                        <div class="checkbox">
                                            <input type="checkbox" class="checkbox-input" id="commercial" name="commercial" value="{{ $commercial }}" <?php if ($commercial == 1) { ?>checked=""<?php } ?>>
                                            <label for="commercial">Comercial</label>
                                        </div>
                                        </fieldset>
                                    </li>
                                    <li class="d-inline-block mr-2 mb-1">
                                        <fieldset>
                                        <div class="checkbox">
                                            <input type="checkbox" class="checkbox-input" id="residential" name="residential" value="{{ $residential }}" <?php if ($residential == 1) { ?>checked=""<?php } ?>>
                                            <label for="residential">Residencial</label>
                                        </div>
                                        </fieldset>
                                    </li>
                                </ul>    
                            </div>
                            
                        </div>
                      </div>
                    </div>
                  </div>
                
            </section>

            <section>
                <div class="card">
                    <div class="card-header">
                      <h4 class="card-title">DADOS DE FÁBRICA</h4>
                    </div>
                    <div class="card-content">
                      <div class="card-body">
                        <div class="row">

                            <div class="col-md-12">
                                <fieldset class="form-group">
                                    <label for="sales_code">Código de venda</label>
									<select class="form-control js-select22" id="sales_code" name="sales_code[]" data-placeholder="" multiple="true">
                                        @foreach ($sales_code as $key)
                                            <option value="<?= $key ?>" selected><?= $key ?></option>
                                        @endforeach
                                    </select>
                                </fieldset>
                            </div>

                            <!--<div class="col-md-12">
                                <fieldset class="form-group">
                                    <label for="code_unity">Código da unidade</label>
                                    <select class="form-control js-select22" id="code_unity" name="code_unity[]" data-placeholder="" multiple="true">
                                        @foreach ($code_unity as $key)
                                            <option value="<?= $key ?>" selected><?= $key ?></option>
                                        @endforeach
                                    </select>
                                </fieldset>
                            </div>-->

                            <div class="col-md-12">
                                <fieldset class="form-group">
                                    <label for="model">Modelo</label>
                                    <input type="text" style="text-transform: uppercase" class="form-control" name="model" id="model" value="{{ $model }}" required>
                                </fieldset>
                            </div>

                            <div class="col-md-12">
                                <fieldset class="form-group">
                                    <label for="dcr">DCR</label>
                                    <input type="text" style="text-transform: uppercase" class="form-control" name="dcr" id="dcr" value="{{ $dcr }}">
                                </fieldset>
                            </div>

                            <div class="col-md-12">
                                <fieldset class="form-group">
                                    <label for="bar_code">Código de barra</label>
                                    <input type="text" class="form-control" name="bar_code" id="bar_code" value="{{ $bar_code }}">
                                </fieldset>
                            </div>

                            <div class="col-md-12">
                                <fieldset class="form-group">
                                    <label for="serial_number">Número de série</label>
                                    <input type="text" class="form-control" name="serial_number" id="serial_number" value="{{ $serial_number }}" required>
                                </fieldset>
                            </div>

                            <div class="col-md-12">
                                <fieldset class="form-group">
                                    <label for="ncm">NCM</label>
                                    <input type="text" class="form-control" name="ncm" id="ncm" value="{{ $ncm }}">
                                </fieldset>
                            </div>

                            <div class="col-md-12">
                                <fieldset class="form-group">
                                    <label for="unity">Unidade</label>
                                    <select class="form-control" id="unity" name="unity" required>
                                        <option value=""></option>
                                        @foreach ($unity as $key)
                                        <option value="{{$key->id}}" @if ($unity_selected == $key->id) selected="" @endif>{{$key->name}}</option>
                                        @endforeach
                                    </select>
                                </fieldset>
                            </div>

                            <div class="col-md-12">
                                <fieldset class="form-group mt-1">
                                <label for="unity">Voltagem</label>
                                <ul class="list-unstyled mb-0">
                                    @foreach ($voltage as $key)
                                    <li class="d-inline-block mr-2 mb-1">
                                        <fieldset>
                                          <div class="radio radio-shadow">
                                              <input type="radio" id="voltage_{{ $key->id }}" value="{{ $key->id }}" name="voltage" @if ($pv) @if ($pv->voltage_id == $key->id) checked @endif @endif>
                                              <label for="voltage_{{ $key->id }}">{{ $key->name }}</label>
                                          </div>
                                        </fieldset>
                                    </li>
                                    @endforeach
                                </ul>
                                </fieldset>
                            </div>

                            <div class="col-md-12">
                                <fieldset class="form-group">
                                    <label for="net_weight">Peso liquido (KG)</label>
                                    <input type="text" class="form-control" name="net_weight" id="net_weight" value="@if ($net_weight) {{ number_format($net_weight, 2) }} @endif">
                                </fieldset>
                            </div>

                            <div class="col-md-12">
                                <fieldset class="form-group">
                                    <label for="gross_weight">Peso bruto (KG)</label>
                                    <input type="text" class="form-control" name="gross_weight" id="gross_weight" value="@if ($gross_weight) {{ number_format($gross_weight, 2) }} @endif">
                                </fieldset>
                            </div>

                            <div class="col-md-12">
                                <fieldset class="form-group">
                                    <label for="length">Comprimento (MM)</label>
                                    <input type="text" class="form-control" name="length" id="length" value="@if ($length) {{ number_format($length, 2) }} @endif">
                                </fieldset>
                            </div>

                            <div class="col-md-12">
                                <fieldset class="form-group">
                                    <label for="width">Largura (MM)</label>
                                    <input type="text" class="form-control" name="width" id="width" value="@if ($width) {{ number_format($width, 2) }} @endif">
                                </fieldset>
                            </div>

                            <div class="col-md-12">
                                <fieldset class="form-group">
                                    <label for="height">Altura (MM)</label>
                                    <input type="text" class="form-control" name="height" id="height" value="@if ($height) {{ number_format($height, 2) }} @endif">
                                </fieldset>
                            </div>
							
							<div class="col-md-12">
                                <fieldset class="form-group">
                                    <label for="length">Comprimento da caixa (MM)</label>
                                    <input type="text" class="form-control" name="length_box" id="length_box" value="@if ($length_box) {{ number_format($length_box, 2) }} @endif">
                                </fieldset>
                            </div>

                            <div class="col-md-12">
                                <fieldset class="form-group">
                                    <label for="width">Largura da caixa (MM)</label>
                                    <input type="text" class="form-control" name="width_box" id="width_box" value="@if ($width_box) {{ number_format($width_box, 2) }} @endif">
                                </fieldset>
                            </div>

                            <div class="col-md-12">
                                <fieldset class="form-group">
                                    <label for="height">Altura da caixa (MM)</label>
                                    <input type="text" class="form-control" name="height_box" id="height_box" value="@if ($height_box) {{ number_format($height_box, 2) }} @endif">
                                </fieldset>
                            </div>

                            <div class="col-md-12">
                                <fieldset class="form-group">
                                    <label for="description">Descrição</label>
                                    <textarea id="js-ckeditor" name="description" rows="6" id="description"> {{ $description }} </textarea>
                                </fieldset>
                            </div>

                            <div class="col-md-12">
                                <fieldset class="form-group">
                                    <label for="observation">Observação</label>
                                    <input type="text" class="form-control" name="observation" id="observation" value="{{ $observation }}">
                                </fieldset>
                            </div>

                            <div class="col-md-12">
                                <fieldset class="form-group">
                                    <label for="exploded_view">Vista explodida <small>Max 5mb de tamanho</small></label>
                                    <br><input type="file" name="exploded_view" id="exploded_view" value="{{ $exploded_view }}">
                                </fieldset>
                                @if ($exploded_view)<p><a href="{{ $exploded_view }}" target="_blank">Ver imagem</a></p>@endif
                            </div>

                            <div class="col-md-12">
                                <fieldset class="form-group">
                                    <label for="electric_circuit">Circuito elêtrico <small>Max 5mb de tamanho</small></label>
                                    <br><input type="file" name="electric_circuit" id="electric_circuit" value="{{ $electric_circuit }}">
                                </fieldset>
                                @if ($electric_circuit)<p><a href="{{ $electric_circuit }}" target="_blank">Ver imagem</a></p>@endif
                            </div>

                            <div class="col-md-12">
                                <fieldset class="form-group">
                                    <label for="manual">Manual <small>Max 5mb de tamanho</small></label>
                                    <br><input type="file" name="manual" id="manual" value="{{ $manual }}">
                                </fieldset>
                                @if ($manual)<p><a href="{{ $manual }}" target="_blank">Ver imagem</a></p>@endif
                            </div>

                            <div class="col-md-12">
                                <fieldset class="form-group">
                                    <label for="datasheet">Ficha técnica <small>Max 5mb de tamanho</small></label>
                                    <br><input type="file" name="datasheet" id="datasheet" value="{{ $datasheet }}">
                                </fieldset>
                                @if ($datasheet)<p><a href="{{ $datasheet }}" target="_blank">Ver imagem</a></p>@endif
                            </div>

                            <div class="col-md-12">
                                <fieldset class="form-group">
                                    <label for="parts">Lista de peças em PDF <small>Max 5mb de tamanho</small></label>
                                    <br><input type="file" name="parts" id="parts" value="{{ $parts }}">
                                </fieldset>
                                @if ($parts)<p><a href="{{ $parts }}" target="_blank">Ver imagem</a></p>@endif
                            </div>
                            
                        </div>
                      </div>
                    </div>
                  </div>
                
            </section>

            <button type="submit" style="width:100%" class="btn btn-primary">@if ($id == 0)
                Criar produto  
                @else
                Atualizar produto
                @endif
            </button>

        </form>
        </div>
    </div>
    <script src="/ckeditor/ckeditor.js"></script>
    <script src="/ckeditor/init.js"></script>
    <script>
		var arrayCopy = new Array();
		function onChangeTags() {
			arrayCopy = new Array();
			var data = $('.js-tags-input').tagsInput()[0]['value'];
			var str_array = data.split(',');

			for(var i = 0; i < str_array.length; i++) {
				// Trim the excess whitespace.
				str_array[i] = str_array[i].replace(/^\s*/, "").replace(/\s*$/, "");
				// Add additional code here, such as:
				arrayCopy.push({
					'code': str_array[i],
				});
			}

			$("#data_code_unity").val(JSON.stringify(arrayCopy));
		}
        $(document).ready(function () {
			
			$(".js-select22").select2({
                tags: true,
                tokenSeparators: [",", " "],
                language: {
                    noResults: function () {
                        return 'Digite o(s) código(s) de venda(s) e aperte ENTER';
                    }
                }
            });

            ckeditorInit('#js-ckeditor');

            $("#submitEdit").submit(function (e) { 
                var form = $(".needs-validation");
                if (form[0].checkValidity() === false) {
                    e.preventDefault();
                } else {
                    block();
                }             
            });
			
			$('.js-tags-input').tagsInput({
				'height':'34px',
				'width':'100%',
				'defaultText':'',
				'onChange' : onChangeTags
			});

            $("#product_category").change(function (e) { 
                $("#product_sub_level_1").load("/misc/product/category/1/"+ $("#product_category").val(), function (response, status, request) {
                    $(".sub_1").show();
                    $(".sub_2").hide();
                    $(".sub_3").hide();
                });
                
            });

            $("#product_sub_level_1").change(function (e) { 
                $("#product_sub_level_2").load("/misc/product/category/2/"+ $("#product_sub_level_1").val(), function (response, status, request) {
                    $(".sub_1").show();
                    if ($("#product_sub_level_2").html() != '<option value="0"></option>') {
                        $(".sub_2").show();
                    } else {
                        $(".sub_2").hide();
                    }
                    $(".sub_3").hide();
                });
                
            });

            $("#product_sub_level_2").change(function (e) { 
                $("#product_sub_level_3").load("/misc/product/category/3/"+ $("#product_sub_level_2").val(), function (response, status, request) {
                    $(".sub_1").show();
                    $(".sub_2").show();
                    if ($("#product_sub_level_3").html() != '<option value="0"></option>') {
                        $(".sub_3").show();
                    } else {
                        $(".sub_3").hide();
                    }
                });
                
            });

            $("#residential, #commercial").change(function(){
                if($(this).is(":checked")){
                    $(this).val(1);
                } else {
                    $(this).val("");
                }
            });

            $('#net_weight, #gross_weight, #length, #width, #height, #length_box, #width_box, #height_box').mask('##0.00', {reverse: true});

            setInterval(() => {
                $("#mIndustrial").addClass('sidebar-group-active active');
                $("#mEngineering").addClass('sidebar-group-active active');
                $("#mEngineeringNewItem").addClass('active');
                
            }, 100);
        });
    </script>
@endsection