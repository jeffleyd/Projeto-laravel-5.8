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
                Peça(s) para o O.S: {{ $os->code }} 
              </div>
            </div>
          </div>
        </div>
      </div>
    <div class="content-header row">
    </div>
    @if ($os->observation)
    <div class="alert alert-info alert-dismissible mb-2" role="alert">
        <div class="d-flex align-items-center">
            <i class="bx bx-error"></i>
            <span>
                <?= $os->observation ?>
            </span>
        </div>
    </div>
    @endif
    <div class="content-body">
        <form method="POST" action="/sac/warranty/parts_do" class="form repeater-default">
        <input type="hidden" name="id" value="<?= $id ?>">
		<input type="hidden" name="os_id" value="<?= $os->id ?>">
        <section>
            <div class="row">
                <div class="col-12">
                    <div class="card">
                        <div class="card-header">
                            <h4 class="card-title">
                                Informações adicionais
                            </h4>
                        </div>
                        <div class="card-content">
                            <div class="card-body">
                                <div class="col-md-12 col-sm-12 form-group">
                                    <label for="paid_info">Terá algum acrescimo de pagamento além dos serviços abaixo?</label>
                                    <input type="text" name="paid_info" value="{{ $protocol->paid_info }}" class="form-control">
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </section>
        <section id="form-repeater-wrapper">
            <div class="row">
                <div class="col-12">
                    <div class="card">
                        <div class="card-header">
                            <h4 class="card-title">
                                Lista de peças
                            </h4>
                        </div>
                        <div class="card-content">
                            <div class="card-body">
                                
                                    <div data-repeater-list="group">
                                        @if (count($parts) > 0)
                                        @foreach ($parts as $item)
                                        <div data-repeater-item>
                                            <input type="hidden" name="item_id" value="{{ $item->id }}">
											<input type="hidden" name="serial_number" value="{{ $item->serial_number }}">
                                            <div class="row justify-content-between">
                                                <div class="col-md-2 col-sm-12 form-group">
                                                    <label for="title">Modelo</label>
                                                    <select name="model" onchange="loadPart(this);" class="form-control">
                                                        <option value=""></option>
                                                        @foreach ($models as $key)
                                                        <option value="{{ $key->id }}" data-serial="{{ $key->serial_number }}" 
																@if ($item->serial_number)
																@if ($item->product_id == $key->id and $item->serial_number == $key->serial_number) selected @endif @else @if ($item->product_id == $key->id) selected @endif  @endif
															>{{ $key->model }} ({{ $key->serial_number }})</option>
                                                        @endforeach
                                                    </select>
                                                </div>
                                                <div class="col-md-3 col-sm-12 form-group">
                                                    <label for="part">Peça</label>
                                                    <select name="part" class="form-control">
                                                        <option value=""></option>
                                                        <?php $p = \App\Model\Parts::find($item->part_id); ?>
                                                        @if ($p)
                                                        <option value="{{ $p->id }}" selected>{{ $p->description }} ({{ $p->code }})</option>
                                                        @endif
                                                    </select>
                                                </div>
                                                <div class="col-md-1 col-sm-12 form-group">
                                                    <label for="quantity">Quantidade</label>
                                                    <input type="number" class="form-control" onkeyup="validMax(this)" name="quantity" value="{{ $item->quantity }}" value="1">
                                                </div>
                                                <div class="col-md-2 col-sm-12 form-group">
                                                    <label for="price">Valor da mão de obra</label>
                                                    <input type="text" class="form-control" value="{{ number_format($item->total,2) }}" id="price" placeholder="0.00" name="price">
                                                </div>
                                                <div class="col-md-2 col-sm-12 form-group">
                                                    <label for="status">Status</label>
                                                    <select name="status" class="form-control">
                                                        <option @if ($item->is_approv == 0 and $item->is_repprov == 0) selected @endif>Sem análise</option>
                                                        <option value="1" @if ($item->is_approv == 1) selected @endif>Aprovado</option>
                                                        <option value="2" @if ($item->is_repprov == 1) selected @endif>Reprovado</option>
                                                    </select>
                                                </div>
                                                <div class="col-md-2 col-sm-12 form-group d-flex align-items-center pt-2">
                                                    <button class="btn btn-danger text-nowrap px-1" data-repeater-delete type="button"> <i class="bx bx-x"></i>
                                                        Deletar
                                                    </button>
                                                </div>

                                                <div class="col-md-12 col-sm-12 form-group">
                                                    <label for="description">Motivo da credenciada</label>
                                                    <input type="text" class="form-control" id="description" value="{{ $item->description }}" name="description">
                                                </div>
												<div class="col-md-12 col-sm-12 form-group">
                                                    <label for="description_reason">Motivo da Gree</label>
                                                    <input type="text" class="form-control" id="description_reason" value="{{ $item->description_reason }}" name="description_reason">
                                                </div>
												<div class="col-md-12 col-sm-12 form-group">
                                                    <label for="description_defect">Defeito da peça</label>
                                                    <input type="text" class="form-control" id="description_defect" value="{{ $item->description_defect }}" name="description_defect">
                                                </div>

                                                @if ($item->attach)
                                                <div class="col-md-12 col-sm-12 form-group">
                                                    <a  href="{{ $item->attach }}" target="_blank" rel="noopener noreferrer"><button class="btn btn-info btn-sm" type="button">Ver imagem de comprovação</button></a>
                                                </div>
                                                @endif
                                            </div>
                                            <hr>
                                        </div>
                                        @endforeach
                                        @else
                                        <div data-repeater-item>
                                            <input type="hidden" name="item_id" value="0">
											<input type="hidden" name="serial_number" value="">
                                            <div class="row justify-content-between">
                                                <div class="col-md-2 col-sm-12 form-group">
                                                    <label for="title">Modelo</label>
                                                    <select name="model" onchange="loadPart(this);" class="form-control">
                                                        <option value=""></option>
                                                        @foreach ($models as $key)
                                                        <option value="{{ $key->id }}" data-serial="{{ $key->serial_number }}">{{ $key->model }} ({{ $key->serial_number }})</option>
                                                        @endforeach
                                                    </select>
                                                </div>
                                                <div class="col-md-3 col-sm-12 form-group">
                                                    <label for="part">Peça</label>
                                                    <select name="part" class="form-control">
                                                        <option value=""></option>
                                                    </select>
                                                </div>
                                                <div class="col-md-1 col-sm-12 form-group">
                                                    <label for="quantity">Quantidade</label>
                                                    <input type="number" class="form-control" onkeyup="validMax(this)" name="quantity" value="1">
                                                </div>
                                                <div class="col-md-2 col-sm-12 form-group">
                                                    <label for="price">Valor da mão de obra</label>
                                                    <input type="text" class="form-control" placeholder="0.00" id="price" name="price">
                                                </div>
                                                <div class="col-md-2 col-sm-12 form-group">
                                                    <label for="status">Status</label>
                                                    <select name="status" class="form-control">
                                                        <option>Sem análise</option>
                                                        <option value="1">Aprovado</option>
                                                        <option value="2">Reprovado</option>
                                                    </select>
                                                </div>
                                                <div class="col-md-2 col-sm-12 form-group d-flex align-items-center pt-2">
                                                    <button class="btn btn-danger text-nowrap px-1" data-repeater-delete type="button"> <i class="bx bx-x"></i>
                                                        Deletar
                                                    </button>
                                                </div>

                                                <div class="col-md-12 col-sm-12 form-group">
                                                    <label for="description">Motivo da credenciada</label>
                                                    <input type="text" class="form-control" id="description" name="description">
                                                </div>
												<div class="col-md-12 col-sm-12 form-group">
                                                    <label for="description_reason">Motivo da Gree</label>
                                                    <input type="text" class="form-control" id="description_reason" name="description_reason">
                                                </div>
												<div class="col-md-12 col-sm-12 form-group">
                                                    <label for="description_defect">Defeito da peça</label>
                                                    <input type="text" class="form-control" id="description_defect" name="description_defect">
                                                </div>
                                            </div>
                                            <hr>
                                        </div>
                                        @endif
                                    </div>
                                    <div class="form-group">
                                        <div class="col p-0">
                                            <button class="btn btn-primary" id="addNew" data-repeater-create type="button"><i class="bx bx-plus"></i>
                                                Nova peça
                                            </button>
                                            @if ($os)
                                            <a href="@if ($os->diagnostic_test_part) {{ $os->diagnostic_test_part }} @else # @endif" target="_blank">
                                                <button class="btn btn-success" type="button">
                                                    Relatório técnico
                                                </button>
                                            </a>
                                            @endif
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
    var item_id;
    function loadPart(obj) {
        var item = $(obj).attr('name');
        var value = $(obj).val();
		var serial_number_selected = $(obj).find(':selected').attr('data-serial');
		var serial_number_field = item.replace("[model]", "[serial_number]");
		$('input[name="'+ serial_number_field +'"]').val(serial_number_selected);
        var res = item.replace("[model]", "[part]");

        if (value != "") {
            $('select[name="'+ res +'"]').load("/misc/part/list/" + value, function (response, status, request) {
                if ( status == "error" ) {
                    console.log('Error');
                    
                    return alert('Ocorreu um erro na conexão, tente novamente!');
                }
            });
        }
    }
	function validMax($this) {
		if ($($this).val() <= 0) { 
			$($this).val(0);
		}	
	}
    $(document).ready(function () {
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

        $("#addNew").click(function (e) { 
            setInterval(() => {
                $('input[name*="price"]').mask('000.00', {reverse: true});
            }, 300);
        });

        // form repeater jquery
        $('.file-repeater, .contact-repeater, .repeater-default').repeater({
            show: function () {
            $(this).slideDown();
            },
            hide: function (deleteElement) {
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
                            url: "/warrany/part/delete/" + item_id,
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

        $('input[name*="price"]').mask('000.00', {reverse: true});

        setInterval(() => {
            $("#mAfterSales").addClass('sidebar-group-active active');
            $("#mTAssist").addClass('sidebar-group-active active');
            $("#mTAssistOsAll").addClass('active');
        }, 100);

    });
    </script>
@endsection