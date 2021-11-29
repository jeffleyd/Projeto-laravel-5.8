@extends('gree_i.layout')

@section('content')
<div class="content-overlay"></div>
    <div class="content-wrapper">
        <div class="content-header row">
            <div class="content-header-left col-12 mb-2 mt-1">
              <div class="row breadcrumbs-top">
                <div class="col-12">
                  <h5 class="content-header-title float-left pr-1 mb-0">Importação de arquivo</h5>
                  <div class="breadcrumb-wrapper col-12">
                    Lembre-se de mudar o "Action" para a função misc desejada.
                  </div>
                </div>
              </div>
            </div>
          </div>
        <div class="content-header row">
        </div>
        <div class="content-body">
            <form class="needs-validation" action="/misc/import/parts" id="submitEdit" method="post" enctype="multipart/form-data">
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
                                    <label for="product_category">Anexe seu CSV separado por virgula</label>
                                    <input type="file" name="csv" class="form-control">
                                </fieldset>
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

        });
    </script>
@endsection