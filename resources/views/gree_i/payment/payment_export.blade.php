@extends('gree_i.layout')

@section('content')
<div class="content-overlay"></div>
<div class="content-wrapper">
    <div class="content-header row">
        <div class="content-header-left col-12 mb-2 mt-1">
          <div class="row breadcrumbs-top">
            <div class="col-12">
              <h5 class="content-header-title float-left pr-1 mb-0">Exportar pagamentos</h5>
              <div class="breadcrumb-wrapper col-12">
                {{ __('trip_i.tpe_export_fly_ps') }}
              </div>
            </div>
          </div>
        </div>
      </div>
    <div class="content-header row">
    </div>
    <div class="content-body">
        <form action="/payment/download/export" id="submitFilter" method="get">
        <div class="card">
            <div class="card-content">
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-12">
                            <div class="form-group">
                                <label for="r_code">{{ __('project_i.ep_4') }}</label>
                                <select class="js-select2 form-control" id="r_code" name="r_code" style="width: 100%;" data-placeholder="Pesquise o nome ou matricula..." multiple>
                                <option></option>
                                <?php if (!empty($colab)) { ?>
                                    <?php foreach ($colab as $key) { ?>
                                        <option value="<?= $key->r_code ?>"><?= $key->first_name ." ". $key->last_name ?> (<?= $key->r_code ?>)</option>
                                    <?php } ?>
                                <?php } ?>    
                                </select>
                            </div>
                            <div class="form-group">
                                <label for="request_category">Categoria de despesas</label>
                                <select class="form-control" id="request_category" name="request_category">
                                    <option value=""></option>
                                    <option value="1">Pagamento a fornecedores</option>
                                    <option value="2">Pagamento a funcionários</option>
                                    <option value="3">Pagamento de encargos sociais/Trabalhistas</option>
                                    <option value="4">Pagamento de tributos</option>
                                    <option value="5">Pagamento de juros e multas fiscais</option>
                                    <option value="6">Pagamento de tarifas bancárias</option>
                                    <option value="7">Pagamento de empréstimo/Reembolso</option>
                                    <option value="8">Investimentos</option>
                                    <option value="9">Pagamento de despesas administrativas</option>
                                    <option value="10">Outros pagamentos</option>
                                </select>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="start_date">Data inicial da solicitação</label>
                                <input class="form-control" type="text" name="start_date" id="start_date">
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="end_date">Data final da solicitação</label>
                                <input class="form-control" type="text" name="end_date" id="end_date">
                            </div>
                        </div>
                        <div class="col-md-12">
                            <button type="submit" class="btn btn-square btn-outline-secondary" style="width: 100%;">{{ __('lending_i.lt_27') }}</button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
</form>

    <script>
    $(document).ready(function () {
        $(".js-select2").select2({
            maximumSelectionLength: 1,
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

        setInterval(() => {
            $("#mAdmin").addClass('sidebar-group-active active');
            $("#mFinancyPayment").addClass('sidebar-group-active active');
            $("#mFinancyPaymentExport").addClass('active');
        }, 100);

    });
    </script>
@endsection