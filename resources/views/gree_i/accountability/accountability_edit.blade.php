@extends('gree_i.layout')

@section('content')
<link rel="stylesheet" type="text/css" href="/admin/app-assets/vendors/css/pickers/pickadate/pickadate.css">
<style>
@media (max-width: 600px) {
  .sumary {
    position: inherit !important;
  }
}

/* @media (min-width: 600px) {
  .sumary {
    position: fixed !important;
  }
} */
</style>
<link rel="stylesheet" type="text/css" href="/admin/app-assets/vendors/css/extensions/shepherd-theme-default.css">
<link rel="stylesheet" type="text/css" href="/admin/app-assets/css/plugins/tour/tour.min.css">
<form action="/financy/accountability/item/delete" id="f_delete_item" method="POST">
	<input type="hidden" id="item_id" name="item_id" value="0">
</form>
<div class="content-overlay"></div>
      <div class="content-wrapper">
        <div class="content-header row">
          <div class="content-header-left col-12 mb-2 mt-1">
            <div class="row breadcrumbs-top">
              <div class="col-12">
                <h5 class="content-header-title float-left pr-1 mb-0">Prestação de Contas</h5>
                <div class="breadcrumb-wrapper col-12">
                    @if ($id == 0)
                    Nova prestação de Contas
                    @else
                    Atualizando Prestação de Contas
                    @endif
                </div>
              </div>
            </div>
        </div>
    </div>
    @if ($has_analyze == 0 and $has_approv_or_repprov == 0)
    <div class="alert alert-primary alert-dismissible mb-2" role="alert">

        <div class="d-flex align-items-center">
            <div style="width:60%">
                <i class="bx bxs-info-circle"></i>
                <span>Atualize seus dados da sua conta bancária para poder enviar para análise.</span>
            </div>
            <div style="width:40%">
            <button type="button" class="btn btn-sm btn-secondary float-right" id="btnAccount" data-toggle="modal" data-target="#modal-account">{{ __('lending_i.lrn_26') }}</button>
            </div>
        </div>
    </div>
    @endif
	@if ($id != 0)
	<section>
		<div class="row">
			<div class="col-md-12">
				<div class="card">
					<div class="card-content">
						<div class="card-body">
							<div class="row">
								<div class="col-md-4">
									<b>ID</b>
									<br>{{$accountability->code}}
								</div>
								<div class="col-md-4">
									<b>Solicitante</b>
									<br><a target="_blank" href="/user/view/{{$accountability->user->r_code}}">{{$accountability->user->short_name}}</a>
								</div>
								<div class="col-md-4">
									<b>Criado em</b>
									<br>{{$accountability->created_at->format('d/m/Y')}}
								</div>
							</div>
						</div>
					</div>
				</div>
			</div>
		</div>
	</section>	
	@endif
    <section>
        <div class="row">
          <div class="col-md-12">
              <div class="card">
                  <div class="card-content">
                      <div class="card-body" style="padding: 0px;margin: 5px 30px;">
                        <div class="row">
                            <div class="col-12">
                                <fieldset class="form-group mt-1">
                                <p>Caso tenha que informar algo importante, digite abaixo.</p>
                                <textarea class="form-control" id="question" name="question" rows="3" @if ($has_analyze == 0 and $has_approv_or_repprov == 0)@else readonly @endif >{{$accountability->description}}</textarea>
                                </fieldset>
                            </div>
                        </div>
                      </div>
                  </div>
              </div>
          </div>
        </div>
      </section>

    @if ($accountability->lending)
    <section>
        <div class="row">
          <div class="col-md-12">
              <div class="card">
                  <div class="card-content">
                      <div class="card-body">
						<div class="table-responsive">
                          <table class="table table-striped mb-0">
                                <thead>
                                    <tr>
                                        <th></th>
                                        <th>#ID</th>
                                        <th style="min-width: 150px;">Valor Emprestimos</th>
                                        <th style="min-width: 150px;">Total Pago</th>
                                        <th style="min-width: 150px;">Saldo a Pagar</th>
                                        <th>Descrição</th>
                                        <th></th>
                                    </tr>
                                </thead>
                                <tbody>
                                @php ($sumTotal = 0)
                                @php ($saldo = 0)
                                @php($class="")

                                    <tr class="cursor-pointer showDetails">
                                        @php($totalEmprestimo=$accountability->lending->getTotalEmprestimo() )
                                        @php($totalPC = $accountability->lending->getTotalPrestacaoConta() )
                                        @php($totalPendente = $accountability->lending->getTotalPendente())
                                        @if ($totalPendente>0)
                                            @php($class="color:green;")
                                            @php($toltip="Saldo a Receber da Gree")
                                        @else
                                            @php($class="color:red;")
                                            @php($toltip="Saldo a Pagar")
                                        @endif

                                        <td style="padding: 0;padding-left: 5px;">
                                            <i class="row_expand bx bx-plus-circle bx-minus-circle cursor-pointer"></i>
                                        </td>
                                        <td class="no-click">
                                            <small>
                                                <a target="_blank" href="/financy/payment/request/print/{{$accountability->lending->payment_request_id}}">{{$accountability->lending->code}}</a>
                                            </small>
                                        </td>
                                        <td style="color:blue"><small>{{formatMoney(abs($totalEmprestimo))}}</small></td>
                                        <td style="color:red"><small>{{formatMoney(abs($totalPC))}}</small></td>
                                        <td style="{{$class}}"><small>{{formatMoney(abs($totalPendente))}}</small></td>

                                        <td>
                                            <small data-toggle="popover" data-content="<?= $accountability->lending->description ?>">{{Str::limit($accountability->lending->description,25)}}</small>
                                        </td>
                                        <td class="no-click">
                                            @if ($has_analyze == 0 and $has_approv_or_repprov == 0 or $has_analyze == 1 and $is_financy == 1)
                                                <div class="col-md-1">
                                                    <i class="bx bx-edit cursor-pointer" onclick="getLendingPending()" style="position: relative;top: 13px; left: 0px; font-size: 30px;" data-html="true" data-toggle="tooltip" data-placement="bottom" title="Mudar Empréstimo"></i>
                                                </div>
                                            @endif
                                        </td>
                                    </tr>
                                    <tr style="display:none">
                                        <td colspan="7">
                                            <div class="card">
                                                <div class="card-header" style="padding-bottom: 1.5rem;">
                                                    <h4 class="card-title">Detalhes do Emprestimo </h4>
                                                </div>
                                                <div class="card-body">
                                                    <table class="table mb-0">
                                                        <thead class="thead-dark">

                                                        </thead>

                                                        <tbody id="ListItens">
                                                            @php($totalEmprestimo=$accountability->lending->getTotalEmprestimo() )
                                                            @php($totalPC = $accountability->lending->getTotalPago() )
                                                            @php($totalAprovado = $accountability->lending->getTotalPago(1) )
                                                            @php($totalAnalise = $accountability->lending->getTotalPago(2) )
                                                            @php($totalPendente = $accountability->lending->getTotalPendente())
                                                            @php($totalReprovado = $accountability->lending->getTotalReprovado())
                                                            @php($totalReembolso = $accountability->lending->getTotalReembolso())

                                                            @php($class="")


                                                                <tr class="cursor-pointer showDetails">
                                                                    <td style="padding: 0;padding-left: 5px;">
                                                                        <i class="row_expand bx bx-plus-circle bx-minus-circle cursor-pointer"></i>
                                                                    </td>
                                                                    <td>Total Emprestimo</td>
                                                                    <td style="color:blue"  colspan="3"><small>{{formatMoney(abs($totalEmprestimo))}}</small></td>

                                                                </tr>
                                                                    <tr style="display:none">
                                                                        <td colspan="6">
                                                                            <div class="card">
                                                                                <div class="card-header" style="padding-bottom: 1.5rem;">
                                                                                    <h4 class="card-title">Detalhes do Emprestimo </h4>
                                                                                </div>
                                                                                <div class="card-body">
                                                                                    <table class="table table-striped mb-0">
                                                                                        <tr>
                                                                                            <th>#ID</th>
                                                                                            <th>Total</th>
                                                                                            <th>Histórico</th>
                                                                                            <th>Data Lançamento</th>
                                                                                            <th>Ações</th>

                                                                                        </tr>
                                                                                        <tbody>
                                                                                            <tr>
                                                                                                <td>
                                                                                                    <small>
                                                                                                    @if($accountability->lending->is_paid==1)
                                                                                                        <a href="/financy/lending/all?id=<?= $accountability->lending->code ?>" target="_blank" href="javascript:void(0)">
                                                                                                            {{$accountability->lending->code}}
                                                                                                        </a>
                                                                                                    @else
                                                                                                        {{$accountability->lending->code}}
                                                                                                    @endif
                                                                                                    </small>
                                                                                                </td>
                                                                                                <td style="color:blue;">
                                                                                                    <small>{{formatMoney($accountability->lending->amount)}}</small>
                                                                                                </td>
                                                                                                <td><small>EMPRÉSTIMO</small></td>
                                                                                                <td><small>{{$accountability->lending->created_at->format('d/m/Y')}}</small></td>
                                                                                                <td></td>
                                                                                            </tr>
                                                                                            @if($accountability->lending->prestacao_conta_manual->isNotEmpty())
                                                                                                @foreach ($accountability->lending->prestacao_conta_manual->where('type_entry',1) as $index => $key)

                                                                                                    <tr class="">

                                                                                                        <td><small>{{$key->code}}</small></td>
                                                                                                        <td style="color:blue;">
                                                                                                            <small>{{formatMoney($key->total)}}</small>
                                                                                                        </td>
                                                                                                        <td>
                                                                                                            <small>
                                                                                                                EMPRÉSTIMO <span class="badge badge-light-warning">LANÇAMENTO MANUAL</span>
                                                                                                            </small>
                                                                                                        </td>
                                                                                                        <td><small>{{ Carbon\Carbon::parse($key->date)->format('d/m/Y')}}</small></td>
                                                                                                        </td>
                                                                                                        <td id="action" class="no-click">
                                                                                                            @if($show_actions AND $accountability->lending->isPending())
                                                                                                            <div class="dropleft">
                                                                                                                <span class="bx bx-dots-horizontal-rounded font-medium-3 nav-hide-arrow cursor-pointer" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false" role="menu"></span>
                                                                                                                <div class="dropdown-menu dropdown-menu-right">

                                                                                                                    <a class="dropdown-item" onclick="editLancManual(this)" json-data="<?= htmlspecialchars(json_encode($key), ENT_QUOTES, 'UTF-8') ?>" href="javascript:void(0);"><i class="bx bx-edit-alt mr-1"></i> Editar</a>
                                                                                                                    <a class="dropdown-item" onclick="delLancManual({{$key->id}})" href="javascript:void(0);"><i class="bx bx-trash-alt mr-1"></i> Excluir</a>

                                                                                                                </div>
                                                                                                            </div>
                                                                                                            @endif
                                                                                                        </td>
                                                                                                    </tr>

                                                                                                @endforeach
                                                                                            @endif

                                                                                        </tbody>
                                                                                        <tfoot>
                                                                                            <tr>
                                                                                                <th>Total</th>
                                                                                                <th style="color:blue"  colspan="4">
                                                                                                    <small>{{formatMoney(abs($totalEmprestimo))}}</small>
                                                                                                </th>
                                                                                            </tr>
                                                                                        </tfoot>
                                                                                    </table>
                                                                                </div>
                                                                            </div>
                                                                        </div>
                                                                        </td>
                                                                    </tr>
                                                                <tr class="cursor-pointer showDetails">
                                                                    <td style="padding: 0;padding-left: 5px;">
                                                                        <i class="row_expand bx bx-plus-circle bx-minus-circle cursor-pointer"></i>
                                                                    </td>
                                                                    <td>Prestações de Contas</td>
                                                                    <td style="color:red"  colspan="3"><small>{{formatMoney(abs($totalPC))}}</small></td>

                                                                </tr>
                                                                    <tr style="display:none">
                                                                        <td colspan="6">
                                                                            <div class="card">
                                                                                <div class="card-header" style="padding-bottom: 1.5rem;">
                                                                                    <h4 class="card-title">Itens da Prestação de Contas</h4>
                                                                                </div>
                                                                                <div class="card-body">
                                                                                    <table class="table table-striped mb-0">
                                                                                        <tr>
                                                                                            <th></th>
                                                                                            <th>#ID</th>
                                                                                            <th>Total</th>
                                                                                            <th>Histórico</th>
                                                                                            <th>Data Lançamento</th>
                                                                                            <th>Ações</th>

                                                                                        </tr>
                                                                                        <tbody>

                                                                                            @if($accountability->lending->prestacao_conta->isNotEmpty())
                                                                                                @foreach ($accountability->lending->prestacao_conta as $index => $key)
                                                                                                    @php($item_class="")

                                                                                                    <tr class="cursor-pointer showDetails">
                                                                                                        <td style="padding: 0;padding-left: 5px;">
                                                                                                            <i class="row_expand bx bx-plus-circle bx-minus-circle cursor-pointer"></i>
                                                                                                        </td>
                                                                                                        <td><small>{{$key->code}}</small></td>

                                                                                                        @if($key->status->id==3)
                                                                                                            @php($item_class="text-decoration: line-through;")
                                                                                                        @endif

                                                                                                        <td style="color:red;{{$item_class}}">
                                                                                                            <small>{{formatMoney($key->total)}}</small>
                                                                                                        </td>
                                                                                                        <td>
                                                                                                            <small>
                                                                                                                PRESTAÇÃO DE CONTAS
                                                                                                                @if($key->status->id==4 )
                                                                                                                    <span class="badge badge-light-success">Aprovado</span>
                                                                                                                @else
                                                                                                                    {!!$key->status->html!!}
                                                                                                                @endif
                                                                                                            </small>
                                                                                                        </td>
                                                                                                        <td><small>{{ Carbon\Carbon::parse($key->date)->format('d/m/Y')}}</small></td>

                                                                                                        <td class="no-click">
                                                                                                            <div class="dropleft">
                                                                                                                <span class="bx bx-dots-horizontal-rounded font-medium-3 nav-hide-arrow cursor-pointer" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false" role="menu"></span>
                                                                                                                <div class="dropdown-menu dropdown-menu-right">

                                                                                                                    <a href="/financy/accountability/edit/<?= $key->id ?>" class="dropdown-item" href="javascript:void(0)"><i class="bx bx-edit-alt mr-1"></i>
                                                                                                                        @if ($key->status->id >0)
                                                                                                                        Visualizar
                                                                                                                        @else
                                                                                                                        Editar
                                                                                                                        @endif
                                                                                                                        </a>
                                                                                                                    <a href="/financy/payment/request/print/<?= $key->payment_request_id ?>" class="dropdown-item" target="_blank" href="javascript:void(0)"><i class="bx bx-printer mr-1"></i>Impr. Solicitação Pag.</a>
                                                                                                                    <?php if ($key->receipt) { ?>
                                                                                                                        <a class="dropdown-item" target="_blank" href="<?= $key->receipt ?>"><i class="bx bx-receipt mr-1"></i> {{ __('lending_i.lt_17') }}</a>
                                                                                                                    <?php } ?>
                                                                                                                    @if ($key->status->id >0)
                                                                                                                    <!--<a onclick="seeAnalyzes(<?= $key->id ?>);" class="dropdown-item" href="javascript:void(0)"><i class="bx bx-list-check mr-1"></i> Hist. de aprovações</a>-->
                                                                                                                    @endif
                                                                                                                </div>
                                                                                                            </div>
                                                                                                        </td>

                                                                                                    </tr>
                                                                                                    <tr style="display:none">
                                                                                                        <td colspan="8">
                                                                                                            <div class="card">
                                                                                                                <div class="card-header" style="padding-bottom: 1.5rem;">
                                                                                                                    <h4 class="card-title">Datalhamento da Prestação de Contas </h4>
                                                                                                                </div>
                                                                                                                <div class="card-body">
                                                                                                                    <table class="table table-striped mb-0">
                                                                                                                        <thead>
                                                                                                                            <tr>
                                                                                                                                <th style="padding: 0;padding-left: 8px;">#</th>
                                                                                                                                <th>TIPO</th>
                                                                                                                                <th>DESCRIÇÃO</th>
                                                                                                                                <th>TOTAL</th>
                                                                                                                                <th>DATA</th>
                                                                                                                                <th>ANEXOS</th>
                                                                                                                            </tr>
                                                                                                                        </thead>
                                                                                                                        <tbody>
                                                                                                                            @foreach ($key->itens as $i=>$item)
                                                                                                                                <tr class="cursor-pointer showDetails">
                                                                                                                                    <td style="padding: 0;padding-left: 5px;">
                                                                                                                                        <i class="row_expand bx bx-plus-circle bx-minus-circle cursor-pointer"></i>
                                                                                                                                    </td>
                                                                                                                                    <td>{{$item->type_description}}</td>
                                                                                                                                    <td>{{$item->description}}</td>
                                                                                                                                    <td style="width: 30%;{{$class}}">

                                                                                                                                        {{$item->total_money_currency}}

                                                                                                                                        @if($item->currency > 1)
                                                                                                                                            <i class="bx bxs-help-circle cursor-pointer" style="position: relative;top: 3px; left: 0px;" data-html="true" data-toggle="tooltip" data-placement="bottom" title="<span>{{$item->total_currency}} * <small>{{$item->quotation}}</small> : {{$item->total_money}}</span>"></i>
                                                                                                                                        @endif
                                                                                                                                    </td>

                                                                                                                                    <td>{{$item->date->format('d/m/Y')}}</td>
                                                                                                                                    <td class="no-click">

                                                                                                                                        @if($item->attach)
                                                                                                                                            @foreach($item->attach as  $index => $attach)
                                                                                                                                                <a target="_blank" data-toggle="popover" data-content="{{$attach->name}}" href="{{$attach->url}}"><i class="bx bxs-file-image mr-1"></i></a>
                                                                                                                                            @endforeach
                                                                                                                                        @endif
                                                                                                                                    </td>

                                                                                                                                </tr>

                                                                                                                            <tr style="display:none" class="seq_{{$index+1}} group">
                                                                                                                                <td colspan="7">
                                                                                                                                    <div class="row" tyle="display:none">
                                                                                                                                        <div class="col-md-4">
                                                                                                                                            <div class="form-group">
                                                                                                                                                <label>QUANTIDADE DE PESSOAS:</label>
                                                                                                                                                <span> {{$item->peoples}}</span>
                                                                                                                                            </div>
                                                                                                                                        </div>
                                                                                                                                        <div class="col-md-4">
                                                                                                                                            <div class="form-group">
                                                                                                                                                <label>CIDADE:</label>
                                                                                                                                                <span> {{$item->city}}</span>
                                                                                                                                            </div>
                                                                                                                                        </div>
                                                                                                                                        <div class="col-md-4">
                                                                                                                                            <div class="form-group">
                                                                                                                                                <label>MOEDA:</label>
                                                                                                                                                <span> {{$item->currency_description}}</span>
                                                                                                                                            </div>
                                                                                                                                        </div>
                                                                                                                                    </div>
                                                                                                                                </td>
                                                                                                                            </tr>
                                                                                                                            @endforeach
                                                                                                                        </tbody>
                                                                                                                        <tfoot>
                                                                                                                            <tr>
                                                                                                                                <th colspan="3">Total</th>
                                                                                                                                <th colspan="3" style="color:red;{{$class}}">
                                                                                                                                    <small>{{formatMoney($key->total)}}</small>
                                                                                                                                </th>
                                                                                                                            </tr>
                                                                                                                        </tfoot>
                                                                                                                    </table>
                                                                                                                </div>
                                                                                                            </div>
                                                                                                        </div>
                                                                                                        </td>
                                                                                                    </tr>

                                                                                                    @if($key->pagamento_prestacao_conta)
                                                                                                        <tr class="">
                                                                                                            <td style="padding: 0;padding-left: 5px;"></td>
                                                                                                            <td><small>{{$key->pagamento_prestacao_conta->code}}</small></td>

                                                                                                            @if($key->pagamento_prestacao_conta->is_paid==1 )
                                                                                                                <td style="color:green;text-decoration: line-through;"><small>{{formatMoney($key->pagamento_prestacao_conta->amount_liquid)}}</small></td>
                                                                                                            @else
                                                                                                                <td style="color:green;"><small>{{formatMoney($key->pagamento_prestacao_conta->amount_liquid)}}</small></td>
                                                                                                            @endif



                                                                                                            <td>
                                                                                                                <small>
                                                                                                                    REEMBOLSO DE PRESTAÇÃO DE CONTAS
                                                                                                                @if($key->pagamento_prestacao_conta->is_paid==1 )
                                                                                                                    <span class="badge badge-light-primary">Transferido</span>
                                                                                                                @else
                                                                                                                    <span class="badge badge-light-success">Aguardando Reembolso</span>
                                                                                                                @endif
                                                                                                                </small>

                                                                                                            </td>
                                                                                                            <td><small>{{ Carbon\Carbon::parse($key->date)->format('d/m/Y')}}</small></td>
                                                                                                            <td></td>

                                                                                                        </tr>
                                                                                                    @endif

                                                                                                @endforeach

                                                                                            @endif

                                                                                            @if($accountability->lending->prestacao_conta_manual->isNotEmpty())
                                                                                                @foreach ($accountability->lending->prestacao_conta_manual->where('type_entry',2) as $index => $key)

                                                                                                    <tr class="">
                                                                                                        <td style="padding: 0;padding-left: 5px;"></td>
                                                                                                        <td><small>{{$key->code}}</small></td>

                                                                                                        <td style="color:red;">
                                                                                                            <small>{{formatMoney($key->total)}}</small>
                                                                                                        </td>
                                                                                                        <td>
                                                                                                            <small>PRESTAÇÃO DE CONTAS <span class="badge badge-light-warning">LANÇAMENTO MANUAL</span></small>
                                                                                                        </td>
                                                                                                        <td><small>{{ Carbon\Carbon::parse($key->date)->format('d/m/Y')}}</small></td>
                                                                                                        <td id="action" class="no-click">
                                                                                                            @if($show_actions AND $accountability->lending->isPending())
                                                                                                            <div class="dropleft">
                                                                                                                <span class="bx bx-dots-horizontal-rounded font-medium-3 nav-hide-arrow cursor-pointer" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false" role="menu"></span>
                                                                                                                <div class="dropdown-menu dropdown-menu-right">

                                                                                                                    <a class="dropdown-item" onclick="editLancManual(this)" json-data="<?= htmlspecialchars(json_encode($key), ENT_QUOTES, 'UTF-8') ?>" href="javascript:void(0);"><i class="bx bx-edit-alt mr-1"></i> Editar</a>
                                                                                                                    <a class="dropdown-item" onclick="delLancManual({{$key->id}})" href="javascript:void(0);"><i class="bx bx-trash-alt mr-1"></i> Excluir</a>

                                                                                                                </div>
                                                                                                            </div>
                                                                                                            @endif
                                                                                                        </td>
                                                                                                    </tr>

                                                                                                @endforeach
                                                                                            @endif

                                                                                        </tbody>
                                                                                        <tfoot>
                                                                                            <tr>
                                                                                                <th colspan="2">Total</th>
                                                                                                <th style="color:red"  colspan="4">
                                                                                                    <small>{{formatMoney(abs($totalPC))}}</small>
                                                                                                </th>
                                                                                            </tr>
                                                                                        </tfoot>
                                                                                    </table>
																					</div>
                                                                                </div>
                                                                            </div>
                                                                        </div>
                                                                        </td>
                                                                    </tr>

                                                                <tr>
                                                                    @if ($totalPendente>0)
                                                                        @php($class="color:green;")
                                                                        @php($toltip="Saldo a Receber da Gree")
                                                                    @else
                                                                        @php($class="color:red;")
                                                                        @php($toltip="Saldo a Pagar")
                                                                    @endif
                                                                    <th colspan="2">Saldo (Pagar / Receber)</th>
                                                                    <th style="{{$class}}" colspan="3">
                                                                        <small>{{formatMoney(abs($totalPendente))}}</small>
                                                                    </th>
                                                                </tr>
                                                        </tbody>

                                                    </table>
                                                </div>
                                            </div>
                                        </div>
                                        </td>
                                    </tr>

                                </tbody>

                            </table>

                      </div>
                  </div>
              </div>
          </div>
        </div>
      </section>
    @endif
    <div class="content-body">
        <div class="row">
                <div class="col-12 col-md-9">
                  <div class="card list">
                    <div class="card-content">
                      <!-- table head dark -->
                      <div class="table-responsive">
                        <table class="table mb-0">
                          <thead class="thead-dark">
                            <tr>
                              <th style="padding: 0;padding-left: 8px;">#</th>

                              <th>TIPO</th>
                              <th>DESCRIÇÃO</th>
                              <th>TOTAL</th>
                              <th>DATA</th>
                              <th>ANEXOS</th>
								@if ($has_analyze == 0 or $has_analyze == 1 and $is_financy == 1)
                                <th>AÇÕES</th>
								@endif
                            </tr>
                          </thead>
                          <tbody id="ListItens">
                        @if($accountability->itens)
                            @foreach($accountability->itens as  $index => $item)

                                @php($class_tr="cursor-pointer showDetails")
                                @if($item->type_entry == 2)
                                    @php($class_tr="")
                                @endif
                                <tr class="{{$class_tr}}">
                                    <td style="padding: 0;padding-left: 5px;">
                                        @if($item->type_entry == 1)
                                        <i class="row_expand bx bx-plus-circle bx-minus-circle cursor-pointer"></i>
                                        @endif

                                    </td>
                                    <td><small>{{$item->type_description}}</small></td>
                                    <td><small>{{$item->description}}</small></td>
                                    <td style="width: 30%;">
                                        <small>
                                        {{$item->total_money_currency}}
                                        @if($item->currency > 1)
                                            <i class="bx bxs-help-circle cursor-pointer" style="position: relative;top: 3px; left: 0px;" data-html="true" data-toggle="tooltip" data-placement="bottom" title="<span>{{$item->total_currency}} * <small>{{$item->quotation}}</small> : {{$item->total_money}}</span>"></i>
                                        @endif
                                        </small>
                                    </td>

                                    <td><small>{{$item->date->format('d/m/Y') }}</small></td>
                                    <td class="no-click">

                                        @if($item->attach)
                                            @foreach($item->attach as $attach)
                                                <a target="_blank" data-toggle="popover" data-content="{{$attach->name}}" href="{{$attach->url}}"><i class="bx bxs-file-image mr-1"></i></a>
                                            @endforeach
                                        @endif
                                    </td>
                                    @if ($has_analyze == 0 or $has_analyze == 1 and $is_financy == 1)
                                        <td id="action" class="no-click">
                                            <div class="dropleft">
                                                <span class="bx bx-dots-horizontal-rounded font-medium-3 nav-hide-arrow cursor-pointer" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false" role="menu"></span>
                                                <div class="dropdown-menu dropdown-menu-right">

                                                    <a class="dropdown-item" onclick="editModal(this,{{$index}})" href="javascript:void(0);"><i class="bx bx-edit-alt mr-1"></i> Editar</a>
                                                    <a class="dropdown-item" onclick="deletes({{$item->id}})" href="javascript:void(0);"><i class="bx bx-trash-alt mr-1"></i> Excluir</a>

                                                </div>
                                            </div>
                                        </td>
                                    @endif
                                </tr>
                                @if($item->type_entry == 1)
                                <tr style="display:none" class="seq_{{$index+1}} group">
                                    <td colspan="7">
                                        <div class="row" tyle="display:none">
                                            <div class="col-md-4">
                                                <div class="form-group">
                                                    <label>QUANTIDADE DE PESSOAS:</label>
                                                    <span> {{$item->peoples}}</span>
                                                </div>
                                            </div>
                                            <div class="col-md-4">
                                                <div class="form-group">
                                                    <label>CIDADE:</label>
                                                    <span> {{$item->city}}</span>
                                                </div>
                                            </div>
                                            <div class="col-md-4">
                                                <div class="form-group">
                                                    <label>MOEDA:</label>
                                                    <span> {{$item->currency_description}}</span>
                                                </div>
                                            </div>
                                        </div>
                                    </td>
                                </tr>
                                @endif

                            @endforeach
                        @endif

                          </tbody>

                            <tfoot>
                                @if($accountability->itens)
                                <tr>
                                    <th colspan="3">Total</th>
                                    <th colspan="3">
                                        <small>{{formatMoney($accountability->total)}}</small>
                                    </th>
                                </tr>
                                @endif
                            </tfoot>
                        </table>
                      </div>
                    </div>
                  </div>
                </div>

                @if ($accountability->lending)
                    <div class="col-12 col-md-3">
                        <div class="card sumary">
                            <div class="card-header">Resumo da Prestação de Contas</div>
                            <div class="card-content">
                                <div class="float-right p-1">
                                    <table class="table" style="font-size: 12px !important;">
                                        <tbody>
                                            <tr>
                                                @php($totalEmprestimo=$accountability->total_lending )
												@php($totalPC = $totalEmprestimo - $accountability->total_pending )
												@php($totalPCLocal = $accountability->total)
												@php($totalPendente = ($totalPC+$totalPCLocal) - $totalEmprestimo)

                                                @if ($totalPendente>0)
                                                    @php($class="color:green;")
                                                    @php($toltip="Saldo a Receber da Gree")
                                                @else
                                                    @php($class="color:red;")
                                                    @php($toltip="Saldo a Pagar")
                                                @endif
                                                <tr>
                                                    <td class="text-right p-1"><b>EMPRÉSTIMO:</b></td>
                                                    <td class="text-right total_lending p-1" style="width: 120px; color: blue;">{{formatMoney($totalEmprestimo)}}
                                                    </td>
                                                </tr>

                                                <tr>
                                                    <td class="text-right p-1">TOTAL PAGO:</td>
                                                    <td class="text-right total_item p-1" style="width: 120px; color:red;">{{formatMoney($totalPC)}}
                                                    </td>
                                                </tr>
                                                <tr>
                                                    <td class="text-right p-1">PRESTAÇÃO DE CONTAS:</td>
                                                    <td class="text-right total_item p-1" style="width: 120px; color:red;">{{formatMoney($totalPCLocal)}}
                                                    </td>
                                                </tr>

                                                <tr id="status-total">
                                                    <td class="text-uppercase text-right p-1"><b>TOTAL A PAGAR:</b></td>
                                                    <td class="text-right amount_total p-1" style="width: 220px; {{$class}}">
                                                        <span class="total_amount">{{formatMoney(abs($totalPendente))}}</span>
                                                        <i class="bx bxs-help-circle cursor-pointer" style="position: relative;top: 3px; left: 0px;" data-toggle="tooltip" data-placement="top" data-original-title="{{$toltip}}"></i>
                                                    </td>


                                                </tr>
                                        </tbody>
                                    </table>
                                </div>
                            </div>

                        </div>
                    </div>
                @endif
        </div>
    </div>
