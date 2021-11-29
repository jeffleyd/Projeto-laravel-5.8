@extends('gree_i.layout')

@section('content')
<style>
    .alert-custom {
        position: fixed;
        bottom: 0;
    }
</style>
<div class="content-overlay"></div>
    <div class="content-wrapper">
        <div class="content-header row">
            <div class="content-header-left col-12 mb-2 mt-1">
              <div class="row breadcrumbs-top">
                <div class="col-12">
                  <h5 class="content-header-title float-left pr-1 mb-0">Atendimento</h5>
                  <div class="breadcrumb-wrapper col-12">
                    @if ($id == 0)
                    Novo atendimento
                    @else
                    Atualizar atendimento: {{ $code }}
                    @endif
                  </div>
                </div>
              </div>
            </div>
          </div>
        <div class="content-header row">
        </div>
        <div class="content-body">
            <form action="/sac/warranty/edit_do" id="submitEdit" method="post" enctype="multipart/form-data">
            <input type="hidden" name="id" id="id" value="<?= $id ?>">
            <input type="hidden" name="latitude" id="latitude" value="<?= $latitude ?>">
            <input type="hidden" name="longitude" id="longitude" value="<?= $longitude ?>">
            <input type="hidden" name="json_data" id="json_data" value="">

            <section id="autoSearch" @if ($is_warranty == 0) style="display: none;" @endif>
                <div class="card">
                    <div class="card-header">
                    </div>
                    <div class="card-content">
                      <div class="card-body">
                        <div class="row">

                            <div class="col-md-12">
                                <ul class="list-unstyled mb-0">
                                    <li class="d-inline-block mr-2 mb-1">
                                      <fieldset>
                                        <div class="checkbox checkbox-shadow">
                                            <input type="checkbox" name="alertRequest" value="1" id="alertRequest">
                                            <label for="alertRequest">Marque essa opção para avisar as autorizadas/credênciadas próxima do atendimento.</label>
                                        </div>
                                      </fieldset>
                                    </li>
                                  </ul>
                            </div>

                        </div>
                      </div>
                    </div>
                  </div>

            </section>

            <section>
                <div class="card">
                    <div class="card-header">
                      <h4 class="card-title">PREENCHA TODOS OS DADOS COM ATENÇÃO</h4>
                    </div>
                    <div class="card-content">
                      <div class="card-body">
                        <div class="row">

							<div class="col-md-12">
                                <fieldset class="form-group">
                                    <label for="type">Tipo de atendimento</label>
                                    <select class="form-control" id="type" name="type">
                                        <option value=""></option>
                                        <option value="1" @if ($type == 1) selected @endif>Reclamação</option>
                                        <option value="2" @if ($type == 2) selected @endif>Atend. em garantia</option>
                                        <option value="3" @if ($type == 3) selected @endif>Dúvida técnica</option>
                                        <option value="4" @if ($type == 4) selected @endif>Revenda</option>
                                        <option value="5" @if ($type == 5) selected @endif>Credenciamento</option>
                                        <option value="7" @if ($type == 7) selected @endif>Atendimento fora de garantia</option>
                                        <option value="8" @if ($type == 8) selected @endif>Atendimento negado (erro de inst.)</option>
                                        <option value="9" @if ($type == 9) selected @endif>Autorização de instalação</option>
                                        <option value="10" @if ($type == 10) selected @endif>Atendimento tercerizado</option>
										<option value="11" @if ($type == 11) selected @endif>Atendimento em cortesia</option>
                                        <option value="6" @if ($type == 6) selected @endif>Outros</option>
                                    </select>
                                </fieldset>
                            </div>
							
                            <div class="col-md-12">
                                <fieldset class="form-group">
                                    <label for="is_warranty">É atendimento em garantia?</label>
                                    <select class="form-control" id="is_warranty" name="is_warranty">
                                        <option value="0" @if ($is_warranty == 0) selected @endif>Não</option>
                                        <option value="1" @if ($is_warranty == 1) selected @endif>Sim</option>
                                    </select>
                                </fieldset>
                            </div>

                            <div class="col-md-6 problem_category" @if ($type == 9) style="display:none;" @endif>
                                <fieldset class="form-group">
                                    <label for="problem_category">Categoria de Problema</label>
                                    <select class="form-control" id="problem_category" name="problem_category">
                                        <option value=""></option>
                                        @foreach ($problem_category as $key)
                                        <option value="{{ $key->id }}" @if ($key->id == $sac_problem_category) selected @endif>{{ $key->description }}</option>
                                        @endforeach
                                    </select>
                                </fieldset>
                            </div>
							
							<div class="col-md-6 installed_by" @if ($type == 9) style="display:none;" @endif>
                                <fieldset class="form-group">
                                    <label for="installed_by">Instalado por</label>
                                    <select class="form-control" name="installed_by" id="installed_by">
                                          <option value=""></option>
										  <option value="2" @if($installed_by == 2) selected @endif>POSTO AUTORIZADO GREE</option>
                                          <option value="1" @if($installed_by == 1) selected @endif>Empresa terceira</option>
                                      </select>
                                </fieldset>
                            </div>

                            <div class="col-md-12">
                                <fieldset class="form-group">
                                    <label for="r_code">Operador responsável</label>
                                    <select class="form-control js-select23" id="r_code" name="r_code" multiple>
                                        @foreach ($users as $key)
                                        <option value="{{ $key->r_code }}" @if ($key->r_code == $r_code) selected @endif>{{ $key->first_name }} {{ $key->last_name }} ({{ $key->r_code }})</option>
                                        @endforeach
                                    </select>
                                </fieldset>
                            </div>

                            <div class="col-md-12 client">
                            <div class="row">
                            <div class="col-md-2">
                                <fieldset class="form-group">
                                    <label for="search_c">Pesquisar (Cliente)</label>
                                    <select class="form-control" id="search_c" name="search_c">
                                        <option value="0">Livre</option>
                                        <option value="1">CPF</option>
                                        <option value="2">CNPJ</option>
                                    </select>
                                </fieldset>
                            </div>
                            <div class="col-md-10">
                                <fieldset class="form-group">
                                    <label for="client">Cliente</label>
                                    <select class="form-control js-select2" id="client" name="client" multiple>
                                        @if ($client)
                                        <option value="{{ $client->id }}" selected>{{ $client->name }} ({{ $client->identity }})</option>
                                        @endif
                                    </select>
                                </fieldset>
                            </div>
                            </div>
                            </div>

                            <div class="col-md-12 authorized">
                            <div class="row">
                            <div class="col-md-2">
                                <fieldset class="form-group">
                                    <label for="search_a">Pesquisar (Autorizada)</label>
                                    <select class="form-control" id="search_a" name="search_a">
                                        <option value="0">Livre</option>
                                        <option value="1">CNPJ</option>
                                    </select>
                                </fieldset>
                            </div>
                            <div class="col-md-10">
                                <fieldset class="form-group">
                                    <label for="authorized">Autorizada/Credênciada</label>
                                    <select class="form-control js-select21" id="authorized" name="authorized" multiple>
                                        @if ($authorized)
                                        <option value="{{ $authorized->id }}" selected>{{ $authorized->name }} ({{ $authorized->identity }})</option>
                                        @endif
                                    </select>
                                </fieldset>
                            </div>
                            </div>
                            </div>

                            <div class="col-md-12">
                                <div class="row listmodels" style="margin-left: 2px;margin-bottom: 20px;margin-top: 15px;">
                                </div>
                            </div>

                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="shop">Loja que comprou</label>
                                    <input type="text" class="form-control" id="shop" name="shop" value="{{ $shop }}">
                                </div>
                            </div>

                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="buy_date">Data da compra</label>
                                    <input type="text" class="form-control" id="buy_date" name="buy_date" value="@if ($buy_date) <?= date('d/m/Y', strtotime($buy_date)) ?> @endif" placeholder="__/__/____">
                                </div>
                            </div>

                            <div class="col-md-12">
                                <fieldset class="form-group">
                                    <label for="origin">Origem do atendimento</label>
                                    <select class="form-control" id="origin" name="origin">
                                        <option value=""></option>
                                        <option value="1" @if ($origin == 1) selected @endif>Telefone</option>
                                        <option value="2" @if ($origin == 2) selected @endif>E-mail</option>
                                        <option value="3" @if ($origin == 3) selected @endif>Reclame aqui</option>
                                        <option value="4" @if ($origin == 4) selected @endif>Midia sociais</option>
                                        <option value="5" @if ($origin == 5) selected @endif>Site</option>
										<option value="6" @if ($origin == 6) selected @endif>Consumidor GOV</option>
										<option value="7" @if ($origin == 7) selected @endif>Procon</option>
                                    </select>
                                </fieldset>
                                <small>Os atendimentos do reclame aqui, não são cancelados automaticamente em 7 dias sem interação.</small>
                            </div>

                            <div class="col-md-12">
                                <fieldset class="form-group">
                                    <label for="description">Motivo</label>
                                    <textarea class="form-control" id="description" name="description">{{ $description }}</textarea>
                                </fieldset>
                            </div>

                        </div>
                      </div>
                    </div>
                  </div>

            </section>

            <section>
                <div class="card">
                    <div class="card-header">
                      <h4 class="card-title">LOCAL DO ATENDIMENTO</h4>
                    </div>
                    <div class="card-content">
                      <div class="card-body">
                        <div class="row">

                            <div class="col-md-5">
                                <fieldset class="form-group">
                                    <label for="address">Digite a rua e o número</label>
                                    <input type="text" class="form-control" value="{{ $address }}" name="address" id="address">
                                </fieldset>
                            </div>
							
							<div class="col-md-2">
                                <fieldset class="form-group">
                                    <label for="complement">Bairro</label>
                                    <input type="text" class="form-control" value="{{ $district }}" name="district" id="district">
                                </fieldset>
                            </div>

                            <div class="col-md-2">
                                <fieldset class="form-group">
                                    <label for="complement">Cidade</label>
                                    <input type="text" class="form-control" value="{{ $city }}" name="city" id="city">
                                </fieldset>
                            </div>

                            <div class="col-md-1">
                                <fieldset class="form-group">
                                    <label for="complement">Estado</label>
                                    <input type="text" class="form-control" value="{{ $state }}" name="state" id="state" placeholder="UF">
                                </fieldset>
                            </div>

                            <div class="col-md-2">
                                <fieldset class="form-group">
                                    <label for="complement">Complemento</label>
                                    <input type="text" class="form-control" value="{{ $complement }}" name="complement" id="complement">
                                </fieldset>
                            </div>

                            <div class="col-md-12">
                                <ul class="list-unstyled mb-0">
                                    <li class="d-inline-block mr-2 mb-1">
                                      <fieldset>
                                        <div class="checkbox checkbox-shadow">
                                            <input type="checkbox" value="1" name="not_address" id="not_address">
                                            <label for="not_address">Confirmo que o endereço não foi encontrado</label>
                                        </div>
                                      </fieldset>
                                    </li>
                                </ul>
                            </div>

                            <div class="col-md-12">
                                <div id="map" style="height: 300px; width:100%"></div>
                            </div>

                        </div>
                      </div>
                    </div>
                  </div>

            </section>

            <section class="request-files">
                <div class="card">
                    <div class="card-header">
                      <h4 class="card-title">Anexar arquivos</h4>
                    </div>
                    <div class="card-content">
                      <div class="card-body">
                        <div class="row">
                            <div class="col-md-4">
                                <fieldset class="form-group">
                                    <label for="number_nf">Número da nota fiscal</label>
                                    <input type="text" class="form-control" value="{{ $number_nf }}" name="number_nf" id="number_nf">
                                </fieldset>
                            </div>
                            <div class="col-md-8">
                                <div class="form-group">
                                    <label for="nf_file">Nota fiscal</label>
                                    <input type="file" class="form-control" id="nf_file" name="nf_file">
                                    @if ($nf_file)
                                    <p><a href="{{ $nf_file }}" target="_blank">VER NOTA FISCAL</a> <button onclick="removeImg('nf_file', {{$id}})" class="btn btn-danger btn-sm" style="height: 20px;padding: 0px 10px;margin-left: 20px;" type="button">Remover</button></p>
                                    @endif
                                </div>
                            </div>

                            <div class="col-md-12">
                                <div class="form-group">
                                    <label for="c_install_file">Comprovante de instalação</label>
                                    <input type="file" class="form-control" id="c_install_file" name="c_install_file">
                                    @if ($c_install_file)
                                    <p><a href="{{ $c_install_file }}" target="_blank">VER COMPROVANTE DE INSTALAÇÃO</a> <button onclick="removeImg('c_install_file', {{$id}})" class="btn btn-danger btn-sm" style="height: 20px;padding: 0px 10px;margin-left: 20px;" type="button">Remover</button></p>
                                    @endif
                                </div>
                            </div>

                            <div class="col-md-12">
                                <div class="form-group">
                                    <label for="tag_file">Foto da etiqueta do produto</label>
                                    <input type="file" class="form-control" id="tag_file" name="tag_file">
                                    @if ($tag_file)
                                    <p><a href="{{ $tag_file }}" target="_blank">VER ETIQUETA DO PRODUTO</a> <button onclick="removeImg('tag_file', {{$id}})" class="btn btn-danger btn-sm" style="height: 20px;padding: 0px 10px;margin-left: 20px;" type="button">Remover</button></p>
                                    @endif
                                </div>
                            </div>
                        </div>

                      </div>
                    </div>
                  </div>

            </section>

            <button type="submit" id="NewRequest" class="btn btn-primary">@if ($id == 0)
                Criar atendimento
                @else
                Atualizar atendimento
                @endif
            </button>

        </form>
        </div>
    </div>

    <div class="modal fade" id="addmodel" tabindex="-1" role="dialog" aria-labelledby="addmodel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered modal-dialog-centered modal-dialog-scrollable" role="document">
          <div class="modal-content">
            <div class="modal-header">
              <h5 class="modal-title">Novo modelo</h5>
              <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                <i class="bx bx-x"></i>
              </button>
            </div>
            <div class="modal-body">
              <div class="row">
                <div class="col-md-12">
                    <fieldset class="form-group">
                        <label for="model">Modelo do equipamento</label>
                        <select class="form-control js-select22" style="width: 100%" id="model" name="model[]" multiple>
                        </select>
                    </fieldset>
                </div>
                <div class="col-md-12">
                    <fieldset>
                        <label for="serie">Número de série</label>
                        <div class="input-group">
                          <input type="text" class="form-control" id="serie" name="serie">
                          <div class="input-group-append">
                            <a href="/sac/warranty/os/all?serial_number=" target="_blank" id="nmb_series_link">
                              <button class="btn btn-primary nmb_series">0</button>
                            </a>
                          </div>
                        </div>
                      </fieldset>
                </div>
                <div class="col-md-12 mt-1">
                    <fieldset class="form-group">
                        <label for="price">Preço da unidade</label>
                        <input type="text"class="form-control" style="width: 100%" id="price" name="price">
                        <p>Veja essa informação na nota fiscal. Informe o valor da unidade.</p>
                    </fieldset>
                </div>
              </div>
            </div>
            <div class="modal-footer">
              {{-- <button type="button" class="btn btn-danger actiondelclick">
                <i class="bx bx-x d-block d-sm-none"></i>
                <span class="d-none d-sm-block">Deletar</span>
              </button> --}}
              <button type="button" class="btn btn-light-secondary ml-1" data-dismiss="modal">
                <i class="bx bx-x d-block d-sm-none"></i>
                <span class="d-none d-sm-block">Fechar</span>
              </button>
              <button type="button" class="btn btn-primary ml-1 actionclick" onclick="addModel_do()">
                <i class="bx bx-check d-block d-sm-none"></i>
                <span class="d-none d-sm-block actiontxt">Adicionar</span>
              </button>
            </div>
          </div>
        </div>
      </div>

      <div class="row no-gutters justify-content-center">
        <div class="alert alert-primary alert-dismissible mb-2 alert-custom" role="alert" id="alert_note" style="display:none;">
            <button type="button" class="close" aria-label="Close" onclick="$('#alert_note').hide()">
                <span aria-hidden="true">×</span>
            </button>
            <div class="d-flex align-items-center">
                <span>
                    <i class="bx bx-error"></i> <span id="text-header"></span><br>
                    <p class="mb-0" id="text-alert" style="white-space: pre;"></p>
                    <span class="mb-0 float-right" id="text-link"><a target="_blank" href="" style="color:#ffffff;"><i class="bx bx-link-external" style="top: 3px;position: relative;"></i>Veja Mais</a></span>
                </span>
            </div>
        </div>
    </div>

    <script>
        var models = new Array();

        function removeImg($column, $id) {
            var r = confirm("Você realmente deseja excluir essa imagem?");
            if (r == true) {
                window.location.href = '/misc/unlink/aws?column='+$column+'&id='+$id
            }
        }
        function reloadModels() {
            var list = "";
            for(var i = 0; i < models.length; i++) {
                var arrayObj = models[i];
                list += '<div class="col-3 mt-1 bg-secondary bg-lighten-1 text-white cursor-pointer" style="padding: 15px;margin-right: 25px;border-radius: 10px; height: 74px;" onclick="editModel('+ i +');">';
                list += '<i class="bx bx-edit-alt" style="position: absolute;right: 10px;top: 7px;"></i>';
                list += '<p style="margin: 0;"><b>Modelo:</b> '+ arrayObj.model_name +'';
                list += '<br><b>N Série:</b> '+ arrayObj.serial +' </p>';
                list += '</div>';
            }

            list += '<div class="col-3 mt-1 bg-secondary bg-lighten-1 text-center text-white cursor-pointer" style="display: flex;justify-content: center;flex-direction: column; border-radius: 10px; height: 74px;" onclick="addModel();">';
            list += '<p style="margin: 0;">Novo modelo';
            list += '<br><i style="font-size: 18px" class="bx bx-plus-circle"></i>';
            list += '</div>';

            $(".listmodels").html(list);
            $("#json_data").val(JSON.stringify(models));
        }
        // function deleteModel(id, index) {
        //     Swal.fire({
        //             title: 'Tem certeza disso?',
        //             text: "Você irá remover o modelo em anexo!",
        //             type: 'warning',
        //             showCancelButton: true,
        //             confirmButtonColor: '#3085d6',
        //             cancelButtonColor: '#d33',
        //             confirmButtonText: 'Confirmar!',
        //             cancelButtonText: 'Cancelar',
        //             confirmButtonClass: 'btn btn-primary',
        //             cancelButtonClass: 'btn btn-danger ml-1',
        //             buttonsStyling: false,
        //             }).then(function (result) {
        //             if (result.value) {
        //                 if (id >0) {
        //                     block();
        //                     ajaxSend('/warrany/model/delete/'+id, '', 'POST').then(function(result) {
        //                         unblock();
        //                         $("#addmodel").modal('toggle');
        //                         models.splice(index, 1);
        //                         reloadModels();
        //                         Swal.fire(
        //                         {
        //                             type: "success",
        //                             title: 'Removido',
        //                             text: 'Modelo foi removido com sucesso do sistema.',
        //                             confirmButtonClass: 'btn btn-success',
        //                         }
        //                         )

        //                     }).catch(function(error) {
        //                         unblock();
        //                         $error(error.message)
        //                     });
        //                 } else {
        //                     $("#addmodel").modal('toggle');
        //                     models.splice(index, 1);
        //                     reloadModels();
        //                     Swal.fire(
        //                     {
        //                         type: "success",
        //                         title: 'Removido',
        //                         text: 'Modelo foi removido com sucesso do sistema.',
        //                         confirmButtonClass: 'btn btn-success',
        //                     }
        //                     )
        //                 }
        //             }
        //             })
        // }
        function addModel() {
            $(".modal-title").html('Novo modelo');
            $(".actionclick").attr('onclick', 'addModel_do()');
            $(".actiontxt").html('Adicionar');
            $('#model').val(0).trigger('change');
            $('#serie').val('');
            $('#price').val('');
            //$(".actiondelclick").hide();
            $("#addmodel").modal();
        }
        function addModel_do() {

            var _model = $('#model').select2('data');
            if (_model[0] == undefined) {

                return error('Você precisa escolher o modelo');
            } else if ($('#serie').val() == "") {

                return error('Você precisa informar o número de série');
            } else if ($('#price').val() == "" || $('#price').val() == 0 || $('#price').val() == 0.00) {

                return error('Você precisa informar o valor da unidade!');
            }
            for(var i = 0; i < models.length; i++) {
                var arrayObj = models[i];
                if (arrayObj.serial == $('#serie').val()) {

                    error('Você já adicionou esse número de série com esse modelo.');
                    $('#serie').val('');
                    return;
                }
            }

            $("#addmodel").modal('toggle');

            models.push({
                "item_id" : 0,
                "product_id" : _model[0].id,
                "model_name" : _model[0].text,
                "serial" : $('#serie').val(),
                "price" : $('#price').val(),
            });
            $('#model').val(0).trigger('change');
            $('#serie').val('');
            $('#price').val('');
            reloadModels();

        }
        function editModel(index) {
            var obj = models[index];
            //$(".actiondelclick").show();
            //$(".actiondelclick").attr('onclick', 'deleteModel('+obj.item_id+', '+index+')');

            $(".actionclick").attr('onclick', 'editModel_do('+index+')');
            $(".actiontxt").html('Atualizar');

            $("#addmodel").find("#model").select2('destroy');
            $("#addmodel").find("#model").html('<option value="'+obj.product_id+'" selected>'+obj.model_name+'</option>');
            $(".modal-title").html('Editando modelo');
            $(".js-select22").select2({
                maximumSelectionLength: 1,
                ajax: {
                    url: '/misc/sac/product/protocol',
                    data: function (params) {
                    var query = {
                        search: params.term,
                        page: params.page || 1
                    }
                    return query;
                    }
                }
            });

            $('#serie').val(obj.serial);
            $('#price').val(obj.price);
            $("#addmodel").modal();
        }
        function editModel_do(index) {
            var obj = models[index];

            var _model = $('#model').select2('data');
            if (_model[0] == undefined) {

                return error('Você precisa escolher o modelo');
            } else if ($('#serie').val() == "") {

                return error('Você precisa informar o número de série');
            } else if ($('#price').val() == "" || $('#price').val() == 0 || $('#price').val() == 0.00) {

                return error('Você precisa informar o valor da unidade!');
            }

            $("#addmodel").modal('toggle');

            obj.item_id = obj.item_id;
            obj.product_id = _model[0].id;
            obj.model_name = _model[0].text;
            obj.serial = $('#serie').val();
            obj.price = $('#price').val();

            $('#model').val('');
            $('#serie').val('');
            $('#price').val('');
            reloadModels();

        }
        $(document).ready(function () {
            @if (count($models) > 0)
            @foreach ($models as $key)
            models.push({
                "item_id" : {{$key->id}},
                "product_id" : {{$key->product_id}},
                "model_name" : '{{$key->model}}',
                "serial" : '{{$key->serial_number}}',
                "price" : '{{$key->price}}',
            });

            reloadModels();
            @endforeach
            @else
            var list = "";
            list += '<div class="col-3 mt-1 bg-secondary bg-lighten-1 text-center text-white cursor-pointer" style="display: flex;justify-content: center;flex-direction: column; border-radius: 10px; height: 74px;" onclick="addModel();">';
            list += '<p style="margin: 0;">Novo modelo';
            list += '<br><i style="font-size: 18px" class="bx bx-plus-circle"></i>';
            list += '</div>';

            $(".listmodels").html(list);
            @endif

            $('#buy_date').mask('00/00/0000', {reverse: false});
            $('#price').mask('00000.00', {reverse: true});

            $(".js-select2").select2({
                maximumSelectionLength: 1,
                language: {
                    noResults: function () {

                        var url = "'/sac/client/edit/0'";
                        return $('<button type="button" style="width: 100%" onclick="document.location.href='+ url +'" class="btn btn-primary">Novo cliente</button>');
                    }
                },
                ajax: {
                    url: '/misc/sac/client/',
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

            $(".js-select21").select2({
                maximumSelectionLength: 1,
                language: {
                    noResults: function () {

                        var url = "'/sac/authorized/edit/0'";
                        return $('<button type="button" onclick="document.location.href='+ url +'" style="width: 100%" class="btn btn-primary">Nova Autorizada</button>');
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

            @if ($is_warranty == 0)
            $(".authorized").hide();
            @endif
            $("#is_warranty").change(function (e) {
                if ($("#is_warranty").val() == 1) {
                    $("#autoSearch").show();
                    $(".authorized").show();
                } else {
                    $("#autoSearch").hide();
                    $(".authorized").hide();
                }
            });

            $('.js-select21').on('select2:select', function (e) {
                $("#autoSearch").hide();
            });

            $('.js-select21').on('select2:unselect', function (e) {
                $("#autoSearch").show();
            });

            $("#serie").blur(function (e) {
               ajaxSend('/sac/warranty/os/all/model/ajax', {serial_number: $("#serie").val(), haspage: 0}, 'GET', '5000').then(function(result){
                   $("#nmb_series_link").attr('href', '/sac/warranty/os/all?serial_number='+$("#serie").val());
                   $(".nmb_series").html(result.count);
               }).catch(function(err){
                   $error(err.message)
               })

            });

            $(".js-select22").select2({
                maximumSelectionLength: 1,
                ajax: {
                    url: '/misc/sac/product/',
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


            $(".js-select26").select2({
                maximumSelectionLength: 1,
                tags: true,
                language: {
                    noResults: function () {
                        return 'Informe o(s) número(s) de serie(s) seguindo a ordem do modelo.';
                    }
                },
            });

            $(".js-select23").select2({
                maximumSelectionLength: 1,
            });

            $("#search_c").change(function (e) {
                if ($("#search_c").val() == 0) {
                    $(".client").find(".select2-search__field").unmask();
                } else if ($("#search_c").val() == 1) {

                    $(".client").find(".select2-search__field").mask('000.000.000-00', {reverse: false});

                } else if ($("#search_c").val() == 2) {

                    $(".client").find(".select2-search__field").mask('00.000.000/0000-00', {reverse: false});
                }

            });

            $("#search_a").change(function (e) {
                if ($("#search_a").val() == 0) {
                    $(".authorized").find(".select2-search__field").unmask();
                } else if ($("#search_a").val() == 1) {

                    $(".authorized").find(".select2-search__field").mask('00.000.000/0000-00', {reverse: false});

                }

            });
		
			$('#type').change(function(){
				if ($('#type').val() != 9) {
					$(".problem_category").show();
					$(".installed_by").show();
				} else {
					$(".problem_category").hide();
					$(".installed_by").hide();	
				}
			});

            $("#submitEdit").submit(function (e) {
                if ($("#r_code").val() == "") {

                    e.preventDefault();

                    return error('Escolha operador responsável por esse atendimento.');
                }else if ($("#problem_category").val() == "" && $('#type').val() != 9) {

                    e.preventDefault();
                    return error('Você precisa escolher a categoria de problema!');
                }else if ($("#installed_by").val() == "" && $('#type').val() != 9) {

                    e.preventDefault();
                    return error('Você precisa escolher por quem foi instalado.');
                }else if ($("#client").val() == "") {

                    e.preventDefault();

                    return error('Você precisa escolher o cliente para esse atendimento.');
                }else if ($("#type").val() == "") {

                    e.preventDefault();

                    return error('Escolha o Tipo de atendimento.');
                } else if ($("#origin").val() == "") {

                    e.preventDefault();

                    return error('Escolha a origem desse atendimento.');
                } else if ($("#description").val() == "") {

                    e.preventDefault();

                    return error('Digite o motivo da abertura desse atendimento.');
                } else if ($("#address").val() == "") {

                    e.preventDefault();

                    return error('É necessário o preenchimento do endereço com número do local do atendimento.');
                } else if ($("#district").val() == "") {

                    e.preventDefault();

                    return error('É necessário o preenchimento do bairro.');
				
				} else if ($("#latitude").val() == "" || $("#longitude").val() == "") {

                    e.preventDefault();

                    return error('Ocorreu algum problema ao gerar LatLong, preenche o endereço novamente.');
                } else if ($("#is_warranty").val() == 1 && models.length == 0) {

                    e.preventDefault();
                    return error('Adiciona ao menos 1 modelo para poder liberar o atendimento para garantia.');
                } else {

                    block();
                }
            });

            $("#authorized").change(function (e) {
                ajaxSend('/sac/authorized/historic', {id: $(this).val()[0]}, 'GET').then(function(result) {
                    if(result.note != null) {
                        $("#alert_note").show();
                        if(result.note['priority'] == 1) {
                            $('.alert').addClass('alert-warning').removeClass('alert-primary').removeClass('alert-danger');
                        } else if(result.note['priority'] == 3) {
                            $('.alert').addClass('alert-danger').removeClass('alert-warning').removeClass('alert-primary');
                        } else {
                            $('.alert').addClass('alert-primary').removeClass('alert-warning').removeClass('alert-danger');
                        }
                        $("#text-alert").text(result.note['description']);
                        $("#text-header").text('Nota criada por '+ result.first_name + ' ' + result.last_name);
                        $("#text-link a").attr("href","/sac/authorized/edit/"+ result.note['authorized_id'] +"");
                    } else {
                        $("#alert_note").hide();
                    }
                }).catch(function(err){
                    $error(err.message);
                });
            });

            setInterval(() => {
            $("#mAfterSales").addClass('sidebar-group-active active');
            $("#mSac").addClass('sidebar-group-active active');
            $("#mSacOSNew").addClass('active');
        }, 100);
        });
    </script>

<script>
    function initAutocomplete() {
        var map = new google.maps.Map(document.getElementById('map'), {
          @if ($address)
          center: {lat: {{ $latitude }}, lng: {{ $longitude }}},
          @else
          center: {lat: -23.005171, lng: -43.348923},
          @endif
          zoom: 16,
          draggable: true,
          mapTypeId: 'roadmap'
        });

		// Drag Event
		var gmarkers = [];
		var markers = [];
		var markern = [];
		var marker_cliente = '/media/pin.png';




        // Create the search box and link it to the UI element.
        var input = document.getElementById('address');
        var searchBox = new google.maps.places.SearchBox(input);

        // Bias the SearchBox results towards current map's viewport.
        map.addListener('bounds_changed', function() {
          searchBox.setBounds(map.getBounds());
        });

		$( "#address" ).blur(function() {
            if ($("#address").val() != "") {

                clearMarkers();



                var geocoder = new google.maps.Geocoder()
                var end = $("#address").val();
                var endereco = end;


                geocoder.geocode( { 'address': endereco}, function(resultado, status) {
                if (status == google.maps.GeocoderStatus.OK) {

                var lat1 = resultado[0].geometry.location.lat();
                var long1 = resultado[0].geometry.location.lng();

                var markern = new google.maps.Marker({
                map: map,
                icon: marker_cliente,
                title: 'Local do serviço',
                animation: google.maps.Animation.DROP,
                zIndex: 2,
                position: {lat: lat1, lng: long1},
                draggable: true
                });
                    markers.push(markern);

                google.maps.event.addListener(markern, 'drag', function() {
                    console.log('posição Lat: ' + markern.position.lat());
                    console.log('posição lng: ' + markern.position.lng());
                    document.getElementById('latitude').value = markern.position.lat();
                    document.getElementById('longitude').value = markern.position.lng();
                });

                google.maps.event.addListener(markern, 'dragend', function() {
                    geocodeR(markern.position.lat(), markern.position.lng());
                });

                console.log( "Endereço: " + endereco + " - Latitude: " + lat1 + " Longitude: " + long1);
                document.getElementById("latitude").value = lat1;
                document.getElementById("longitude").value = long1;
                document.getElementById("city").value = getCityState(resultado[0].address_components, ['administrative_area_level_2']).long_name;
                        document.getElementById("state").value = getCityState(resultado[0].address_components, ['administrative_area_level_1']).short_name;

                } else {
                    var r = confirm("Endereço não foi encontrado, deseja continuar assim mesmo?");
                    if (r == true) {
                        $("#not_address").click();
                    }
                }
            });

            }

        });

        function intersect(a,b){
            return new Set( a.filter( v => ~b.indexOf( v ) ) );
        };

        function getCityState( addcomp, order ){
            if( typeof( addcomp )=='object' && addcomp instanceof Array ){
                var arr = [];
                for(var i=0; i < addcomp.length; i++ ){
                    var obj=addcomp[ i ];
                    var types=obj.types;

                    if( intersect( order, types ).size > 0 ) {
                        return obj;
                    }
                }
            }
            return false;
        };

		// Sets the map on all markers in the array.
		function setMapOnAll(map) {
				for (var i = 0; i < markers.length; i++) {
				  markers[i].setMap(map);
				}
        }

        function geocodeR(latitude, longitude) {
            var pos = {
            lat: latitude,
            lng: longitude
            };

            var geocoder = new google.maps.Geocoder();
            geocoder.geocode({ location: pos }, function(results, status) {
                if (status === "OK") {
                if (results[0]) {
                    $("#address").val(results[0].formatted_address);
                } else {
                    window.alert("Não foi encontrado o endereço.");
                }
                } else {
                window.alert("Geocoder failed due to: " + status);
                }
            });
        }

		// Removes the markers from the map, but keeps them in the array.
		 function clearMarkers() {
				setMapOnAll(null);
			  }


		 // Create a marker for each place.
            var marker = new google.maps.Marker({
              map: map,
              icon: marker_cliente,
              title: 'Local do serviço',
              zIndex: 2,
			  animation: google.maps.Animation.DROP,
              @if ($address)
              position: {lat: {{ $latitude }}, lng: {{ $longitude }}},
            @else
            position: {lat: -23.005171, lng: -43.348923},
            @endif
			  draggable: true,
            });


			google.maps.event.addListener(marker, 'drag', function() {
                console.log('posição Lat: ' + marker.position.lat());
                console.log('posição lng: ' + marker.position.lng());
                document.getElementById('latitude').value = marker.position.lat();
			    document.getElementById('longitude').value = marker.position.lng();
            });

            google.maps.event.addListener(marker, 'dragend', function() {
                geocodeR(marker.position.lat(), marker.position.lng());
            });

        // Listen for the event fired when the user selects a prediction and retrieve
        // more details for that place.
        searchBox.addListener('places_changed', function() {
          var places = searchBox.getPlaces();


          if (places.length == 0) {
            return;
          }



         clearMarkers();

          // For each place, get the icon, name and location.
          var bounds = new google.maps.LatLngBounds();
          places.forEach(function(place) {
            if (!place.geometry) {
              console.log("Returned place contains no geometry");
              return;
            }


			document.getElementById('latitude').value = place.geometry.location.lat();
			document.getElementById('longitude').value = place.geometry.location.lng();

            marker.setPosition(place.geometry.location);
			marker.setVisible(false);


            if (place.geometry.viewport) {
              // Only geocodes have viewport.
              bounds.union(place.geometry.viewport);
            } else {
              bounds.extend(place.geometry.location);
            }
          });
          map.fitBounds(bounds);
        });



      }
</script>

<script src="https://maps.googleapis.com/maps/api/js?v=3.exp&key={{ getConfig("google_key_web") }}&libraries=places&callback=initAutocomplete"></script>

@endsection