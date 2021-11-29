
<!DOCTYPE html>
<html class="loading" lang="en" data-textdirection="ltr">
    <!-- BEGIN: Head-->
    
    <head>
      <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
      <meta http-equiv="X-UA-Compatible" content="IE=edge">
      <meta name="viewport" content="width=device-width, initial-scale=1.0, user-scalable=0">
      <meta name="robots" content="noindex, nofollow">
      
        <title>Gree - System Internal</title>
        <link rel="apple-touch-icon" href="/admin/app-assets/images/ico/favicon-192x192.png">
        <link rel="shortcut icon" type="image/x-icon" href="/admin/app-assets/images/ico/favicon.png">
        <link href="https://fonts.googleapis.com/css?family=Rubik:300,400,500,600%7CIBM+Plex+Sans:300,400,500,600,700" rel="stylesheet">

        <!-- BEGIN: Vendor CSS-->
        <link rel="stylesheet" type="text/css" href="/admin/app-assets/vendors/css/vendors.min.css">
        <!-- END: Vendor CSS-->


        <!-- BEGIN: Theme CSS-->
        <link rel="stylesheet" type="text/css" href="/admin/app-assets/css/bootstrap.css">
        <link rel="stylesheet" type="text/css" href="/admin/app-assets/css/bootstrap-extended.css">
        <link rel="stylesheet" type="text/css" href="/admin/app-assets/css/colors.css">
        <link rel="stylesheet" type="text/css" href="/admin/app-assets/css/components.css">
        <link rel="stylesheet" type="text/css" href="/admin/app-assets/css/themes/dark-layout.css">
        <link rel="stylesheet" type="text/css" href="/admin/app-assets/css/themes/semi-dark-layout.css">
        <!-- END: Theme CSS-->

        <!-- BEGIN: Page CSS-->
        <link rel="stylesheet" type="text/css" href="/admin/app-assets/css/core/menu/menu-types/horizontal-menu.css">
        
        <!-- END: Page CSS-->

        <!-- BEGIN: Custom CSS-->
        <link rel="stylesheet" type="text/css" href="/admin/app-assets/css/all.css">
        <link rel="stylesheet" type="text/css" href="/admin/assets/css/style.css">
        <!-- END: Custom CSS-->

        <script src="/admin/app-assets/js/jquery-3.4.1.min.js"></script>

    </head>

    <!-- END: Head-->

    <!-- BEGIN: Body-->

    <body class="horizontal-layout horizontal-menu 2-columns" data-open="hover" data-menu="horizontal-menu" data-col="2-columns">
        <div class="toast toast-light" role="alert" aria-live="assertive" aria-atomic="true" data-delay="10000">
            <div class="toast-header">
                <i class="bx bx-bell"></i>
                <span class="mr-auto toast-title fcm-title"></span>
                <small class="d-sm-block d-none">Agora</small>
                <button type="button" class=" close" data-dismiss="toast" aria-label="Close">
                <i class="bx bx-x"></i>
                </button>
            </div>
            <div class="toast-body fcm-body">
            </div>
        </div>
        <div class="toast-bs-container">
            <div class="toast-position"></div>
        </div>


        <!-- BEGIN: Content-->
        <div class="app-content content">

            <div class="content-overlay"></div>
              <div class="content-wrapper">
                  
                  {{-- <div class="alert alert-warning alert-dismissible mb-2" role="alert">
                    <div class="d-flex align-items-center">
                        <i class="bx bx-error"></i>
                        <span>
                            Deixe apenas uma pergunta ativa por vez e não esqueça de desativar sua pergunta quando não estiver mais usando.
                        </span>
                    </div>
                  </div> --}}
                    
                    {{-- alert alert-danger alert-dismissible mb-2 --}}
                    @if($show_alert)
                        <div class="alert alert-danger alert-dismissible mb-2" role="alert">
                            <div class="d-flex align-items-center">
                                <i class="bx bx-error"></i>
                                <span>
                                    Sua resposta ja foi enviada. A Gree agradece sua participação nesta pesquisa
                                </span>
                            </div>
                        </div>

                        @if($response_again)
                            <div class="alert alert-warning alert-dismissible mb-2" role="alert">
                                <div class="d-flex align-items-center">
                                    
                                    <a class="align-items-center" style="color: white;" href="/pesquisa/{{$survey->id}}/resposta?new_response=true">
                                        <i class="bx bx-error"></i>
                                        <span>
                                            Clique aqui para responder novamente
                                        </span>
                                    </a>
                                </div>
                            </div>
                        @endif

                    @else
                        <div class="card">
                            <div class="container">
                                <div class="card-content" id="survey_modal_{{$survey->id}}" tabindex="-1" role="dialog" aria-labelledby="survey_modal_{{$survey->id}}" aria-hidden="true">
                                    <div role="document">
                                    <div >
                                        <div class="card-header">
                                        
                                        <h3 class="title" style="text-align: center;"><?= htmlspecialchars_decode($question[0]->name) ?></h3>
                                        <p class="text-center mb-1">
                                            <?= htmlspecialchars_decode($question[0]->description) ?>
                                        </p>
                                        
                                        </div>
                                        <div class="card-body">
                                            <form class="needs-validation" action="#" id="formSurvey_{{$survey->id}}">
                                            <input type="hidden" name="survey_id" value="<?= $question[0]->survey_id ?>">


                                            <div class="row control-group">
                                                <div class="col-12">
                                                    @foreach($question as  $index => $item)
                                                        
                                                        <div class="form-group row">
                                                            <div class="col-12">
                                                            <p class="text-left">
                                                                
                                                                <b>{{$index+1}}.</b> {{$item->title}}

                                                                <input type="hidden" name="question_{{$item->survey_id}}_{{$index+1}}_id" value="{{$item->id}}">
                                                            </p>
                                                            </div>
                                                        </div>

                                                            
                                                            {{-- Texto --}}
                                                            @if($item->answer_type == 0)
                                                                <div class="form-group row">
                                                                    <div class="form-group col-12">
                                                                        <fieldset class="form-group mb-2">
                                                                            <input type="text" @if($item->is_required) required @endif
                                                                            id="question_{{$item->survey_id}}_{{$index+1}}_text" name="question_{{$item->survey_id}}_{{$index+1}}" class="form-control round" placeholder="digite aqui sua resposta...">
                                                                        </fieldset>
                                                                    </div>
                                                                </div>
                                                                @if($item->show_obs == 1)
                                                                    <div class="form-group row">
                                                                        <div class="form-group col-12">
                                                                            <fieldset class="form-group mb-2">
                                                                                <input type="text" id="question_{{$item->survey_id}}_{{$index+1}}_input" name="question_{{$item->survey_id}}_{{$index+1}}_input" class="form-control round" placeholder="observações...">
                                                                            </fieldset>
                                                                        </div>
                                                                    </div>
                                                                @endif
                                                            @endif

                                                            {{-- Sim/Não --}}
                                                            @if($item->answer_type == 1)
                                                                <div class="form-group row">
                                                                    <div class="form-group col-md-2 col-4">
                                                                        <fieldset>
                                                                            <div class="radio radio-primary radio-glow">
                                                                                <input type="radio" value="Sim" @if($item->is_required) required @endif
                                                                                id="question_{{$item->survey_id}}_{{$index+1}}_yes" name="question_{{$item->survey_id}}_{{$index+1}}">
                                                                                <label for="question_{{$item->survey_id}}_{{$index+1}}_yes">Sim</label>
                                                                            </div>
                                                                        </fieldset>
                                                                    </div>
                                                                    <div class="form-group col-md-2 col-4">
                                                                        <fieldset>
                                                                            <div class="radio radio-primary radio-glow">
                                                                                <input type="radio" value="Não" @if($item->is_required) required @endif
                                                                                id="question_{{$item->survey_id}}_{{$index+1}}_no" name="question_{{$item->survey_id}}_{{$index+1}}" checked="">
                                                                                <label for="question_{{$item->survey_id}}_{{$index+1}}_no">Não</label>
                                                                            </div>
                                                                        </fieldset>
                                                                    </div>
                                                                </div>
                                                                @if($item->show_obs == 1)
                                                                    <div class="form-group row">
                                                                        <div class="form-group col-12">
                                                                            <fieldset class="form-group mb-2">
                                                                                <input type="text" id="question_{{$item->survey_id}}_{{$index+1}}_input" name="question_{{$item->survey_id}}_{{$index+1}}_input" class="form-control round" placeholder="observações...">
                                                                            </fieldset>
                                                                        </div>
                                                                    </div>
                                                                @endif
                                                                
                                                                
                                                            @endif

                                                            {{-- Caixa de Seleção --}}
                                                            @if($item->answer_type == 2)
                                                                <div class="form-group row">
                                                                    @foreach ($item->json_answer as $index_answer => $answer )
                                                                        
                                                                        <div class="form-group  @if (Str::length($answer['title']) < 7) col-md-3 col-4 @elseif (Str::length($answer['title']) > 10) col-md-6 col-12  @else col-md-3 col-6  @endif" style="padding-left: 10px;padding-right: 10px;">
                                                                            <fieldset>
                                                                                <div class="radio radio-primary radio-glow">
                                                                                    <input type="radio" value="{{$answer['title']}}" 
                                                                                    @if($item->is_required) required @endif
                                                                                    @if($item->is_required && $index_answer==0) checked @endif
                                                                                    id="question_{{$item->survey_id}}_{{$index+1}}_radio_{{$index_answer}}" name="question_{{$item->survey_id}}_{{$index+1}}">
                                                                                    <label for="question_{{$item->survey_id}}_{{$index+1}}_radio_{{$index_answer}}">{{$answer['title']}}</label>
                                                                                </div>
                                                                            </fieldset>
                                                                        </div>
                                                                    @endforeach
                                                                </div>
                                                                @if($item->show_obs == 1)
                                                                    <div class="form-group row">
                                                                        <div class="form-group col-12">
                                                                            <fieldset class="form-group mb-2">
                                                                                <input type="text" id="question_{{$item->survey_id}}_{{$index+1}}_input" name="question_{{$item->survey_id}}_{{$index+1}}_input" class="form-control round" placeholder="observações...">
                                                                            </fieldset>
                                                                        </div>
                                                                    </div>
                                                                @endif
                                                                
                                                            @endif

                                                            {{-- Multipla Escolha --}}
                                                            @if($item->answer_type == 3)
                                                                <div class="form-group row">

                                                                    @foreach ($item->json_answer as $index_answer => $answer )
                                                                        <div class="form-group  @if (Str::length($answer['title']) < 7) col-md-3 col-4 @elseif (Str::length($answer['title']) > 10) col-md-6 col-12  @else col-md-3 col-6  @endif" style="padding-left: 10px;padding-right: 10px;">
                                                                            <fieldset>
                                                                                <div class="checkbox checkbox-primary checkbox-glow">
                                                                                    <input type="checkbox" value="{{$answer['title']}}" 
                                                                                    @if($item->is_required && $index_answer==0) checked @endif
                                                                                    id="question_{{$item->survey_id}}_{{$index+1}}_checkbox_{{$index_answer}}" name="question_{{$item->survey_id}}_{{$index+1}}[]">
                                                                                    <label for="question_{{$item->survey_id}}_{{$index+1}}_checkbox_{{$index_answer}}">{{$answer['title']}}</label>
                                                                                </div>
                                                                            </fieldset>
                                                                        </div>
                                                                    @endforeach
                                                                </div>
                                                                
                                                                @if($item->show_obs == 1)
                                                                    <div class="form-group row">
                                                                        <div class="form-group col-12">
                                                                            <fieldset class="form-group mb-2">
                                                                                <input type="text" id="question_{{$item->survey_id}}_{{$index+1}}_input" name="question_{{$item->survey_id}}_{{$index+1}}_input" class="form-control round" placeholder="observações...">
                                                                            </fieldset>
                                                                        </div>
                                                                    </div>
                                                                @endif
                                                            @endif

                                                            {{-- Lista Suspensa --}}
                                                            @if($item->answer_type == 4)
                                                                <div class="form-group row">
                                                                    <div class="col-12">
                                                                        <select class=" form-control" 
                                                                        @if($item->is_required) required @endif
                                                                        id="question_{{$item->survey_id}}_{{$index+1}}_select" name="question_{{$item->survey_id}}_{{$index+1}}" style="width: 100%;" data-placeholder="Selecione uma das opções">
                                                                            @foreach ($item->json_answer as $index_answer => $answer )
                                                                                <option value="{{$answer['title']}}" 
                                                                                >{{$answer['title']}}</option>
                                                                            @endforeach
                                                                        </select>
                                                                    </div>
                                                                </div>
                                                                
                                                                @if($item->show_obs == 1)
                                                                    <div class="form-group row">
                                                                        <div class="form-group col-12">
                                                                            <fieldset class="form-group mb-2">
                                                                                <input type="text" id="question_{{$item->survey_id}}_{{$index+1}}_input" name="question_{{$item->survey_id}}_{{$index+1}}_input" class="form-control round" placeholder="observações...">
                                                                            </fieldset>
                                                                        </div>
                                                                    </div>
                                                                @endif

                                                            @endif
                                                            

                                                    @endforeach
                                                </div>
                                            </div>

                                            
                                            </form>
                                        </div>
                                        <div class="card-footer">
                                        <button type="button" class="btn btn-primary ml-1" onclick="sendAswerModal({{$survey->id}});">
                                            <i class="bx bx-check"></i>
                                            <span class="">Enviar questionário</span>
                                        </button>
                                        </div>
                                    </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    @endif
                  {{--  --}}
                  {{--  --}}

            </div>
              <script>
                  $(document).ready(function () {

                      setInterval(() => {
                          $("#mAdmin").addClass('sidebar-group-active active');
                          $("#msurvey").addClass('active');
                          
                      }, 100);
                  });
              </script>

        </div>
        <!-- END: Content-->
        
        
        <!-- END: Theme JS-->
        
        
        <script src="/admin/app-assets/vendors/js/ui/blockUI.min.js"></script>
        
        
        <!-- END: JS Vendor Scripts-->

        @include('gree_i.layout.js-user-scripts')
  
        <script>
        

            function sendAswerModal(idModal) {
                //alert('teste');
                //$('#survey_modal_'+idModal).modal('toggle');
                //let form = $(".needs-validation");
                let form = $("#formSurvey_"+idModal);
                if (form[0].checkValidity() === true) {
                    
                    
                    block();
                    
                    $.ajax({
                        type: "POST",
                        url: "/surveys/anonymous/answers",
                        data: $("#formSurvey_"+idModal).serialize(),
                        success: function (response) {
                            unblock();
                            window.location.href = '/pesquisa/'+idModal+'/resposta?msg=true'
                            
                        },
                        error: function (xhr, ajaxOptions, thrownError) {
                            unblock();
                            alert('Não foi possível enviar, tente novamente!');
                        }
                    });
                    
                    form.removeClass('was-validated');
                }else{
                    form.addClass('was-validated');
                   
                }
                
            }

            
            
      </script>
    </body>
    <!-- END: Body-->

   

</html>
