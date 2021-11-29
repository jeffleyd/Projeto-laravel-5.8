@extends('gree_i.layout')

@section('content')

<style>
    .help-block {
        color: #bf3232;
        font-size: 13px;
        font-weight: 100;
    }
</style>    

<div class="content-overlay"></div>
    <div class="content-wrapper">
        <div class="content-header row">
            <div class="content-header-left col-12 mb-2 mt-1">
                <div class="row breadcrumbs-top">
                    <div class="col-12">
                        <h5 class="content-header-title float-left pr-1 mb-0">Jurídico</h5>
                        <div class="breadcrumb-wrapper col-12">Escritórios de advocacia</div>
                    </div>
                </div>
            </div>
        </div>
        <div class="content-header row"></div>
        <div class="content-body">
            <form class="needs-validation" action="/juridical/law/firm/edit_do" id="submitLawFirm" method="post" enctype="multipart/form-data">
                <input type="hidden" name="id" value="{{ $id }}">
                <input type="hidden" id="arr_law_firm_contacts" name="arr_contacts">
                <input type="hidden" id="arr_law_firm_bank" name="arr_bank">
                <section id="basic-tabs-components">
                    <div class="card">
                        <div class="card-header">
                            <div class="card-title">
                                @if($id == 0)
                                    <h4>Novo escritório</h4>
                                @else     
                                    <h4>Atualizar escritório</h4>    
                                @endif
                            </div>
                        </div>
                        <div class="card-content">
                            <div class="card-body">
                                <ul class="nav nav-tabs" role="tablist">
                                    <li class="nav-item current">
                                        <a class="nav-link active" id="home-tab" data-toggle="tab" href="#home" aria-controls="home" role="tab" aria-selected="true">
                                            <i class="bx bx-spreadsheet align-middle"></i>
                                            <span class="align-middle">Informações</span>
                                        </a>
                                    </li>
                                    <li class="nav-item">
                                        <a class="nav-link" id="profile-tab" data-toggle="tab" href="#profile" aria-controls="profile" role="tab" aria-selected="false">
                                        <i class="bx bx-group align-middle"></i>
                                        <span class="align-middle">Contatos</span>
                                        </a>
                                    </li>
                                    <li class="nav-item">
                                        <a class="nav-link" data-toggle="tab" href="#bank_tab" aria-controls="bank" role="tab" aria-selected="false">
                                        <i class="bx bxs-bank align-middle"></i>
                                        <span class="align-middle">Dados bancários</span>
                                        </a>
                                    </li>
                                </ul>
                                <div class="tab-content">
                                    <div class="tab-pane active" id="home" aria-labelledby="home-tab" role="tabpanel">
                                        <div class="row">
                                            <div class="col-md-12">
                                                <fieldset class="form-group">
                                                    <label for="name">Razão Social / Nome</label>
                                                    <input type="text" class="form-control" id="name" name="name" value="{{$law_firm->name}}" required>
                                                </fieldset>
                                            </div>
                                        </div>    
                                        <div class="row">
                                            <div class="col-md-2">
                                                <fieldset class="form-group">
                                                    <label for="type_people">Tipo de pessoa</label>
                                                    <select name="type_people" id="type_people" class="form-control">
                                                        <option value="1" @if ($law_firm->type_people == 1) selected @endif>Pessoa física (CPF)</option>
                                                        <option value="2" @if ($law_firm->type_people == 2) selected @endif>Pessoa Juridica (CNPJ)</option>
                                                    </select>
                                                </fieldset>
                                            </div>
                                            <div class="col-md-10">
                                                <fieldset class="form-group">
                                                    <label for="identity">CPF/CNPJ</label>
                                                    <input type="text" class="form-control mask-cnpj-cpf" id="identity" name="identity" value="{{$law_firm->identity}}" required>
                                                </fieldset>
                                            </div>
                                        </div>    
                                        <div class="row">
                                            <div class="col-md-12">
                                                <fieldset class="form-group">
                                                    <label for="address">Endereço</label>
                                                    <input type="text" class="form-control" id="address" name="address" value="{{$law_firm->address}}" placeholder="Informe o endereço" required>
                                                </fieldset>
                                            </div>
                                        </div>    
                                        <div class="row">    
                                            <div class="col-md-6">
                                                <fieldset class="form-group">
                                                    <label for="address">Cidade</label>
                                                    <input type="text" class="form-control" id="city" name="city" value="{{$law_firm->city}}" placeholder="Informe a cidade" required>
                                                </fieldset>
                                            </div>
                                            <div class="col-md-6">
                                                <fieldset class="form-group">
                                                    <label for="address">Estado</label>
                                                    <select class="form-control" id="state" name="state" required>
                                                        <option value="">Selecione o estado</option>
                                                        @foreach (config('gree.states') as $key => $value)
                                                            <option value="{{ $key }}" @if ($key == $law_firm->state) selected @endif>{{ $value }}</option>
                                                        @endforeach
                                                    </select>
                                                </fieldset>
                                            </div>
                                        </div>    
                                        <div class="row">    
                                            <div class="col-md-12">
                                                <fieldset class="form-group">
                                                    <label for="complement">Comeplemento</label>
                                                    <input type="text" class="form-control" id="complement" name="complement" value="{{$law_firm->complement}}" placeholder="Bloco D apto 108">
                                                </fieldset>
                                            </div>
                                        </div>
                                        <br>
                                        <div class="row"> 
                                            <div class="col-md-12">
                                                <ul class="list-unstyled mb-0 border p-2 text-center mb-2">
                                                    <li class="d-inline-block mr-2">
                                                        <fieldset>
                                                            <div class="custom-control custom-radio">
                                                                <input type="radio" class="custom-control-input" value="1" name="is_active" id="active" 
                                                                    checked="" <?php if($law_firm->is_active == "1") { echo 'checked'; } ?>>
                                                                <label class="custom-control-label" for="active">Ativo</label>
                                                            </div>
                                                        </fieldset>
                                                    </li>
                                                    <li class="d-inline-block mr-2">
                                                        <fieldset>
                                                            <div class="custom-control custom-radio">
                                                                <input type="radio" class="custom-control-input" value="0" name="is_active" id="desactive"
                                                                    <?php if($law_firm->is_active == 0) { echo 'checked'; } ?>>
                                                                <label class="custom-control-label" for="desactive">Desativado</label>
                                                            </div>
                                                        </fieldset>
                                                    </li>
                                                </ul>
                                            </div> 
                                        </div>
                                    </div>
                                    <div class="tab-pane" id="profile" aria-labelledby="profile-tab" role="tabpanel">
                                        <div class="row" style="margin-bottom:20px;">
                                            <div class="col-md-8">
                                                <p style="position: relative; top: 10px;">Após adicionar os contatos é necessário salvar em <code>Criar Escritório</code></p>
                                            </div>
                                            <div class="col-md-4">
                                                <button type="button" id="btnHistoric" class="btn btn-primary float-right" data-toggle="modal" data-target="#modal-contacts">Adicionar Contato</button>
                                            </div>
                                        </div>
                                        
                                        <div class="table-responsive">
                                            <table class="table">
                                            <thead>
                                                <tr>
                                                <th>Nome</th>
                                                <th>Telefone</th>
                                                <th>Email</th>
                                                <th>Ação</th>
                                                </tr>
                                            </thead>
                                            <tbody id="table_contacts">
                                                @if(count($arr_contacts) > 0)
                                                    @foreach ($arr_contacts as $index => $key)
                                                    <tr>
                                                        <td>{{ $key->name }}</td>
                                                        <td>{{ $key->phone_1 }} / {{ $key->phone_2 }}</td>
                                                        <td>{{ $key->email }}</td>
                                                        <td><a onclick='deleteTableContact(this)' data-id='<?= $index ?>' style='cursor: pointer;' class='btn-less'><i class='badge-circle badge-circle-light-secondary bx bx-trash font-medium-1'></i></a></td>
                                                    </tr>
                                                    @endforeach
                                                @else
                                                    <tr>
                                                        <td class="text-bold-200">Não há contatos adicionados!</td>
                                                    <tr>
                                                @endif
                                            </tbody>
                                            </table>
                                        </div>
                                    </div>
                                    <div class="tab-pane" id="bank_tab" aria-labelledby="bank-tab" role="tabpanel">
                                        <div class="row" style="margin-bottom:20px;">
                                            <div class="col-md-12">
                                                <button type="button" id="btnHistoric" class="btn btn-primary float-left" data-toggle="modal" data-target="#modal-bank">Adicionar Conta</button>
                                            </div>
                                        </div>
                                        <div class="table-responsive">
                                            <table class="table">
                                            <thead>
                                                <tr>
                                                    <th>Agência</th>
                                                    <th>Conta</th>
                                                    <th>Banco</th>
                                                    <th>CNPJ / CPF</th>
                                                    <th>Conta principal</th>
                                                    <th>Ação</th>
                                                </tr>
                                            </thead>
                                            <tbody id="table_bank">
                                                @if(count($arr_bank) > 0)
                                                    @foreach ($arr_bank as $index => $key)
                                                    <tr>
                                                        <td>{{ $key->agency }}</td>
                                                        <td>{{ $key->account }}</td>
                                                        <td>{{ $key->bank }}</td>
                                                        <td>{{ $key->identity_account }}</td>
                                                        <td>{{ $key->is_master ? 'Sim' : 'Não' }}</td>
                                                        <td><a onclick='deleteTableBank(this)' data-id='<?= $index ?>' style='cursor: pointer;' class='btn-less'><i class='badge-circle badge-circle-light-secondary bx bx-trash font-medium-1'></i></a></td>
                                                    </tr>
                                                    @endforeach
                                                @else
                                                    <tr>
                                                        <td class="text-bold-200">Não há contatos adicionados!</td>
                                                    <tr>
                                                @endif
                                            </tbody>
                                            </table>
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="col-md-12">
                                            <button type="button" id="new_office" class="btn btn-primary" style="width:100%;">@if($id == 0)Criar Escritório @else Atualizar cadastro @endif</button>
                                        </div>
                                    </div>
                                </div>
                            </div>                       
                        </div>
                    </div>
                </section>
            </form>    
        </div>
    </div>

    <div class="modal fade text-left" id="modal-contacts" tabindex="-1" role="dialog" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered modal-dialog-scrollable" role="document">
            <div class="modal-content">
                <div class="modal-header bg-primary white">
                    <span class="modal-title">Novo Contato</span>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close"><i class="bx bx-x"></i></button>
                </div>
                <div class="modal-body">
                    <div class="row">
                        <div class="col-sm-12">
                            <label for="type">Nome *</label>
                            <fieldset class="form-group">
                                <input type="text" class="form-control" id="contact_name" name="contact_name" placeholder="Informe o nome completo">
                            </fieldset>
                        </div>
                        <div class="col-sm-12">
                            <label for="type">Email *</label>
                            <fieldset class="form-group">
                                <input type="text" class="form-control" id="contact_email" name="contact_email" placeholder="joao@dominio.com.br">
                            </fieldset>
                        </div>
                        <div class="col-sm-6">
                            <label for="type">Telefone 1 *</label>
                            <fieldset class="form-group">
                                <input type="text" class="form-control format-phone" id="contact_phone1" name="contact_phone1" placeholder="(00) 00000-0000">
                            </fieldset>
                        </div>
                        <div class="col-sm-6">
                            <label for="users-list-verified">Telefone 2</label>
                            <fieldset class="form-group">
                                <input type="text" class="form-control format-phone" id="contact_phone2" name="contact_phone2" placeholder="(00) 00000-0000">
                            </fieldset>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" id="btn_add_contact" class="btn btn-primary ml-1">
                        <span class="d-sm-block">Adicionar contato</span>
                    </button>
                </div>
            </div>
        </div>
    </div>

    <div class="modal fade text-left" id="modal-bank" tabindex="-1" role="dialog" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered modal-dialog-scrollable" role="document">
            <div class="modal-content">
                <div class="modal-header bg-primary white">
                    <span class="modal-title">Nova conta bancária</span>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close"><i class="bx bx-x"></i></button>
                </div>
                <div class="modal-body">
                    <div class="row">
                        <div class="col-sm-12">
                            <label for="type">Agência *</label>
                            <fieldset class="form-group">
                                <input type="text" class="form-control" id="bank_agency" name="bank_agency" placeholder="Informe a agência">
                            </fieldset>
                        </div>
                        <div class="col-sm-12">
                            <label for="type">Conta *</label>
                            <fieldset class="form-group">
                                <input type="text" class="form-control" id="bank_account" name="bank_account" placeholder="Informe a conta">
                            </fieldset>
                        </div>
                        <div class="col-sm-12">
                            <label for="type">Banco *</label>
                            <fieldset class="form-group">
                                <input type="text" class="form-control" id="bank_name" name="bank_name" placeholder="Informe o banco">
                            </fieldset>
                        </div>
                        <div class="col-sm-12">
                            <label for="users-list-verified">CNPJ / CPF</label>
                            <fieldset class="form-group">
                                <input type="text" class="form-control mask-cnpj-cpf" id="identity_account" name="identity_account" placeholder="00.000.000/0000-00">
                            </fieldset>
                        </div>
                        <div class="col-sm-12">
                            <label for="users-list-verified">Conta principal ?</label>
                            <fieldset class="form-group">
                                <ul class="list-unstyled mb-0">
                                    <li class="d-inline-block mr-2 mb-1">
                                      <fieldset>
                                        <div class="radio radio-shadow">
                                            <input type="radio" id="radio1" name="is_master" value="1" checked="">
                                            <label for="radio1">Sim</label>
                                        </div>
                                      </fieldset>
                                    </li>
                                    <li class="d-inline-block mr-2 mb-1">
                                      <fieldset>
                                        <div class="radio radio-shadow">
                                            <input type="radio" id="radio2" name="is_master" value="0">
                                            <label for="radio2">Não</label>
                                        </div>
                                      </fieldset>
                                    </li>
                                </ul>
                            </fieldset>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" id="btn_add_account" class="btn btn-primary ml-1">
                        <span class="d-sm-block">Adicionar Conta</span>
                    </button>
                </div>
            </div>
        </div>
    </div>

    <script>

        var arr_contact = {!! json_encode($arr_contacts) !!};
        var arr_bank = {!! json_encode($arr_bank) !!};

        $(document).ready(function () {

            $.extend($.validator.messages, {
                required: "Campo é obrigatório."
            });

            $("#submitLawFirm").validate({
                rules: {},
                ignore:"ui-tabs-hide",
                errorElement: "span",
                errorClass: "help-block",
                highlight: function (element, errorClass, validClass) {

                    $(element).removeClass(errorClass); //.removeClass(errorClass);
                    $(element).closest('.form-group').removeClass('has-success').addClass('has-error');
                },
                unhighlight: function (element, errorClass, validClass) {
                    $(element).addClass(validClass); //.addClass(validClass);
                    $(element).closest('.form-group').removeClass('has-error').addClass('has-success');
                },
                submitHandler: function(form) {
                    form.submit();
                }
            });

            $("#btn_add_contact").click(function(){
                var obj_contact = {
                    id: 0,
                    juridical_law_firm_id: 0,
                    name: $("#contact_name").val(),
                    phone_1: $("#contact_phone1").val(),
                    phone_2: $("#contact_phone2").val(),
                    email: $("#contact_email").val()
                };

                if($("#contact_name").val() == "") {

                    return $error('Nome do contato é obrigatório!');
                } else if ($("#contact_email").val() == "") {

                    return $error('Email do contato é obrigatório!');
                } else if($("#contact_phone1").val() == "") {

                    return $error('Telefone 1 do contato é obrigatório!');
                } else {

                    arr_contact.push(obj_contact);
                    $('#table_contacts').html(genHTML(arr_contact));

                    $("#contact_name, #contact_email, #contact_phone1, #contact_phone2").val('');
                    $("#modal-contacts").modal('hide');
                }
            });

            $("#btn_add_account").click(function(){

                var obj_bank = {
                    id: 0,
                    juridical_law_firm_id: 0,
                    agency: $("#bank_agency").val(),
                    account: $("#bank_account").val(),
                    bank: $("#bank_name").val(),
                    identity_account: $("#identity_account").val(),
                    is_master: $('input[name=is_master]:radio:checked').val()
                };

                if($("#bank_agency").val() == "") {

                    return $error('Agência obrigatória!');
                } 
                else if ($("#bank_account").val() == "") {

                    return $error('Conta é obrigatória!');
                } 
                else if ($("#bank_name").val() == "") {

                    return $error('Banco é obrigatório!');
                } 
                else if($("#identity_account").val() == "") {

                    return $error('CNPJ / CPF obrigatório!');
                } else {

                    if($('input[name=is_master]:radio:checked').val() == 1) {
                        setAccountMaster();
                    }

                    arr_bank.push(obj_bank);
                    $('#table_bank').html(genBankHtml(arr_bank.reverse()));

                    $("#bank_agency, #bank_account, #bank_name, #identity_account").val('');
                    $("#modal-bank").modal('hide');
                }
            });

            $("#type_people").change(function (e) { 
                if($("#type_people").val() == 1) {

                    $('#identity').mask('000.000.000-00', {reverse: false});
                } else {
                    $('#identity').mask('00.000.000/0000-00', {reverse: false});
                }
            });

            $("#new_office").click(function (){

                if ($('#submitLawFirm').valid()) {

                    if(arr_contact.length == 0) {
                        return $error('Adicione ao menos um contato!');
                    } else {
                        $("#arr_law_firm_contacts").val(JSON.stringify(arr_contact));
                    }
                    $("#arr_law_firm_bank").val(JSON.stringify(arr_bank));

                    block();
                    $('#submitLawFirm').submit();
                }   
            });
            
            $('.format-phone').mask('(00) 00000-0000', {reverse: false});

            var CpfCnpjMaskBehavior = function (val) {
                return val.replace(/\D/g, '').length <= 11 ? '000.000.000-009' : '00.000.000/0000-00';
            },
            cpfCnpjpOptions = {
                    onKeyPress: function(val, e, field, options) {
                        field.mask(CpfCnpjMaskBehavior.apply({}, arguments), options);
                }
            };
            $('.mask-cnpj-cpf').mask(CpfCnpjMaskBehavior, cpfCnpjpOptions);
           
            setInterval(() => {
                $("#mJuridical").addClass('sidebar-group-active active');
                $("#mJuridicalLawFirm").addClass('sidebar-group-active active');
                $("#mJuridicalLawFirmNew").addClass('active');
            }, 100);
        });

        function setAccountMaster() {
            if(arr_bank.length != 0) {
                arr_bank.forEach(element => {
                    element.is_master = 0;
                });
            }
        }

        function genHTML(object) {
            var html = '';
            for (var i = 0; i < object.length; i++) {
                var column = object[i];

                html += '<tr>';
                html += '<td>'+column.name+'</td>';
                html += '<td>'+column.phone_1+' / '+column.phone_2+'</td>';
                html += '<td>'+column.email+'</td>';
                html += "<td><a onclick='deleteTableContact(this)' style='cursor: pointer;' data-id='"+ i +"' class='btn-less'><i class='badge-circle badge-circle-light-secondary bx bx-trash font-medium-1'></i></a></td>";
                html += '</tr>';
            }
            return html;
        }

        function genBankHtml(object) {
            var html = '';
            for (var i = 0; i < object.length; i++) {
                var column = object[i];

                var is_master = column.is_master == 1 ? 'Sim' : 'Não';

                html += '<tr>';
                html += '<td>'+column.agency+'</td>';
                html += '<td>'+column.account+'</td>';
                html += '<td>'+column.bank+'</td>';
                html += '<td>'+column.identity_account+'</td>';
                html += '<td>'+is_master+'</td>';
                html += "<td><a onclick='deleteTableBank(this)' style='cursor: pointer;' data-id='"+ i +"' class='btn-less'><i class='badge-circle badge-circle-light-secondary bx bx-trash font-medium-1'></i></a></td>";
                html += '</tr>';
            }
            return html;
        }

        function deleteTableContact(el) {
            var index = $(el).attr('data-id');
            arr_contact.splice(index, 1);
            $(el).parent().parent().remove();
        }

        function deleteTableBank(el) {
            var index = $(el).attr('data-id');
            arr_bank.splice(index, 1);
            $(el).parent().parent().remove();
        }
    </script>
@endsection