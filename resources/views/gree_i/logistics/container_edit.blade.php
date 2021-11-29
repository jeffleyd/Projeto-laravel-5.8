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

<link rel="stylesheet" type="text/css" href="/admin/app-assets/vendors/css/pickers/pickadate/pickadate.css">
<div class="content-overlay"></div>
<div class="content-wrapper">
    <div class="content-header row">
        <div class="content-header-left col-12 mb-2 mt-1">
        <div class="row breadcrumbs-top">
            <div class="col-12">
            <h5 class="content-header-title float-left pr-1 mb-0">Logística</h5>
            <div class="breadcrumb-wrapper col-12">
                Novo container
            </div>
            </div>
        </div>
        </div>
    </div>
    <div class="content-body">
        <div class="card">
            <div class="card-header">
                <h4 class="card-title">DADOS DO CONTAINER</h4>
            </div>
            <div class="card-content">
                <div class="card-body">
                    <form action="/logistics/container/edit_do" id="form_container" method="post" enctype="multipart/form-data">
                        <input type="hidden" name="id" value="{{$id}}">

                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label>Transportadora</label>
                                    <select class="form-control select-transporter" id="transporter" name="transporter" style="width: 100%;" multiple>
                                        @if ($transporter)
                                            <option value="{{ $transporter }}" selected>{{ $company_name }} ({{ $company_identity }})</option>
                                        @endif
                                    </select>
                                    <small>Caso o proprietário do container seja transportador, selecione acima</small>
                                </div>
                            </div>    
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label>Descrição</label>
                                    <input type="text" class="form-control" id="description" name="description" value="{{ $description }}" placeholder="Ex. Container Dry Box">
                                </div>    
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label>Número do container</label>
                                    <input type="text" class="form-control" id="number_container" name="number_container" value="{{ $number_container }}" placeholder="Informe o número">
                                </div>    
                            </div>    
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label>Número da frota</label>
                                    <input type="text" class="form-control" id="number_fleet"name="number_fleet" value="{{ $number_fleet }}" placeholder="Informe o número da frota">
                                </div>    
                            </div>    
                        </div>
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label>Empresa proprietária</label>
                                    <input type="text" class="form-control" id="company_name" name="company_name" value="{{ $company_name }}" placeholder="Informe a razão social">
                                </div>    
                            </div>    
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label>Empresa CNPJ</label>
                                    <input type="text" class="form-control identity-mask" id="company_identity"name="company_identity" value="{{ $company_identity }}" placeholder="00.000.000/0000-00">
                                </div>    
                            </div>    
                        </div>
                        <div class="row">
                            <div class="col-md-12">
                                <div class="form-group">
                                    <label for="subject">Endereço empresa</label>
                                    <input type="text" class="form-control" id="company_address" name="company_address" value="{{ $company_address }}" placeholder="Informe o endereço da empresa">
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label>Cidade</label>
                                    <input type="text" class="form-control" id="company_city" name="company_city" value="{{ $company_city }}"placeholder="Informe a cidade da empresa">
                                </div>    
                            </div>    
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label>País</label>
                                    <select class="form-control" name="company_country" id="company_country">
                                        <option value="">Selecione o país</option>
                                        @if($transporter > 0)
                                            <?php foreach ($country as $key) { ?>
                                                <option value="<?= $key->id ?>" @if($key->id == 27) selected @endif><?= $key->name ?></option>
                                            <?php } ?> 
                                        @else
                                            <?php foreach ($country as $key) { ?>
                                                <option value="<?= $key->id ?>" @if($key->id == $company_country) selected @endif><?= $key->name ?></option>
                                            <?php } ?> 
                                        @endif    
                                    </select>
                                </div>    
                            </div>    
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label>Estado</label>
                                    @if($transporter > 0)
                                        <select class="form-control" name="company_state" id="company_state">
                                            @foreach (config('gree.states') as $key => $value)
                                                <option value="{{ $key }}" @if($key == $company_state) selected @endif>{{ $value }}</option>
                                            @endforeach
                                        </select>
                                    @else 
                                        <select class="form-control" name="company_state" id="company_state"></select>
                                    @endif    
                                </div>    
                            </div>    
                        </div>

                        <div class="row">
                            <div class="col-md-3">
                                <div class="form-group">
                                    <label>Telefone</label>
                                    <input type="text" class="form-control phone" id="company_phone" name="company_phone" value="{{ $company_phone }}" placeholder="(00) 00000-0000">
                                </div>    
                            </div>    
                            <div class="col-md-3">
                                <div class="form-group">
                                    <label>ramal</label>
                                    <input type="text" class="form-control" id="company_ramal" name="company_ramal" value="{{ $company_ramal }}" placeholder="0000">
                                </div>    
                            </div>    
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label>Email</label>
                                    <input type="text" class="form-control" id="company_email"name="company_email" value="{{ $company_email }}" placeholder="Informe o email da empresa">
                                </div>    
                            </div>    
                        </div>

                        <div class="row mt-1">
                            <div class="col-md-12">
                                <button type="button" class="btn btn-primary" id="btn_save_container" style="width:100%;">Cadastrar Container</button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
