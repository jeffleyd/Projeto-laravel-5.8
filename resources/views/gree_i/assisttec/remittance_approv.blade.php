@extends('gree_i.layout')

@section('content')
<style>
    .table th, .table td {
        padding: 1.10rem 0.7rem;
    }
    .div-cost-process:hover {
        background-color: #dfe3e7;
        cursor: pointer;
    }
</style>  
<div class="content-overlay"></div>
<div class="content-wrapper">
    <div class="content-header row">
        <div class="content-header-left col-12 mb-2 mt-1">
          <div class="row breadcrumbs-top">
            <div class="col-12">
              <h5 class="content-header-title float-left pr-1 mb-0">Assitência Técnica</h5>
              <div class="breadcrumb-wrapper col-12">
                Aprovação de remessa
              </div>
            </div>
          </div>
        </div>
      </div>
    <div class="content-header row">
    </div>
    <div class="content-body">
        
        @if(Session::has('partAlert'.$remittance->id) && Session::get('partAlert'.$remittance->id))
        
        <div class="alert alert-danger alert-dismissible mb-2" role="alert">
            <div class="d-flex align-items-center">
              <i class="bx bx-error"></i>
              <span>Necessário aprovar ou reprovar todas as peças, para que seja enviada à Expedição!</span>
            </div>
        </div>
        @endif
        

        <div class="row">
            <div class="col-md-3">
                <div class="card text-center">
                    <a href="{{ $remittance->remittance_note }}" target="_blank">
                        <div class="card-content div-cost-process">
                            <div class="card-body py-1">
                                <h6 class="mb-0">
                                    <i class="bx bx-receipt font-medium-5" style="top: 3px;position: relative; color: #3568df;"></i> 
                                    Nota de remessa<i class="bx bx-link-external" style="font-size: 0.7rem; bottom: 6px; position: relative;"></i>
                                </h6>
                            </div>
                        </div>
                    </a>
                </div>
            </div>
            <div class="col-md-3">
                <div class="card text-center">
                    <a href="{{ $remittance->diagnostic_file }}" target="_blank">
                        <div class="card-content div-cost-process">
                            <div class="card-body py-1">
                                <h6 class="mb-0">
                                    <i class="bx bx-receipt font-medium-5" style="top: 3px;position: relative; color: #3568df;"></i> 
                                    Relatório técnico<i class="bx bx-link-external" style="font-size: 0.7rem; bottom: 6px; position: relative;"></i>
                                </h6>
                            </div>
                        </div>
                    </a>
                </div>
            </div>
            <div class="col-md-3">
                <div class="card text-center">
                    @if($remittance->purchase_origin_note != null)
                    <a href="{{ $remittance->purchase_origin_note }}" target="_blank">
                        <div class="card-content div-cost-process">
                            <div class="card-body py-1">
                                <h6 class="mb-0">
                                    <i class="bx bx-file font-medium-5" style="top: 3px;position: relative; color: #3568df;"></i> 
                                    Nota de compra<i class="bx bx-link-external" style="font-size: 0.7rem; bottom: 6px; position: relative;"></i>
                                </h6>
                            </div>
                        </div>
                    </a>  
                    @else
                    <div class="card-content div-cost-process" style="padding-bottom: 2.5px;padding-top: 2.5px;">
                        <div class="card-body py-1">
                            <h6 class="mb-0">
                                Nota de compra <small>(Não anexado!)</small>
                            </h6>
                        </div>
                    </div>
                    @endif
                </div>    
            </div>
            <div class="col-md-3">
                <div class="card text-center">
                    @if($remittance->photo_tag != null)
                    <a href="{{ $remittance->photo_tag }}" target="_blank">
                        <div class="card-content div-cost-process">
                            <div class="card-body py-1">
                                <h6 class="mb-0">
                                    <i class="bx bx-file font-medium-5" style="top: 3px;position: relative; color: #3568df;"></i> 
                                    Foto da etiqueta<i class="bx bx-link-external" style="font-size: 0.7rem; bottom: 6px; position: relative;"></i>
                                </h6>
                            </div>
                        </div>
                    </a>  
                    @else
                    <div class="card-content div-cost-process" style="padding-bottom: 2.5px;padding-top: 2.5px;">
                        <div class="card-body py-1">
                            <h6 class="mb-0">
                                Foto da etiqueta <small>(Não anexado!)</small>
                            </h6>
                        </div>
                    </div>
                    @endif
                </div>    
            </div>
        </div> 
		<form action="/sac/assistance/remittance/approv_do" id="sendApprov" method="post">
		<section>
            <div class="row">
                <div class="col-12">
                    <div class="card">
                        <div class="card-header">
                            <h4 class="card-title">
                                Informações adicionais
                            </h4>
                        </div>
                        <div class="card-content">
                            <div class="card-body">
                                <div class="col-md-12 col-sm-12 form-group">
                                    <label for="paid_info">Terá algum acrescimo de pagamento além dos serviços abaixo?</label>
                                    <input type="text" name="paid_info" value="{{$remittance->payment_info}}" class="form-control">
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </section>

        <section class="users-list-wrapper">
            <div class="users-list-table">
                <div class="card">
                    <div class="card-content">       
                        <div class="card-body">
                            <div class="top d-flex flex-wrap">
                                <div class="action-filters flex-grow-1">
                                    <div class="mt-1">
                                        <b>SOLICITAÇÃO {{$remittance->code}}</b> - <span style="font-weight: 400;"><a href="/sac/authorized/edit/<?= $remittance->authorized_id ?>" target="_blank"><?= $remittance->sac_authorized->name ?></a></span>
                                    </div>
                                </div>
                            </div>
                            <hr>
                                <input type="hidden" name="is_approv" id="is_approv">
                                <input type="hidden" name="remittance_id" value="{{$remittance->id}}">
                                <div class="table-responsive">
                                    <table id="list-datatable" class="table">
                                        <thead>
                                            <tr>
                                                <th>#</th>
                                                <th>Modelo</th>
                                                <th>Peça</th>
                                                <th>Código</th>
                                                <th>Quantidade</th>
                                                <th>Motivo de Solicitação</th>
                                                <th>Valor serviço</th>
                                                <th>Status</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @foreach ($remitance_parts as $key)
                                            <tr>
                                                @if($key->is_repprov == 0 && $key->is_approv == 0)
                                                <td class="text-center">
                                                    <div class="checkbox"><input type="checkbox" class="checkbox-input" id="check_<?= $key->id ?>" name="check[]" value="<?= $key->id ?>">
                                                        <label for="check_<?= $key->id ?>"></label>
                                                    </div>
                                                </td>
                                                @else 
                                                <td>-</td>
                                                @endif
                                                <td>@if(isset($key->product_air['model'])) {{ $key->product_air['model'] }} @else {{ $key->model }} @endif</td>
                                                <td>@if(isset($key->parts['description'])) {{ $key->parts['description'] }} @else {{ $key->part }} @endif</td>
                                                <td>@if(isset($key->parts['code'])) {{ $key->parts['code'] }} @else - @endif</td>
                                                <td class="text-center">{{ $key->quantity }}</td>
                                                <td>
                                                    <span data-toggle="tooltip" data-placement="left" title="{{ $key->description_order_part }}" style="cursor: pointer;">
                                                        <?= stringCut($key->description_order_part, 35) ?>
                                                        <i class="bx bx-info-circle" style="color: #3568df;"></i>
                                                    </span>
                                                </td>
                                                <td>
                                                    @if($key->is_repprov == 0 && $key->is_approv == 0)
                                                        <input type="text" class="form-control money" name="service[]" style="width: 60%;" placeholder="R$ 0,00">
                                                    @else
                                                         R$ {{number_format($key->service_value, 2,",",".")}}    
                                                    @endif
                                                </td>
                                                <td>
                                                    @if($key->is_repprov == 1)
                                                        <div class="badge badge-light-danger">Reprovado</div>
                                                    @endif    
                                                    @if($key->is_approv == 1)     
                                                        <div class="badge badge-light-success">Aprovado</div>
                                                    @endif    
                                                <td>
                                            </tr>
                                            @endforeach
                                        </tbody>
                                    </table>
                                </div>
                            
                        </div>
                    </div>
                </div>
            </div>
        </section>
			@if($remittance->is_paid != 1 && $remittance->status != 1)
				<input type="hidden" name="is_paid_info" value="1">
				<button type="button" class="btn btn-primary mr-1" value="1" style="width: 100%;" id="btn_upd_info">Atualizar</button> 
			@endif
	</form>
    </div>
