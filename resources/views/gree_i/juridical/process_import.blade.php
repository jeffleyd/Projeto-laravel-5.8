@extends('gree_i.layout')

@section('content')
<div class="content-overlay"></div>
<div class="content-wrapper">
  <div class="content-header row">
    <div class="content-header-left col-12 mb-2 mt-1">
      <div class="row breadcrumbs-top">
        <div class="col-12">
          <h5 class="content-header-title float-left pr-1 mb-0">Jurídico</h5>
          <div class="breadcrumb-wrapper col-12">
            Importação de Processos
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
          Os dados devem corresponder a SEARA e ESCRITÓRIO selecionados. <a targe="_blank" href="/excell/model_process_juridical.xlsx" style="color:#ebef07;">Modelo de importação <i class="bx bxs-download"></i></a>
        </span>
    </div>
  </div>
  <div class="content-body">
    <form class="needs-validation" action="/juridical/process/import_do" id="submitEdit" method="post" enctype="multipart/form-data">
      <section>
        <div class="card">
          <div class="card-header">
            <h4 class="card-title">SELECIONE PARA IMPORTAR</h4>
          </div>
          <div class="card-content">
            <div class="card-body">
              <div class="row">
                <div class="col-md-12">
                    <fieldset class="form-group">
                        <label for="type_process">Seara</label>
                        <select class="form-control" id="type_process" name="type_process">
                            <option value="" disabled selected>Selecione</option>
                            <option value="1">Consumidor</option>
                            <option value="2">Trabalhista</option>
                            <option value="3">Cível</option>
                            <option value="4">Penal</option>
                            <option value="5">Tributário</option>
                            <option value="6">Adminstrativo</option>
                        </select>
                    </fieldset>
                </div>
                <div class="col-md-12">
                  <fieldset class="form-group">
                        <label for="=law_firm">Escritório</label>
                        <select class="form-control" id="law_firm" name="law_firm" required>
                                <option value="" disabled selected>Selecione</option>
                                @foreach ($law_firm as $key)
                                    <option value="{{ $key->id }}">{{ $key->name }}</option>
                                @endforeach
                        </select>
                  </fieldset>
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
    
    $("#submitEdit").submit(function (e) { 
      block();
    });

    setInterval(() => {
        $("#mJuridical").addClass('sidebar-group-active active');
        //$("#mEngineering").addClass('sidebar-group-active active');
        $("#mJuridicalImportProcess").addClass('active');
    }, 100);
  });
</script>
@endsection