<script>    

    var transport_id = {!! $transporter !!};
    var container_id = {!! $id !!};
    var company_country = {!! $company_country !!};
    var company_state = '{!! $company_state !!}';
    var code_state = null;

    var states = { 
        'AC' : 415, 'AL' : 422, 'AP' : 406, 'AM' : 407, 'BA' : 402, 'CE' : 409, 
        'DF' : 424, 'ES' : 401, 'GO' : 411, 'MA' : 419, 'MT' : 418, 'MS' : 399, 
        'MG' : 404, 'PA' : 408, 'PB' : 405, 'PR' : 413, 'PE' : 417, 'PI' : 416, 
        'RJ' : 410, 'RN' : 414, 'RS' : 400, 'RO' : 403, 'RR' : 421, 'SC' : 398, 
        'SP' : 412, 'SE' : 423, 'TO' : 420
    };

    $(document).ready(function () {

        if(transport_id > 0) {
            $('#company_name, #company_identity, #company_address, #company_city, #company_state, #company_phone, #company_email, #company_ramal, #company_country').prop('disabled', true);
        }
        else if(container_id > 0 && transport_id == 0) {

            $('#company_state').load('/states?country='+company_country, function(response, status, xhr) {
                if ( status == "error" ) {
                    error('<?= __('trip_i.etn_select_network_error') ?>');
                } else {
                    $('#company_state').val(company_state);
                }
            });
        }

        $(".select-transporter, .select-transporter-filter").select2({
            maximumSelectionLength: 1,
            placeholder: "Selecione",
            language: {
                noResults: function () {
                    return 'Tranportadora não encontrada!';
                }
            },
            ajax: {
                url: '/logistics/transporter/list/dropdown',
                data: function (params) {
                    var query = {
                        search: params.term,
                        page: params.page || 1
                    }
                    return query;
                }
            }
        });

        $('.select-transporter').on('select2:select', function (e) {

            var data = e.params.data;

            $('#company_name').val(data.name).prop('disabled', true);
            $('#company_identity').val(data.identity).prop('disabled', true);
            $('#company_address').val(data.address).prop('disabled', true);
            $('#company_city').val(data.city).prop('disabled', true);
            $('#company_phone').val(data.phone).prop('disabled', true);
            $('#company_email').val(data.email).prop('disabled', true);
            $('#company_country').val(27).change().prop('disabled', true);
            code_state = states[data.state];
            
        });   

        $('.select-transporter').on('select2:close', function (e) {
            $('#company_name, #company_identity, #company_address, #company_city, #company_state, #company_phone, #company_email, #company_ramal, #company_country').val('').prop('disabled', false);
        });

        $('#company_country').change(function(){

            $('#company_state').load('/states?country='+$('#company_country').val(), function(response, status, xhr) {
                if ( status == "error" ) {
                    error('<?= __('trip_i.etn_select_network_error') ?>');
                } else {
                    if(code_state == null) {
                        $('#company_state').prepend('<option value="" selected>Selecione o estado</option>');
                    } else {
                        $('#company_state').val(code_state).change();
                    }
                }
            });
        });

        $("#btn_save_container").click(function() {

            if($("#number_container").val() == "") {
                return $error('Informe o número do container');
            }
            else if ($('#company_name').val() == "") {
                return $error('Informe a razão social da empresa');
            }
            else if ($('#company_identity').val() == "") {
                return $error('Informe o CNPJ da empresa');
            }
            else if ($('#company_address').val() == "") {
                return $error('Informe o endereço da empresa');
            }
            else if ($('#company_city').val() == "") {
                return $error('Informe a cidade da empresa');
            }
            else if ($('#company_country').val() == "") {
                return $error('Informe o país da empresa');
            }
            else if ($('#company_state').val() == "") {
                return $error('Informe o estado da empresa');
            }
            else if ($('#company_phone').val() == "") {
                return $error('Informe o telefone da empresa');
            }
            else if ($('#company_email').val() == "") {
                return $error('Informe o email da empresa');
            }
            else {
                $("#form_container").submit();
            }
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

        setInterval(() => {
            $("#mLogistics").addClass('sidebar-group-active active');
            $("#mLogisticsContainer").addClass('sidebar-group-active active');
        }, 100);
    });
</script>
@endsection
