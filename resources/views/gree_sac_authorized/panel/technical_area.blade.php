@extends('gree_sac_authorized.panel.layout')

@section('content')
<div class="row js-appear-enabled animated fadeIn" data-toggle="appear">
    <div class="col-12 col-xl-12">
        <div class="block">
            <div class="block-content block-content-full">
                Modelo padrão de relatório de análise técnica: <a href="{{ Request::root() }}/area_tecnica/report_tech.pdf" target="_blank">Baixe aqui</a>
            </div>
        </div>
    </div>
</div>
<div class="row js-appear-enabled animated fadeIn" data-toggle="appear">
    <div class="col-12 col-xl-12">
        <div class="block">
            <div class="block-content block-content-full">
                <form action="/autorizada/area-tecnica" method="get">
                    <div class="row">
                        <div class="col-8">
                            <label for="model">Modelo</label>
                            <input type="text" class="form-control" value="{{ Session::get('filter_model') }}" id="model" name="model" placeholder="Ex: GWH09TB-D3DNA1CI">
                            <div class="form-text text-muted">Digite o modelo que deseja pesquisar.</div>
                        </div>
                        <div class="col-4" style="display: flex;flex-direction: column;justify-content: center;">
                            <button class="btn btn-primary" type="submit">Buscar</button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>


    

<div class="row js-appear-enabled animated fadeIn" data-toggle="appear">
    <div class="col-md-12">
        <div class="block block-header-default">
            <div class="block-header">
                <h3 class="block-title">Documentos Técnicos</h3>
            </div>
            <div class="block-content">

            <div class="p-10 bg-white push">
                <ul class="nav nav-pills">

                    @if (!empty($s3_files['back']['name']) )

                        <li class="nav-item">
                            <a class="nav-link link-load" href="?s3_prefix={{$s3_files['back']['name']}}&s3_level={{$s3_files['back']['level']}}">
                                <i class="si si-action-undo mr-5"></i> Voltar
                                
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link link-load active" href="#" data-category-link="{{$s3_files['local_folder']}}">
                                <i class="fa fa-fw fa-folder-open-o mr-5"></i> {{$s3_files['local_folder']}}
                                {{$s3_files['back']['local_folder'] }}
                            </a>
                        </li>
                        
                        
                    @endif
                    

                    @if (count($s3_files['folders'])>0 )
                        @foreach ($s3_files['folders'] as $folder )
                            <li class="nav-item">
                                <a class="nav-link link-load" href="?s3_prefix={{$s3_files['prefix']}}&s3_folder={{$folder['name']}}&s3_level={{$folder['level']}}">
                                    <i class="fa fa-fw fa-folder mr-5"></i> {{$folder['name']}}
                                </a>
                            </li>
                        @endforeach
                    @endif
                    
                </ul>
            </div>
            
                <div class="row">
                    
                    @if (count($s3_files['files'])>0 )
                        @foreach ($s3_files['files'] as $files )
                            <div class="col-12 col-md-2">
                                <a class="block block-link-shadow text-center" href="{{$files['link']}}">
                                    <div class="block-content">
                                        <p class="mt-5">
                                            <i class="si si-doc"></i>
                                        </p>
                                        <p class="font-w600">{{$files['basename']}}</p>
                                    </div>
                                </a>
                            </div>
                        @endforeach
                    @else
                        <div class="block-content" style="display: flex;justify-content: center;padding: 30px;font-size: 18px;">
                        Nenhum Arquivo Encontrado
                        </div>
                    @endif

                </div>
            </div>
        </div>
    </div>
</div>

<div class="row js-appear-enabled animated fadeIn" data-toggle="appear">
    <div class="col-12 col-xl-12">
        <div class="block">
            <div class="block-header block-header-default">
                <h3 class="block-title">Área Técnica</h3>
                <div class="block-options">
                    <button type="button" style="display: none" class="btn-block-option" data-toggle="block-option" data-action="state_toggle">
                        <i class="si si-refresh"></i>
                    </button>
                </div>
            </div>
            <div class="block-content block-content-full">
                <div class="table-responsive">
                <table class="table table-bordered table-striped table-vcenter js-dataTable-full">
                    <thead>
                        <tr>
                            <th>Modelo</th>
                            <th>Vista explodida</th>
							<th>Descrição de peças</th>
                            <th>Circuito elétrico</th>
                            <th>Manual</th>
                            <th>Ficha técnica</th>
                            
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($product as $key)
                        <tr>
                            <td>{{ $key->model }}</td>
                            <td>@if ($key->exploded_view)<a href="{{ $key->exploded_view }}" target="_blank">Visualizar</a>@else -- @endif</td>
							<td>@if ($key->parts)<a href="{{ $key->parts }}" target="_blank">Visualizar</a>@else -- @endif</td>
                            <td>@if ($key->electric_circuit)<a href="{{ $key->electric_circuit }}" target="_blank">Visualizar</a>@else -- @endif</td>
                            <td>@if ($key->manual)<a href="{{ $key->manual }}" target="_blank">Visualizar</a>@else -- @endif</td>
                            <td>@if ($key->datasheet)<a href="{{ $key->datasheet }}" target="_blank">Visualizar</a>@else -- @endif</td>
                        </tr>   
                        @endforeach         
                    </tbody>
                </table>
                <nav aria-label="Page navigation">
                    <ul class="pagination justify-content-end">
                        <?= $product->appends(['model' => Session::get('filter_model')])->links(); ?>
                    </ul>
                </nav>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
    $(document).ready(function () {

        $("#navTec").addClass('active');

        
        $(".link-load").click(function (e) { 
            
            Codebase.loader('show', 'bg-gd-sea');
        });
        

    });
</script>
@endsection