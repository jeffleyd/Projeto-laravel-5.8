@extends('gree_i.layout')

@section('content')
<div class="content-overlay"></div>
<div class="content-wrapper">
    <div class="content-header row">
        <div class="content-header-left col-12 mb-2 mt-1">
          <div class="row breadcrumbs-top">
            <div class="col-12">
              <h5 class="content-header-title float-left pr-1 mb-0">Pagamento</h5>
              <div class="breadcrumb-wrapper col-12">
                @if ($id == 0)
                Antecipando aprovação de NF
                @else
                Atualizando antecipação de NF
                @endif
              </div>
            </div>
          </div>
        </div>
      </div>
    <div class="content-header row">
    </div>
    <div class="content-body">
        <!-- users list start -->
        <section class="users-list-wrapper">
            <div class="users-list-table">
                <div class="card">
                    <div class="card-content">
                        <div class="card-body">
                            <form class="push" id="a_update_form" method="POST" action="/financy/payment/supervisor/update" enctype="multipart/form-data">
                            <input type="hidden" id="id" name="id" value="<?= $id ?>">
                            <div class="row">
                                <div class="col-sm-12">
                                    <label for="nf_number">Número da nota fiscal</label>
                                    <fieldset class="form-group">
                                    <input type="text" name="nf_number" id="nf_number" value="{{ $nf_number }}" class="form-control">
                                    </fieldset>
                                </div>
                                <div class="col-sm-12">
                                    <label for="nf_attach">Anexo NF</label>
                                    <fieldset class="form-group">
                                        <input type="file" name="nf_attach" id="nf_attach">
                                    </fieldset>
                                    @if ($nf_attach)
                                    <p>
                                        <a href="{{ $nf_attach }}" target="_blank">Ver anexo</a>
                                    </p>
                                    @endif
                                </div>
                                <div class="col-sm-12">
                                    <label for="status">Sua análise</label>
                                    <fieldset class="form-group">
                                        <select name="status" class="form-control" id="status">
                                            <option value="1" @if ($status == 1) selected @endif>Aprovado</option>
                                            <option value="2" @if ($status == 2) selected @endif>Reprovado</option>
                                        </select>
                                    </fieldset>
                                </div>
                                <div class="col-sm-12">
                                    <label for="description">Informação adicional</label>
                                    <fieldset class="form-group">
                                        <textarea name="description" id="description" class="form-control" rows="3">{{ $description }}</textarea>
                                    </fieldset>
                                </div>
                            </div>
                            <button type="submit" class="btn btn-dark">
                                <i class="bx bx-check d-block d-sm-none"></i>
                                @if ($id == 0)
                                <span class="d-none d-sm-block">Criar antecipação de análise</span>
                                @else
                                <span class="d-none d-sm-block">Atualizar antecipação de análise</span>
                                @endif
                            </button>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </section>
        <!-- users list ends -->
    </div>
</div>
<script>
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

        $("#a_update_form").submit(function (e) { 
            block();
            
        });

        setInterval(() => {
            $("#mAdmin").addClass('sidebar-group-active active');
            $("#mFinancyPayment").addClass('sidebar-group-active active');
            $("#mFinancyPaymentSupervisor").addClass('active');
        }, 100);

    });
    </script>
@endsection