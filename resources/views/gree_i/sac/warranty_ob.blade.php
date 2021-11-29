@extends('gree_i.layout')

@section('content')
<div class="content-overlay"></div>
<div class="content-wrapper">
    <div class="content-header row">
        <div class="content-header-left col-12 mb-2 mt-1">
          <div class="row breadcrumbs-top">
            <div class="col-12">
              <h5 class="content-header-title float-left pr-1 mb-0">Atendimento</h5>
              <div class="breadcrumb-wrapper col-12">
                Lista de ordem de compra
              </div>
            </div>
          </div>
        </div>
      </div>
    <div class="content-header row">
    </div>
    <div class="content-body">
        <div class="users-list-filter px-1">
            <form action="/sac/warranty/ob" id="searchTrip" method="GET">
                <input type="hidden" value="0" name="export">
                <div class="row border rounded py-2 mb-2">
                    <div @if (Session::get('filter_line') == 1) class="col-12 col-sm-12 col-lg-2" @else class="col-12 col-sm-12 col-lg-4" @endif>
                        <label for="users-list-verified">Pesquisa por ordem de compra</label>
                        <fieldset class="form-group">
                            <input type="text" name="ob" value="{{ Session::get('sacf_ob') }}" class="form-control">
                        </fieldset>
                    </div>
                    <div class="col-12 col-sm-12 col-lg-2">
                        <label for="type_people">Tipo de pesquisa</label>
                        <fieldset class="form-group">
                            <select class="form-control" id="type_people" name="type_people" style="width: 100%;">
                                <option value="0" selected>Livre</option>
                                <option value="2">Jurídica (CNPJ)</option>
                            </select>
                        </fieldset>
                    </div>
                    <div class="col-12 col-sm-12 col-lg-6">
                        <label for="users-list-verified">Autorizada</label>
                        <fieldset class="form-group">
                            <select class="js-select22 form-control" id="authorized" name="authorized" style="width: 100%;" multiple>
                            </select>
                        </fieldset>
                    </div>
                    @if (Session::get('filter_line') == 1)
                    <div class="col-12 col-sm-12 col-lg-2">
                        <label for="type_line">Tipo de linha</label>
                        <fieldset class="form-group">
                            <select class="form-control" id="type_line" name="type_line" style="width: 100%;">
                                <option value="0"></option>
                                <option value="1" @if (Session::get('sacf_type_line') == 1) selected @endif>Residencial</option>
                                <option value="2" @if (Session::get('sacf_type_line') == 2) selected @endif>Comercial</option>
                                <option value="3" @if (Session::get('sacf_type_line') == 3) selected @endif>não identificado</option>
                            </select>
                        </fieldset>
                    </div>
                    @endif
                    <div class="col-12 col-sm-12 col-lg-8">
                        <label for="users-list-verified">Status</label>
                        <fieldset class="form-group">
                            <select class="form-control" id="status" name="status">
                                <option value=""></option>
                                <option value="1" @if (Session::get('sacf_status') == 1) selected @endif>Em análise</option>
                                <option value="6" @if (Session::get('sacf_status') == 6) selected @endif>Imprimido</option>
                                <option value="2" @if (Session::get('sacf_status') == 2) selected @endif>Aguardando pagamento</option>
                                <option value="3" @if (Session::get('sacf_status') == 3) selected @endif>Enviado</option>
                                <option value="4" @if (Session::get('sacf_status') == 4) selected @endif>Concluído</option>
                                <option value="5" @if (Session::get('sacf_status') == 5) selected @endif>Cancelado</option>
                            </select>
                        </fieldset>
                    </div>
                    <div class="col-12 col-sm-12 col-lg-4 d-flex align-items-center">
                        <button type="submit" class="btn btn-primary btn-block glow users-list-clear mb-0">{{ __('news_i.lt_03') }}</button>
                    </div>
                </div>
            </form>
        </div>
        <div class="users-list-filter px-1">
            <form action="/sac/warranty/ob" id="exportdata" method="GET">
                <input type="hidden" value="1" name="export">
                <input type="hidden" value="{{ Session::get('sacf_type_line') }}" name="type_line_exp" id="type_line_exp">
                <div class="row border rounded py-2 mb-2">
                    <div class="col-12 col-sm-12 col-lg-5">
                        <label for="users-list-verified">Data inicial</label>
                        <fieldset class="form-group">
                            <input type="text" name="start_date" id="start_date" class="form-control">
                        </fieldset>
                    </div>
                    <div class="col-12 col-sm-12 col-lg-5">
                        <label for="users-list-verified">Data final</label>
                        <fieldset class="form-group">
                            <input type="text" name="end_date" id="end_date" class="form-control">
                        </fieldset>
                    </div>
                    <div class="col-12 col-sm-12 col-lg-2 d-flex align-items-center">
                        <button type="submit" class="btn btn-success btn-block glow users-list-clear mb-0">Exportar</button>
                    </div>
                </div>
            </form>
        </div>
        <section class="users-list-wrapper">
            <div class="users-list-table">
                <div class="card">
                    <div class="card-content">
                        <div class="card-body">
                            <!-- datatable start -->
                            <form action="/sac/warranty/ob/send/print" id="sendPrint" method="post">
                            <div class="table-responsive">
                                <table id="list-datatable" class="table">
                                    <thead>
                                        <tr>
                                            <th>#</th>
                                            <th>Código</th>
                                            <th>Autorizada</th>
                                            <th>Linha</th>
                                            <th>Status</th>
                                            <th>Rastreio</th>
                                            <th>Feito em</th>
                                            <th>Última atualização</th>
                                            <th>Ações</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php foreach ($ob as $key) { ?>
                                            
                                        <tr>
                                            <td class="text-center">
                                                <div class="checkbox"><input type="checkbox" class="checkbox-input" id="check_<?= $key->id ?>" name="check[]" value="<?= $key->id ?>">
                                                    <label for="check_<?= $key->id ?>"></label>
                                                </div>
                                            </td>
                                            <td>{{ $key->code }}</td>
                                            <td><a target="_blank" href="/sac/authorized/edit/<?= $key->authorized_id ?>"><?= strWordCut($key->name, 30) ?></a></td>
                                            <td>
                                                <?php  
                                                    $commercial = false;
                                                    $residential = false;
                                                    $not_identifier = false;
                                                    $types =  App\Model\SacBuyParts::where('sac_buy_part_id', '=', $key->id)
                                                                ->leftJoin('product_air', 'sac_buy_parts.model', '=', 'product_air.id')
                                                                ->select('sac_buy_parts.not_part', 'product_air.residential', 'product_air.commercial')->get();

                                                    if (count($types) > 0) {
                                                        foreach ($types as $type) {
                                                            
                                                            if (!$not_identifier and $type->not_part == 1) {
                                                                $not_identifier = true;
                                                            }
    
                                                            if (!$residential and $type->not_part == 0) {
                                                                if ($type->residential == 1) {
                                                                    $residential = true;
                                                                }
                                                            }
    
                                                            if (!$commercial and $type->not_part == 0) {
                                                                if ($type->commercial == 1) {
                                                                    $commercial = true;
                                                                }
                                                            }
                                                        }
                                                    } else {
                                                        $not_identifier = true;
                                                    }  
                                                    
                                                ?> 

                                                @if ($residential)
                                                    <span class="badge badge-primary" style="font-size: 9px;">Residencial</span>
                                                @endif
                                                @if ($commercial)    
                                                    <span class="badge badge-success" style="font-size: 9px;">Comercial</span>
                                                @endif
                                                @if ($not_identifier)    
                                                    <span class="badge badge-danger" style="font-size: 9px;">Não identificado</span>
                                                @endif
                                            </td>
                                            <td>
                                                @if ($key->is_cancelled == 0)
                                                    @if ($key->status == 1)
                                                    <span class="badge badge-light-warning">Em análise</span>
                                                    @elseif ($key->status == 2)
                                                    <span class="badge badge-light-warning">Aguardando pagamento</span>
                                                    @elseif ($key->status == 3)
                                                    <span class="badge badge-light-info">Enviado</span>
                                                    @elseif ($key->status == 4)
                                                    <span class="badge badge-light-success">Concluído</span>
                                                    @elseif ($key->status == 6)
                                                    <span class="badge badge-light-primary">Imprimido</span>
                                                    @endif
                                                @else
                                                <span class="badge badge-light-danger">Cancelado</span>
                                                @endif
                                            </td>
                                            <td>
                                                @if ($key->track_code)
                                                {{$key->track_code}}
                                                @else
                                                --
                                                @endif
                                            </td>
                                            <td>{{ date('d-m-Y', strtotime($key->created_at)) }}</td>
                                            <td>{{ date('d-m-Y', strtotime($key->updated_at)) }}</td>
                                            <td>
                                                <div class="dropleft">
                                                    <span class="bx bx-dots-horizontal-rounded font-medium-3 nav-hide-arrow cursor-pointer" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false" role="menu"></span>
                                                    <div class="dropdown-menu dropdown-menu-right">
                                                        <a class="dropdown-item" href="javascript:void(0)" onclick="edit(<?= $key->id ?>, <?= $key->status ?>, '<?= $key->track_code ?>', '<?= $key->shipping_cost ?>', '<?= $key->total ?>' )"><i class="bx bx-edit-alt mr-1"></i> Editar</a>
                                                        <a class="dropdown-item" href="/sac/warranty/parts/ob/<?= $key->id ?>"><i class="bx bxs-package mr-1"></i> Editar peças</a>
                                                        <a class="dropdown-item" href="/sac/warranty/print/ob/<?= $key->id ?>" target="_blank"><i class="bx bx-receipt mr-1"></i> Impr. OC</a>
                                                    </div>
                                                </div>
                                            </td>                                          
                                        </tr>
                                        <?php } ?>
                                    </tbody>
                                </table>
                                <nav aria-label="Page navigation">
                                    <ul class="pagination justify-content-end">
                                        <?= $ob->appends([
                                            'ob' => Session::get('sacf_ob'),
                                            'authorized' => Session::get('sacf_authorized'),
                                            'status' => Session::get('sacf_status'),
                                            'type_line' => Session::get('sacf_type_line')
                                            ])->links(); ?>
                                    </ul>
                                </nav>
                            </div>
                            <div class="mb-2" style="width: 390px; position: fixed;left: 0;right: 0;bottom: 0;margin: 0 auto; z-index: 99; text-align: center;">
                                <button type="submit" onclick="cSubmit()" class="btn btn-secondary">Mudar status p/ Imprimido</button>
                            </div>
                            </form>
                            <!-- datatable ends -->
                        </div>
                    </div>
                </div>
            </div>
        </section>
        <!-- users list ends -->
    </div>
