@extends('gree_i.layout')

@section('content')
<div class="content-overlay"></div>
<div class="content-wrapper">
    <div class="content-header row">
        <div class="content-header-left col-12 mb-2 mt-1">
          <div class="row breadcrumbs-top">
            <div class="col-12">
              <h5 class="content-header-title float-left pr-1 mb-0">Assistência técnica</h5>
              <div class="breadcrumb-wrapper col-12">
                Solicitação de peças:
              </div>
            </div>
          </div>
        </div>
      </div>
    <div class="content-header row">
    </div>
    <div class="content-body">
        <form action="/sac/warranty/parts/ob_do" id="submitForm" enctype="multipart/form-data" method="post">
            <input type="hidden" name="id" value="<?= $id ?>">
            <section id="form-repeater-wrapper">
                <div class="row">
                    <div class="col-12">
                        <div class="card">
                            <div class="card-header">
                                <h4 class="card-title">Lista de peças</h4>
                            </div>
                            <div class="card-content">
                                <div class="card-body">
                                    <div class="repeater-default ">
                                        <div data-repeater-list="group">
                                            @if (count($parts) > 0)
                                            @foreach ($parts as $item)
                                            <div data-repeater-item>
                                                <input type="hidden" name="item_id" value="{{ $item->sac_buy_parts_id }}">
                                                <input type="hidden" name="not_part" value="{{$item->not_part}}">
                                                <input type="hidden" name="model_item" data-name="{{ $item->model }}" data-value="{{ $item->id }}">
                                                
                                                <?php $p = \App\Model\Parts::find($item->part); ?>
                                                @if ($p)
                                                    <input type="hidden" name="part_item" data-name="{{ $p->description }} ({{ $p->code }})" data-value="{{ $p->id }}">
                                                @endif
                                                <div class="row justify-content-between">
                                                    <div class="col-md-4 col-sm-12 form-group">
                                                        <label for="title">Modelo do equipamento</label>

                                                        <div name="emodels2" @if ($item->not_part == 1) style="display: none;" @endif>
                                                            <select class="js-select21 form-control" data-value style="width: 100%" name="models2">
                                                            </select>
                                                        </div>
                                                        <input @if ($item->not_part == 0) style="display: none;" value="" @else value="{{ $item->sac_buy_parts_model }}" @endif type="text" name="model" class="form-control">

                                                        <small name="emodels2t" style="display: none;">Sem resultado. (Digite um texto e apague pra tentar novamente)</small>
                                                    </div>
                                                    <div class="col-md-4 col-sm-12 form-group">
                                                        <label for="part">Código/Nome da peça</label>
                                                        <div name="eparts2" @if ($item->not_part == 1) style="display: none;" @endif>
                                                            <select class="js-select22 form-control" style="width: 100%" name="parts2">
                                                            </select>
                                                        </div>
                                                        <input @if ($item->not_part == 0) style="display: none;" value="" @else value="{{ $item->part }}" @endif type="text" name="part" class="form-control">
                                                    </div>

                                                    <div class="col-md-2 col-sm-12 form-group">
                                                        <label for="quantity">Quantidade</label>
                                                        <input type="number" class="form-control" name="quantity" value="{{ $item->quantity }}">
                                                    </div>
                                                    <div class="col-md-2 col-sm-12 form-group d-flex align-items-center pt-2">
                                                        <button class="btn btn-danger text-nowrap px-1" data-repeater-delete type="button"> <i class="bx bx-x"></i>
                                                            Deletar
                                                        </button>
                                                    </div>
                                                    <div class="col-md-12 col-sm-12 form-group">
                                                        <label for="description">Descrição da peça</label>
                                                        <input type="text" name="description" class="form-control" value="{{ $item->description }}">
                                                    </div>
                                                    <div class="col-md-12 col-sm-12 form-group">
                                                        <label for="picture">Imagem para identificação <small>Max. 5mb</small></label>
                                                        <br><input type="file" name="picture" accept="image/png, image/jpeg, application/pdf">
                                                        @if($item->image)
                                                        <br><a href="{{ $item->image }}" id="imagem" target="_blank">Imagem</a>
                                                        @endif
                                                    </div>    
                                                </div>
                                                <hr>
                                            </div>
                                            @endforeach
                                            @else
                                            <div data-repeater-item>
                                                <input type="hidden" name="item_id" value="0">
                                                <input type="hidden" name="not_part" value="">
                                                <input type="hidden" name="model_item" data-name="" data-value="">
                                                <input type="hidden" name="part_item" data-name="" data-value="">
                                                <div class="row justify-content-between">
                                                    <div class="col-md-4 col-sm-12 form-group">
                                                        <label for="title">Modelo do equipamento</label>
                                                        <div name="emodels2">
                                                            <select class="js-select21 form-control" style="width: 100%" name="models2">
                                                            </select>
                                                        </div>
                                                        <input style="display:none;" value="" type="text" name="model" class="form-control">
                                                        <small name="emodels2t" style="display: none;">Sem resultado. (Digite um texto e apague pra tentar novamente)</small>
                                                    </div>
                                                    <div class="col-md-4 col-sm-12 form-group">
                                                        <label for="part">Código/Nome da peça</label>
                                                        <div name="eparts2">
                                                            <select class="js-select22 form-control" style="width: 100%" name="parts2">
                                                            </select>
                                                        </div>
                                                        <input style="display: none;" value="" type="text" name="part" class="form-control">
                                                    </div>

                                                    <div class="col-md-2 col-sm-12 form-group">
                                                        <label for="quantity">Quantidade</label>
                                                        <input type="number" class="form-control" name="quantity" value="">
                                                    </div>
                                                    <div class="col-md-2 col-sm-12 form-group d-flex align-items-center pt-2">
                                                        <button class="btn btn-danger text-nowrap px-1" data-repeater-delete type="button"> <i class="bx bx-x"></i>
                                                            Deletar
                                                        </button>
                                                    </div>
                                                    <div class="col-md-12 col-sm-12 form-group">
                                                        <label for="description">Descrição da peça</label>
                                                        <input type="text" name="description" class="form-control" value="">
                                                    </div>
                                                    <div class="col-md-12 col-sm-12 form-group">
                                                        <label for="picture">Imagem para identificação <small>Max. 5mb</small></label>
                                                        <br><input type="file" name="picture" accept="image/png, image/jpeg, application/pdf">
                                                    </div>    
                                                </div>
                                                <hr>
                                            </div>
                                            @endif
                                        </div>
                                        <div class="form-group">
                                            <div class="col p-0">
                                                <button class="btn btn-primary" id="newPart" data-repeater-create type="button"><i class="bx bx-plus"></i>
                                                    Nova peça
                                                </button>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="col-md-12 col-sm-12 form-group">
                                            <label for="optional">Observação adicional</label>
                                            <textarea name="optional" rows="5" id="optional" class="form-control">{{ $optional }}</textarea>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </section>
            <button type="submit" class="btn btn-primary glow mb-1 mb-sm-0 mr-0 mr-sm-1" style="width: 100%;">Atualizar lista de peças</button>
        </form>
    </div>
