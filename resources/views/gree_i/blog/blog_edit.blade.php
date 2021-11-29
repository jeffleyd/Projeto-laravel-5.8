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
                    <?php if ($id == 0) { ?>
                        {{ __('news_i.ep_01') }}
                    <?php } else { ?>
                        {{ __('news_i.ep_02') }} <?php if (Session::get('lang') == 'en') { echo $title_en; } else { echo $title_pt; } ?>
                    <?php } ?>
                </div>
                </div>
            </div>
            </div>
        </div>
        <div class="content-body">
            <div class="card">
                <div class="card-content">
                    <div class="card-body">
                        <form action="/blog/update/do" id="UpdatePost" method="post" enctype="multipart/form-data">
                            <ul class="list-unstyled mb-0 border p-2 text-center mb-2">
                                <li class="d-inline-block mr-2">
                                  <fieldset>
                                    <div class="custom-control custom-radio">
                                      <input type="radio" class="custom-control-input" value="1" <?php if ($is_publish == 1) { ?> checked=""<?php } else { ?><?php } ?> name="is_publish" id="active" checked="">
                                      <label class="custom-control-label" for="active">{{ __('news_i.ep_03') }}</label>
                                    </div>
                                  </fieldset>
                                </li>
                                <li class="d-inline-block mr-2">
                                  <fieldset>
                                    <div class="custom-control custom-radio">
                                      <input type="radio" class="custom-control-input" value="0" <?php if ($is_publish == 0) { ?> checked=""<?php } else { ?><?php } ?> name="is_publish" id="desactive">
                                      <label class="custom-control-label" for="desactive">{{ __('news_i.ep_04') }}</label>
                                    </div>
                                  </fieldset>
                                </li>
                            </ul>
                        <div class="form-group">
                            <label for="picture">{{ __('news_i.ep_05') }}</label>
                            <input type="file" accept="image/x-png,image/gif,image/jpeg" class="form-control" id="picture" name="picture">
                            <?php if ($picture) { ?><div><img height="100" src="<?= $picture ?>" alt=""></div><?php } ?>
                        </div>
                        <div class="form-group">
                            <label for="attach">{{ __('news_i.ep_06') }}</label>
                            <input type="file" class="form-control" id="attach[]" name="attach[]" multiple>
                            <p>
                                <small class="text-muted">{{ __('news_i.ep_07') }}</small>
                            </p>
                            <?php if ($attachs) { ?> <?php foreach ($attachs as $key) { ?> <a target="_blank" href="<?= $key->url ?>"><?= $key->name ?> (<?= readableBytes($key->size) ?>)</a> <br> <?php } } ?>
                        </div>
                        <div class="form-group">
                            <label>{{ __('news_i.ep_08') }}</label>
                            <select class="form-control" id="category" name="category">
                                <?php foreach ($author as $key) { ?>
                                <option value="<?= $key->category_id ?>" <?php if ($key->category_id == $category){ echo "selected"; } ?> ><?= __('layout_i.'. $key->name .'') ?></option>
                                <?php } ?>
                            </select>
                        </div>
                        <div class="form-group">
                            <label>{{ __('news_i.ep_09') }}</label>
                            <select class="form-control" id="caution" name="caution">
                                <option value="1" <?php if (1 == $caution){ echo "selected"; } ?>>{{ __('news_i.ep_10') }}</option>
                                <option value="2" <?php if (2 == $caution){ echo "selected"; } ?>>{{ __('news_i.ep_11') }}</option>
                                <option value="3" <?php if (3 == $caution){ echo "selected"; } ?>>{{ __('news_i.ep_12') }}</option>
                            </select>
                        </div>
                        <ul class="nav nav-tabs nav-justified" id="myTab2" role="tablist">
                            <li class="nav-item current">
                              <a class="nav-link active" id="home-tab-justified" data-toggle="tab" href="#home-just" role="tab" aria-controls="home-just" aria-selected="true">
                                {{ __('news_i.ep_13') }}
                              </a>
                            </li>
                            <li class="nav-item">
                              <a class="nav-link" id="profile-tab-justified" data-toggle="tab" href="#profile-just" role="tab" aria-controls="profile-just" aria-selected="true">
                                {{ __('news_i.ep_14') }}
                              </a>
                            </li>
                          </ul>
                          <div class="tab-content pt-1">
                            <div class="tab-pane active" id="home-just" role="tabpanel" aria-labelledby="home-tab-justified">
                                <div class="form-group">
                                    <label for="title">{{ __('news_i.ep_15') }}</label>
                                    <input type="text" class="form-control" maxlength="28" id="title_pt" value="<?= $title_pt ?>" name="title_pt" placeholder="...">
                                    <p>
                                        <small class="text-muted">{{ __('news_i.ep_16') }}</small>
                                    </p>
                                </div>
                                <div class="form-group">
                                    <label for="description_pt">{{ __('news_i.ep_17') }}</label>
                                    <textarea id="js-ckeditor-pt" name="description_pt" rows="6" id="description_pt"><?= $description_pt ?></textarea>
                                </div>
                            </div>
                            <div class="tab-pane" id="profile-just" role="tabpanel" aria-labelledby="profile-tab-justified">
                                <div class="form-group">
                                    <label for="title">{{ __('news_i.ep_15') }}</label>
                                    <input type="text" class="form-control" maxlength="28" id="title_en" value="<?= $title_en ?>" name="title_en" placeholder="...">
                                    <p>
                                        <small class="text-muted">{{ __('news_i.ep_16') }}</small>
                                    </p>
                                </div>
                                <div class="form-group">
                                    <label for="description_en">{{ __('news_i.ep_17') }}</label>
                                    <textarea id="js-ckeditor-en" name="description_en" rows="6" id="description_en"><?= $description_en ?></textarea>
                                </div>
                            </div>

                            <ul class="list-unstyled mb-0 border p-2 text-center mb-2">
                                <h6 class="mb-2">{{ __('news_i.ep_25') }}</h6>
                                <li class="d-inline-block mr-2">
                                  <fieldset>
                                    <div class="custom-control custom-radio">
                                      <input type="radio" class="custom-control-input" value="0" name="notify" id="nobody" checked="">
                                      <label class="custom-control-label" for="nobody">{{ __('news_i.ep_26') }}</label>
                                    </div>
                                  </fieldset>
                                </li>
                                <li class="d-inline-block mr-2">
                                  <fieldset>
                                    <div class="custom-control custom-radio">
                                      <input type="radio" class="custom-control-input" value="1" name="notify" id="only_sector">
                                      <label class="custom-control-label" for="only_sector">{{ __('news_i.ep_27') }}</label>
                                    </div>
                                  </fieldset>
                                </li>
								<li class="d-inline-block mr-2">
                                    <fieldset>
                                      <div class="custom-control custom-radio">
                                        <input type="radio" class="custom-control-input" value="3" name="notify" id="everyone_manaus">
                                        <label class="custom-control-label" for="everyone_manaus">Todos Manaus</label>
                                      </div>
                                    </fieldset>
                                </li>
								<li class="d-inline-block mr-2">
                                    <fieldset>
                                      <div class="custom-control custom-radio">
                                        <input type="radio" class="custom-control-input" value="4" name="notify" id="everyone_sp">
                                        <label class="custom-control-label" for="everyone_sp">Todos São Paulo</label>
                                      </div>
                                    </fieldset>
                                </li>
                                <li class="d-inline-block mr-2">
                                    <fieldset>
                                      <div class="custom-control custom-radio">
                                        <input type="radio" class="custom-control-input" value="2" name="notify" id="everyone_general">
                                        <label class="custom-control-label" for="everyone_general">Todos geral</label>
                                      </div>
                                    </fieldset>
                                </li>
                            </ul>
                          </div>
                        <div class="form-group mt-2">
                            <button type="submit" id="sendPost" class="btn btn-primary" style="width:100%;"><?php if ($id == 0) { ?>{{ __('news_i.ep_18') }}<?php } else { ?>{{ __('news_i.ep_19') }}<?php } ?></button>
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
            ckeditorInit('#js-ckeditor-en');
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
    
            <?php if ($id == 0) { ?>
    
                setInterval(() => {
                    $("#mNews").addClass('sidebar-group-active active');
                    $("#mBlogNew").addClass('active');
                }, 100);
                
            <?php } else { ?>
    
                setInterval(() => {
                    $("#mNews").addClass('sidebar-group-active active');
                }, 100);
    
            <?php } ?>
            
        });
        </script>
@endsection