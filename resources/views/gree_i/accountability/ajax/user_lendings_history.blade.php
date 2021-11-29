<!-- datatable start -->
<div>
    

    @if($lendings)
		<div class="table-responsive">
        <table class="table table-striped mb-0">
            <thead>
                <tr>
                    <th>#ID</th>
                    <th style="min-width: 150px;">Valor Emprestimos</th>
                    <th style="min-width: 150px;">Total Pago</th>
                    <th style="min-width: 150px;">Total Em Análise</th>
                    <th style="min-width: 150px;">Saldo</th>
                    <th>Descrição</th>
                    <th>Ações</th>
                </tr>
            </thead>
            <tbody>
            
                @php($totalEmprestimo=0 )
                @php($totalPC = 0 )
                @php($totalAnalyze = 0 )
                @php($totalPendente = 0)
                @php($toltip = "")
                @php($class = "")

            @foreach ($lendings as $i=>$lending)
                <tr>
                    @php($item_totalEmprestimo=$lending->getTotalEmprestimo() )
                    @php($item_totalPago = $lending->getTotalPago(1) )
                    @php($item_totalAnalise = $lending->getTotalPago(2) )
                    @php($item_totalPendente = $lending->getTotalPendente())
                    
                    @php($item_toltip = "")
                    @php($item_class = "")

                    @php($totalEmprestimo+= $item_totalEmprestimo )
                    @php($totalPC+= $item_totalPago )
                    @php($totalAnalyze+= $item_totalAnalise )
                    @php($totalPendente+= $item_totalPendente )

                    @if ($item_totalPendente>0)
                        @php($item_class="color:green;")
                        @php($item_toltip="Saldo a Receber")
                    @else
                        @php($item_class="color:red;")
                        @php($item_toltip="Saldo a Pagar")
                    @endif

                    <td><small>{{$lending->code}}</small></td>

                    <td style="color:blue"><small>{{formatMoney(abs($item_totalEmprestimo))}}</small></td>
                    <td style="color:red"><small>{{formatMoney(abs($item_totalPago))}}</small></td>

                    @if($totalAnalyze!=0)
                        <td style="color:red"><small>{{formatMoney(abs($item_totalAnalise))}}</small></td>
                    @else
                        <td></td>
                    @endif

                    <td style="{{$item_class}}">
                        <small data-toggle="popover" data-content="{{$item_toltip}}">{{formatMoney(abs($item_totalPendente))}}</small>
                    </td>
                    
                    <td>
                        <small data-toggle="popover" data-content="<?= $lending->description ?>">{{Str::limit($lending->description,25)}}</small>
                    </td>
                    <td>
                        
                        <div class="dropleft">
                            <span class="bx bx-dots-horizontal-rounded font-medium-3 nav-hide-arrow cursor-pointer" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false" role="menu"></span>
                            <div class="dropdown-menu dropdown-menu-right">
                                
                                <a class="dropdown-item" onclick="showAccountabilityModal(this,{{$lending->id}})" json-data="<?= htmlspecialchars(json_encode($lending), ENT_QUOTES, 'UTF-8') ?>" href="javascript:void(0);"><i class="bx bx-edit-alt mr-1"></i> Prestações de Contas</a>

                            </div>
                        </div>
                    </td>
                </tr>
                
            @endforeach
            </tbody>
            <tfoot>
                <tr>
                    @if ($totalPendente>0)
                        @php($class="color:green;")
                        @php($toltip="Total a Receber")
                    @else
                        @php($class="color:red;")
                        @php($toltip="Total a Pagar")
                    @endif

                    <th>Total</th>
                    <th style="color:blue"><small>{{formatMoney(abs($totalEmprestimo))}}</small></th>
                    <th style="color:red"><small>{{formatMoney(abs($totalPC))}}</small></th>
                    @if($totalAnalyze!=0)
                        <th style="color:red"><small>{{formatMoney(abs($totalAnalyze))}}</small></th>
                    @else
                        <th></th>
                    @endif

                    <th colspan="3" style="{{$class}}">
                        <small data-toggle="popover" data-content="{{$toltip}}">{{formatMoney(abs($totalPendente))}}</small>
                    </th>
                </tr>
            </tfoot>
        </table>
		</div>
        <nav aria-label="Page navigation" class="mt-2">
            <ul class="pagination justify-content-end">
                <?= $lendings->links('vendor.pagination.ajax',['html_render' => $html_render]); ?>
            </ul>
        </nav>
    @endif

</div>
<!-- datatable ends -->