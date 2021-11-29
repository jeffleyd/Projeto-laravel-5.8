@extends('gree_i.layout')

@section('content')
<div class="content-overlay"></div>
<div class="content-wrapper">
    <div class="content-header row">
        <div class="content-header-left col-12 mb-2 mt-1">
          <div class="row breadcrumbs-top">
            <div class="col-12">
              <h5 class="content-header-title float-left pr-1 mb-0">Editando rota #<?= $trip_plan->id ?></h5>
              <div class="breadcrumb-wrapper col-12">
                Atualizando informações
              </div>
            </div>
          </div>
        </div>
      </div>

      <div class="alert alert-danger alert-dismissible mb-2" role="alert">
        <div class="d-flex align-items-center">
            <i class="bx bx-error"></i>
            <span>
                Para situações que precisar enviar mais de 1 PDF, use esse site para mesclar os PDFs <a target="_blank" href="https://www.ilovepdf.com/pt/juntar_pdf">https://www.ilovepdf.com/pt/juntar_pdf</a>
            </span>
        </div>
    </div>
    <div class="content-header row">
    </div>
    <div class="content-body">
        <form action="/trip/edit/route_do/<?= $trip_plan->id ?>" id="mng_route" method="post" enctype="multipart/form-data">
        <div class="card">
            <div class="card-content">
                <div class="card-body">
                    <div class="row">
                        <?php if (count($peoples) > 0) { ?>
                            <div class="col-md-12">
                                <h6>Pessoas adicionais</h6>
                            </div>
            
                            <div class="col-md-12">
                                <table class="table table-bordered table-striped table-vcenter">
                                    <thead>
                                        <tr>
                                            <th>Nome</th>
                                            <th>Bilhetes</th>
                                            <th></th>
                                            <th></th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php foreach ($peoples as $key) { ?>
                                            <tr>
                                                <td><?= $key->name ?></td>
                                                <form id="mng_people" action="/trip/edit/route/peoples_do/<?= $trip_plan->id ?>" method="post" enctype="multipart/form-data">
                                                <td>
                                                    <?php if (!empty($key->ticket_url)) { ?>
                                                        <a target="_blank" href="<?= $key->ticket_url ?>">
                                                            <button type="button" class="btn btn-sm btn-outline-primary mb-10">Ver arquivo</button>
                                                        </a>
                                                    <?php } ?>
                                                </td>
                                                <td>
                                                    <input type="hidden" name="identity" value="<?= $key->identity ?>">
                                                    <input type="file" id="people" name="people">
                                                </td>
                                                <td>
                                                    <button type="submit" class="btn btn-sm btn-success mb-10">Atualizar</button>
                                                </td>
                                                </form>
                                            </tr>
                                        <?php } ?>
                                    </tbody>
                                </table>
                            </div>
                            <?php } ?>
                            <div class="col-12 mt-1 text-center">
                                <ul class="list-unstyled mb-0">
                                    <li class="d-inline-block mr-2 mb-1">
                                      <fieldset>
                                        <div class="custom-control custom-radio">
                                          <input type="radio" class="custom-control-input" name="is_notify" id="nt_yes" value="1">
                                          <label class="custom-control-label" for="nt_yes">Notificar solicitante</label>
                                        </div>
                                      </fieldset>
                                    </li>
                                    <li class="d-inline-block mr-2 mb-1">
                                      <fieldset>
                                        <div class="custom-control custom-radio">
                                          <input type="radio" class="custom-control-input" name="is_notify" id="nt_no" value="0" checked="">
                                          <label class="custom-control-label" for="nt_no">Não notificar solicitante</label>
                                        </div>
                                      </fieldset>
                                    </li>
                                  </ul>
                            </div>
                            <div class="col-md-12">
                                <div class="form-group">
                                    <label>Status</label>
                                    <select class="form-control" id="status" name="status">
                                        <option value="">Escolha</option>
                                        <option value="1">Concluir</option>
                                        <option value="2">Cancelar</option>
                                        <option value="3">Torna como crédito</option>
                                    </select>
                                </div>
                            </div>
            
                            <div class="col-md-12">
                                <h6>Arquivos do solicitante</h6>
                            </div>
            
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label>Bilhete de passagem</label>
                                    <input type="file" name="ticket" class="form-control" id="ticket">
                                </div>
                                <?php if (isset($trip_budget)) { ?>
                                    <a target="_blank" href="<?= $trip_budget->ticket_url ?>">
                                        <button type="button" class="btn btn-sm btn-outline-info mb-10">Ver arquivo enviado</button>
                                    </a>
                                <?php } ?>
                            </div>
            
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label>Bilhete de hotel</label>
                                    <input type="file" name="hotel" class="form-control" id="hotel">
                                </div>
                                <?php if (isset($trip_budget)) { ?>
                                    <a target="_blank" href="<?= $trip_budget->ticket_hotel ?>">
                                        <button type="button" class="btn btn-sm btn-outline-info mb-10">Ver arquivo enviado</button>
                                    </a>
                                <?php } ?>
                            </div>
            
                            <div class="col-md-12">
                                <div class="form-group">
                                    <label>Total pago</label>
                                    <input type="text" name="total" class="form-control" id="total" <?php if (isset($trip_budget)) { ?>value="<?= number_format($trip_budget->total, 2, ".", "") ?>"<?php } ?>>
                                </div>
                                <?php if (isset($trip_budget)) { ?>
                                    Último valor: <?= $trip_budget->total ?>
                                <?php } ?>
                            </div>
            
                        </div>

                        <div class="col-md-12 mt-1">
                            <button type="submit" class="btn btn-square btn-primary" style="width: 100%;">Atualizar informações</button>
                        </div>
            
                    </div>
                        
                </div>
            </div>
        </div>
    </div>
</div>
</form>

    <script>
    $(document).ready(function () {

        $('#mng_route').submit(function (e) { 
            block();
        });

        $('#mng_people').submit(function (e) { 
            block();
            
        });

        $('#total').mask('00000.00', {reverse: true});
        setInterval(() => {
            $("#mAdmin").addClass('sidebar-group-active active');
            $("#mTrip").addClass('sidebar-group-active active');
            $("#mTripViewApprov").addClass('active');
        }, 100);

    });
    </script>
@endsection