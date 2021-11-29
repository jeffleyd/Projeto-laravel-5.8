@extends('gree_i.layout')

@section('content')
<div class="content-overlay"></div>
    <div class="content-wrapper">
        <div class="content-header row">
            <div class="content-header-left col-12 mb-2 mt-1">
              <div class="row breadcrumbs-top">
                <div class="col-12">
                  <h5 class="content-header-title float-left pr-1 mb-0">Engenharia</h5>
                  <div class="breadcrumb-wrapper col-12">
                    @if ($id == 0)
                    Nova peça
                    @else
                    Atualizando peça
                    @endif
                  </div>
                </div>
              </div>
            </div>
          </div>
        <div class="content-header row">
        </div>
        <div class="content-body">
            <form class="needs-validation" action="/engineering/part/edit_do" id="submitEdit" method="post" enctype="multipart/form-data">
            <input type="hidden" name="id" id="id" value="<?= $id ?>">
            <section>
                <div class="card">
                    <div class="card-header">
                      <h4 class="card-title">PRODUTO VINCULADO</h4>
                    </div>
                    <div class="card-content">
                      <div class="card-body">
                        <div class="row">

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

                            <div class="col-md-12 product">
                                <fieldset class="form-group">
                                    <select class="js-select2 form-control" id="product" name="product[]" style="width: 100%;" data-placeholder="Pesquise modelo..." multiple>
                                        <option></option>
                                        @if (!empty($product))
                                            @foreach ($product as $key)
                                                <option value="{{ $key->id }}">{{ $key->model }} ( {{ $key->voltages_name }} )</option>
                                            @endforeach
                                        @endif
                                    </select>
                                </fieldset>
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
                                    <label for="code">Código</label>
                                    <input type="text" class="form-control" name="code" id="code" value="{{ $code }}" required>
                                </fieldset>
                            </div>

                            <div class="col-md-12">
                                <fieldset class="form-group">
                                    <label for="description">Descrição</label>
                                    <input type="text" class="form-control" name="description" id="description" value="{{ $description }}" required>
                                </fieldset>
                            </div>

                            <div class="col-md-12">
                                <fieldset class="form-group">
                                    <label for="ncm">NCM</label>
                                    <input type="text" style="text-transform: uppercase" class="form-control" name="ncm" id="ncm" value="{{ $ncm }}">
                                </fieldset>
                            </div>

                            <div class="col-md-12">
                                <fieldset class="form-group">
                                    <label for="cest">CEST</label>
                                    <input type="text" style="text-transform: uppercase" class="form-control" name="cest" id="cest" value="{{ $cest }}">
                                </fieldset>
                            </div>

                            <div class="col-md-12">
                                <label for="amount">Valor da unidade</label>
                                <fieldset class="mb-1">
                                    <div class="input-group">
                                        <div class="input-group-prepend">
                                            <span class="input-group-text">R$</span>
                                        </div>
                                        <input type="text" class="form-control" name="amount" id="amount" value="@if ($amount) {{ number_format($amount, 2) }} @else 0.00 @endif">
                                    </div>
                                </fieldset>
                            </div>

                            <div class="col-md-12">
                                <fieldset class="form-group">
                                    <label for="dcr">Armazém</label>
                                    <input type="text" class="form-control" name="warehouse" id="warehouse" value="{{ $warehouse }}">
                                </fieldset>
                            </div>

                            <div class="col-md-12">
                                <fieldset class="form-group">
                                    <label for="observation">Observação</label>
                                    <input type="text" class="form-control" name="observation" id="observation" value="{{ $observation }}">
                                </fieldset>
                            </div>
                            
                        </div>
                      </div>
                    </div>
                  </div>
                
            </section>

            <button type="submit" style="width:100%" class="btn btn-primary">@if ($id == 0)
                Criar peça  
                @else
                Atualizar peça
                @endif
            </button>

        </form>
        </div>
    </div>
    <script>
        $(document).ready(function () {
            $(".js-select2").select2();
            $("#submitEdit").submit(function (e) { 
                var form = $(".needs-validation");
                if (form[0].checkValidity() === false) {
                    e.preventDefault();
                } else {
                    block();
                }             
            });

            $("#product_category").change(function (e) { 
                $("#product").load("/misc/product/part/"+ $("#product_category").val(), function (response, status, request) {
                    $(".product").show();
                });
                
            });
            
            <?php if (!empty($product)) { ?>
                $("#product").load("/misc/product/part/{{ $product_category_id }}", function (response, status, request) {
                    $(".product").show();
                    $('.js-select2').val([
                    <?php foreach ($product as $key) { ?>
                        '<?= $key->id ?>',
                    <?php } ?>
                    ]).trigger('change');
                });
            <?php } ?>

            $('#amount').mask('##0.00', {reverse: true});

            setInterval(() => {
                $("#mIndustrial").addClass('sidebar-group-active active');
                $("#mEngineering").addClass('sidebar-group-active active');
                $("#mEngineeringNewPart").addClass('active');
                
            }, 100);
        });
    </script>
@endsection