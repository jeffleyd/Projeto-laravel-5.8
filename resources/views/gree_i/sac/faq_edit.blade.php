@extends('gree_i.layout')

@section('content')
<div class="content-overlay"></div>
<div class="content-wrapper">
    <div class="content-header row">
        <div class="content-header-left col-12 mb-2 mt-1">
          <div class="row breadcrumbs-top">
            <div class="col-12">
              <h5 class="content-header-title float-left pr-1 mb-0">Perguntas frequentes</h5>
              <div class="breadcrumb-wrapper col-12">
                @if ($id == 0)
                Nova pergunta com resposta 
                @else
                Pergunta: {{ $question }}
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
                            <form class="push" id="a_update_form" method="POST" action="/sac/faq/update" enctype="multipart/form-data">
                            <input type="hidden" id="id" name="id" value="<?= $id ?>">
                            <div class="row">
                                <div class="col-sm-12">
                                    <label for="type">Tipo</label>
                                    <fieldset class="form-group">
                                        <select name="type" class="form-control" id="type">
                                            <option value="1" @if ($type == 1) selected @endif>Autorizada</option>
                                            <option value="2" @if ($type == 2) selected @endif>Consumidor</option>
                                        </select>
                                    </fieldset>
                                </div>
                                <div class="col-sm-12">
                                    <label for="question">Pergunta</label>
                                    <fieldset class="form-group">
                                    <input type="text" name="question" id="question" value="{{ $question }}" class="form-control">
                                    </fieldset>
                                </div>
                                <div class="col-sm-12">
                                    <label for="answer">Resposta</label>
                                    <fieldset class="form-group">
                                        <textarea name="answer" id="answer" class="form-control" rows="3">{{ $answer }}</textarea>
                                    </fieldset>
                                </div>
                            </div>
                            <button type="submit" class="btn btn-dark ml-1">
                                <i class="bx bx-check d-block d-sm-none"></i>
                                @if ($id == 0)
                                <span class="d-none d-sm-block">Criar pergunta frequente</span>
                                @else
                                <span class="d-none d-sm-block">Atualizar pergunta frequente</span>
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
<script src="/ckeditor/ckeditor.js"></script>
<script src="/ckeditor/init.js"></script>
<script>
    $(document).ready(function () {
        ckeditorInit('#answer');
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
            $("#mAfterSales").addClass('sidebar-group-active active');
            $("#mSacComunication").addClass('sidebar-group-active active');
            $("#mSacComunicationFaq").addClass('active');
        }, 100);

    });
    </script>
@endsection