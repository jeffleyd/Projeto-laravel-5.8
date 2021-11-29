@extends('gree_i.layout')

@section('content')
<link rel="stylesheet" type="text/css" href="/admin/app-assets/vendors/css/pickers/pickadate/pickadate.css">
<div class="content-overlay"></div>
<div class="content-wrapper">
    <div class="content-header row">
        <div class="content-header-left col-12 mb-2 mt-1">
          <div class="row breadcrumbs-top">
            <div class="col-12">
              <h5 class="content-header-title float-left pr-1 mb-0">Usuários Devedores</h5>
              <div class="breadcrumb-wrapper col-12">
                Todas os Devedores
              </div>
            </div>
          </div>
        </div>
      </div>      
    <div class="content-header row">
    </div>
    <div class="content-body">
        <div class="users-list-filter px-1">
            <form action="{{Request::url()}}" id="search" method="GET">
                <div class="row border rounded py-2 mb-2">
                    <div class="col-12 col-sm-12 col-lg-6">
                        <label for="users-list-verified">{{ __('trip_i.tntp_collaborator') }}</label>
                        <fieldset class="form-group">
                            <select class="js-select2 form-control" id="r_code" name="r_code" style="width: 100%;" data-placeholder="{{ __('trip_i.td_search_r_code') }}" multiple>
                                <option></option>
                                <?php foreach ($userall as $key) { ?>
                                    <option value="<?= $key->r_code ?>"
                                        @if ( $key->r_code == Session::get('accountability_filter_r_code'))
                                            selected
                                        @endif
                                    ><?= $key->first_name ." ". $key->last_name ?> (<?= $key->r_code ?>)</option>
                                <?php } ?>
                            </select>
                        </fieldset>
                    </div>
                    {{-- <div class="col-12 col-sm-12 col-lg-3">
                        <label for="users-list-verified">Status</label>
                        <fieldset class="form-group">
                            <select class="form-control" id="f_status" name="f_status">
                                @php($f_status = Session::get('accountability_filter_f_status'))
                                <option value="1" 
                                    @if ($f_status==1)
                                        selected
                                    @endif
                                >Emprestimos Pendentes
                                </option>
                                <option value="2"
                                    @if ( 2 == $f_status)
                                        selected
                                    @endif
                                >Todos os Emprestimos
                                </option>
                                
                            </select>
                        </fieldset>
                    </div> --}}
                    <div class="col-12 col-sm-12 col-lg-2 d-flex align-items-center">
                        <button type="submit" class="btn btn-primary btn-block glow users-list-clear mb-0">{{ __('news_i.lt_03') }}</button>
                    </div>
                </div>
            </form>
        </div>
        <section class="users-list-wrapper">
            <div class="users-list-table">
                <div class="card">
                    <div class="card-header">
                        <h4 class="card-title">Lista de Devedores </h4>
                        <a class="heading-elements-toggle">
                            <i class="bx bx-dots-vertical font-medium-3"></i>
                        </a>
                        <div class="heading-elements">
                            
                            <form action="{{Request::url()}}" id="f_export" method="GET">
                                <input type="hidden" id="export" name="export" value="1">
                                <input type="hidden" id="r_code" name="r_code" value="">
                                <button type="button" id="btn_export" class="btn btn-primary btn-block glow users-list-clear mb-0">
                                    <i class="bx bx-spreadsheet font-medium-3"></i>
                                    Exportar para Excel
                                </button>
                            </form>
                                
                            
                        </div>
                    </div>

                    <div class="card-content">

                        <div class="card-body">
                            <!-- datatable start -->
                            <div class="table-responsive">
                                <table id="list-datatable" class="table">
                                    <thead>
                                        <tr>
                                            <th></th>
                                            <th>Devedor</th>
                                            <th>Total de Pendente</th>
                                            <th>Total Pago</th>
                                            <th>Total Em Análise</th>
                                            <th>Saldo</th>
                                            
                                            <th>Ultima Atualização</th>
                                            
                                        </tr>
                                    </thead>
                                    <tbody>
                                        
                                    @foreach ($models as $index=>$model)
                                        
                                        @php($totalEmprestimo=$model->total_lendings )
                                        @php($totalPC = $model->total_paid )
                                        @php($totalAnalyze = $model->total_analyze)
                                        @php($totalPendente = $model->balance_due)
                                        
                                        @if ($totalPendente>0)
                                            @php($class="color:green;")
                                            @php($toltip="Saldo a Receber")
                                        @else
                                            @php($class="color:red;")
                                            @php($toltip="Saldo a Pagar")
                                        @endif

                                        <tr class="cursor-pointer showDetails">
                                            <td style="width: 1%">
                                                <i class="row_expand bx bx-plus-circle bx-minus-circle cursor-pointer"></i>
                                            </td>
                                            <td>@if($model->user) <a target="_blank" href="/user/view/<?= $model->r_code ?>">{{$model->user->short_name}}</a> @endif</td>
                                            <td style="color:blue">
                                                <small>{{formatMoney( $totalEmprestimo )}}</small>
                                            </td>
                                            <td style="color:red">
                                                <small>{{formatMoney( $totalPC )}}</small>
                                            </td>
                                            <td style="color:red">
                                                <small>{{formatMoney( $totalAnalyze )}}</small>
                                            </td>
                                            <td style="{{$class}}">
                                                <small data-toggle="popover" data-content="{{$toltip}}">{{formatMoney(abs($totalPendente))}}</small>
                                            </td>
                                            
                                            
                                            <td><small>{{$model->updated_at->format('d/m/Y')}}</small></td>
                                            
                                        </tr>
                                        <tr style="display:none" r_code="{{$model->r_code}}" class="hideDetails group">
                                            <td colspan="8">
                                                
                                                <div class="card">
                                                    <div class="card-header" >
                                                        <h4 class="card-title">Emprestimos Realizados </h4>
                                                    </div>
                                                    <div class="card-header">
                                                        <form id="f_export_{{$model->r_code}}" method="GET">
                                                            <input type="hidden" id="f_export_{{$model->r_code}}" name="f_export_{{$model->r_code}}" value="1">
                                                            <div class="row">
                                                                <div class="col-12 col-sm-12 col-lg-3">
                                                                    <label for="users-list-verified">#ID</label>
                                                                    <fieldset class="form-group">
                                                                        <input type="text" class="form-control" id="f_code_{{$model->r_code}}" name="f_code_{{$model->r_code}}" value="<?= Session::get('ajax_filter_f_code_'.$model->r_code) ?>">
                                                                    </fieldset>
                                                                </div>
                                                                <div class="col-12 col-sm-12 col-lg-3">
                                                                    <label for="f_status_{{$model->r_code}}">Status</label>
                                                                    <fieldset class="form-group">
                                                                        <select class="form-control" id="f_status_{{$model->r_code}}" name="f_status_{{$model->r_code}}">
                                                                            @php($f_status = Session::get('ajax_filter_f_status_'.$model->r_code))
                                                                            <option value="1" 
                                                                                @if ($f_status==1)
                                                                                    selected
                                                                                @endif
                                                                            >Emprestimos Pendentes
                                                                            </option>
                                                                            <option value="2" 
                                                                                @if ($f_status==2)
                                                                                    selected
                                                                                @endif
                                                                            >Emprestimos Pagos
                                                                            </option>
                                                                            <option value="3"
                                                                                @if ( $f_status == 3)
                                                                                    selected
                                                                                @endif
                                                                            >Todos os Emprestimos
                                                                            </option>
                                                                            
                                                                        </select>
                                                                    </fieldset>
                                                                </div>
                                                                <div class="col-12 col-sm-12 col-lg-2 d-flex align-items-center">
                                                                    <button type="button" r_code="{{$model->r_code}}" class="filter-ajax btn btn-primary btn-block glow users-list-clear mb-0">{{ __('news_i.lt_03') }}</button>
                                                                </div>
                                                                
                                                                <div class="col-12 col-sm-12 col-lg-4 d-flex align-items-center" style="justify-content: end;">
                                                                    <button type="button" r_code="{{$model->r_code}}" class="btn_export btn btn-primary btn-block glow users-list-clear mb-0" style="max-width: 215px;">
                                                                        <i class="bx bx-spreadsheet font-medium-3"></i>
                                                                        Exportar para Excel
                                                                    </button>
                                                                </div>
                                                                
                                                            </div>
                                                        </form>
                                                            
                                                        
                                                    </div>
                                                    <div class="card-content collapse show" style="">
                                                        <div class="card-body">
                                                        
                                                            <div id="ajax-table-lendings_{{$model->r_code}}" class="ajax-table-lendings"></div>

                                                        </div>
                                                    </div>
                                                </div>
                                                
                                            </td>
                                        </tr>
                                    @endforeach
                                        
                                    </tbody>
                                </table>
                                <nav aria-label="Page navigation">
                                    <ul class="pagination justify-content-end">
                                        <?= $models->appends([
											'r_code' => Request::get('r_code')
											])->links(); ?>
                                    </ul>
                                </nav>
                            </div>
                            <!-- datatable ends -->
                        </div>
                        
                    </div>
                </div>
            </div>
        </section>
        <!-- users list ends -->
    </div>