</div>

<div class="modal fade text-left" id="update-modal" tabindex="-1" role="dialog" aria-labelledby="update-modal" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-dialog-scrollable" role="document">
      <div class="modal-content">
        <div class="modal-header">
          <h4 class="modal-title">ATUALIZAR INFORMAÇÕES</h4>
          <button type="button" class="close" data-dismiss="modal" aria-label="Close">
            <i class="bx bx-x"></i>
          </button>
        </div>
        <form action="/sac/warranty/ob_do" id="sendform" method="post">
        <input type="hidden" value="0" id="id" name="id">
        <div class="modal-body">
            <fieldset class="form-group">
                <label for="status">Status</label>
                <select class="form-control" name="status" id="status">
                    <option value="99">Cancelado</option>
                    <option value="1">Em análise</option>
                    <option value="6">Imprimido</option>
                    <option value="2">Aguardando pagamento</option>
                    <option value="3">Enviado</option>
                    <option value="4">Concluído</option>
                </select>
            </fieldset>
            <fieldset class="form-group">
                <label for="track_code">Código de rastreio</label>
                <input type="text" class="form-control" name="track_code" id="track_code">
            </fieldset>
            <fieldset class="form-group">
                <label for="shipping_cost">Valor do frete</label>
                <input type="text" style="text-transform: uppercase" class="form-control" name="shipping_cost" id="shipping_cost" value="0.00">
            </fieldset>
            <fieldset class="form-group">
                <label for="total">Valor total</label>
                <input type="text" class="form-control" name="total" id="total" value="0.00">
            </fieldset>
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-light-secondary btn-sm" data-dismiss="modal">
            <i class="bx bx-x d-block d-sm-none"></i>
            <span class="d-sm-block d-none">Fechar</span>
          </button>
          <button type="button" onclick="sendInfo()" class="btn btn-primary ml-1 btn-sm">
            <i class="bx bx-check d-block d-sm-none"></i>
            <span class="d-sm-block d-none">Concluir</span>
          </button>
        </div>
        </form>
      </div>
    </div>
  </div>

    <script>
    function edit(id, status, track, shipping, total) {
        $("#id").val(id);
        $("#status").val(status);
        $("#track_code").val(track);
        $("#shipping_cost").val(shipping);
        $("#total").val(total);
        $("#update-modal").modal();
    }
    function sendInfo() {
        $("#update-modal").modal('toggle');
        block();
        $("#sendform").submit();
        
    }
    function cSubmit() {
        $("#sendPrint").submit();
    }
    $(document).ready(function () {

        $("#type_people").change(function (e) { 
            if ($("#type_people").val() == 0) {
                $('.select2-search__field').unmask();
            } else if ($("#type_people").val() == 2) {

                $('.select2-search__field').mask('00.000.000/0000-00', {reverse: false});
            }
            
        });

        $("#sendPrint").submit(function (e) { 
            if ($(':checkbox[name="check[]"]:checked').length == 0) {
                
                e.preventDefault();

                return $error('Selecione ao menos 1');
            }
            
            block();
        });

        $("#exportdata").submit(function (e) { 
            Swal.fire({
                type: "success",
                title: 'Exportando...',
                text: 'Aguarde nessa tela, enquanto estamos criando o arquivo para você :)',
                confirmButtonClass: 'btn btn-success',
            });
            
        });

        <?php if (!empty(Session::get('sacf_authorized'))) { ?>
        $('.js-select22').val(['<?= Session::get('sacf_authorized') ?>']).trigger('change');
        <?php } ?>

        $(".js-select22").select2({
            maximumSelectionLength: 1,
            language: {
                noResults: function () {

                    var url = "'/sac/authorized/edit/0'";
                    return $('<button type="submit" style="width: 100%" onclick="document.location.href='+ url +'" class="btn btn-primary">Nova Autorizada</button>');
                }
            },
            ajax: {
                url: '/misc/sac/authorized/',
                data: function (params) {
                var query = {
                    search: params.term,
                    page: params.page || 1
                }

                // Query parameters will be ?search=[term]&page=[page]
                return query;
                }
            }
        });

        $('#start_date').daterangepicker({
            singleDatePicker: true,
            showDropdowns: true,
            locale: {
                format: 'YYYY-MM-DD'
            },
        });

        $('#end_date').daterangepicker({
            singleDatePicker: true,
            showDropdowns: true,
            locale: {
                format: 'YYYY-MM-DD'
            },
        });

        $('#total').mask('000.00', {reverse: true});
        $('#shipping_cost').mask('000.00', {reverse: true});

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

        $('[data-toggle="popover"]').popover({
            placement: 'right',
            trigger: 'hover',
        });

        setInterval(() => {
            $("#mAfterSales").addClass('sidebar-group-active active');
            $("#mTAssist").addClass('sidebar-group-active active');
            $("#mTAssistOB").addClass('active');
        }, 100);

        $("#type_line").change(function() {
            $("#type_line_exp").val($(this).val());
        });

    });
    </script>
@endsection