</div>

@if($remittance->status == 1)
<div class="mb-2 cursor-pointer" id="showAnalyze" style="position: fixed;left: 0;right: 0;bottom: 0;margin: 0 auto; z-index: 99; text-align: center;">
    <i class="bx bx-up-arrow-alt"></i>
    <br>Aprovar ou reprovar
</div>
<div class="card text-center" id="Analyze" style="width: 395px; position: fixed;left: 0;right: 0;bottom: 0;margin: 0 auto; opacity: 0.9;z-index: 99;">
    <div class="card-content">
        <button type="button" id="HAnalyze" class="close HideAnalyze" aria-label="Close"><i class="bx bx-x"></i></button>
        <div class="card-body">
            <div class="row">
                <p class="text-center">Para aprovar ou reprovar selecione a(s) peça(s)</p>
                <div class="col-sm-12 d-flex justify-content-center">
                    <button type="button" class="btn btn-success mr-1 btn_is_approv" value="1">{{ __('trip_i.trc_7') }}</button> 
                    <button type="button" class="btn btn-danger btn_is_approv" value="2">{{ __('trip_i.trc_8') }}</button> 
                </div>
            </div>
        </div>
    </div>
</div>
@endif

<script>
    $(document).ready(function () {
		
		$("#btn_upd_info").click(function() {
			block();
			$("#sendApprov").unbind().submit();
		});

        $(".btn_is_approv").click(function(){

            var desc_approv = "";
            if($(this).val() == 1) {
                desc_approv = "aprovação";
                $("#is_approv").val(1);
            } else {
                desc_approv = "reprovação";
                $("#is_approv").val(2);
            }

            var total_part = $(':checkbox[name="check[]"]:checked').length;
            var verify = 0;

            console.log(total_part);

            if (total_part == 0) {
                return $error('Selecione ao menos uma peça!');
            } else { 

                $(':checkbox[name="check[]"]:checked').each(function(){
                    
                    var service = $(this).closest('tr').find("input[type='text']").val();
                    var model = $(this).closest('tr').find('td').eq(1).text();

                    if (service == "") {
                        return $error('Informe o VALOR SERVIÇO do model: '+model+'');
                    } else {
                        verify += 1;
                    }
                });

                if(verify == total_part) {
                    Swal.fire({
                        title: 'Confirmar '+ desc_approv +'',
                        text: "Deseja confirmar esta ação?",
                        type: 'warning',
                        showCancelButton: true,
                        confirmButtonColor: '#3085d6',
                        cancelButtonColor: '#d33',
                        confirmButtonText: 'Confirmar',
                        cancelButtonText: 'Cancelar',
                        confirmButtonClass: 'btn btn-primary',
                        cancelButtonClass: 'btn btn-danger ml-1',
                        buttonsStyling: false,
                    }).then(function (result) {
                        if (result.value) {
							$("#sendApprov").submit(function (e) {
								if ($(':checkbox[name="check[]"]:checked').length == 0) {
									e.preventDefault();
									return $error('Selecione ao menos 1');
								}
								block();
							});
							
                            $("#sendApprov").unbind().submit();
                            Codebase.loader('show', 'bg-gd-sea');
                        }
                    });
                }    
            }
        });
        
        $("#sendApprov").submit(function (e) {
            if ($(':checkbox[name="check[]"]:checked').length == 0) {
                e.preventDefault();
                return $error('Selecione ao menos 1');
            }
            block();
        });

        $("#HAnalyze").click(function (e) { 
            $("#Analyze").hide();
        
        });

        $("#showAnalyze").click(function (e) { 
            $("#Analyze").show();
        });

        $('.money').mask('000.000,00', {reverse: true});

        setInterval(() => {
            $("#mAfterSales").addClass('sidebar-group-active active');
            $("#mTAssist").addClass('sidebar-group-active active');
            $("#mTAssistRemittance").addClass('active');
        }, 100);

    });
    </script>
@endsection
