@extends('gree_i.layout')

@section('content')
<div class="content-overlay"></div>
<div class="content-wrapper">
  <div class="content-header row">
    <div class="content-header-left col-12 mb-2 mt-1">
      <div class="row breadcrumbs-top">
        <div class="col-12">
          <h5 class="content-header-title float-left pr-1 mb-0">Importação de Peças</h5>
          <div class="breadcrumb-wrapper col-12">
            Modelo para importação de Peças: 
            <a targe="_blank" href="/excell/pecas_importacao_modelo.xlsx">Modelo Padrão</a>
          </div>
        </div>
      </div>
    </div>
  </div>
  <div class="content-header row"></div>
  <div class="alert alert-danger alert-dismissible mb-2" role="alert">
    <div class="d-flex align-items-center">
        <i class="bx bx-error"></i>
        <span>
          As peças do arquivo para importação devem ser da mesma categoria e subcategorias selecionadas abaixo.
        </span>
    </div>
    <small>Modelo de importação: A coluna modelo deve ser a mesma relacionada a peça.</small>
  </div>
  <div class="content-body">
    <form class="needs-validation" action="/engineering/part/import_do" id="submitEdit" method="post" enctype="multipart/form-data">
      <section>
        <div class="card">
          <div class="card-header">
            <h4 class="card-title">INFORMAÇÕES</h4>
          </div>
          <div class="card-content">
            <div class="card-body">
              <div class="row">
                <div class="col-md-12">
                  <fieldset class="form-group">
                      <label for="product_category">Categoria da peça</label>
                      <select class="form-control" id="product_category" name="product_category" required>
                          <option value=""></option>
                          @foreach ($product_category as $key)
                          <option value="{{$key->id}}">{{$key->name}}</option>
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
                <div class="col-md-12">
                  <fieldset class="form-group">
                      <label for="unity">Unidade</label>
                      <select class="form-control" id="unity" name="unity" required>
                          <option value=""></option>
                          @foreach ($unity as $key)
                          <option value="{{$key->id}}">{{$key->name}}</option>
                          @endforeach
                      </select>
                  </fieldset>
                </div>  
                <div class="col-12">    
                  <label for="bank">Tipo de linha</label>
                  <ul class="list-unstyled mb-0">
                      <li class="d-inline-block mr-2 mb-1">
                          <fieldset>
                          <div class="checkbox">
                              <input type="checkbox" class="checkbox-input" id="commercial" name="commercial" value="1">
                              <label for="commercial">Comercial</label>
                          </div>
                          </fieldset>
                      </li>
                      <li class="d-inline-block mr-2 mb-1">
                          <fieldset>
                          <div class="checkbox">
                              <input type="checkbox" class="checkbox-input" id="residential" name="residential" value="1">
                              <label for="residential">Residencial</label>
                          </div>
                          </fieldset>
                      </li>
                  </ul>    
                  <small>Caso selecione os dois tipos de linha, tenha certeza que todas as peças na planilha pertençam a estes dois grupos.</small>
                </div>       
                <div class="col-md-12">
                  <br>
                  <fieldset class="form-group">
                    <label for="product_category">Anexe seu arquivo (csv, xlsx, xls)</label>
                    <input type="file" name="attach" class="form-control" accept=".csv, application/vnd.openxmlformats-officedocument.spreadsheetml.sheet, application/vnd.ms-excel" required/>
                  </fieldset>
                  <small>Tamanho máximo do arquivo 1MB.</small>
                </div>
              </div>
            </div>
          </div>
        </div>
      </section>
      <button type="submit" style="width:100%" class="btn btn-primary">Enviar arquivo</button>
    </form>
  </div>
</div>
<script>
  $(document).ready(function () {
    
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

    $("#submitEdit").submit(function (e) { 
      block();
    });

    setInterval(() => {
        $("#mIndustrial").addClass('sidebar-group-active active');
        $("#mEngineering").addClass('sidebar-group-active active');
        $("#mEngineeringImportPart").addClass('active');
    }, 100);
  });
</script>
@endsection