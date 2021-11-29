@extends('gree_i.layout')

@section('content')
<link rel="stylesheet" type="text/css" href="/admin/app-assets/css/pages/page-knowledge-base.min.css">
<div class="content-overlay"></div>
    <div class="content-wrapper">
        <div class="content-header row">
            <div class="content-header-left col-12 mb-2 mt-1">
            <div class="row breadcrumbs-top">
                <div class="col-12">
                <h5 class="content-header-title float-left pr-1 mb-0">TI - Criação de software</h5>
                <div class="breadcrumb-wrapper col-12">
                    Criação de um novo projeto
                </div>
                </div>
            </div>
            </div>
        </div>

        <div class="alert alert-info alert-dismissible mb-2" role="alert">
            <div class="d-flex align-items-center">
                <i class="bx bx-error"></i>
                <span>
                    Sua ideia de projeto será avaliada pelo responsável da área e em seguida aprovada ou não.
                    <br> Quando tivermos a situação da sua avaliação, iremos enviar um email para você.
                </span>
            </div>
        </div>
        <div class="content-body">
            <div class="card">
                <div class="card-content">
                    <div class="card-body">
                        <form action="/blog/update/do" id="UpdatePost" method="post" enctype="multipart/form-data">
                        <div class="form-group">
                            <label for="subject">Assunto</label>
                            <input type="text" class="form-control" maxlength="60" id="subject" value="<?= $title_pt ?>" name="title_pt" placeholder="...">
                            <p>
                                <small class="text-muted">Fale em poucas palavras o que é o seu projeto</small>
                            </p>
                        </div>
                        <div class="form-group">
                            <label for="description_pt">Descrição</label>
                            <textarea id="js-ckeditor-pt" name="description" rows="6" id="description_pt" placeholder="Descreva o seu projeto detalhadamente"></textarea>
                        </div>
                        <div class="form-group mt-2">
                            <button type="submit" id="sendPost" class="btn btn-primary" style="width:100%;">Criar novo projeto</button>
                        </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <script src="/ckeditor/ckeditor.js"></script>
    <script src="/ckeditor/init.js"></script>
    <script>
        var editor_pt, editor_en;
        $(document).ready(function () {
            
            ckeditorInit('#js-ckeditor-pt');
            $("#UpdatePost").submit(function (e) { 
                if ($("#title_pt").val() == "") {
    
                    error('<?= __('news_i.ep_20') ?>');
                    e.preventDefault();
                    return;
                } else if ($("#description_pt").val() == "") {
    
                    error('<?= __('news_i.ep_22') ?>');
                    e.preventDefault();
                    return;
                } else if ($("#category").val() == "") {
    
                    error('<?= __('news_i.ep_24') ?>');
                    e.preventDefault();
                    return;
                }

                block();
                
            });
    
            setInterval(() => {
                $("#mNews").addClass('sidebar-group-active active');
                $("#mBlogNew").addClass('active');
            }, 100);
            
        });
        </script>
@endsection