</div>

<script>
    var i = @if (count($parts) > 0) {{count($parts) - 1}} @else 0 @endif;
    $(document).ready(function () {

        setInterval(() => {
            $("#mAfterSales").addClass('sidebar-group-active active');
            $("#mTAssist").addClass('sidebar-group-active active');
            $("#mTAssistOB").addClass('active');
        }, 100);

        // form repeater jquery
        $('.file-repeater, .contact-repeater, .repeater-default').repeater({
            show: function () {
                i++;
                $('input[name="group['+ i +'][model]"]').hide();
                $('small[name="group['+ i +'][emodels2t]"]').hide();
                $('input[name="group['+ i +'][part]"]').hide();
                $('div[name="group['+ i +'][emodels2]"]').show();
                $('div[name="group['+ i +'][eparts2]"]').show();
                $('select[name="group['+ i +'][models2]"]').select2({
                    ajax: {
                        url: '/suporte/products',
                        data: function (params) {
                        var query = {
                            search: params.term,
                            page: params.page || 1
                        }

                        // Query parameters will be ?search=[term]&page=[page]
                        return query;
                        }
                    },
                    language: {
                        noResults: function () {
                            // Hide Models
                            $('input[name="group['+ i +'][model]"]').show();
                            $('input[name="group['+ i +'][model]"]').focus();
                            $('div[name="group['+ i +'][emodels2]"]').hide();

                            // Hide Parts
                            $('input[name="group['+ i +'][part]"]').show();
                            $('div[name="group['+ i +'][eparts2]"]').hide();
                            return;
                        }
                    }
                });

                $('select[name="group['+ i +'][models2]"]').on('select2:select', function (e) {
                    var data = e.params.data;
                    $('select[name="group['+ i +'][parts2]"]').val(0).trigger('change');
                    $('select[name="group['+ i +'][parts2]"]').select2({
                        ajax: {
                            url: '/suporte/parts?p=' + data.id,
                            data: function (params) {
                            var query = {
                                search: params.term,
                                page: params.page || 1
                            }

                            // Query parameters will be ?search=[term]&page=[page]
                            return query;
                            }
                        },
                        language: {
                            noResults: function () {

                                return 'Peça não foi encontrada!';
                            }
                        }
                    });
                });

                $('input[name="group['+ i +'][model]"]').on('keyup', function () {
                    if ($('input[name="group['+ i +'][model]"]').val() == "") {
                        $('input[name="group['+ i +'][model]"]').hide();
                        $('small[name="group['+ i +'][emodels2t]"]').hide();
                        $('input[name="group['+ i +'][part]"]').hide();
                        $('select[name="group['+ i +'][models2]"]').find(".select2-search__field").focus();
                        $('select[name="group['+ i +'][models2]"]').val(0).trigger("change");
                        $('select[name="group['+ i +'][parts2]"]').val(0).trigger("change");
                        $('div[name="group['+ i +'][emodels2]"]').show();
                        $('div[name="group['+ i +'][eparts2]"]').show();
                    }
                });

                $('input[name="group['+ i +'][part]"]').on('keyup', function () {
                    if ($('input[name="group['+ i +'][part]"]').val() == "") {
                        $('input[name="group['+ i +'][part]"]').hide();
                        $('small[name="group['+ i +'][emodels2t]"]').hide();
                        $('input[name="group['+ i +'][model]"]').hide();
                        $('select[name="group['+ i +'][parts2]"]').find(".select2-search__field").focus();
                        $('select[name="group['+ i +'][parts2]"]').val(0).trigger("change");
                        $('div[name="group['+ i +'][eparts2]"]').show();
                        $('div[name="group['+ i +'][emodels2]"]').show();
                    }
                });
                $(this).slideDown();
            },
            hide: function (deleteElement) {
                i--;
                item_id = $(this)[0].children[0].defaultValue;
                if (item_id == "0" || item_id == "") {
                    $(this).slideUp(deleteElement); 
                } else {
                    Swal.fire({
                    title: 'Remover peça',
                    text: "Tem certeza que deseja remover essa peça do protocolo!?",
                    type: 'warning',
                    showCancelButton: true,
                    confirmButtonColor: '#3085d6',
                    cancelButtonColor: '#d33',
                    confirmButtonText: 'Confirmar!',
                    cancelButtonText: 'Cancelar',
                    confirmButtonClass: 'btn btn-primary',
                    cancelButtonClass: 'btn btn-danger ml-1',
                    buttonsStyling: false,
                    }).then(function (result) {
                    if (result.value) {

                        $.ajax({
                            type: "POST",
                            url: "/warrany/parts/ob/delete/" + item_id,
                            success: function (response) {
                                if (response.success) {
                                    $(this).slideUp(deleteElement);

                                    Swal.fire({
                                        type: "success",
                                        title: 'Removido',
                                        text: 'Peça removida da lista.',
                                        confirmButtonClass: 'btn btn-success',
                                    })
                                } else {
                                    error('Peça não foi encontrada!');
                                }
                            }
                        });
                    }
                    });
                }
            }
        });

        $('input').on('keyup', function () {

            var obj = $(this)[0].name;
            if (obj.indexOf('model') > -1)
            {
                if ($('input[name="group['+ i +'][model]"]').val() == "") {

                    $('input[name="group['+ i +'][model]"]').hide();
                    $('small[name="group['+ i +'][emodels2t]"]').hide();
                    $('input[name="group['+ i +'][part]"]').hide();
                    $('select[name="group['+ i +'][models2]"]').find(".select2-search__field").focus();
                    $('select[name="group['+ i +'][models2]"]').val(0).trigger("change");
                    $('div[name="group['+ i +'][emodels2]"]').show();
                    $('div[name="group['+ i +'][eparts2]"]').show();
                }
            } else if (obj.indexOf('part') > -1) {

                if ($('input[name="group['+ i +'][part]"]').val() == "") {
                    $('input[name="group['+ i +'][part]"]').hide();
                    $('small[name="group['+ i +'][emodels2t]"]').hide();
                    $('input[name="group['+ i +'][model]"]').hide();
                    $('select[name="group['+ i +'][parts2]"]').find(".select2-search__field").focus();
                    $('select[name="group['+ i +'][parts2]"]').val(0).trigger("change");
                    $('div[name="group['+ i +'][eparts2]"]').show();
                    $('div[name="group['+ i +'][emodels2]"]').show();
                }
            }

        });


        $('select').on('select2:select', function (e) {

            var obj2 = $(this).attr("name");
            var index = obj2.replace(/[^0-9]/g, '')[0];
            
            var data = e.params.data;

            if (obj2.indexOf('models2') > -1) {
            
                $('select[name="group['+ index +'][parts2]"]').val(0).trigger('change');

                $('select[name="group['+ index +'][parts2]"]').select2({
                    ajax: {
                        url: '/suporte/parts?p=' + data.id,
                        data: function (params) {
                        var query = {
                            search: params.term,
                            page: params.page || 1
                        }

                        // Query parameters will be ?search=[term]&page=[page]
                        return query;
                        }
                    },
                    language: {
                        noResults: function () {

                            return 'Peça não foi encontrada!';
                        }
                    }
                });
            } 
        });
        
        var cont = {{count($parts)}};
        if(cont == 0) {
            $('select[name="group[0][models2]"]').select2({
                    ajax: {
                        url: '/suporte/products',
                        data: function (params) {
                        var query = {
                            search: params.term,
                            page: params.page || 1
                        }

                        // Query parameters will be ?search=[term]&page=[page]
                        return query;
                        }
                    },
                    language: {
                        noResults: function () {
                            // Hide models
                            $('input[name="group[0][model]"]').show();
                            $('small[name="group[0][emodels2t]"]').show();
                            $('input[name="group[0][model]"]').focus();
                            $('div[name="group[0][emodels2]"]').hide();

                            // Hide parts
                            $('input[name="group[0][part]"]').show();
                            $('div[name="group[0][eparts2]"]').hide();
                            return;
                        }
                    }
                });
        }
        for (let index = 0; index < {{count($parts)}}; index++) {

            if ($('input[name="group['+ index +'][not_part]"]').val() == 0) {
                
                $('select[name="group['+ index +'][models2]"]').html('<option value="'+ $('input[name="group['+ index +'][model_item]"]').attr('data-value') +'">'+ $('input[name="group['+ index +'][model_item]"]').attr('data-name') +'</option>');
                $('select[name="group['+ index +'][models2]"]').select2({
                    ajax: {
                        url: '/suporte/products',
                        data: function (params) {
                        var query = {
                            search: params.term,
                            page: params.page || 1
                        }

                        // Query parameters will be ?search=[term]&page=[page]
                        return query;
                        }
                    },
                    language: {
                        noResults: function () {
                            // Hide models
                            $('input[name="group['+ index +'][model]"]').show();
                            $('small[name="group['+ index +'][emodels2t]"]').show();
                            $('input[name="group['+ index +'][model]"]').focus();
                            $('div[name="group['+ index +'][emodels2]"]').hide();

                            // Hide parts
                            $('input[name="group['+ index +'][part]"]').show();
                            $('div[name="group['+ index +'][eparts2]"]').hide();
                            return;
                        }
                    }
                });

                
                $('select[name="group['+ index +'][parts2]"]').html('<option value="'+ $('input[name="group['+ index +'][part_item]"]').attr('data-value') +'">'+ $('input[name="group['+ index +'][part_item]"]').attr('data-name') +'</option>');
                $('select[name="group['+ index +'][parts2]"]').select2({
                        ajax: {
                            url: '/suporte/parts?p=' + $('input[name="group['+ index +'][model_item]"]').attr('data-value'),
                            data: function (params) {
                            var query = {
                                search: params.term,
                                page: params.page || 1
                            }
                            // Query parameters will be ?search=[term]&page=[page]
                            return query;
                            }
                        },
                        language: {
                            noResults: function () {
                                $('input[name="group['+ index +'][model]"]').show();
                                $('input[name="group['+ index +'][model]"]').focus();
                                $('div[name="group['+ index +'][emodels2]"]').hide();

                                $('input[name="group['+ index +'][part]"]').show();
                                $('div[name="group['+ index +'][eparts2]"]').hide();
                                return;
                            }
                        }
                });
            }
        }
        
        $("#submitForm").submit(function (e) {
            Codebase.loader('show', 'bg-gd-sea');                
        });

        $("#navBuyPart").addClass('active');
    });
</script>

@endsection