                
<?php 

    $surveys = App\Model\Survey::where('is_active', 1)->where('survey_init', 1)->get();
    
    if(!$surveys->isEmpty()) {
?>
        <script src="/admin/app-assets/vendors/js/forms/validation/jqBootstrapValidation.js"></script>
        <li id="msurveys" class="dropdown dropdown-submenu" data-menu="dropdown-submenu"><a class="dropdown-item align-items-center dropdown-toggle" href="#" data-toggle="dropdown"><i class="bx bx-right-arrow-alt" data-icon="grid"></i><span>Responder Pesquisas</span></a>
            <ul class="dropdown-menu">
                @foreach ( $surveys as $index=>$survey)
                    <li id="link_survey_{{$index}}" data-menu="">
                        <a class="dropdown-item align-items-center nav-link-button" id="{{$survey->id}}"
                        href="javascript:void(0);"
                        onclick="showModalSurvey({{$survey->id}}); return false;"
                        data-toggle="dropdown"><i class="bx bxs-edit"></i>{{strip_tags(mb_strtoupper($survey->name))}}</a>
                    </li>
                @endforeach
            </ul>
        </li>

        @section('survey-modal')
        <!-- SURVEY-MODAL -->
<?php 
        foreach ( $surveys as $index=>$survey){
            $question = App\Model\SurveyQuestions::leftJoin('survey','survey_questions.survey_id','=','survey.id')
                                        ->select('survey_questions.*', 'survey.name', 'survey.description')
                                        ->where('survey_id', $survey->id)
                                        ->orderBy('created_at', 'asc')->get();
?>

            
                
                <div class="modal fade text-left modal-borderless" id="survey_modal_{{$survey->id}}" tabindex="-1" role="dialog" aria-labelledby="survey_modal_{{$survey->id}}" aria-hidden="true">
                    <div class="modal-dialog modal-dialog-scrollable" role="document">
                    <div class="modal-content">
                        <div class="modal-header">
                        <h3 class="modal-title"><?= htmlspecialchars_decode($question[0]->name) ?></h3>
                        </div>
                        <div class="modal-body">
                            <form class="needs-validation" action="#" id="formSurvey_{{$survey->id}}">
                            <input type="hidden" name="survey_id" value="<?= $question[0]->survey_id ?>">
                            <p class="text-center mb-1">
                                <?= htmlspecialchars_decode($question[0]->description) ?>
                            </p>


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
                        <div class="modal-footer">
                        <button type="button" class="btn btn-primary ml-1" onclick="sendAswerModal({{$survey->id}});">
                            <i class="bx bx-check d-block d-sm-none"></i>
                            <span class="d-none d-sm-block">Enviar questionário</span>
                        </button>
                        </div>
                    </div>
                    </div>
                </div>
                
                
            

<?php } ?>

        <!-- SURVEY-MODAL -->
        
        <script>
            function sendAswerModal(idModal) {

                //$('#survey_modal_'+idModal).modal('toggle');
                //let form = $(".needs-validation");
                let form = $("#formSurvey_"+idModal);
                if (form[0].checkValidity() === true) {
                    
                    block();
                    $.ajax({
                        type: "POST",
                        url: "/misc/survey/user",
                        data: $("#formSurvey_"+idModal).serialize(),
                        success: function (response) {
                            unblock();
                            $('#survey_modal_'+idModal).modal('toggle');
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

            function showModalSurvey(idModal) {
                $('#survey_modal_'+idModal).modal('toggle');
            }
            

            
            
        </script>
        @endsection
        
        
<?php } ?>