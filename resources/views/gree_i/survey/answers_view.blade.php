@extends('gree_i.layout')

@section('content')
<div class="content-overlay"></div>
    <div class="content-wrapper">
        <div class="content-header row">
            <div class="content-header-left col-12 mb-2 mt-1">
              <div class="row breadcrumbs-top">
                <div class="col-12">
                  <h5 class="content-header-title float-left pr-1 mb-0">Pesquisas</h5>
                  <div class="breadcrumb-wrapper col-12">
                    Visualizando resposta
                  </div>
                </div>
              </div>
            </div>
          </div>
        <div class="content-header row">
        </div>
        <div class="content-body">
            <section class="request-payment">
                <div class="card">
                    <div class="card-header">
                      <h4 class="card-title">RESPOSTAS</h4>
                    </div>
                    <div class="card-content">
                      <div class="card-body">
                        <div class="row">
                            @foreach ($answer as $key)                            
                            <div class="col-md-12">
                                <fieldset class="form-group">
                                    <label for="disabledInput"><?= $key->title ?></label>
                                    <p class="form-control-static" id="staticInput">(<?= $key->answer_option ?>) <?= $key->answer_obs ?></p>
                                </fieldset>
                            </div>
                            @endforeach
                        </div>
                      </div>
                    </div>
                  </div>
                
            </section>
        </div>
    </div>
    <script>
        $(document).ready(function () {

            setInterval(() => {
                $("#mAdmin").addClass('sidebar-group-active active');
                $("#msurvey").addClass('active');
                
            }, 100);
        });
    </script>
@endsection