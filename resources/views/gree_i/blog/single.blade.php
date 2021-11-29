@extends('gree_i.layout')

@section('content')
<link rel="stylesheet" type="text/css" href="/admin/app-assets/css/pages/page-knowledge-base.min.css">
<div class="content-overlay"></div>
      <div class="content-wrapper">
        <div class="content-header row">
          <div class="content-header-left col-12 mb-2 mt-1">
            <div class="row breadcrumbs-top">
              <div class="col-12">
                <h5 class="content-header-title float-left pr-1 mb-0">{{ __('layout_i.menu_news') }}</h5>
                <div class="breadcrumb-wrapper col-12">
                    <?php if (Session::get('lang') == 'en') { echo $post->title_en; } else { echo $post->title_pt; } ?>
                </div>
              </div>
            </div>
          </div>
        </div>
        <div class="content-body"><!-- Knowledge base categories Content start  -->
<section class="kb-categories">
    <div class="row">
        <div class="col-md-12">
            <div class="card">
                <div class="card-content">
                    <div class="card-body">
                        <div class="title mb-2">
                            <h2 class="kb-title"><?php if (Session::get('lang') == 'en') { echo $post->title_en; } else { echo $post->title_pt; } ?></h2>
                            <p><?= getENameF($post->r_code) ?></a> • <?= date('Y-m-d', strtotime($post->created_at)) ?></p>
                            
                            <?php if (count($attachs) > 0) { ?><div id="attach" style="padding: 10px 17px;border: solid 1px;"> <?php foreach ($attachs as $key) { ?><a style="padding: 5px;background-color: aliceblue;margin-right: 10px;" target="_blank" href="<?= $key->url ?>"><?= $key->name ?> (<?= readableBytes($key->size) ?>)</a><?php } ?></div><?php } ?>
                            
                        </div>
                        <?php if (!empty($post->picture)) { ?>
                        <div id="kb-carousel" class="carousel slide my-2 text-center" data-ride="carousel">
                            <ol class="carousel-indicators">
                                <li data-target="#kb-carousel" data-slide-to="0" class="active"></li>
                            </ol>
                            <div class="carousel-inner" role="listbox">
                                <div class="carousel-item carousel-item-next carousel-item-left">
                                    <img class="img-fluid" src="<?= $post->picture ?>" alt="banner">
                                </div>
                            </div>
                        </div>
                        <?php } ?>
                        <p>
                            <?php if (Session::get('lang') == 'en') { echo $post->description_en; } else { echo $post->description_pt; } ?>
                        </p>
                        <div class="d-flex justify-content-between mt-2">
                            <a href="/news" class="btn btn-light-primary">{{ __('news_i.back') }}</a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="kb-overlay"></div>
    </div>
</section>
<!-- Knowledge base categories Content ends -->

</div>
</div>

<?php if ($post->attach) { ?>
<div class="mb-2" style="width: 390px; position: fixed;left: 0;right: 0;bottom: 0;margin: 0 auto; z-index: 99; text-align: center;">
    <a target="_blank" href="<?= $post->attach ?>">
    <button type="button" class="btn btn-info">
        Baixar anexo
    </button>
    </a>
</div>
<?php } ?>

<script>
    $(document).ready(function () {
        setInterval(() => {
            $("#mNews").addClass('active');
        }, 100);
    });
</script>
    
@endsection