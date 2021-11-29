<html class="loaded" lang="en" data-textdirection="ltr"><!-- BEGIN: Head--><head>
  <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <meta name="viewport" content="width=device-width, initial-scale=1.0, user-scalable=0">
  <title>Gree do Brasil</title>
  <meta name="description" content="Serviço de atendimento ao cliente - SAC">
  <meta name="keywords" content="SAC Gree, gree sac, atendimento ao cliente gree">
  <meta name="robots" content="index, follow">
  <link rel="shortcut icon" href="/admin/app-assets/images/ico/favicon.png">
  <link rel="icon" type="image/png" sizes="192x192" href="/admin/app-assets/images/ico/favicon-192x192.png>
  <link rel="apple-touch-icon" sizes="180x180" href="/admin/app-assets/images/ico/favicon-192x192.png">
  <link href="https://fonts.googleapis.com/css?family=Rubik:300,400,500,600%7CIBM+Plex+Sans:300,400,500,600,700" rel="stylesheet">

  <!-- BEGIN: Vendor CSS-->
  <link rel="stylesheet" type="text/css" href="/admin/app-assets/vendors/css/vendors.min.css">
  <link rel="stylesheet" type="text/css" href="/admin/app-assets/vendors/css/extensions/swiper.min.css">
  <!-- END: Vendor CSS-->

  <!-- BEGIN: Theme CSS-->
  <link rel="stylesheet" type="text/css" href="/admin/app-assets/css/bootstrap.min.css">
  <link rel="stylesheet" type="text/css" href="/admin/app-assets/css/bootstrap-extended.min.css">
  <link rel="stylesheet" type="text/css" href="/admin/app-assets/css/colors.min.css">
  <link rel="stylesheet" type="text/css" href="/admin/app-assets/css/components.min.css">
  <link rel="stylesheet" type="text/css" href="/admin/app-assets/css/themes/dark-layout.min.css">
  <link rel="stylesheet" type="text/css" href="/admin/app-assets/css/themes/semi-dark-layout.min.css">
  <!-- END: Theme CSS-->

  <!-- BEGIN: Page CSS-->
  <link rel="stylesheet" type="text/css" href="/admin/app-assets/css/core/menu/menu-types/horizontal-menu.min.css">

  <!-- BEGIN: Custom CSS-->
  <link rel="stylesheet" type="text/css" href="/admin/assets/css/style.css">
  <!-- END: Custom CSS-->

</head>
<!-- END: Head-->

<!-- BEGIN: Body-->
<body class="horizontal-layout navbar-sticky 2-columns footer-static pace-done pace-done pace-done pace-done pace-done menu-expanded horizontal-menu" data-open="hover" data-menu="horizontal-menu" data-col="2-columns"><div class="pace  pace-inactive pace-inactive pace-inactive pace-inactive pace-inactive"><div class="pace-progress" data-progress-text="100%" data-progress="99" style="transform: translate3d(100%, 0px, 0px);">
<div class="pace-progress-inner"></div>
</div>
<div class="pace-activity"></div></div>

  <!-- BEGIN: Header-->    

  <!-- BEGIN: Content-->
  <div class="app-content content">
    
    <div class="content-wrapper" style="margin-top: 32px !important">
      
      <div class="content-body"><!-- faq search start -->

