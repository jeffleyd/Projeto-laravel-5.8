@extends('gree_i.layout')

@section('content')

<style>
    .select2-container .select2-selection__rendered > *:first-child.select2-search--inline {
        width: 100% !important;
    }
    .select2-container .select2-selection__rendered > *:first-child.select2-search--inline .select2-search__field {
        width: 100% !important;
    }
</style>    

<div class="content-overlay"></div>
<div class="content-wrapper">
    <div class="content-header row">
        <div class="content-header-left col-12 mb-2 mt-1">
        <div class="row breadcrumbs-top">
            <div class="col-12">
            <h5 class="content-header-title float-left pr-1 mb-0">Logística</h5>
            <div class="breadcrumb-wrapper col-12">
                Novo vigilante
            </div>
            </div>
        </div>
        </div>
    </div>
    <div class="content-body">
        <div class="card">
            <div class="card-header">
                <h4 class="card-title">DADOS DO VIGILANTE</h4>
            </div>
            <div class="card-content">
                <div class="card-body">
                    <form action="/logistics/security/guard/edit_do" id="form_guard" method="post" enctype="multipart/form-data">
                        <input type="hidden" name="id" value="{{$id}}">
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label>Portaria</label>
                                    <select class="custom-select select-gate" name="entry_exit_gate" id="entry_exit_gate" style="width: 100%;" multiple>
                                        @if ($entry_exit_gate)
                                            <option value="{{ $entry_exit_gate }}" selected>{{ $entry_exit_gate_name }}</option>
                                        @endif
                                    </select>
                                </div>
                            </div>    
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label>É supervisor?</label>
                                    <select class="custom-select" name="is_supervisor" id="is_supervisor">
                                        <option value="">Selecione</option>
                                        <option value="1" @if($is_supervisor == 1) selected @endif>SIM</option>
                                        <option value="0" @if($is_supervisor == 0) selected @endif>NÃO</option>
                                    </select>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label>Nome</label>
                                    <input type="text" class="form-control" id="name" name="name" value="{{$name}}" placeholder="Nome completo">
                                </div>    
                            </div>    
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label>CPF</label>
                                    <input type="text" class="form-control identity-mask" id="identity" name="identity" value="{{$identity}}" placeholder="000.000.000.00">
                                </div>    
                            </div>    
                        </div>
                        <div class="row">   
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label>Telefone 1</label>
                                    <input type="text" class="form-control phone" id="phone_1" name="phone_1" value="{{$phone_1}}" placeholder="(00) 00000-0000">
                                </div>    
                            </div>    
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label>Telefone 2</label>
                                    <input type="text" class="form-control phone" id="phone_2" name="phone_2" value="{{$phone_2}}" placeholder="(00) 00000-0000">
                                </div>    
                            </div>  
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label>Empresa do vigilante</label>
                                    <input type="text" class="form-control" id="company_name" name="company_name" value="{{$company_name}}" placeholder="Informe o nome da empresa">
                                </div>  
                            </div> 
                        </div>
                        <div class="row">   
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label>Início do expediente</label>
                                    <fieldset class="form-group position-relative has-icon-left">
                                        <input type="text" class="form-control hour-work-mask" name="begin_hour_work" id="begin_hour_work" value="{{$begin_hour_work}}" placeholder="00:00">
                                        <div class="form-control-position">
                                            <i class='bx bx-history'></i>
                                        </div>
                                    </fieldset>
                                </div>    
                            </div>    
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label>Fim do expediente</label>
                                    <fieldset class="form-group position-relative has-icon-left">
                                        <input type="text" class="form-control hour-work-mask" name="final_hour_work" id="final_hour_work" value="{{$final_hour_work}}" placeholder="00:00">
                                        <div class="form-control-position">
                                            <i class='bx bx-history'></i>
                                        </div>
                                    </fieldset>
                                </div>    
                            </div>  
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label>Turno de trabalho</label>
                                    <select class="custom-select" name="working_turn" id="working_turn">
                                        <option value="">Selecione</option>
                                        <option value="1" @if($working_turn == 1) selected @endif>1° turno</option>
                                        <option value="2" @if($working_turn == 2) selected @endif>2° turno</option>
                                        <option value="3" @if($working_turn == 3) selected @endif>3° turno</option>
                                    </select>
                                </div>  
                            </div> 
                        </div>
                        <div class="row">
                            <div class="col-md-12">
                                <fieldset class="form-group">
                                    <label for="price">Foto do vigilante</label>
                                    <div class="custom-file">
                                        <input type="file" class="custom-file-input" name="picture" id="picture">
                                        <label class="custom-file-label label-attach-file">Escolher arquivo</label>
                                    </div>
                                </fieldset>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label>Senha</label>
                                    <input type="password" class="form-control" id="password" name="password" placeholder="******">
                                </div>  
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label>Status</label>
                                    <ul class="list-unstyled border">
                                        <li class="d-inline-block" style="padding: 7px;">
                                            <div class="radio">
                                                <input type="radio" class="radio-auth" id="radio1" name="is_active" value="1" @if($is_active == 1) checked @endif>
                                                <label for="radio1">Habilitado</label>
                                            </div>
                                        </li>
                                        <li class="d-inline-block" style="padding: 7px;">
                                            <div class="radio">
                                                <input type="radio" class="radio-auth" id="radio2" name="is_active" value="0" @if($is_active == 0) checked @endif>
                                                <label for="radio2">Desabilitado</label>
                                            </div>
                                        </li>
                                    </ul>
                                </div>  
                            </div>
                        </div>
                        
                        <div class="row mt-1">
                            <div class="col-md-12">
                                <button type="button" class="btn btn-primary" id="btn_save_guard" style="width:100%;">
                                    @if ($id == 0)
                                        Cadastrar Vigilante    
                                    @else
                                        Atualizar Vigilante    
                                    @endif
                                </button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

