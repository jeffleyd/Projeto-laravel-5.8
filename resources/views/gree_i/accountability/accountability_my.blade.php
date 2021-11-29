@extends('gree_i.layout')

@section('content')
<div class="content-overlay"></div>
<div class="content-wrapper">
    <div class="content-header row">
        <div class="content-header-left col-12 mb-2 mt-1">
          <div class="row breadcrumbs-top">
            <div class="col-12">
              <h5 class="content-header-title float-left pr-1 mb-0">Prestação de Contas</h5>
              <div class="breadcrumb-wrapper col-12">
                Minhas Prestações de Contas
              </div>
            </div>
          </div>
        </div>
      </div>

      <div class="alert alert-primary alert-dismissible mb-2" role="alert">
        <div class="d-flex align-items-center">
            <i class="bx bxs-info-circle"></i>
            <span>
            Atualize seus dados da sua conta bancária para poder enviar para análise.
            
            </span>
            <div style="width:100%">
            <button type="button" class="btn btn-sm btn-secondary float-right" data-toggle="modal" data-target="#modal-account">{{ __('lending_i.lrn_26') }}</button>
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
                        <label for="users-list-verified">#ID</label>
                        <fieldset class="form-group">
                            <input type="text" class="form-control" id="f_code_{{$r_code}}" name="f_code_{{$r_code}}" value="<?= Session::get("ajax_filter_f_code_".$r_code) ?>">
                        </fieldset>
                    </div>
                    <div class="col-12 col-sm-12 col-lg-3">
                        <label for="users-list-verified">Status</label>
                        <fieldset class="form-group">
                            <select class="form-control" id="f_status_{{$r_code}}" name="f_status_{{$r_code}}">
                                @php($f_status = Session::get('ajax_filter_f_status_'.$r_code))
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
                        <button type="submit" class="btn btn-primary btn-block glow users-list-clear mb-0">{{ __('news_i.lt_03') }}</button>
                    </div>
                </div>
            </form>
        </div>
        <!-- users list start -->
        <section class="users-list-wrapper">
            <div class="users-list-table">
                <div class="card">
                    <div class="card-content">
                        <div class="card-body">
                            <!-- datatable start -->
                            <div id="ajax-table-lendings_{{Session::get('r_code')}}" class="ajax-table-lendings">{!!$html_table!!}</div>
                            <!-- datatable ends -->
                        </div>
                    </div>
                </div>
            </div>
        </section>
        <section>
            <div class="card border-info text-center bg-transparent">
                <div class="card-content">
                  <div class="card-body">
                    <div class="row">
                      <div class="col-md-12 col-sm-12 d-flex justify-content-center">
                        <img src="/admin/app-assets/images/backgrounds/process_approv.png" alt="element 04" class="float-left mt-1 img-fluid">
                      </div>
                    </div>
                  </div>
                </div>
              </div>
        </section>
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
                <form id="UpdateAccount" action="#" method="post">
                    <div class="modal-body">
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
				<div class="table-responsive">
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
				</div>
                <div id="ajax-table"></div>
            </div>
            <div class="modal-footer"></div>
        </div>
        </div>
    </div>

    @include('gree_i.misc.process_view_analyze.view')

    <script>
    @include('gree_i.misc.process_view_analyze.script', ['type' => 6])
    function Approv(index) {
        Swal.fire({
            title: 'Aprovação',
            text: "Confirma o envio da solicitação de reembolso para aprovação?",
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
                    window.location.href = "/financy/accountability/send/analyze/"+ index;
                }
            })
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

        ajaxSend(`/financy/accountability/ajax/lending/history/${idLending}`,{show_actions:false})
        .then((response) => {
                $("#ajax-table").html(response);

                $('#modal-accountability').modal('toggle');

                let botao = `<button type="button" id="bt-new-manual-entry" onclick="getLendingPending(this)" class="btn btn-success">Nova Prestação de Contas</button>`;
                
                if(json_row.is_accountability_paid==0){
                    $('#modal-accountability').find(".modal-footer").append(botao);
                    $('#bt-new-manual-entry').attr("json-data",$(elem).attr("json-data"));
                }
                
                unblock();
                $('[data-toggle=tooltip]').tooltip();
        })
        .catch((error) => {
            $error(error.message);
            unblock();
        });
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
        })
        .catch((error) => {
            $error(error.message);
            unblock();
        });

    }

    function getLendingPending(elem) {
        
        let json_row = JSON.parse($(elem).attr("json-data"));

        block();

        ajaxSend(`/financy/accountability/change/lending`,{lending_request_id:json_row.id},'POST')
        .then((response) => {
                window.location.href = response.redirect;
        })
        .catch((error) => {
            $error(error.message);
            unblock();
        });

    }
    
    $(document).on('click',".showDetails td:not(.no-click)",function(e) {
        e.preventDefault();
        let elem = $(this).parent().next();
        let r_code = elem.attr("r_code");
        
        elem.toggle();

        $(this).parent().find('.row_expand').toggleClass('bx-plus-circle');

    })

    $(document).ready(function () {
        $('[data-toggle="popover"]').popover({
            placement: 'right',
            trigger: 'hover',
        });

        $('#list-datatable').DataTable( {
            searching: false,
            paging: false,
            ordering:false,
            lengthChange: false,
            language: {
                search: "{{ __('layout_i.dtbl_search') }}",
                zeroRecords: "{{ __('layout_i.dtbl_zero_records') }}",
                info: "{{ __('layout_i.dtbl_info') }}",
                infoEmpty: "{{ __('layout_i.dtbl_info_empty') }}",
                infoFiltered: "{{ __('layout_i.dtbl_info_filtred') }}",
            }
        });


        var options = {
                onKeyPress : function(cpfcnpj, e, field, options) {
                    var masks = ['000.000.000-009', '00.000.000/0000-00'];
                    var mask = (cpfcnpj.length > 14) ? masks[1] : masks[0];
                    $('#identity').mask(mask, options);
                }
            };
        $('#identity').mask('000.000.000-009', options);

        setInterval(() => {
            $("#mAdmin").addClass('sidebar-group-active active');
            $("#mFinancyLending").addClass('sidebar-group-active active');
            $("#mFinancyAccountability").addClass('sidebar-group-active active');
            $("#mFinancyAccountabilityMy").addClass('active');
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

    });
    </script>
@endsection