<!-- faq start -->
<section class="faq">
  <div class="row">
    <div class="col-sm-2">
      <div class="card">
        <div class="card-content">
          <div class="card-body">
            <div class="list-group div-scroll">
              @foreach($folders as $folder)
                <div class="list-group-item">
                  <a href="?f={{ \Illuminate\Support\Facades\Crypt::encrypt($folder) }}">
                    <span class="fa fa-folder"></span> {{$folder}}
                  </a>
                  @if ($current_folder == $folder)
                    <div class="list-group folder">
                      @foreach($folder_files as $file)
                        <a href="?l={{ \Illuminate\Support\Facades\Crypt::encrypt($file) }}&f={{ \Illuminate\Support\Facades\Crypt::encrypt($folder) }}"
                          class="list-group-item @if ($current_file == $file) llv-active @endif">
                          {{$file}}
                        </a>
                      @endforeach
                    </div>
                  @endif
                </div>
              @endforeach
              @foreach($files as $file)
                <a href="?l={{ \Illuminate\Support\Facades\Crypt::encrypt($file) }}"
                   class="list-group-item @if ($current_file == $file) llv-active @endif">
                  {{$file}}
                </a>
              @endforeach
            </div>
          </div>
        </div>
      </div>
    </div>

    <div class="col-sm-10">
      <div class="card">
        <div class="card-content">
          <div class="card-body">
            @if ($logs === null)
              <div>
                Log file com mais de 50M, baixe o arquivo.
              </div>
            @else
              <div class="table-responsive">
                <table id="list-datatable" class="table">
                    <thead>
                        <tr>
                            @if ($standardFormat)
                            <th>Level</th>
                            <th>Context</th>
                            <th>Date</th>
                          @else
                            <th>Line number</th>
                          @endif
                          <th>Content</th>
                        </tr>
                    </thead>
                    <tbody>
                      @foreach($logs as $key => $log)
                        <tr data-display="stack{{{$key}}}">
                          @if ($standardFormat)
                            <td class="nowrap text-{{{$log['level_class']}}}">
                              <span class="fa fa-{{{$log['level_img']}}}" aria-hidden="true"></span>&nbsp;&nbsp;{{$log['level']}}
                            </td>
                            <td class="text">{{$log['context']}}</td>
                          @endif
                          <td class="date">{{{$log['date']}}}</td>
                          <td class="text">
                            @if ($log['stack'])
                              <button type="button"
                                      class="float-right expand btn btn-outline-dark btn-sm mb-2 ml-2"
                                      data-display="stack{{{$key}}}">
                                <span class="fa fa-search"></span>
                              </button>
                            @endif
                            {{{$log['text']}}}
                            @if (isset($log['in_file']))
                              <br/>{{{$log['in_file']}}}
                            @endif
                            @if ($log['stack'])
                              <div class="stack" id="stack{{{$key}}}"
                                  style="display: none; white-space: pre-wrap;">{{{ trim($log['stack']) }}}
                              </div>
                            @endif
                          </td>
                        </tr>
                      @endforeach
                    </tbody>
                </table>
            </div>
            @endif
          </div>
        </div>
      </div>
    </div>

  </div>

  <div class="row">
    <div class="col-sm-2">
    </div>
    <div class="col-sm-10">
      <div class="card">
        <div class="card-content">
          <div class="card-body">
              @if($current_file)
                <a href="?dl={{ \Illuminate\Support\Facades\Crypt::encrypt($current_file) }}{{ ($current_folder) ? '&f=' . \Illuminate\Support\Facades\Crypt::encrypt($current_folder) : '' }}">
                  <button type="button" class="btn btn-outline-success mr-1 mb-1"><i class="fa fa-download"></i><span class="align-middle ml-25">Baixar arquivo</span></button>
                </a>
                <a id="clean-log" href="?clean={{ \Illuminate\Support\Facades\Crypt::encrypt($current_file) }}{{ ($current_folder) ? '&f=' . \Illuminate\Support\Facades\Crypt::encrypt($current_folder) : '' }}">
                  <button type="button" class="btn btn-outline-info mr-1 mb-1"><i class="fa fa-sync"></i><span class="align-middle ml-25">Limpar Arquivo</span></button>
                </a>
                <a id="delete-log" href="?del={{ \Illuminate\Support\Facades\Crypt::encrypt($current_file) }}{{ ($current_folder) ? '&f=' . \Illuminate\Support\Facades\Crypt::encrypt($current_folder) : '' }}">
                  <button type="button" class="btn btn-outline-danger mr-1 mb-1"><i class="fa fa-trash"></i><span class="align-middle ml-25">Deletar Arquivo</span></button>
                </a>
                @if(count($files) > 1)
                  <a id="delete-all-log" href="?delall=true{{ ($current_folder) ? '&f=' . \Illuminate\Support\Facades\Crypt::encrypt($current_folder) : '' }}">
                    <button type="button" class="btn btn-outline-danger mr-1 mb-1"><i class="fa fa-trash-alt"></i><span class="align-middle ml-25">Deleter todos os arquivos</span></button>
                  </a>
                @endif
              @endif
          </div>
        </div>
      </div>
    </div>
  </div>
</section>
<!-- faq ends -->



  <!-- BEGIN: Vendor JS-->
  <script src="/admin/app-assets/vendors/js/vendors.min.js"></script>
  <script src="/admin/app-assets/fonts/LivIconsEvo/js/LivIconsEvo.tools.min.js"></script>
  <script src="/admin/app-assets/fonts/LivIconsEvo/js/LivIconsEvo.defaults.min.js"></script>
  <script src="/admin/app-assets/fonts/LivIconsEvo/js/LivIconsEvo.min.js"></script>
  <!-- BEGIN Vendor JS-->

  <!-- BEGIN: Theme JS-->
  <script src="/admin/app-assets/js/all.js"></script>
  <script src="/admin/app-assets/js/scripts/configs/horizontal-menu.min.js"></script>
  <script src="/admin/app-assets/js/core/app-menu.min.js"></script>
  <script src="/admin/app-assets/js/core/app.js"></script>
  <script src="/admin/app-assets/js/scripts/components.min.js"></script>
  <script src="/admin/app-assets/js/scripts/footer.min.js"></script>
  <script src="/admin/app-assets/js/scripts/customizer.min.js"></script>
  <!-- END: Theme JS-->

<script>

</script>
<!-- END: Body-->
</body></html>