</div>


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

    <div class="modal fade text-left" id="modal-accountability" tabindex="-1" role="dialog" aria-labelledby="modal-accountability" aria-hidden="true">
        <div class="modal-dialog modal-xl modal-dialog-scrollable" role="document">
        <div class="modal-content">
            <div class="modal-header">
            <h3 class="modal-title">PRESTAÇÃO DE CONTAS</h3>
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
            </div>
        </div>
        </div>
    </div>

    <div class="modal fade text-left" id="modal-pending-lending" tabindex="-1" role="dialog" aria-labelledby="modal-pending-lending" aria-hidden="true">
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
                <div id="ajax-table-pending-lending"></div>
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

    <div class="modal fade text-left" id="modal-new" tabindex="-1" role="dialog" aria-labelledby="modal-new" aria-hidden="true">
        <div class="modal-dialog modal-xl modal-dialog-centered" role="document">
        <div class="modal-content">
           <div class="modal-header bg-primary white">
           <span class="modal-title title-item" id="modal-update"></span>
           <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                 <i class="bx bx-x"></i>
           </button>
           </div>
           <form class="push" action="/financy/accountability/manual_entry/edit_do" id="a_update_form" method="POST" enctype="multipart/form-data">
           <div class="modal-body">
  
                 <div class="row">
                    <input type="hidden" id="entry_id" name="entry_id" value="0">
                    <input type="hidden" id="financy_lending_id" name="financy_lending_id" value="0">
                    <input type="hidden" id="id" name="id" value="0">
  
                    <div class="col-sm-8">
                       <label for="sector">COLABORADOR</label>
                       <input type="hidden" name="user_r_code" id="user_r_code" class="form-control" required>
                       <fieldset class="form-group">
                                <select class="js-select2 form-control" required id="user_name" name="user_name" style="width: 100%;" data-placeholder="{{ __('trip_i.td_search_r_code') }}" multiple readonly disabled>
                                    <option></option>
                                    @foreach ($userall as $key)
                                        <option value="{{$key->r_code}}">{{$key->first_name ." ". $key->last_name}} ({{$key->r_code}})</option>
                                    @endforeach
                                </select>
                        </fieldset>
                    </div>
                    <div class="col-md-4 col-sm-4">
                      <label for="date">DATA</label>
                      <fieldset class="form-group">
                            <input type="text" name="date" id="date" class="form-control date-format js-flatpickr js-flatpickr-enabled flatpickr-input" required>
                      </fieldset>
                   </div>
                    <div class="col-sm-12">
                       <label for="description">DESCRIÇÃO DO LANÇAMENTO</label>
                       <fieldset class="form-group">
                             <textarea class="form-control" name="description" required id="description" rows="3" placeholder="Justifique se necessário..."></textarea>
                       </fieldset>
                    </div>
                    
                    <div class="col-md-4 col-sm-4">
                       <label for="type_entry">TIPO DE LANÇAMENTO</label>
                       <fieldset class="form-group">
                                  <select class="form-control" name="type_entry" required id="type_entry" style="width: 100%;" data-placeholder="">
                                      <option></option>
                                      <option value="1">Prestação de Contas (+)</option>
                                      <option value="2">Prestação de Contas (-)</option>
                                  </select>
                       </fieldset>
                    </div>
                    <div class="col-sm-4">
                       <label for="p_method">FORMA DE PAGAMENTO</label>
                       <fieldset class="form-group">
                             <select class="form-control" name="p_method" required id="p_method" style="width: 100%;" data-placeholder="">
                                          <option></option>
                                          <option value="2">Transferência / D.Automático</option>
                                          <option value="3">Caixa</option>
                             </select>
                       </fieldset>
                    </div>
                    <div class="col-md-4 col-sm-4">
                       <label for="total">VALOR</label>
                       <fieldset class="form-group">
                             <input type="text" class="form-control" name="total" required id="total" placeholder="0,00">
                       </fieldset>
                    </div>
                          
                    <div class="col-sm-12">
                       <label for="receipt">COMPROVANTE</label>
                       <fieldset class="form-group">
                             <input type="hidden" id="receipt_id" name="receipt_id" value="0">
                             <input type="file" class="form-control" name="receipt" required id="receipt"> 
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
                    
                    
                 </div>
           </div>
           <div class="modal-footer">
                 <button type="button" onclick="resetFields();" class="btn btn-light-secondary" data-dismiss="modal">
                    <i class="bx bx-x d-block d-sm-none"></i>
                    <span class="d-none d-sm-block">FECHAR</span>
                 </button>
                  <button type="button" id="updoradditem" class="btn btn-success ml-1">
                      <i class="bx bx-check d-block d-sm-none"></i>
                      <span class="d-none d-sm-block">ATUALIZAR</span>
                  </button>
           </div>
           </form>
        </div>
        </div>
    </div>

    <form class="push" action="/financy/accountability/manual_entry/item/delete" id="f_delete_item" method="POST">
        <input type="hidden" id="item_id" name="item_id" value="0">
    </form>

    <script>
        var is_edit = 0;

    function seeAnalyzes(id) {
        block();
        $.ajax({
            type: "GET",
            url: "/misc/modeule/timeline/6/" + id,
            success: function (response) {
                unblock();
                if (response.success) {

                    if (response.history.length > 0) {
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
            }
        });
	 }
	 
	 function resetFields() {

        let form = $("#a_update_form");
        form.each(function(){
            this.reset();
        });
        form.removeClass('was-validated');

        $("#modal-new").find("#id").val(0);
        $("#modal-new").find('#financy_lending_id').val(0);
        $("#modal-new").find('#entry_id').val(0);

        $("#modal-new").find('#user_r_code').val(null);
        $("#modal-new").find('#user_name').val(null).trigger('change');

    }

    function ajaxPaginator(url_page, html_render, formdata) {
        block();
        let $params = {
                type: "GET",
                data: formdata,
                url: url_page,
            };
        ajaxSend($params.url, $params.data, $params.type,$params.form)
        .then((response) => {
                $(html_render).html(response);
                
                unblock();
                $('[data-toggle=tooltip]').tooltip();
                $('[data-toggle="popover"]').popover({
                    placement: 'right',
                    trigger: 'hover',
                });
        })
        .catch((error) => {
            $error(error.message);
            unblock();
        });

    }

    function getLendingPending(elem) {
        
        let json_row = JSON.parse($(elem).attr("json-data"));

        $('#modal-new').modal('toggle');
        $('#modal-new').find(".title-item").html('Prestação de Contas Manual (Novo)');
        $("#modal-new").find('#financy_lending_id').val(json_row.id);
        $("#modal-new").find('#entry_id').val(0);
        $("#modal-new").find('#user_r_code').val(json_row.r_code);
        $("#modal-new").find('#user_name').val(json_row.r_code).trigger('change');

    }

    function showAccountabilityModal(elem,idLending) {
        
        //dados do emprestimo
        let json_row = JSON.parse($(elem).attr("json-data"));
        console.log("showAccountabilityModal");
        console.log(json_row);
        
        let botaoFechar = `<button type="button" class="btn btn-light-secondary" data-dismiss="modal">
                                <i class="bx bx-x d-block d-sm-none"></i>
                                <span class="d-none d-sm-block">{{ __('lending_i.lrn_32') }}</span>
                           </button>`;

        $('#modal-accountability').find(".modal-footer").html(botaoFechar);

        block();

        ajaxSend(`/financy/accountability/ajax/lending/history/${idLending}`)
        .then((response) => {
                $("#ajax-table").html(response);

                let botao = `<button type="button" id="bt-new-manual-entry" onclick="getLendingPending(this)" class="btn btn-success">Nova Prestação de Contas</button>`;

                if(json_row.is_accountability_paid==0){
                    $('#modal-accountability').find(".modal-footer").append(botao);
                    $('#bt-new-manual-entry').attr("json-data",$(elem).attr("json-data"));
                }
                $('#modal-accountability').modal('toggle');
                
                unblock();
                $('[data-toggle=tooltip]').tooltip();
                $('[data-toggle="popover"]').popover({
                    placement: 'right',
                    trigger: 'hover',
                });
        })
        .catch((error) => {
            $error(error.message);
            unblock();
        });
    }

    function editLancManual(elem) {
        //reset fields Modal

        $('#modal-new').find(".title-item").html('Prestação de Contas Manual (Edição)');

        let json_row = JSON.parse($(elem).attr("json-data"));
        console.log("editLancManual");
        console.log(json_row);

        //bind hidden ids
        $("#modal-new").find('#financy_lending_id').val(json_row.financy_lending_id);
        $("#modal-new").find('#entry_id').val(json_row.id);


        $("#modal-new").find('#user_r_code').val(json_row.r_code);
        $("#modal-new").find('#user_name').val(json_row.r_code).trigger('change');
        $("#modal-new").find('#type_entry').val(json_row.type_entry).trigger('change');
        $("#modal-new").find('#p_method').val(json_row.p_method).trigger('change');
        $("#modal-new").find('#description').val(json_row.description);
        $("#modal-new").find('#total').val(json_row.total_formated);
        $("#modal-new").find('#date').val(json_row.date_formated);
        
        if( json_row.attach[0] ){
                $("#modal-new").find("#receipt_id").val(json_row.attach[0].id);
                $("#modal-new").find("#receipt_url").show();
                $("#modal-new").find("#receipt_url").attr('href', json_row.attach[0].url);
                $("#modal-new").find("#receipt_url").html(json_row.attach[0].name);
        }else{
            $("#modal-new").find("#receipt_url").hide();
        }
        if( json_row.attach[1] ){
                $("#modal-new").find("#other_id").val(json_row.attach[0].id);
                $("#modal-new").find("#other_url").show();
                $("#modal-new").find("#other_url").attr('href', json_row.attach[0].url);
                $("#modal-new").find("#other_url").html(json_row.attach[0].name);
        }else{
            $("#modal-new").find("#other_url").hide();
        }
        

        $('#modal-new').modal({
            backdrop: 'static',
            keyboard: false
        });
        
    }
    
    function delLancManual(item_id) {

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
                    // $("#f_delete_item").find('#item_id').val(item_id);
                    // $("#f_delete_item").submit();

                    ajaxSend("/financy/accountability/manual_entry/item/delete", {item_id: item_id}, "POST")
                    .then((response) => {
                            window.location.href = response.redirect;
                            // unblock();
                    })
                    .catch((error) => {
                        $error(error.message);
                        unblock();
                    });

                }
            })

    }

    $(document).on('click',".showDetails td:not(.no-click)",function(e) {
        e.preventDefault();
        let elem = $(this).parent().next();
        let r_code = elem.attr("r_code");
        
        elem.toggle();

        $(this).parent().find('.row_expand').toggleClass('bx-plus-circle');

        if( elem.is(":visible") ){
            
            let ajax_table_elem = elem.find(".ajax-table-lendings");
            elem_render = ajax_table_elem.attr('id');
            let f_status = $(`#f_status_${r_code}`).val();
            let f_code = $(`#f_code_${r_code}`).val();

            let post_data = {
                [`f_status_`+r_code]: f_status,
                [`f_code_`+r_code]: f_code
            };

            if(ajax_table_elem.html() == ""){
                ajaxPaginator(`/financy/accountability/ajax/lendings/history/user/${r_code}`,`#${elem_render}`,post_data);
            }
        }

    })

    $(document).on('click',".filter-ajax",function(e) {
        e.preventDefault();
        
        let r_code = $(this).attr("r_code");
        
        let f_status = $(`#f_status_${r_code}`).val();
        let f_code = $(`#f_code_${r_code}`).val();
        
        let post_data = {
            [`f_status_`+r_code]: f_status,
            [`f_code_`+r_code]: f_code
        };
        let elem_render = `ajax-table-lendings_${r_code}`;

        ajaxPaginator(`/financy/accountability/ajax/lendings/history/user/${r_code}`,`#${elem_render}`,post_data);

    })
    
    $(document).on('click',".btn_export",function(e) {
        e.preventDefault();
        
        let r_code = $(this).attr("r_code");
        
        $(`#f_export_${r_code}`).attr('action', `/financy/accountability/ajax/lendings/history/user/${r_code}`);
        $(`#f_export_${r_code}`).submit();
    })

    $(document).on('click',"#btn_export",function(e) {
        e.preventDefault();
        
        $("#f_export").find('#r_code').val($("#search").find('#r_code').val());
        $("#f_export").submit();


    })

    $(function () {
        $('[data-toggle="popover"]').popover({
            placement: 'right',
            trigger: 'hover',
        });
        $('[data-toggle=tooltip]').tooltip();
    })

    $(document).ready(function () {
        
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

        $('.date-format').removeAttr('readonly');
        //$('.mdb-select.select-wrapper .select-dropdown').val("").removeAttr('readonly').attr("placeholder", "Choose your country").prop('required', true).addClass('form-control');
  	

        $('#total').mask('##.##0,00', {reverse: true});
        var options = {
                onKeyPress : function(cpfcnpj, e, field, options) {
                    var masks = ['000.000.000-009', '00.000.000/0000-00'];
                    var mask = (cpfcnpj.length > 14) ? masks[1] : masks[0];
                    $('#identity').mask(mask, options);
                }
            };
        $('#identity').mask('000.000.000-009', options);

        $(".js-select2").select2({
            maximumSelectionLength: 1,
        });
        
        setInterval(() => {
            $("#mAdmin").addClass('sidebar-group-active active');
            $("#mFinancyLending").addClass('sidebar-group-active active');
            $("#mFinancyAccountabilityListDebtors").addClass('active');
        }, 100);

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
		  
		  $("#updoradditem").click(function (e) {

            
            if( $("#a_update_form").find("#receipt_id").val() != 0){
                $("#a_update_form").find("#receipt").removeAttr('required');
            }

            // if( $("#a_update_form").find("#other_id").val() != 0){
            //     $("#a_update_form").find("#other").removeAttr('required');
            // }

            let form = $("#a_update_form");
            if (form[0].checkValidity() === false) {
                    e.preventDefault();
                    e.stopPropagation();
                    form.addClass('was-validated');
            }else{
                
                
                if ($("#total").val() == "" || $("#total").val() == "0,00") {
                    return error('Preecha o valor total.');
                } else if ($("#date").val() == "") {

                    return error('Você precisa informar a data de Lançamento');
                } else if ($("#receipt").val() == "" && is_edit == 0) {

                    // return error('É necessário adicionar o comprovante.');
                }

                // $("#a_update_form").submit();
                block();
                ajaxSend("/financy/accountability/manual_entry/edit_do", $("#a_update_form").serialize(), "POST", 10000, $("#a_update_form"))
                .then((response) => {
                        window.location.href = response.redirect;
                        // unblock();
                        $('#modal-new').modal('toggle');
                })
                .catch((error) => {
                    $error(error.message);
                    unblock();
                });
                
                
                
                // form.removeClass('was-validated');
            }

            
        });

    });
    </script>
@endsection