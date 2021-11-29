@extends('gree_i.layout')

@section('content')
	<link rel="stylesheet" type="text/css" href="/admin/app-assets/css/plugins/introjs/introjs.min.css">
    <link rel="stylesheet" type="text/css" href="/admin/app-assets/css/plugins/introjs/introjs.min.css.map">
	<script src="/admin/app-assets/js/introjs/intro.min.js"></script>
    <script src="/admin/app-assets/js/pdf.js"></script>
    <script src="/admin/app-assets/js/pdf.worker.js"></script>
    <style>
        .page-control {
            position: absolute;
            top: 0;
            right: 0;
            left: 0;
            z-index: 99;
            margin: auto;
            padding: 8px;
            width: 175px;
            background: #719df0;
            border-radius: 35px;
            box-shadow: -8px 12px 18px 0 rgba(25, 42, 70, 0.13);
            transition: all .3s ease-in-out;
            color: white;
            font-size: 11px;
        }
    </style>
    <div class="content-overlay"></div>
    <div class="content-wrapper">
        <div class="content-header row">
            <div class="content-header-left col-12 mb-2 mt-1">
                <div class="row breadcrumbs-top">
                    <div class="col-12">
                        <h5 class="content-header-title float-left pr-1 mb-0">Administração</h5>
                        <div class="breadcrumb-wrapper col-12">
                            @if ($id == 0)
                                Nova solicitação
                            @else
                                Solicitação: <b>{{$AdmRequests->code}}</b>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="content-header row">
        </div>
        <form action="" method="post" id="sendForm" enctype="multipart/form-data">
            <input type="hidden" id="approvers" name="approvers" value="[]">
            <input type="hidden" id="observers" name="observers" value="[]">
            <input type="hidden" id="analyze" name="analyze" value="0">
			<input type="hidden" id="url" name="url" value="">
            <input type="hidden" id="obs" name="obs" value="0">
            <input type="hidden" id="id" name="id" value="{{Session::get('admRequestID')}}">
            <div class="content-body">
                <div class="row">
                    @if ($id == 0)
                        <div class="col-sm-12">
                            <div class="alert alert-warning alert-dismissible mb-2" role="alert">
                                <div class="d-flex align-items-center">
                                    <i class="bx bx-error"></i>
                                    <span>
                            Você pode selecionar varios arquivos segurando control no teclado, mas lembre-se, só pode enviar <b>20mb</b> de arquivo no máximo.
										<br>Extensões aceitas para envio: <b>pptx</b>, <b>ppt</b>, <b>pdf</b>, <b>docx</b>, <b>doc</b>, <b>xlsx</b>, <b>xls</b>, <b>png</b>, <b>jpg</b>, <b>jpeg</b>
                        </span>
                                </div>
                            </div>
                        </div>
                    @endif
                    @if ($id != 0)
                        @if ($AdmRequests->is_cancelled == 1)
                            <div class="col-sm-12">
                                <div class="alert alert-danger alert-dismissible mb-2" role="alert">
                                    <div class="d-flex align-items-center">
                                        <i class="bx bx-error"></i>
                                        <span>
                        Essa solicitação foi cancelada e não poderá ser reaberta, precisará criar uma nova.
                        </span>
                                    </div>
                                </div>
                            </div>
                        @endif
                    @endif
                    <div class="col-sm-4">
                        @if ($id != 0)
                            <section>
                                <div class="card">
                                    <div class="card-content">
                                        <div class="card-body">
                                            <h4 style="margin-left: 14px;">Solicitante</h4>
                                            <div class="col-12 col-sm-12 col-lg-12">
                                                <div class="row m-0">
                                                    <div class="col-12" style="padding: 15px 11px;border: solid 1px #5A8DEE;margin: 10px 0px;">
                                                        <div class="media align-items-center">
                                                            <a class="media-left mr-50" href="#">
                                                                <img style="object-fit: cover;" src="@if ($AdmRequests->Users->picture) {{$AdmRequests->Users->picture}} @else /media/avatars/avatar10.jpg @endif" alt="avatar" height="40" width="40">
                                                            </a>
                                                            <div class="media-body">
                                                                <h6 class="media-heading mb-0">{{$AdmRequests->Users->first_name}} {{$AdmRequests->Users->last_name}}</h6>
                                                                <span class="font-small-2">Matricula: {{$AdmRequests->r_code}}</span>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </section>
                        @endif
                        @if ($id == 0)
                            <section>
                                <div class="card">
                                    <div class="card-content">
                                        <div class="card-body">
                                            <ul class="nav nav-tabs nav-fill" id="myTab" role="tablist">
                                                <li class="nav-item current" data-intro="Nessa opção, você poderá escolher os aprovadores e também informar uma descrição sobre o tipo de solicitação que eles irão analisar." data-step="1" data-position="top" data-title="Aprovadores">
                                                    <a class="nav-link active" id="home-tab-fill" data-toggle="tab" href="#home-fill" role="tab" aria-controls="home-fill" aria-selected="true">
                                                        Aprovadores
                                                    </a>
                                                </li>
                                                <li class="nav-item" data-intro="Caso queira informar pessoas que receberão as atualizações dessa solicitação em cópia, poderá ser informado aqui." data-step="5" data-position="top" data-title="Em cópia">
                                                    <a class="nav-link" id="profile-tab-fill" data-toggle="tab" href="#profile-fill" role="tab" aria-controls="profile-fill" aria-selected="false">
                                                        Observadores
                                                    </a>
                                                </li>
                                            </ul>
                                            <div class="tab-content pt-1">
                                                <div class="tab-pane active" id="home-fill" role="tabpanel" aria-labelledby="home-tab-fill">
                                                    <div class="row">
                                                        <div class="col-12 col-sm-12 col-lg-12 mb-1" data-intro="Aqui você irá pesquisar a pessoa que vai aprovar sua solicitação. Você poderá adicionar vários." data-step="2" data-position="right" data-title="Quem aprovará?">
                                                            <label for="users-list-verified">Escolha o aprovador</label>
                                                            <fieldset>
                                                                <div class="input-group">
                                                                    <select class="js-select2 form-control" id="r_code" name="r_code" style="width: 78%;" data-placeholder="{{ __('trip_i.td_search_r_code') }}" multiple aria-describedby="button-addon2">
                                                                    </select>
                                                                    <div class="input-group-append" id="button-addon2">
                                                                        <button onclick="addPeoples()" class="btn btn-primary" type="button"><i class="bx bx-plus"></i></button>
                                                                    </div>
                                                                </div>
                                                            </fieldset>
                                                        </div>
                                                        <div class="col-12 col-sm-12 col-lg-12" data-intro="Caso precise explicar do que se trata o documento que precisa aprovar, informe aqui uma breve descrição para os aprovadores." data-step="3" data-position="top" data-title="O que se trata?">
                                                            <label for="users-list-verified">Observação</label>
                                                            <fieldset class="form-group">
                                                                <textarea class="form-control" name="description" rows="3"></textarea>
                                                            </fieldset>
                                                        </div>
                                                    </div>
                                                    <div class="row listApprov">

                                                    </div>
                                                    <div class="row">
                                                        <div class="col-12 col-sm-12 col-lg-12">
                                                            <button type="button" class="btn btn-primary btn-block sendRequest" style="border-radius: 0; display:none">ENVIAR SOLICITAÇÃO</button>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="tab-pane" id="profile-fill" role="tabpanel" aria-labelledby="profile-tab-fill">
                                                    <div class="row">
                                                        <div class="col-12 col-sm-12 col-lg-12">
                                                            <label for="users-list-verified">Escolha observador</label>
                                                            <fieldset>
                                                                <div class="input-group">
                                                                    <select class="js-select22 form-control" id="observer" name="observer" style="width: 78%;" data-placeholder="{{ __('trip_i.td_search_r_code') }}" multiple aria-describedby="button-addon2">
                                                                    </select>
                                                                    <div class="input-group-append" id="button-addon2">
                                                                        <button class="btn btn-primary" onclick="addObserver()" type="button"><i class="bx bx-plus"></i></button>
                                                                    </div>
                                                                </div>
                                                            </fieldset>
                                                        </div>
                                                    </div>
                                                    <div class="row listObservers">

                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </section>
                        @endif

                        @if ($id != 0)
                            <section>
                                <div class="card">
                                    <div class="card-content">
                                        <div class="card-body">
                                            <ul class="nav nav-tabs nav-fill" id="myTab" role="tablist">
                                                <li class="nav-item current">
                                                    <a class="nav-link active" id="home-tab-fill" data-toggle="tab" href="#home-fill" role="tab" aria-controls="home-fill" aria-selected="true">
                                                        Análises
                                                    </a>
                                                </li>
                                                <li class="nav-item">
                                                    <a class="nav-link" id="profile-tab-fill" data-toggle="tab" href="#profile-fill" role="tab" aria-controls="profile-fill" aria-selected="false">
                                                        Observadores
                                                    </a>
                                                </li>
                                            </ul>
                                            <div class="tab-content pt-1">
                                                <div class="tab-pane active" id="home-fill" role="tabpanel" aria-labelledby="home-tab-fill">
                                                    <div class="row">
                                                        <div class="col-12 col-sm-12 col-lg-12">
                                                            <div class="alert alert-secondary alert-dismissible mb-2" role="alert">
                                                                <div class="d-flex align-items-center">
                                                        <span>
                                                        <?= nl2br($AdmRequests->description) ?>
                                                        </span>
                                                                </div>
                                                            </div>
                                                        </div>
                                                        @foreach ($AdmRequests->AdmRequestAnalyze as $index => $item)
                                                            <div class="col-12 col-sm-12 col-lg-12">
                                                                <div class="row m-0">
																	@if ($item->is_approv == 1 or $item->is_reprov == 1)
																	<div style="position:absolute;right: 20px;top: 12px;font-size: 9px;">{{date('d/m/Y H:i', strtotime($item->updated_at))}}</div>
																	@endif
                                                                    <div class="col-12" style="padding: 15px 11px;border: solid 1px #5A8DEE;margin: 10px 0px;">
                                                                        <span class="badge badge-pill @if ($item->is_approv == 1) badge-success @elseif ($item->is_reprov == 1) badge-danger @else badge-primary @endif badge-round badge-glow" style="position: absolute;top: -0.8rem;left: -0.5rem;">{{$index+1}}</span>
                                                                        <div class="media align-items-center">
                                                                            <a class="media-left mr-50" href="#">
                                                                                <img style="object-fit: cover;" src="@if ($item->Users->picture) {{$item->Users->picture}} @else /media/avatars/avatar10.jpg @endif" alt="avatar" height="40" width="40">
                                                                            </a>
                                                                            <div class="media-body">
                                                                                <h6 class="media-heading mb-0">{{$item->Users->first_name.' '.$item->Users->last_name}}</h6>
                                                                                <span class="font-small-2">Matricula: {{$item->Users->r_code}}</span>
                                                                            </div>
                                                                            @if ($item->is_approv == 1)
                                                                                <i class="bx bx-check-circle text-success" style="float: right;"></i>
                                                                            @elseif ($item->is_reprov == 1)
                                                                                <i class="bx bx-x-circle text-danger" style="float: right;"></i>
                                                                            @else
                                                                                <i class="bx bxs-user-voice text-primary" style="float: right;"></i>
                                                                            @endif
                                                                        </div>

                                                                        @if ($item->description)
                                                                            <div style="border: solid 1px;padding: 5px; margin-top: 5px;border-radius: 5px;">
                                                                                {{$item->description}}
                                                                            </div>
                                                                        @endif
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        @endforeach
                                                        @if ($AdmRequests->is_cancelled == 0)
                                                            <div class="col-12 col-sm-12 col-lg-12">
                                                                <div class="row">
                                                                    @if ($isAnalyze)
                                                                        @if ($isAnalyze->is_approv == 0 and $isAnalyze->is_reprov == 0)
                                                                            <div class="col-6 col-sm-6 col-lg-6 pr-0">
                                                                                <button type="button" onclick="analyzeNow(1)" class="btn btn-success btn-block" style="border-radius: 0;">APROVAR</button>
                                                                            </div>
                                                                            <div class="col-6 col-sm-6 col-lg-6 pl-0">
                                                                                <button type="button" onclick="analyzeNow(2)" class="btn btn-danger btn-block" style="border-radius: 0;">REPROVAR</button>
                                                                            </div>
                                                                        @elseif ($AdmRequests->AdmRequestAnalyze->count() == $AdmRequests->AdmRequestAnalyze->where('is_approv', 1)->count() or $AdmRequests->AdmRequestAnalyze->where('is_reprov', 1)->count() > 0)
                                                                            <button type="button" onclick="window.open('{{$AdmRequests->AdmRequestFiles->url}}','_blank');" class="btn btn-primary btn-block" style="border-radius: 0;margin-right: 15px;margin-left: 15px;">IMPRIMIR</button>
                                                                        @endif
                                                                    @elseif ($AdmRequests->AdmRequestAnalyze->count() == $AdmRequests->AdmRequestAnalyze->where('is_approv', 1)->count() or $AdmRequests->AdmRequestAnalyze->where('is_reprov', 1)->count() > 0)
                                                                        <button type="button" onclick="window.open('{{$AdmRequests->AdmRequestFiles->url}}','_blank');" class="btn btn-primary btn-block" style="border-radius: 0;margin-right: 15px;margin-left: 15px;">IMPRIMIR</button>
                                                                    @endif
                                                                </div>
                                                            </div>
                                                        @endif
                                                    </div>
                                                </div>
                                                <div class="tab-pane" id="profile-fill" role="tabpanel" aria-labelledby="profile-tab-fill">
                                                    <div class="row">
                                                        <div class="col-12 col-sm-12 col-lg-12 text-center">
                                                            <b>Link para compartilhar</b>
                                                            <br>
                                                            Ao adicionar a pessoa abaixo, você poderá compartilhar o código para que ela veja essa página.

                                                            <div style="padding: 10px;border: solid 1px #DFE3E7;border-radius: 5px;margin: 20px 0px;">
                                                                <a href="#">{{Request::URL()}}s={{Request::input('s')}}</a>
                                                            </div>
                                                        </div>

                                                        @if ($isAnalyze)
                                                            @if ($AdmRequests->is_cancelled == 0)
                                                                <?php $t_analyze = $AdmRequests->AdmRequestAnalyze->where('is_approv', 1)->count(); ?>
                                                                @if ($AdmRequests->AdmRequestAnalyze->count() != $t_analyze or $AdmRequests->AdmRequestAnalyze->where('is_reprov', 1)->count() == 0)
                                                                    <div class="col-12 col-sm-12 col-lg-12">
                                                                        <label for="users-list-verified">Escolha observador</label>
                                                                        <fieldset>
                                                                            <div class="input-group">
                                                                                <select class="js-select22 form-control" id="observer" name="observer" style="width: 78%;" data-placeholder="{{ __('trip_i.td_search_r_code') }}" multiple aria-describedby="button-addon2">
                                                                                </select>
                                                                                <div class="input-group-append" id="button-addon2">
                                                                                    <button class="btn btn-primary" onclick="addObserver()" type="button"><i class="bx bx-plus"></i></button>
                                                                                </div>
                                                                            </div>
                                                                        </fieldset>
                                                                    </div>
                                                                @endif
                                                            @endif
                                                        @endif
                                                    </div>
                                                    <div class="row listObservers">

                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </section>
                        @endif
                    </div>
                    <div class="col-sm-8">
                        <section id="imgSelect" @if ($id != 0)style="display: none"@endif>
                            <div class="card" data-intro="Aqui você informará os documentos que precisará da aprovação." data-step="4" data-position="left" data-title="Documentos">
                                <div class="card-content">
                                    <div class="card-body text-center">
                                        <div id="drop-area" onclick="setFile()" style="font-size: 25px;font-weight: bold;color: #5A8DEE;min-height: 350px;border: 2px dashed #5A8DEE;background: #F2F4F4; cursor: pointer; padding: 23px;display: flex;justify-content: center;flex-direction: column;">
                                            Clique aqui para enviar o arquivo ou <br>arraste aqui em cima
                                            <br><i style="font-size: 50px;" class="bx bx-download"></i>
                                        </div>
                                        <input type="file" name="file[]" multiple id="file" style="display: none;">
                                    </div>
                                </div>
                            </div>
                        </section>
                        <section id="imgShow" @if ($id == 0)style="display: none"@endif>
                            @if ($id == 0)
                                <button type="button" onclick="removeImg();" class="btn btn-icon rounded-circle btn-danger glow" style="position: absolute;top: -0.8rem;right: -0.5rem;z-index: 9;"><i class="bx bx-x"></i></button>
                            @endif
                            <button type="button" id="pdf-prev" class="btn btn-icon rounded-circle btn-primary glow" style="position: absolute;top: -0.8rem;left: -0.5rem;z-index: 9;"><i class="bx bx-left-arrow-alt"></i></button>
                            <button type="button" id="pdf-next" class="btn btn-icon rounded-circle btn-primary glow" style="position: absolute;top: -0.8rem;left: 2.5rem;z-index: 9;"><i class="bx bx-right-arrow-alt"></i></button>
                            <div class="text-center page-control">
                                Visualização
                                <br>Páginas: <b><span class="page-actual">1</span>/<span class="total-pages">1</span></b>
                            </div>
                            <div class="card">
                                <div class="card-content">
                                    <div class="card-body text-center">
                                        <canvas id="pdfUpload" width="800" style="display:none"></canvas>
                                    </div>
                                </div>
                            </div>
                        </section>
                    </div>
                </div>
            </div>
        </form>
    </div>

	<div class="customizer d-none d-md-block">
			<a class="customizer-toggle training" href="#" style="writing-mode: vertical-lr;height: 115px;blackground:black !important;">
				<b>COMO USAR ?</b>
			</a>
	</div>

    <script>
        var size = 0;
        var peoples = [];
        var observers = [];
        var __PDF_DOC,
            __CURRENT_PAGE,
            __TOTAL_PAGES,
            __PAGE_RENDERING_IN_PROGRESS = 0,
            __CANVAS = $('#pdfUpload').get(0),
            __CANVAS_CTX = __CANVAS.getContext('2d');

        function analyzeNow(type) {
            $("#analyze").val(type);
            Swal.fire({
                title: type == 1 ? 'Aprovando solicitação' : 'Reprovando Solicitação',
                text: "Você realmente deseja confirmar sua análise?",
                type: 'warning',
                input: 'text',
                showCancelButton: true,
				inputPlaceholder: "Informe sua observação...",
                confirmButtonColor: '#3085d6',
                cancelButtonColor: '#d33',
                confirmButtonText: 'Confirmar!',
                cancelButtonText: 'Cancelar',
                confirmButtonClass: 'btn btn-primary',
                cancelButtonClass: 'btn btn-danger ml-1',
                buttonsStyling: false,
            }).then(function (result) {
                console.log(result);
                if(result.dismiss != "cancel") {
                    if (type == 1) {

                        $("#obs").val(result.value);
                        $("#sendForm").attr('action', '/administration/generic/request/analyze_do');
                        $("#sendForm").submit();
                        block();
                    } else if ($('.swal2-input').val() != "") {

                        $("#obs").val(result.value);
                        $("#sendForm").attr('action', '/administration/generic/request/analyze_do');
                        $("#sendForm").submit();
                        block();
                    } else {
                        $error('Você precisa dizer o motivo da reprovação!');
                    }
                }
            });
        }

        function setFile() {
            $("#file").trigger('click');
        }

        function removeImg() {
			block();
			// Set up the request
			var xhr = new XMLHttpRequest();
			// Open the connection
			xhr.open('POST', 'https://filemanager.gree.com.br/api/v1/administration/generic/remove/file', true);

			// Set up a handler for when the task for the request is complete
			xhr.onload = function () {
				unblock();
				if (xhr.status == 200) {
					unblock();
					$("#imgSelect").show();
					$("#imgShow").hide();
					$("#file").val('');
					$('#imgUpload').attr('src', '');
					size = 0;
					$('#url').val('');
				} else {
					unblock();
					var error = JSON.parse(xhr.responseText);
					$error(error.msg);
				}
			};

			// Send the data.
			xhr.send("url="+$('#url').val());
			
        }

        function addPeoples() {
            if ($('#r_code').select2('data').length > 0) {

                for (let index = 0; index < observers.length; index++) {
                    const elem = observers[index];

                    if ($('#r_code').select2('data')[0].r_code == elem.r_code) {

                        return $error('Esse aprovador já está consta na lista de observadores.');
                    }
                }
                for (let index = 0; index < peoples.length; index++) {
                    const elem = peoples[index];

                    if ($('#r_code').select2('data')[0].r_code == elem.r_code) {

                        return $error('Esse aprovador já está na lista.');
                    }
                }

                var pic = $('#r_code').select2('data')[0].picture == '' ? '/media/avatars/avatar10.jpg' : $('#r_code').select2('data')[0].picture;

                peoples.push({
                    'picture': pic,
                    'name': $('#r_code').select2('data')[0].text,
                    'r_code': $('#r_code').select2('data')[0].r_code,
                });
            } else {

                $error('Escolha um aprovador e depois aperte no botão azul com "+".');
            }

            $(".sendRequest").show();
            $('#r_code').val(0).trigger("change");
            reloadPeoplesList();

        }

        function removePeople(index) {

            peoples.splice(index, 1);
            reloadPeoplesList();
            $success('Aprovador foi removido da lista.');

        }

        function reloadPeoplesList() {

            if (peoples.length == 0)
                $(".sendRequest").hide();

            var list = '';
            for (let index = 0; index < peoples.length; index++) {
                const elem = peoples[index];

                var pos = index + 1;
                list += '<div class="col-12 col-sm-12 col-lg-12">';
                list += '<div class="row m-0">';
                list += '<div class="col-12" style="padding: 15px 11px;border: solid 1px #5A8DEE;margin: 10px 0px;">';
                list += '<span class="badge badge-pill badge-primary badge-round badge-glow" style="position: absolute;top: -0.8rem;left: -0.5rem;">'+ pos +'</span>';
                list += '<div class="media align-items-center">';
                list += '<a class="media-left mr-50" href="#">';
                list += '<img src="'+elem.picture+'" style="object-fit: cover;" alt="avatar" height="40" width="40">';
                list += '</a>';
                list += '<div class="media-body">';
                list += '<h6 class="media-heading mb-0">'+elem.name+'</h6>';
                list += '<span class="font-small-2">Matricula: '+elem.r_code+'</span>';
                list += '</div>';
                list += '<i class="bx bxs-trash cursor-pointer" onclick="removePeople('+index+')" style="float: right;"></i>';
                list += '</div>';
                list += '</div>';
                list += ' </div>';
                list += '</div>';

            }

            $(".listApprov").html(list);
            $("#approvers").val(JSON.stringify(peoples));
        }

        function addObserver() {
            if ($('#observer').select2('data').length > 0) {

                for (let index = 0; index < peoples.length; index++) {
                    const elem = peoples[index];

                    if ($('#observer').select2('data')[0].r_code == elem.r_code) {

                        return $error('Esse observador já consta na lista de aprovadores.');
                    }
                }
                for (let index = 0; index < observers.length; index++) {
                    const elem = observers[index];

                    if ($('#observer').select2('data')[0].r_code == elem.r_code) {

                        return $error('Esse observador já está na lista.');
                    }
                }

                var pic = $('#observer').select2('data')[0].picture == '' ? '/media/avatars/avatar10.jpg' : $('#observer').select2('data')[0].picture;

                observers.push({
                    'picture': pic,
                    'name': $('#observer').select2('data')[0].text,
                    'r_code': $('#observer').select2('data')[0].r_code,
                });
            } else {

                $error('Escolha um aprovador e depois aperte no botão azul com "+".');
            }

            $('#observer').val(0).trigger("change");
            reloadObserversList();
        }

        function removeObserver(index) {

            observers.splice(index, 1);
            reloadObserversList();
            $success('Observador foi removido da lista.');

        }

        function reloadObserversList() {

            var list = '';
            for (let index = 0; index < observers.length; index++) {
                const elem = observers[index];

                var pos = index + 1;
                list += '<div class="col-12 col-sm-12 col-lg-12">';
                list += '<div class="row m-0">';
                list += '<div class="col-12" style="padding: 15px 11px;border: solid 1px #ff5722;margin: 10px 0px;">';
                list += '<div class="media align-items-center">';
                list += '<a class="media-left mr-50" href="#">';
                list += '<img src="'+elem.picture+'" alt="avatar" style="object-fit: cover;" height="40" width="40">';
                list += '</a>';
                list += '<div class="media-body">';
                list += '<h6 class="media-heading mb-0">'+elem.name+'</h6>';
                list += '<span class="font-small-2">Matricula: '+elem.r_code+'</span>';
                list += '</div>';
                @if ($id != 0)
                    @if ($AdmRequests->is_cancelled == 0)
                    @if($isAnalyze)
                <?php $t_analyze = $AdmRequests->AdmRequestAnalyze->where('is_approv', 1)->count(); ?>
                    @if ($AdmRequests->AdmRequestAnalyze->count() != $t_analyze or $AdmRequests->AdmRequestAnalyze->where('is_reprov', 1)->count() == 0)
                    list += '<i class="bx bxs-trash cursor-pointer" onclick="removeObserver('+index+')" style="float: right;"></i>';
                @endif
                    @endif
                    @endif
                    @else
                    list += '<i class="bx bxs-trash cursor-pointer" onclick="removeObserver('+index+')" style="float: right;"></i>';
                @endif
                    list += '</div>';
                list += '</div>';
                list += ' </div>';
                list += '</div>';

            }

            $(".listObservers").html(list);
            $("#observers").val(JSON.stringify(observers));
        }

        function showPDF(pdf_url) {

            PDFJS.getDocument({ url: pdf_url }).then(function(pdf_doc) {
                __PDF_DOC = pdf_doc;
                __TOTAL_PAGES = __PDF_DOC.numPages;
                $(".total-pages").html(__TOTAL_PAGES);

                // Show the first page
                showPage(1);
            }).catch(function(error) {

                alert(error.message);
            });
        }
        function showPage(page_no) {
            __PAGE_RENDERING_IN_PROGRESS = 1;
            __CURRENT_PAGE = page_no;

            $(".page-actual").html(__CURRENT_PAGE);
            // Disable Prev & Next buttons while page is being loaded
            $("#pdf-next, #pdf-prev").attr('disabled', 'disabled');

            // Update current page in HTML
            $("#pdf-current-page").text(page_no);

            // Fetch the page
            __PDF_DOC.getPage(page_no).then(function(page) {
                // As the canvas is of a fixed width we need to set the scale of the viewport accordingly
                var scale_required = __CANVAS.width / page.getViewport(1).width;

                // Get viewport of the page at required scale
                var viewport = page.getViewport(scale_required);

                // Set canvas height
                __CANVAS.height = viewport.height;

                var renderContext = {
                    canvasContext: __CANVAS_CTX,
                    viewport: viewport
                };

                // Render the page contents in the canvas
                page.render(renderContext).then(function() {
                    __PAGE_RENDERING_IN_PROGRESS = 0;

                    // Re-enable Prev & Next buttons
                    $("#pdf-next, #pdf-prev").removeAttr('disabled');
                });
            });
        }
		
		function formatBytes(bytes, decimals = 2) {
			if (bytes === 0) return '0 Bytes';

			const k = 1024;
			const dm = decimals < 0 ? 0 : decimals;
			const sizes = ['Bytes', 'KB', 'MB', 'GB', 'TB', 'PB', 'EB', 'ZB', 'YB'];

			const i = Math.floor(Math.log(bytes) / Math.log(k));

			return parseFloat((bytes / Math.pow(k, i)).toFixed(dm)) + ' ' + sizes[i];
		}
		
		let dropArea = document.getElementById('drop-area');
		['dragenter', 'dragover', 'dragleave', 'drop'].forEach(eventName => {
		  dropArea.addEventListener(eventName, preventDefaults, false)
		})

		function preventDefaults (e) {
		  e.preventDefault()
		  e.stopPropagation()
		}

		dropArea.addEventListener('drop', handleDrop, false);
		
		function handleDrop(e) {
		  let dt = e.dataTransfer
		  let files = dt.files
		  $("#file")[0].files = files;
		  getFiles(files);
		}
		
		function getFiles(files = '') {
			if ($("#file")[0].files && $("#file")[0].files[0] || files) {
				block();
				// Create a FormData object
				var formData = new FormData();

				if (files == '') {
					// Select only the first file from the input array
					var file = $('#file')[0].files; 
					$.each($("#file")[0].files, function(i, file) {
						formData.append('file[]', file);
					});	
				} else {
					// Select only the first file from the input array
					var file = files; 
					$.each(files, function(i, file) {
						formData.append('file[]', file);
					});
				}

				// Set up the request
				var xhr = new XMLHttpRequest();
				// Open the connection
				xhr.open('POST', 'https://filemanager.gree.com.br/api/v1/administration/generic/request/upload', true);

				// listen for `upload.error` event
				xhr.onerror = () => {
					$error('Ocorreu um erro inesperado ao fazer upload do arquivo.');
				}

				// listen for `progress` event
				$('.blockMsg').append('<br><b>Dados sendo carregados</b><div id="bloaded">Calculando...</div>');
				xhr.onprogress = (event) => {
					// event.loaded returns how many bytes are downloaded
					// event.total returns the total number of bytes
					// event.total is only available if server sends `Content-Length` header

					$('#bloaded').html(formatBytes(event.loaded));
				}

				// Set up a handler for when the task for the request is complete
				xhr.onload = function () {
					if (xhr.status == 200) {
						unblock();
						var result = JSON.parse(xhr.responseText);
						$("#imgSelect").hide();
						$("#imgShow").show();

						$('#url').val(result.url);  
						var base64str = result.base64;

						// decode base64 string, remove space for IE compatibility
						var binary = atob(base64str.replace(/\s/g, ''));
						var len = binary.length;
						var buffer = new ArrayBuffer(len);
						var view = new Uint8Array(buffer);
						for (var i = 0; i < len; i++) {
							view[i] = binary.charCodeAt(i);
						}

						// create the blob object with content-type "application/pdf"
						var blob = new Blob( [view], { type: "application/pdf" });
						showPDF(URL.createObjectURL(blob));
						$('#pdfUpload').show();
					} else {
						unblock();
						var error = JSON.parse(xhr.responseText);
						$error(error.msg);
					}
				};

				// Send the data.
				xhr.send(formData);
			}
		}
        $(document).ready(function () {
            $(".js-select2, .js-select22").select2({
                maximumSelectionLength: 1,
                language: {
                    noResults: function () {

                        return 'Usuário não existe ou está desativado...';
                    }
                },
                ajax: {
                    url: '/users/dropdown',
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
			
			if (localStorage.getItem('admDocApprovedTrainig') == null) {
						introJs().setOption("nextLabel", 'Próximo').setOption("prevLabel", 'Voltar').setOption("doneLabel", 'Concluir').setOption('exitOnEsc', 'false').setOption('exitOnEsc', 'false').setOption('exitOnOverlayClick', 'false').setOption('skipLabel', '').start();
				localStorage.setItem('admDocApprovedTrainig', true);
			}
			
			$('.training').click(function() {
				introJs().setOption("nextLabel", 'Próximo').setOption("prevLabel", 'Voltar').setOption("doneLabel", 'Concluir').start();
				localStorage.setItem('admDocApprovedTrainig', true);
			});

            $('.js-select2').on('select2:select', function (e) {
                addPeoples();
            });

            $('.js-select22').on('select2:select', function (e) {
                addObserver();
            });

            // Previous page of the PDF
            $("#pdf-prev").on('click', function() {
                if(__CURRENT_PAGE != 1)
                    showPage(--__CURRENT_PAGE);
            });

            // Next page of the PDF
            $("#pdf-next").on('click', function() {
                if(__CURRENT_PAGE != __TOTAL_PAGES)
                    showPage(++__CURRENT_PAGE);
            });

			
            $("#file").change(function () {
                getFiles();
            });

            $(".sendRequest").click(function (e) {
                if (peoples.length == 0) {

                    return $error('Você precisa ao menos selecionar 1 pessoa para aprovar a solicitação.');
                } else if ($("#file").val() == "") {

                    return $error('Você precisa enviar 1 arquivo para ser aprovado!');
                }

                Swal.fire({
                    title: 'Enviar Solicitação',
                    text: "Você realmente deseja enviar esses arquivos para aprovação?",
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
                        $("#sendForm").attr('action', '/administration/generic/request/view_do');
                        $("#sendForm").submit();
                        block();
                    }
                });
            });

            @if ($id != 0)
            @foreach ($AdmRequests->AdmRequestObservers as $key)
            observers.push({
                'picture': @if ($key->Users->picture) '{{$key->Users->picture}}' @else '/media/avatars/avatar10.jpg' @endif,
                'name': '{{$key->Users->first_name}} {{$key->Users->last_name}}',
                'r_code': '{{$key->Users->r_code}}',
            });
            @endforeach
            reloadObserversList();
            @endif

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

            $('[data-toggle="popover"]').popover({
                placement: 'right',
                trigger: 'hover',
            });

            @if ($id != 0)

            block();
            ajaxSend('/administration/generic/request/base64', {id:<?= $id ?>}, 'GET', '1200000').then(function(result){
                unblock();
                // base64 string
                var base64str = result.base64;

                // decode base64 string, remove space for IE compatibility
                var binary = atob(base64str.replace(/\s/g, ''));
                var len = binary.length;
                var buffer = new ArrayBuffer(len);
                var view = new Uint8Array(buffer);
                for (var i = 0; i < len; i++) {
                    view[i] = binary.charCodeAt(i);
                }

                // create the blob object with content-type "application/pdf"
                var blob = new Blob( [view], { type: "application/pdf" });
                showPDF(URL.createObjectURL(blob));
                $('#pdfUpload').show();

            }).catch(function(err){
                unblock();
                $error(err.message)
            });

            @endif

            setInterval(() => {
                $("#mAdmin").addClass('sidebar-group-active active');
                $("#mrequests").addClass('sidebar-group-active active');
                $("#mrequestsNew").addClass('active');
            }, 100);

        });
    </script>
@endsection
