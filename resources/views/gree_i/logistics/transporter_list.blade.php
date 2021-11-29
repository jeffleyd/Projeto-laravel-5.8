@extends('gree_i.layout')

@section('content')

<style>
    .table th, .table td {
        padding: 1.10rem 0.20rem;
    }
</style>

<link rel="stylesheet" type="text/css" href="/admin/app-assets/vendors/css/pickers/pickadate/pickadate.css">
<div class="content-overlay"></div>
<div class="content-wrapper">
    <div class="content-header row">
        <div class="content-header-left col-12 mb-2 mt-1">
          <div class="row breadcrumbs-top">
            <div class="col-12">
              <h5 class="content-header-title float-left pr-1 mb-0">Logística</h5>
              <div class="breadcrumb-wrapper col-12">
                Transportadoras
              </div>
            </div>
          </div>
        </div>
      </div>
    <div class="content-header row">
    </div>
    <div class="content-body">
        <section class="users-list-wrapper">
            <div class="users-list-table">
                <div class="card">
                    <div class="card-content">
                        <div class="card-body">
                            <div class="table-responsive">
                                <div class="top d-flex flex-wrap">
                                    <div class="action-filters flex-grow-1">
                                        <div class="dataTables_filter mt-1">
                                            <h5>Todas transportadoras</h5>
                                        </div>
                                    </div>
                                    <div class="actions action-btns d-flex align-items-center">
                                        <div class="dropdown invoice-filter-action">
                                            <button type="button" class="btn btn-primary shadow mr-1" id="btn_modal_transp"><i class="bx bx-add-to-queue"></i> Novo</button>
                                        </div>
                                        <div class="dropdown invoice-filter-action">
                                            <button type="button" class="btn btn-dark shadow mr-1" data-toggle="modal" data-target="#modal_filter"><i class="bx bx-search-alt"></i> Filtrar</button>
                                        </div>
                                        <!--<div class="dropdown invoice-options">
                                            <button type="button" class="btn btn-success shadow mr-0" data-toggle="modal" data-target="#modal_export"><i class="bx bx-import"></i> Exportar</button>
                                        </div>-->
                                    </div>
                                </div>
                                <hr>
                                <table class="table" id="list-datatable" style="text-align: center;">
                                    <thead>
                                        <tr role="row">
                                            <th>Nome fantasia</th>
                                            <th>CNPJ</th>
                                            <th>Endereço</th>
                                            <th>Cidade</th>
                                            <th>Estado</th>
                                            <th>Telefone</th>
                                            <th>Ramal</th>
                                            <th>Recepcionista</th>
                                            <th>Status</th>
                                            <th>Ação</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach ($transporter as $trans)
                                            <tr role="row">
                                                <td>
                                                    <span data-toggle="tooltip" data-placement="top" title="{{ $trans->name }}" style="cursor: pointer;">
                                                        <?= stringCut($trans->name, 25) ?>
                                                    </span>
                                                </td>
                                                <td>{{ $trans->identity }}</td>
                                                <td>
                                                    <span data-toggle="tooltip" data-placement="top" title="{{ $trans->address }}" style="cursor: pointer;">
                                                        <?= stringCut($trans->address, 35) ?>
                                                    </span>
                                                </td>
                                                <td>{{ $trans->city }}</td>
                                                <td>{{ $trans->state }}</td>
                                                <td>{{ $trans->phone }}</td>
                                                <td>{{ $trans->receptionist_ramal }}</td>
                                                <td>{{ $trans->receptionist_name }}</td>
                                                <td>
                                                    @if($trans->is_active == 1)
                                                        <span class="badge badge-light-success">Ativo</span>
                                                    @else
                                                        <span class="badge badge-light-danger">Desativado</span>    
                                                    @endif
                                                </td>
                                                <td>
                                                    <div class="dropleft">
                                                        <span class="bx bx-dots-horizontal-rounded font-medium-3 nav-hide-arrow cursor-pointer" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false" role="menu"></span>
                                                        <div class="dropdown-menu dropdown-menu-right">
                                                            <a class="dropdown-item edit-transporter" data-json='<?= htmlspecialchars(json_encode($trans), ENT_QUOTES, "UTF-8") ?>' href='javascript:void(0)'><i class="bx bx-edit-alt mr-1"></i> Editar</a>
                                                            <a class="dropdown-item change-status" data-id="{{ $trans->id }}" data-status='{{ $trans->is_active }}' href='javascript:void(0)'>
                                                                @if ($trans->is_active == 1)
                                                                    <i class="bx bx-x-circle mr-1" style="color:#ff0707;"></i> Desativar
                                                                @else
                                                                    <i class="bx bx-check-circle mr-1" style="color:#20d379;"></i> Ativar
                                                                @endif
                                                            </a>
                                                        </div>
                                                    </div>
                                                </td>
                                            </tr>    
                                        @endforeach
                                    </tbody>
                                </table>
                                <nav aria-label="Page navigation">
                                    <ul class="pagination justify-content-end">
                                        <?= $transporter->appends([
                                            'name' => Session::get('trans_name'),
                                            'identity' => Session::get('trans_identity'),
                                            'status' => Session::get('trans_status')
                                        ])->links(); ?>  
                                    </ul>
                                </nav>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </section>
    </div>
