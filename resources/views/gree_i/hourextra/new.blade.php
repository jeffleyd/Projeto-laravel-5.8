@extends('gree_i.layout')

@section('content')
<link rel="stylesheet" type="text/css" href="/admin/app-assets/vendors/css/pickers/pickadate/pickadate.css">
<div class="content-overlay"></div>
<div class="content-wrapper">
    <div class="content-header row">
        <div class="content-header-left col-12 mb-2 mt-1">
        <div class="row breadcrumbs-top">
            <div class="col-12">
            <h5 class="content-header-title float-left pr-1 mb-0">RH - Solicitação de hora extra</h5>
            <div class="breadcrumb-wrapper col-12">
                Nova solicitação
            </div>
            </div>
        </div>
        </div>
    </div>
    <div class="content-body">
        <div class="card">
            <div class="card-content">
                <div class="card-body">
                    <form action="/hour-extra/new_do" class="needs-validation" id="sendForm" method="post" enctype="multipart/form-data" novalidate>
                        <div class="row">
                            <div class="col-md-6">
                                <fieldset class="form-group">
                                    <label for="start_date">Data de trabalho</label>
                                    <input type="text" class="form-control date-mask datepicker js-flatpickr js-flatpickr-enabled flatpickr-input" name="start_date" id="start_date" placeholder="0000-00-00" required>
                                </fieldset>
                            </div>
                            <div class="col-md-3">
                                <fieldset class="form-group">
                                    <label for="start_hour">Hora de entrada</label>
                                    <input type="text" class="form-control hour-mask" placeholder="00:00" name="start_hour" id="start_hour" required>
                                </fieldset>
                            </div>
                            <div class="col-md-3">
                                <fieldset class="form-group">
                                    <label for="end_hour">Hora de saida</label>
                                    <input type="text" class="form-control hour-mask" placeholder="00:00" name="end_hour" id="end_hour" required>
                                </fieldset>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-12">
                                <div class="form-group">
                                    <label for="description">Motivo da hora extra</label>
                                    <textarea name="description" id="description" class="form-control" rows="10" required></textarea>
                                    <div class="form-text text-muted">Informe a descrição completa.</div>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-12">
                                <div class="form-group">
                                    <label for="file">Arquivo adicional</label>
                                    <input type="file" class="form-control" id="file" name="file">
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-12">
                                <button type="submit" class="btn btn-primary" style="width:100%;">Criar Solicitação</button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
<script>
    $(document).ready(function () {

        $("#sendForm").submit(function (e) {
            var form = $(".needs-validation");
            if (form[0].checkValidity() === false) {
                e.preventDefault();
                e.stopPropagation();
                form.addClass('was-validated');

            } else {
                block();
            }

        });

        $('.date-mask').pickadate({
            //editable: true,
            formatSubmit: 'yyyy-mm-dd',
            format: 'yyyy-mm-dd',
            today: 'Hoje',
            clear: 'Limpar',
            close: 'Fechar',
            monthsFull: ['Janeiro', 'Fevereiro', 'Março', 'Abril', 'Maio', 'Junho', 'Julho', 'Agosto', 'Setembro', 'Outubro', 'Novembro', 'Dezembro'],
            monthsShort: ['Jan', 'Fev', 'Mar', 'Abr', 'Mai', 'Jun', 'Jul', 'Ago', 'Set', 'Out', 'Nov', 'Dez'],
            weekdaysFull: ['Domingo', 'Segunda-Feira', 'Terça-Feira', 'Quarta-Feira', 'Quinta-Feira', 'Sexta-Feira', 'Sábado'],
            weekdaysShort: ['Dom', 'Seg', 'Ter', 'Qua', 'Qui', 'Sex', 'Sab'],
        });
		$('.date-mask').removeAttr('readonly');

        $('.hour-mask').mask('00:00', {reverse: false});

        setInterval(() => {
            $("#mRH").addClass('sidebar-group-active active');
            $("#mHourExtra").addClass('sidebar-group-active active');
            $("#mHourExtraNew").addClass('active');
        }, 100);
    });
</script>
@endsection
