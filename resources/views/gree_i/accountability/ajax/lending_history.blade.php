<!-- datatable start -->
<div>
    

    <div class="table-responsive-md">
        <table class="table mb-0">
          <thead class="thead-dark">
            
          </thead>
          
          <tbody id="ListItens">
            @php($totalEmprestimo=$lending->getTotalEmprestimo() )
            @php($totalPC = $lending->getTotalPago(3) )
            @php($totalAprovado = $lending->getTotalPago(1) )
            @php($totalAnalise = $lending->getTotalPago(2) )
            @php($totalPendente = $lending->getTotalPendente())
            @php($totalReprovado = $lending->getTotalReprovado())
            @php($totalReembolso = $lending->getTotalReembolso())

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
                                                    @if($lending->is_paid==1)
                                                        <a href="/financy/lending/all?id=<?= $lending->code ?>" target="_blank" href="javascript:void(0)">
                                                            {{$lending->code}}
                                                        </a>
                                                    @else
                                                        {{$lending->code}}
                                                    @endif
                                                    </small>
                                                </td>
                                                <td style="color:blue;">
                                                    <small>{{formatMoney($lending->amount)}}</small>
                                                </td>
                                                <td><small>EMPRÉSTIMO</small></td>
                                                <td><small>{{$lending->created_at->format('d/m/Y')}}</small></td>
                                                <td></td>
                                            </tr>
                                            @if($lending->prestacao_conta_manual->isNotEmpty())
                                                @foreach ($lending->prestacao_conta_manual->where('type_entry',1) as $index => $key)
                                                    
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
                                                            @if($show_actions AND $lending->isPending())
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
                                            
                                            @if($lending->prestacao_conta->isNotEmpty())
                                                @foreach ($lending->prestacao_conta as $index => $key)
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
                                                                    
                                                                    <?php if ($key->mng_approv == 1 and $key->financy_approv == 1 and $key->pres_approv == 1) { ?>
                                                                        
                                                                        <a href="/financy/payment/request/print/<?= $key->payment_request_id ?>" class="dropdown-item" target="_blank" href="javascript:void(0)"><i class="bx bx-printer mr-1"></i>Impr. Solicitação Pag.</a>
                                                                    <?php } ?>
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
                                                                                        
                                                                                        {{$item->total_money}}
                                                                    
                                                                                        @if($item->currency > 1)
                                                                                            <i class="bx bxs-help-circle cursor-pointer" style="position: relative;top: 3px; left: 0px;" data-html="true" data-toggle="tooltip" data-placement="bottom" title="<span>{{$item->total_money_currency}} * <small>{{$item->quotation}}</small> : {{$item->total_money}}</span>"></i>
                                                                                        @endif
                                                                                    </td>
                                                                                    
                                                                                    <td>{{$item->date_formated}}</td>
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

                                            @if($lending->prestacao_conta_manual->isNotEmpty())
                                                @foreach ($lending->prestacao_conta_manual->where('type_entry',2) as $index => $key)
                                                    
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
                                                            @if($show_actions AND $lending->isPending())
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
<!-- datatable ends -->