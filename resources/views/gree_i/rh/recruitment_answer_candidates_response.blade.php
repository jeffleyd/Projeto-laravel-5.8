@extends('gree_i.layout')

@section('content')
<div class="content-overlay"></div>
    <div class="content-wrapper">
        <div class="content-header row">
            <div class="content-header-left col-12 mb-2 mt-1">
              <div class="row breadcrumbs-top">
                <div class="col-12">
                  <h5 class="content-header-title float-left pr-1 mb-0">Candidato</h5>
                  <div class="breadcrumb-wrapper col-12">
                    Visualizando resposta
                  </div>
                </div>
              </div>
            </div>
          </div>
        <div class="content-header row">
        </div>
        <div class="content-body">
            <section class="request-payment">
                <div class="card">
                    <div class="card-header">
                      <h4 class="card-title text-center"><?= $title ?></h4>
                    </div>
                    <div class="card-content">
                      <div class="card-body">
                         
                        @foreach ($questions as $index => $key)               
                          <div class="row control-group">
                            <div class="col-12">
                              <p class="text-left" style="color:#5a8dee;"><b>Questão <?= $index + 1 ?></b></p>
                            </div>
                            <div class="col-12">
                              <div class="row">
                                <div class="col-12">
                                  <p class="text-left" style="color: #50575f;"><?= $key->title ?></p>
                                </div>  
                              </div>  
                              @foreach ($key->recruitment_test_questions_answer as $i => $answer)
                                <div class="row" style="margin-left: 2px;margin-bottom: -10px;">
                                  <div class="col-md-6 col-12">
                                    <p class="text-left"
                                      @if($answer->is_correct == 1) 
                                          style="background-color: #c9f5df;" 
                                      @endif
                                      @if($response->where('answer_option_id', $answer->id)->first())
                                        @if($answer->is_correct == 1) 
                                          style="background-color: #c9f5df;" 
                                        @else
                                          style="background-color: #f1c0c0;"
                                        @endif
                                      @endif
                                    >
                                      <span style="color: #50575f;"><?= $arr_alphabet[$i] ?>.</span> <?= $answer->description ?>
                                      @if($response->where('answer_option_id', $answer->id)->first())
                                        @if($answer->is_correct == 1)
                                          <i class="bx bx-check-double" style="color: #05c767;font-size: 1.4rem; position: relative; top: 4px;"></i>
                                        @else  
                                          <i class="bx bx-x" style="color: #ff0f10;font-size: 1.4rem; position: relative; top: 4px;"></i>
                                        @endif                                        
                                      @endif
                                    </p>
                                  </div>  
                                </div>  
                              @endforeach  
                            </div>
                          </div><br>
                        @endforeach
                      </div>
                    </div>
                </div>
            </section>
        </div>
    </div>
    <script>
        $(document).ready(function () {

          setInterval(() => {
            $("#mAdmin").addClass('sidebar-group-active active');
            $("#msurvey").addClass('active');
          }, 100);
        });
    </script>
@endsection