</div>

<div class="modal fade" id="modal_add_transporter" tabindex="-1" aria-modal="true" role="dialog">
    <div class="modal-dialog modal-dialog-scrollable modal-lg">
        <div class="modal-content">
            <div class="modal-header bg-primary">
                <h5 class="modal-title white modal-title-add">Cadastrar transportadora</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <i class="bx bx-x"></i>
                </button>
            </div>
            <div class="modal-body">
                <form method="post" action="/logistics/transporter/edit_do" id="form_register_transporter">
                    <input type="hidden" name="transporter_id" id="transporter_id" value="0">
                    <div class="row">
                        <div class="col-6">
                            <div class="form-group">
                                <label>Nome</label>
                                <input type="text" class="form-control" name="name" id="name" placeholder="Informe nome fantasia">
                            </div>
                        </div>
                        <div class="col-6">        
                            <div class="form-group">
                                <label>CNPJ</label>
                                <input type="text" class="form-control identity-mask" name="identity" id="identity" placeholder="00.000.000/0000-00">
                            </div>
                        </div>    
                    </div>    
                    <div class="row">
                        <div class="col-12">        
                            <div class="form-group">
                                <label>Endereço</label>
                                <input type="text" class="form-control " name="address" id="address" placeholder="Informe o endereço">
                            </div>
                        </div>    
                    </div>    
                    <div class="row">
                        <div class="col-6">        
                            <div class="form-group">
                                <label>Cidade</label>
                                <input type="text" class="form-control" name="city" id="city" placeholder="Informe a cidade">
                            </div>
                        </div>    
                        <div class="col-6">        
                            <div class="form-group">
                                <label>Estado</label>
                                <select class="form-control" name="state" id="state">
                                    <option value="">Selecione o estado</option>
                                    @foreach (config('gree.states') as $key => $value)
                                        <option value="{{ $key }}">{{ $value }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>    
                    </div>  
                    <div class="row">
                        <div class="col-6">        
                            <div class="form-group">
                                <label>Telefone</label>
                                <input type="text" class="form-control phone" name="phone" id="phone" placeholder="(00) 00000-0000">
                            </div>
                        </div>    
                        <div class="col-6">        
                            <div class="form-group">
                                <label>Ramal</label>
                                <input type="text" class="form-control" name="receptionist_ramal" id="receptionist_ramal" placeholder="Informe o ramal">
                            </div>
                        </div>    
                    </div>    
                    <div class="row">
                        <div class="col-6">        
                            <div class="form-group">
                                <label>Email</label>
                                <input type="text" class="form-control" name="email" id="email" placeholder="Informe o email">
                            </div>
                        </div>      
                        <div class="col-6">        
                            <div class="form-group">
                                <label>Nome recepcionista</label>
                                <input type="text" class="form-control" name="receptionist_name" id="receptionist_name" placeholder="Informe o nome da recepcionista">
                            </div>
                        </div>      
                    </div>    
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-light-secondary" data-dismiss="modal">
                    <i class="bx bx-x d-block d-sm-none"></i>
                    <span class="d-none d-sm-block">Fechar</span>
                </button>
                <button type="button" class="btn btn-primary ml-1" id="btn_save_transporter">
                    <i class="bx bx-check d-block d-sm-none"></i>
                    <span class="d-none d-sm-block">Salvar</span>
                </button>
            </div>
        </div>
    </div>
</div>

<div class="modal fade" id="modal_filter" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header bg-primary white">
                <span class="modal-title">Filtrar Transportadoras</span>
            </div>
            <div class="modal-body">
                <form method="get" action="{{Request::url()}}" id="form_transp_filter">
                    <div class="row">
                        <div class="col-12">
                            <div class="form-group">
                                <label for="first-name-vertical">Nome da transportadora</label>
                                <input type="text" class="form-control" name="name" placeholder="">
                            </div>
                        </div>
                        <div class="col-12">
                            <div class="form-group">
                                <label for="first-name-vertical">CNPJ</label>
                                <input type="text" class="form-control" name="identity" placeholder="">
                            </div>
                        </div>
                        <div class="col-md-12">
                            <fieldset class="form-group">
                                <label for="status">Status</label>
                                <select class="form-control" id="status" name="status">
                                    <option value="" selected disabled>Selecione</option>
                                    <option value="1">Ativo</option>
                                    <option value="not">Desativado</option>
                                </select>
                            </fieldset>
                        </div>
                    </div>
                </form>    
            </div>    
            <div class="modal-footer">
                <button type="button" class="btn btn-light-secondary ml-1" data-dismiss="modal">
                    <i class="bx bx-x d-block d-sm-none"></i>
                    <span class="d-none d-sm-block">Fechar</span>
                </button>
                <button type="button" class="btn btn-primary ml-1" id="btn_transporter_filter">
                    <i class="bx bx-check d-block d-sm-none"></i>
                    <span class="d-none d-sm-block actiontxt">Filtrar</span>
                </button>
            </div>
        </div>    
    </div>   
</div>

<script>
    $(document).ready(function () {

        if(window.location.href.indexOf('#modal_add_transporter') != -1) {
            $('#modal_add_transporter').modal('show');
        }
        
        $("#btn_save_transporter").click(function() {

            var desc = "da transportadora obrigatório!";
            if($("#name").val() == "") {
                return $error('Nome ' + desc);
            }
            else if($("#identity").val() == "") {
                return $error('CNPJ ' + desc);
            }
            else if($("#address").val() == "") {
                return $error('Endereço ' + desc);
            }    
            else if($("#city").val() == "") {
                return $error('Cidade ' + desc);
            }    
            else if($("#state").val() == "") {
                return $error('Estado ' + desc);
            }    
            else if($("#phone").val() == "") {
                return $error('Telefone ' + desc);
            }
            else if($("#receptionist_ramal").val() == "") {
                return $error('Ramal ' + desc);
            }
            else if($("#email").val() == "") {
                return $error('email ' + desc);
            }
            else if($("#receptionist_name").val() == "") {
                return $error('Nome da recepcionista ' + desc);
            }
            else {
                block();
                $('#form_register_transporter').submit();
            }
        });

        $("#btn_transporter_filter").click(function() {
            $('#form_transp_filter').submit();
        });

        $(".edit-transporter").click(function() {

            obj = JSON.parse($(this).attr('data-json'));
            $("#transporter_id").val(obj.id);
            $("#name").val(obj.name);
            $("#identity").val(obj.identity);
            $("#address").val(obj.address);
            $("#city").val(obj.city);
            $("#state").val(obj.state);
            $("#phone").val(obj.phone);
            $("#receptionist_ramal").val(obj.receptionist_ramal);
            $("#receptionist_name").val(obj.receptionist_name);
            $(".modal-title-add").text('Editar transportadora');

            $("#modal_add_transporter").modal('show');

        });

        $(".change-status").click(function() {

            var id = $(this).attr('data-id');
            var status = $(this).attr('data-status');
            var title = status == 1 ? "Desativar" : "Ativar";
            var text = status == 1 ? "desativação" : "ativação";
            var change_status = status == 1 ? 0 : 1;

            Swal.fire({
                title: title + ' transportador',
                text: "Deseja confirmar a " + text + " do transportador?",
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
                    window.location.href = "/logistics/transporter/change/status/"+id+"/"+change_status+"";
                }
            });
        });

        $("#btn_modal_transp").click(function() {

            $("#transporter_id, #name, #identity, #address, #city, #state, #phone, #receptionist_ramal, #receptionist_name").val('');
            $(".modal-title-add").text('Cadastrar transportadora');
            $("#modal_add_transporter").modal('show');
        });

        $('.identity-mask').mask('00.000.000/0000-00', {reverse: false});
        
        var SPMaskBehavior = function (val) {
            return val.replace(/\D/g, '').length === 11 ? '(00) 00000-0000' : '(00) 0000-00009';
        },
        spOptions = {
            onKeyPress: function(val, e, field, options) {
                field.mask(SPMaskBehavior.apply({}, arguments), options);
            }
        };
        $('.phone').mask(SPMaskBehavior, spOptions);


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

        
        setInterval(() => {
            $("#mLogistics").addClass('sidebar-group-active active');
            $("#mLogisticsTransporter").addClass('sidebar-group-active active');
            $("#mLogisticsTransporterList").addClass('active');
        }, 100);
    });

</script>
@endsection