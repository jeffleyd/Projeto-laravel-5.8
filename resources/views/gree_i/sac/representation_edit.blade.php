@extends('gree_i.layout')

@section('content')
<div class="content-overlay"></div>
    <div class="content-wrapper">
        <div class="content-header row">
            <div class="content-header-left col-12 mb-2 mt-1">
              <div class="row breadcrumbs-top">
                <div class="col-12">
                  <h5 class="content-header-title float-left pr-1 mb-0">Representantes</h5>
                  <div class="breadcrumb-wrapper col-12">
                    @if ($id == 0)
                    Novo representante
                    @else
                    Atualizar representante: {{ $id }}
                    @endif
                  </div>
                </div>
              </div>
            </div>
          </div>
        <div class="content-header row">
        </div>
        <div class="content-body">
            <form class="needs-validation" action="/sac/register/salesman/edit_do" id="submitEdit" method="post" enctype="multipart/form-data">
            <input type="hidden" name="id" id="id" value="<?= $id ?>">
            <section>
                <div class="card">
                    <div class="card-header">
                      <h4 class="card-title">Preencha todos os dados com atenção</h4>
                    </div>
                    <div class="card-content">
                      <div class="card-body">
                        <div class="row">

                            <div class="col-md-12">
                                <ul class="list-unstyled mb-0 border p-2 text-center mb-2">
                                    <li class="d-inline-block mr-2">
                                    <fieldset>
                                        <div class="custom-control custom-radio">
                                        <input type="radio" class="custom-control-input" value="1" <?php if ($is_active == 1) { ?> checked=""<?php } else { ?><?php } ?> name="is_active" id="active" checked="">
                                        <label class="custom-control-label" for="active">Ativo</label>
                                        </div>
                                    </fieldset>
                                    </li>
                                    <li class="d-inline-block mr-2">
                                    <fieldset>
                                        <div class="custom-control custom-radio">
                                        <input type="radio" class="custom-control-input" value="0" <?php if ($is_active == 0) { ?> checked=""<?php } else { ?><?php } ?> name="is_active" id="desactive">
                                        <label class="custom-control-label" for="desactive">Desativado</label>
                                        </div>
                                    </fieldset>
                                    </li>
                                </ul>
                            </div>

                            <div class="col-md-6">
                                <fieldset class="form-group">
                                    <label for="identity">CPF</label>
                                    <input type="text" class="form-control" id="identity" name="identity" value="{{ $identity }}" required>
                                </fieldset>
                            </div>

                            <div class="col-md-6">
                                <fieldset class="form-group">
                                    <label for="name">Nome fantasia</label>
                                    <input type="text" class="form-control" name="name" value="{{ $name }}" required>
                                </fieldset>
                            </div>
                            
                            <div class="col-md-6">
                                <fieldset class="form-group">
                                    <label for="name_contact">Nome do contato</label>
                                    <input type="text" class="form-control" name="name_contact" value="{{ $name_contact }}" required>
                                </fieldset>
                            </div>

                            <div class="col-md-6">
                                <fieldset class="form-group">
                                    <label for="phone_1">Telefone</label>
                                    <input type="text" class="form-control" id="phone_1" name="phone_1" value="{{ $phone_1 }}" required>
                                </fieldset>
                            </div>

                            <div class="col-md-6">
                                <fieldset class="form-group">
                                    <label for="phone_2">Telefone</label>
                                    <input type="text" class="form-control" id="phone_2" name="phone_2" value="{{ $phone_2 }}">
                                </fieldset>
                            </div>

                            <div class="col-md-6">
                                <fieldset class="form-group">
                                    <label for="email">Email</label>
                                    <input type="text" class="form-control" name="email" value="{{ $email }}">
                                </fieldset>
                            </div>

                            <div class="col-md-9">
                                <fieldset class="form-group">
                                    <label for="address">Digite a rua e o número</label>
                                    <input type="text" class="form-control" name="address" id="address" value="{{ $address }}" placeholder="Digite o endereço e número." required>
                                </fieldset>
                            </div>

                            <div class="col-md-3">
                                <fieldset class="form-group">
                                    <label for="complement">Comeplemento</label>
                                    <input type="text" class="form-control" name="complement" value="{{ $complement }}" placeholder="Bloco D apto 108">
                                </fieldset>
                            </div>

                            <div class="col-md-6">
                                <fieldset class="form-group">
                                    <label for="zipcode">CEP</label>
                                    <input type="text" class="form-control" name="zipcode" id="zipcode" value="{{ $zipcode }}" placeholder="Digite o cep.">
                                </fieldset>
                            </div>

                            <div class="col-md-3">
                                <fieldset class="form-group">
                                    <label for="city">Cidade</label>
                                    <input type="text" class="form-control" name="city" id="city" value="{{ $city }}" placeholder="Digite a cidade.">
                                </fieldset>
                            </div>

                            <div class="col-md-3">
                                <fieldset class="form-group">
                                    <label for="state">Estado</label>
                                    <input type="text" class="form-control" name="state" id="state" value="{{ $state }}" placeholder="Digite o estado.">
                                </fieldset>
                            </div>
                            
                        </div>
                      </div>
                    </div>
                  </div>
                
            </section>

            <button type="submit" id="NewRequest" class="btn btn-primary">@if ($id == 0)
                Criar representante  
                @else
                Atualizar representante
                @endif
            </button>

        </form>
        </div>
    </div>
    <script>
        $(document).ready(function () {
                
            $("#submitEdit").submit(function (e) { 
                var form = $(".needs-validation");
                if (form[0].checkValidity() === false) {
                    e.preventDefault();
                } else {
                    block();
                }
            });

            $('#zipcode').mask('00000-000', {reverse: false});
            $('#identity').mask('00.000.000/0000-00', {reverse: false});
            $('#phone_1').mask('(00) 00000-0000', {reverse: false});
            $('#phone_2').mask('(00) 00000-0000', {reverse: false});

            setInterval(() => {
            $("#mAfterSales").addClass('sidebar-group-active active');
            $("#mSacRegister").addClass('sidebar-group-active active');
            $("#mSacRegisterSalesman").addClass('active');
        }, 100);
        });
    </script>
@endsection