</div>

    <div class="modal fade text-left" id="modal-refund" tabindex="-1" role="dialog" aria-labelledby="modal-refund" aria-hidden="true">
        <div class="modal-dialog modal-xl modal-dialog-scrollable" role="document">
        <div class="modal-content">
            <div class="modal-header">
            <h3 class="modal-title">EMPRESTIMOS PENDENTES DE PRESTAÇÃO DE CONTAS</h3>
            <button type="button" class="close rounded-pill" data-dismiss="modal" aria-label="Close">
                <i class="bx bx-x"></i>
            </button>
            </div>
            <div class="modal-body">
                <table class="table table-borderless" style="font-size: 12px !important;">
                    <tbody class="detail_cons">
                            <div class="list-consum">

                            </div>
                            <div class="list-consum-inter" style="display:none;">
                                <div class="list-consum-inter-item">

                                </div>
                            </div>
                    </tbody>
                </table>
                <div id="ajax-table"></div>
            </div>
            <div class="modal-footer">
            <button type="button" class="btn btn-light-secondary" data-dismiss="modal">
                <i class="bx bx-x d-block d-sm-none"></i>
                <span class="d-none d-sm-block">{{ __('lending_i.lrn_32') }}</span>
            </button>
            </div>
        </div>
        </div>
    </div>

    <div class="modal fade text-left" id="modal-update" tabindex="-1" role="dialog" aria-labelledby="modal-update" aria-hidden="true">
        <div class="modal-dialog modal-lg modal-dialog-centered" role="document">
        <div class="modal-content">
            <div class="modal-header bg-primary white">
            <span class="modal-title title-item" id="modal-update"></span>
            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                <i class="bx bx-x"></i>
            </button>
            </div>
            <form class="push" action="/financy/accountability/edit_do" id="a_update_form" method="POST" enctype="multipart/form-data">
            <div class="modal-body">
                <div class="row">
                    <input type="hidden" id="item_id" name="item_id" value="0">
                    <input type="hidden" id="description_accountability" name="description_accountability" value="{{$accountability->description}}">

                    <input type="hidden" id="id" name="id" value="<?= $id ?>">
                    <input type="hidden" id="lending_request_id" name="lending_request_id" value="{{$accountability->lending_request_id}}">

                    @if ($is_financy == 0)
                    <div class="col-sm-6">
                        <label for="sector">TIPO DE LANÇAMENTO</label>
                        <fieldset class="form-group">
                            <select class="form-control" name="type_entry" id="type_entry" required>
                                <option value="1">PRESTAÇÃO DE CONTAS</option>
                                <option value="2">TRANSF. / DEVOLUÇÃO</option>
                            </select>
                        </fieldset>
                    </div>
                    <div class="col-sm-6 hide-elements">
                        <label for="sector">TIPO DE CONSUMO</label>
                        <fieldset class="form-group">
                            <select class="form-control" name="type" id="type" required>
                                <option value="1">COMBUSTÍVEL</option>
                                <option value="2">TAXI</option>
                                <option value="3">UBER/99</option>
                                <option value="4">PASSAGEM AÉREA</option>
                                <option value="5">HOSPEDAGEM</option>
                                <option value="6">ALIMENTAÇÃO</option>
                                <option value="7">OUTRO</option>
                            </select>
                        </fieldset>
                    </div>
                    <div class="col-sm-12">
                        <label for="description">DESCRIÇÃO</label>
                        <fieldset class="form-group">
                            <textarea class="form-control" name="description" id="description" rows="3" placeholder="Justifique se necessário..." required></textarea>
                        </fieldset>
                    </div>
                    <div class="col-sm-6 hide-elements">
                        <label for="peoples">QUANTIDADE DE PESSOAS</label>
                        <fieldset class="form-group">
                            <input type="number" class="form-control" name="peoples" id="peoples" value="1" required>
                        </fieldset>
                    </div>
                    <div class="col-sm-6 hide-elements">
                        <label for="city">CIDADE</label>
                        <fieldset class="form-group">
                            <input type="text" class="form-control" name="city" id="city" placeholder="Digite a cidade..." required>
                        </fieldset>
                    </div>
                    <div class="col-sm-6 hide-elements">
                        <label for="currency">MOEDA</label>
                        <fieldset class="form-group">
                            <select class="form-control" name="currency" id="currency" required>
                            <option value="1">REAL (BRL)</option>
                            <option value="2">DOLLAR (USD)</option>
                            <option value="3">RENMIBI (CNY)</option>
                            <option value="4">HONG KONG DOLLAR (HKD)</option>
                            </select>
                        </fieldset>
                    </div>
                    <div class="col-sm-6">
                        <label for="total">TOTAL</label>
                        <fieldset class="form-group">
                            <input type="text" class="form-control" name="total" id="total" placeholder="0,00" required>
                        </fieldset>
                    </div>
                    <div class="col-sm-12">
                        <label for="date">DATA DE CONSUMO</label>
                        <fieldset class="form-group">
                            <input type="text" readonly="readonly" name="date" id="date" class="form-control date-format datepicker js-flatpickr js-flatpickr-enabled flatpickr-input" autocomplete="off" required>
                        </fieldset>
                    </div>
                    <div class="col-sm-12">
                        <label for="receipt">COMPROVANTE</label>
                        <fieldset class="form-group">
                            <input type="hidden" id="receipt_id" name="receipt_id" value="0">
                            <input type="file" class="form-control" name="receipt" id="receipt" required>
                            <small>Esse arquivo é obrigatório.</small>
                            <br><a href="#" id="receipt_url" style="display:none"></a>
                        </fieldset>


                        <label for="other">OUTRO ARQUIVO</label>
                        <fieldset class="form-group">
                            <input type="hidden" id="other_id" name="other_id" value="0">
                            <input type="file" class="form-control" name="other" id="other">
                            <small>Esse arquivo não é obrigatório.</small>
                            <br><a href="#" id="other_url" style="display:none"></a>
                        </fieldset>

                    </div>
                    @endif
                    @if ($is_financy == 1)
                    <div class="col-sm-12">
                        <label for="total">TOTAL</label>
                        <fieldset class="form-group">
                            <input type="text" class="form-control" name="total" id="total" placeholder="0,00">
                        </fieldset>
                    </div>
                    @endif
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" onclick="resetFields();" class="btn btn-light-secondary" data-dismiss="modal">
                    <i class="bx bx-x d-block d-sm-none"></i>
                    <span class="d-none d-sm-block">FECHAR</span>
                </button>
            <button type="button" id="updoradditem" class="btn btn-success ml-1">
                <i class="bx bx-check d-block d-sm-none"></i>
                <span class="d-none d-sm-block">ATUALIZAR LISTA</span>
            </button>
            </div>
            </form>
        </div>
        </div>
    </div>

    @if ($id == 0)
        <div class="mb-2" style="text-align: center; position: fixed;left: 0;right: 0;bottom: 0;margin: 0 auto; z-index: 99;">
            <button type="button" onclick="getLendingPending()" class="btn btn-success mb-1 sendapprov">Selecionar Empréstimo</button>
        </div>
    @elseif ($accountability->has_analyze == 0 and $accountability->is_reprov == 1 or $accountability->has_analyze == 0 and $accountability->is_reprov == 0 and $accountability->is_approv == 0)
        <div class="mb-2" style="text-align: center; position: fixed;left: 0;right: 0;bottom: 0;margin: 0 auto; z-index: 99;">
            <button type="button" class="btn btn-primary mb-1 mr-1" data-toggle="modal" id="newItem">Novo item</button>
            <button type="button" onclick="Approv();" class="btn btn-success mb-1 sendapprov" style="display:none">Enviar</button>
        </div>
    @endif



    <div class="modal fade text-left" id="modal-account" tabindex="-1" role="dialog" aria-labelledby="modal-account" aria-hidden="true">
        <div class="modal-dialog modal-dialog-scrollable" role="document">
        <div class="modal-content">
            <div class="modal-header">
            <h3 class="modal-title" id="modal-account">{{ __('lending_i.lrn_27') }}</h3>
            <button type="button" class="close rounded-pill" data-dismiss="modal" aria-label="Close">
                <i class="bx bx-x"></i>
            </button>
            </div>
            <div class="modal-body">
                <form id="UpdateAccount" action="#" method="post">
                    <div class="form-group">
                        <label for="agency">{{ __('lending_i.lrn_28') }}</label>
                        <input class="form-control" type="text" name="agency" id="agency" value="<?php if (isset($a_bank)) { ?><?= $a_bank->agency ?><?php } ?>">
                    </div>
                    <div class="form-group">
                        <label for="account">{{ __('lending_i.lrn_29') }}</label>
                        <input class="form-control" type="text" name="account" id="account" value="<?php if (isset($a_bank)) { ?><?= $a_bank->account ?><?php } ?>">
                    </div>
                    <div class="form-group">
                        <label for="bank">{{ __('lending_i.lrn_30') }}</label>
                        <input class="form-control" type="text" name="bank" id="bank" value="<?php if (isset($a_bank)) { ?><?= $a_bank->bank ?><?php } ?>">
                    </div>
                    <div class="form-group">
                        <label for="identity">{{ __('lending_i.lrn_31') }}</label>
                        <input class="form-control" type="text" name="identity" id="identity" value="<?php if (isset($a_bank)) { ?><?= $a_bank->identity ?><?php } ?>">
                    </div>
            </div>
            <div class="modal-footer">
            <button type="button" class="btn btn-light-secondary" data-dismiss="modal">
                <i class="bx bx-x d-block d-sm-none"></i>
                <span class="d-none d-sm-block">{{ __('lending_i.lrn_32') }}</span>
            </button>
            <button type="submit" class="btn btn-primary ml-1">
                <i class="bx bx-check d-block d-sm-none"></i>
                <span class="d-none d-sm-block">{{ __('lending_i.lrn_33') }}</span>
            </button>
            </div>
            </form>
        </div>
        </div>
    </div>

	@if ($has_analyze == 1)
	@if (Session::get('r_code') != $accountability->r_code)
	<div class="mb-2 cursor-pointer" id="showAnalyze" style="position: fixed;left: 0;right: 0;bottom: 0;margin: 0 auto; z-index: 99; text-align: center;">
        <i class="bx bx-up-arrow-alt"></i>
        <br>Mostrar análise
    </div>

    <div class="card text-center" id="Analyze" style="width: 395px; position: fixed;left: 0;right: 0;bottom: 0;margin: 0 auto; opacity: 0.9;z-index: 99;">
        <div class="card-content">
            <button type="button" id="HAnalyze" class="close HideAnalyze" aria-label="Close">
                <i class="bx bx-x"></i>
            </button>
            <div class="card-body">
            
            <form id="AnalyzeForm" action="/trip/analyze-in/update" method="post">
                <input type="hidden" name="reason" id="reason">
                <input type="hidden" name="is_approv" id="is_approv" value="1">
                <div class="row">
                    <div class="col-sm-12 d-flex justify-content-center">
                        <button type="button" class="btn btn-success" onclick="analyze(<?= $id ?>, <?= $accountability->position_analyze ?>)">Realizar análise</button> 
                    </div>
                </div>
            </div>
        </div>
    </div>

	@endif
	@endif
		<div class="modal fade text-left" id="termsModal" tabindex="-1" aria-labelledby="myModalLabel160" style="display: none;" aria-hidden="true" data-backdrop="static">
        <div class="modal-dialog modal-dialog-centered modal-dialog-scrollable">
            <div class="modal-content">
            <div class="modal-header bg-primary">
                <h5 class="modal-title white" id="myModalLabel160">Termos & Condições</h5>
            </div>
            <div class="modal-body">
                <p  style="text-transform:uppercase; text-align: justify">REQUISITOS DO PRESTAÇÃO DE CONTAS</p>
                <ol style="text-align:justify">
                    <li>O prazo médio para verificação e aprovação financeira é de até 5 (cinco) dias úteis contados da aprovação do gestor (e entrega ao departamento financeiro);</li>
                    <li>Obedecer o limite estabelecido para cada tipo de despesa:
                      <ol>
                        <li>Alimentação de colaborador >> R$ 50 por refeição principal;</li>
                        <li>Despesas telefônicas >> ½ Fatura limitado a R$ 100;</li>
                        <li>Transporte >> Apresentar comprovantes e itinerários;</li>
                      </ol>
                    </li>        
                    <li>Algumas despesas são considerados como “Não Reembolsáveis”:
                    <ol>
                        <li>Bebidas alcoólicas e energéticas;</li>
                        <li>Cigarros de qualquer natureza;</li>
                        <li>Bens pessoas, roupas e acessórios;</li>
                        <li>Bilhetes em casa de eventos;</li>
                        <li>Despesas com higiene pessoal e embelezamento;</li>
                        <li>Impostos e tributos pessoais;</li>
                        <li>Gorjetas e taxas de serviços;</li>
                        <li>Outros não convenientes a operação.</li>
                      </ol>               
                    </li>
					<li>Quando do não cumprimento dos prazos para prestação de contas, os débitos serão enviados para tratativas de desconto em folha de pagamento;</li>
					<li>Não será concedido novo empréstimo se houver qualquer pendencia financeira.</li>
                  </ol>                   
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-primary ml-1" id="btn_confirm_term">
                <i class="bx bx-check d-block d-sm-none"></i>
                <span class="d-none d-sm-block " style="text-transform: uppercase"> estou de acordo</span>
                </button>
            </div>
            </div>
        </div>
    </div>  
		
    <div class="customizer d-md-block text-center"><a class="customizer-close" href="#"><i class="bx bx-x"></i></a><div class="customizer-content p-2 ps ps--active-y">
        <h4 class="text-uppercase mb-0 histId"></h4>
        <small>Veja todo histórico de análises</small>
        <hr>
        <div class="theme-layouts">
        <div class="d-flex justify-content-start text-left p-1">
                <ul class="widget-timeline listitens" style="width: 100%;">
                </ul>
        </div>
        </div>

        <!-- Hide Scroll To Top Ends-->
        <div class="ps__rail-x" style="left: 0px; bottom: 0px;"><div class="ps__thumb-x" tabindex="0" style="left: 0px; width: 0px;"></div></div><div class="ps__rail-y" style="top: 0px; height: 754px; right: 0px;"><div class="ps__thumb-y" tabindex="0" style="top: 0px; height: 590px;"></div></div></div>
    </div>

    <form action="/financy/accountability/send_analyze_do/" id="f_send_analyze" method="POST">
        <input type="hidden" id="description_accountability" name="description_accountability" value="{{$accountability->description}}">
    </form>

    @if($id)
    <div class="customizer d-md-block text-center">
        <a onclick="rtd_analyzes(<?= $id ?>, 'App\\Model\\FinancyAccountability');" style="writing-mode: vertical-lr;height: 150px;font-weight: bold;top: 40%;" class="customizer-toggle btn-historic-approv" href="javascript:void(0);">
            Hist. de aprovação
        </a>
    </div>
    @endif

    @include('gree_i.misc.components.analyze.history.view')
    @include('gree_i.misc.components.analyze.do_analyze.inputs', ['url' => '/financy/accountability/analyze_do'])
    @include('gree_i.misc.components.analyze.do_analyze.script')
    
    <script src="/admin/app-assets/vendors/js/extensions/shepherd.min.js"></script>
    <script src="/js/StepsTour.js"></script>
    <script>
    @include('gree_i.misc.components.analyze.history.script')

    var is_edit = 0;
    var arrayItens = {!! json_encode($accountability->itens) !!};

    var index_edit = 0;
    var id_accountability = <?= $id ?>;

    @php($rotaEmprestimosPendentes = '/financy/accountability/ajax/lending' )

    function seeAnalyzes(id) {
        $("#history-analyze").show();
        block();

        ajaxSend("/misc/modeule/timeline/6/" + id)
        .then((response) => {
            unblock();
                if (response.success) {

                    if (response.history.length > 0) {
                        // $(".histId").html('Histórico ID# ' + id);
                        $(".histId").html(response.code);
                        var list = '';
                        console.log(response.history);

                        for (let i = 0; i < response.history.length; i++) {
                            var obj = response.history[i];
                            var status;
                            if (obj.type == 1) {
                                status = 'Aprovado';
                            } else if (obj.type == 2) {
                                status = 'Reprovado';
                            } else if (obj.type == 3) {
                                status = 'Suspenso';
                            } else if (obj.type == 4) {
                                status = 'Aguardando aprovação';
                            }

                            list += '<li class="timeline-items timeline-icon-'+ obj.status +' active">';
                            if (obj.type != 4) {
                                list += '<div class="timeline-time">'+ obj.created_at +'</div>';
                            } else {
                                list += '<div class="timeline-time">--</div>';
                            }


                            for (let index = 0; index < obj.users.length; index++) {
                                var obj_users = obj.users[index];

                                list += '<h6 class="timeline-title"><a target="_blank" href="/user/view/'+ obj_users.r_code +'">'+ obj_users.name +'</a></h6>';

                            }

                            list += '<p class="timeline-text">'+ obj.sector +': <b>'+ status +'</b></p>';
                            if (obj.type != 4 && obj.message != null && obj.message != "") {
                                list += '<div class="timeline-content">'+ obj.message +'</div>';
                            }
                            list += '</li>';

                        }

                        $(".listitens").html(list);
                        $($(".customizer")).toggleClass('open');

                    } else {
                        error('Ainda não foi enviado para análise.');
                    }

                } else {
                    error(response.msg);
                }

        })
        .catch((error) => {
            $error(error.message);
            unblock();
        });
    }


    function ajaxPaginator(url_page, html_render) {

        block();
        let $params = {
                type: "GET",
                data: '',
                url: url_page,
            };

        ajaxSend($params.url, $params.data, $params.type,$params.form)
        .then((response) => {
                $(html_render).html(response);
                unblock();
        })
        .catch((error) => {
            $error(error.message);
            unblock();
        });

    }

    function windowOpen(url, name) {
      var win =  window.open(url, name, 'width=650,height=600');
    }

    function resetFields() {

        let form = $("#a_update_form");
        form.each(function(){
            this.reset();
        });
        form.removeClass('was-validated');

        $("#a_update_form").find("#type_entry").val(1).trigger('change');
        $("#a_update_form").find("#item_id").val(0);
        $("#a_update_form").find("#receipt_id").val(0);
        $("#a_update_form").find("#other_id").val(0);
        $("#a_update_form").find("#receipt_url").hide();
        $("#a_update_form").find("#other_url").hide();

    }

    function getLendingPending() {
        ajaxPaginator("{{$rotaEmprestimosPendentes}}","#ajax-table");
        $('#modal-refund').modal('toggle');
    }
	@if ($id != 0)
    @if ($accountability->has_analyze == 0 and $accountability->is_reprov == 1 or $accountability->has_analyze == 0 and $accountability->is_reprov == 0 and $accountability->is_approv == 0)
    function Approv() {

        if($('#UpdateAccount').find('#account').val() !=""){
            Swal.fire({
                title: 'Aprovação',
                text: "Confirma o envio da prestação de Contas?",
                type: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#3085d6',
                cancelButtonColor: '#d33',
                confirmButtonText: '<?= __('layout_i.btn_confirm') ?>',
                cancelButtonText: '<?= __('layout_i.btn_cancel') ?>',
                confirmButtonClass: 'btn btn-success',
                cancelButtonClass: 'btn btn-danger ml-1',
                buttonsStyling: false,
                }).then(function (result) {
                    if (result.value) {

                        block();
                        let $action = $("#f_send_analyze").attr("action");
                        $action = $action+id_accountability;
                        $("#f_send_analyze").attr("action",$action);
                        $("#f_send_analyze").submit();

                    }
            })
        }else{
            Swal.fire({
                title: 'Conta bancaria não informada',
                text: "Por favor atualize os dados de sua conta bancaria!",
                type: 'warning',
                showCancelButton: true,
                showConfirmButton: false,

                cancelButtonColor: '#d33',

                cancelButtonText: 'Fechar',

                cancelButtonClass: 'btn btn-danger ml-1',
                buttonsStyling: false,
                }).then(function (result) {
                    if (result.value) {
                        block();
                        let $action = $("#f_send_analyze").attr("action");
                        $action = $action+id_accountability;
                        $("#f_send_analyze").attr("action",$action);
                        $("#f_send_analyze").submit();

                    }
            })

        }



    }
		@endif
    @endif

    function editModal(elem,index) {
        $(".title-item").html('EDITANDO ITEM');

        let json_row = arrayItens[index];;

        $("#a_update_form").find("#item_id").val(json_row.id);
        $("#a_update_form").find("#type_entry").val(json_row.type_entry).trigger('change');
        $("#a_update_form").find("#type").val(json_row.type).change();
        $("#a_update_form").find("#description").val(json_row.description);
        $("#a_update_form").find("#peoples").val(json_row.peoples);
        $("#a_update_form").find("#city").val(json_row.city);
        $("#a_update_form").find("#currency").val(json_row.currency);
        $("#a_update_form").find("#total").val(json_row.total_formated);

		@if ($has_analyze == 0)
        let date = new Date(json_row.date);
        let picker = $("#a_update_form").find("#date").pickadate('picker');
        picker.set('select', date);
		@endif

        if( json_row.attach[0] ){
                $("#a_update_form").find("#receipt_id").val(json_row.attach[0].id);
                $("#a_update_form").find("#receipt_url").show();
                $("#a_update_form").find("#receipt_url").attr('href', json_row.attach[0].url);
                $("#a_update_form").find("#receipt_url").html(json_row.attach[0].name);
        }else{
            $("#a_update_form").find("#receipt_url").hide();
        }
        if( json_row.attach[1] ){
            $("#modal-new").find("#other_id").val(json_row.attach[0].id);
            $("#a_update_form").find("#other_url").show();
            $("#a_update_form").find("#other_url").attr('href', json_row.attach[0].url);
            $("#a_update_form").find("#other_url").html(json_row.attach[0].name);
        }else{
            $("#a_update_form").find("#other_url").hide();
        }


        $('#modal-update').modal({
            backdrop: 'static',
            keyboard: false
        });

    }

    function deletes(id) {

        Swal.fire({
            title: 'Deletar item',
            text: "Você tem certeza dessa ação?",
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

                    block();

                    $("#f_delete_item").find('#item_id').val(id);
                    $("#f_delete_item").submit();
                }
            })

    }

    @if ($has_analyze == 1)
    @if ($accountability->r_code != Session::get('r_code'))
    function suspending() {
        if ($("#password").val() == "") {

            return error("Você precisa digitar sua senha de acesso para realizar essa ação!");
        } else {
            block();
            window.location.href = "/misc/suspended/request/3/<?= $id ?>?r_val_3=" + $("#r_val_3").val() + "&password=" + $("#password").val() + "&people=" + $("#people").val();
        }
    }
    function retroc() {
        if ($("#password").val() == "") {

            return error("Você precisa digitar sua senha de acesso para realizar essa ação!");
        } else if ($("#step").val() == "") {

            return error("Você precisa escolher a etapa de volta.");
        } else {
            block();
            window.location.href = "/misc/retroc/request/3/<?= $id ?>?description=" + $("#r_val_4").val() + "&password=" + $("#password").val() + "&step=" + $("#step").val();
        }
    }

    function approvPayment() {
        if ($("#password").val() == "") {

            return error("Você precisa digitar sua senha de acesso para realizar essa ação!");
        } else {
            block();
            @if ($is_financy == 1)
                window.location.href = "/financy/payment/analyze/<?= $id ?>/1?description=" + $("#r_val_1").val() + "&password=" + $("#password").val();
            @else
                window.location.href = "/financy/payment/analyze/<?= $id ?>/1?description=" + $("#r_val_1").val() + "&password=" + $("#password").val();
            @endif
        }
    }

    function reprovPayment() {
        if ($("#password").val() == "") {

            return error("Você precisa digitar sua senha de acesso para realizar essa ação!");
        } else if ($("#r_val_2").val() == "") {

            return error("Por favor, digite o motivo da reprovação!");
        } else {
            block();
            window.location.href = "/financy/payment/analyze/<?= $id ?>/2?description=" + $("#r_val_2").val() + "&password=" + $("#password").val();
        }
    }
    @endif
    @endif

    $(document).ready(function () {
		
		var confirm_term =  localStorage.getItem("account_confirm_term");
        if(JSON.parse(confirm_term)) {
            $("#termsModal").modal('hide');
        } else {
            $("#termsModal").modal('show');
        }

        $("#btn_confirm_term").click(function() {
            localStorage.setItem("account_confirm_term", true);
            $("#termsModal").modal('hide');
        });

		/*$("#termsModal").modal({
			keyboard: false,
			backdrop: "static"
		});*/
		
        $('#type_entry').on('change', function (e) {

            if( $(this).val() == 2 ){
                $("#a_update_form").find('#type').val(7);
                $("#a_update_form").find("label[for='date']").html("DATA DE LANÇAMENTO");

                $("#a_update_form").find('#peoples').val(1);
                $("#a_update_form").find("#city").removeAttr('required');
                $("#a_update_form").find('#city').val(null);

                $("#a_update_form").find('#currency').val(1);
                $("#a_update_form").find('.hide-elements').hide();

            }else{
                $("#a_update_form").find("label[for='date']").html("DATA DE CONSUMO");
                $("#a_update_form").find('.hide-elements').show();
                $("#a_update_form").find("#city").attr('required',true);
            }

        });


        $(document).on('click',".select_lending",function(e) {
            e.preventDefault();
            let json_row = JSON.parse($(this).attr("data-object"));

                block();
                let post_data = {
                    id: $("#a_update_form").find('#id').val(),
                    lending_request_id:json_row.id,
                    description_accountability: $("#question").val()
                };

                ajaxSend(`/financy/accountability/change/lending`,post_data,'POST')
                .then((response) => {
                        window.location.href = response.redirect;
                })
                .catch((error) => {
                    $error(error.message);
                    unblock();
                });

        });

        $(document).on('click',".showDetails td:not(.no-click)",function(e) {
            e.preventDefault();
            $(this).parent().next().toggle();
            $(this).parent().find('.row_expand').toggleClass('bx-plus-circle');
        });


        $(".js-select2").select2({
            maximumSelectionLength: 1,
        });

        $('[data-toggle="popover"]').popover({
            placement: 'right',
            trigger: 'hover',
        });

        setInterval(() => {
            $("#mAdmin").addClass('sidebar-group-active active');
            $("#mFinancyLending").addClass('sidebar-group-active active');
            $("#mFinancyAccountability").addClass('sidebar-group-active active');
            $("#mFinancyAccountabilityNew").addClass('active');
        }, 100);

        $('#total').mask('##.##0,00', {reverse: true});

        $("#clickexport").click(function (e) {
            $("#exportSubmit").submit();

        });

        var options = {
                onKeyPress : function(cpfcnpj, e, field, options) {
                    var masks = ['000.000.000-009', '00.000.000/0000-00'];
                    var mask = (cpfcnpj.length > 14) ? masks[1] : masks[0];
                    $('#identity').mask(mask, options);
                }
            };
        $('#identity').mask('000.000.000-009', options);

        $('.date-format').pickadate({
            formatSubmit: 'yyyy-mm-dd',
            format: 'dd/mm/yyyy',
            today: 'Hoje',
            clear: 'Limpar',
            close: 'Fechar',
            monthsFull: ['Janeiro', 'Fevereiro', 'Março', 'Abril', 'Maio', 'Junho', 'Julho', 'Agosto', 'Setembro', 'Outubro', 'Novembro', 'Dezembro'],
            monthsShort: ['Jan', 'Fev', 'Mar', 'Abr', 'Mai', 'Jun', 'Jul', 'Ago', 'Set', 'Out', 'Nov', 'Dez'],
            weekdaysFull: ['Domingo', 'Segunda-Feira', 'Terça-Feira', 'Quarta-Feira', 'Quinta-Feira', 'Sexta-Feira', 'Sábado'],
            weekdaysShort: ['Dom', 'Seg', 'Ter', 'Qua', 'Qui', 'Sex', 'Sab'],
        });
        //$('.date-format').removeAttr('readonly');



        if (arrayItens && arrayItens.length > 0) {
            $(".sendapprov").show();
        }

        $("#updoradditem").click(function (e) {

            //remove required quando houver anexo
            if( $("#a_update_form").find("#receipt_id").val() != 0){
                $("#a_update_form").find("#receipt").removeAttr('required');
            }

            let form = $("#a_update_form");
            if (form[0].checkValidity() === false) {
                    e.preventDefault();
                    e.stopPropagation();
                    form.addClass('was-validated');
            }else{
                if ($("#total").val() == "" || $("#total").val() == "0,00") {
                    return error('Preecha o valor total.');
                } else if ($("#date").val() == "") {
                    return error('Preecha a data de consumo.');
                }


                $("#a_update_form").submit();
            }


        });


        // RESPONSIVE FIELDS
        $(window).resize(function() {
            var width = $(window).width();
            if (width <= 380){
                $("*#mobile-c9-c12").each(function (index, element) {
                    $(element).removeClass().addClass('col-12');

                });
                $("*#mobile-c3-c12").each(function (index, element) {
                    $(element).removeClass().addClass('col-12');
                    $(element).removeAttr('style');

                });
            } else {
                $("*#mobile-c9-c12").each(function (index, element) {
                    $(element).removeClass().addClass('col-9');

                });
                $("*#mobile-c3-c12").each(function (index, element) {
                    $(element).removeClass().addClass('col-3');
                    $(element).attr('style', 'position: fixed; right: 0');

                });
            }
        });


        if ($(window).width() <= 380)
        {
            $("*#mobile-c9-c12").each(function (index, element) {
                $(element).removeClass().addClass('col-12');

            });
            $("*#mobile-c3-c12").each(function (index, element) {
                $(element).removeClass().addClass('col-12');
                $(element).removeAttr('style');

            });
        } else {
            $("*#mobile-c9-c12").each(function (index, element) {
                $(element).removeClass().addClass('col-9');

            });
            $("*#mobile-c3-c12").each(function (index, element) {
                $(element).removeClass().addClass('col-3');
                $(element).attr('style', 'position: fixed; right: 0');

            });
        }

        $("#UpdateAccount").submit(function (e) {
            if ($("#agency").val() == "") {

                error('<?= __('lending_i.lrn_43') ?>');
                e.preventDefault();
            } else if ($("#account").val() == "") {

                error('<?= __('lending_i.lrn_44') ?>');
                e.preventDefault();
            } else if ($("#bank").val() == "") {

                error('<?= __('lending_i.lrn_45') ?>');
                e.preventDefault();
            } else if ($("#identity").val() == "") {

                error('<?= __('lending_i.lrn_46') ?>');
                e.preventDefault();
            } else {

                $.ajax({
                    type: "POST",
                    url: "/financy/lending/bank_upd",
                    data: {agency: $("#agency").val(), account: $("#account").val(), bank: $("#bank").val(), identity: $("#identity").val()},
                    success: function (response) {
                        success('<?= __('lending_i.lrn_47') ?>');
                        $("#modal-account").modal('toggle');
                    }
                });

                e.preventDefault();
            }


        });

        $("#HAnalyze").click(function (e) {
            $("#Analyze").hide();

        });

        $("#showAnalyze").click(function (e) {
            $("#Analyze").show();

        });

        $("#question").blur(function (e) {
            $("#a_update_form").find('#description_accountability').val($("#question").val());
            $("#f_send_analyze").find('#description_accountability').val($("#question").val());
        });


        $("#newItem").click(function (e) {
			resetFields();
            $(".title-item").html('NOVO ITEM');

            if($("#a_update_form").find('#lending_request_id').val() == 0){
                ajaxPaginator("{{$rotaEmprestimosPendentes}}","#ajax-table");
                $('#modal-refund').modal('toggle');
            }else{
                $('#modal-update').modal('toggle');
            }
            // console.log($("#a_update_form").find('#lending_request_id').val());


        });

    });
    </script>

@endsection