<script>    

    var guard_id = {!! $id !!};

    $(document).ready(function () {

        $(".select-gate").select2({
            maximumSelectionLength: 1,
            placeholder: "Selecione a portaria",
            language: {
                noResults: function () {
                    return 'Portaria não encontrada'; 
                }
            },
            ajax: {
                url: '/logistics/gate/dropdown/list',
                data: function (params) {
                    var query = {
                        search: params.term,
                        page: params.page || 1
                    }
                    return query;
                }
            }
        });

        $("#btn_save_guard").click(function() {

            if($("#entry_exit_gate").val() == "") {
                return $error('Portaria obrigatória');
            }
            else if ($('#is_supervisor').val() == "") {
                return $error('Supervisor é obrigatório');
            }
            else if ($('#name').val() == "") {
                return $error('Informe o nome do vigilante');
            }
            else if ($('#identity').val() == "") {
                return $error('Informe o RG do vigilante');
            }
            else if ($('#phone_1').val() == "") {
                return $error('Informe o telefone 1 do vigilante');
            }
            else if ($('#company_name').val() == "") {
                return $error('Informe a empresa do vigilante');
            }
            else if ($('#begin_hour_work').val() == "") {
                return $error('Informe o inicio do expediente');
            }
            else if ($('#final_hour_work').val() == "") {
                return $error('Informe o final do expediente');
            }
            else if ($('#working_turn').val() == "") {
                return $error('Selecione o turno de trabalho');
            }
            else if ($('#password').val() == "" && guard_id == 0) {
                return $error('Informe uma senha para o vigilante');
            }
            else {
                $("#form_guard").submit();
            }
        });


        $('.hour-work-mask').mask('00:00', {reverse: false});
        $('.identity-mask').mask('000.000.000-00', {reverse: false});
        
        var SPMaskBehavior = function (val) {
            return val.replace(/\D/g, '').length === 11 ? '(00) 00000-0000' : '(00) 0000-00009';
        },
        spOptions = {
            onKeyPress: function(val, e, field, options) {
                field.mask(SPMaskBehavior.apply({}, arguments), options);
            }
        };
        $('.phone').mask(SPMaskBehavior, spOptions);

        setInterval(() => {
            $("#mLogistics").addClass('sidebar-group-active active');
            $("#mLogisticsEntryExitGuards").addClass('active');
        }, 100);
    });
</script>
@endsection
