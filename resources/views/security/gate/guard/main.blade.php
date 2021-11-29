@extends('security.gate.guard.layout')

@section('page-css')
@endsection

@section('breadcrumbs')
    <div class="col-12">
        ESCOLHA UMA OPÇÃO
    </div>
@endsection
@section('content')
    <div class="row ml-4 mr-4">
        <div class="col-4">
            <div style="display: flex;justify-content: center;">
                <div class="legendBoll bg-success"></div>
                <span style="font-size: 9px;">Liberado</span>
            </div>
        </div>
        <div class="col-4">
            <div style="display: flex;justify-content: center;">
                <div class="legendBoll bg-warning"></div>
                <span style="font-size: 9px;">Renstrição</span>
            </div>
        </div>
        <div class="col-4">
            <div style="display: flex;justify-content: center;">
                <div class="legendBoll bg-danger"></div>
                <span style="font-size: 9px;">Bloqueado</span>
            </div>
        </div>
    </div>
    <div class="row ml-4 mr-4" style="margin-top: 20px">
        <div class="col-12 alert-vigilant">
            1. Você só poderá liberar entrada/saída da sua <b>portaria.</b>
            <br>2. Seu horário de expediente é: <b>{{date('H:i', strtotime(\Session::get('security_guard_data')->begin_hour_work))}}</b> às <b>{{date('H:i', strtotime(\Session::get('security_guard_data')->final_hour_work))}}</b>
        </div>
    </div>

    <div class="row ml-4 mr-4" style="margin-top: 35px">
        <div class="col-6">
            <div class="card ripple" onclick="window.open('/controle/portaria/paginas/visita', '_self')">
                <div class="card-body text-center">
                    <img src="/elite/assets/security/svg/man.svg" height="70">
                    <p class="card-text card-title">
                        VISITANTE/P.SERVIÇO
                    </p>
                </div>
            </div>
        </div>
        <div class="col-6">
            <div class="card ripple" onclick="window.open('/controle/portaria/paginas/transporte-de-carga', '_self')">
                <div class="card-body text-center">
                    <img src="/elite/assets/security/svg/truck.svg" height="70">
                    <p class="card-text card-title">
                        TRANSPORTE DE CARGA
                    </p>
                </div>
            </div>
        </div>
    </div>
    <div class="row ml-4 mr-4">
        <div class="col-6">
            <div class="card ripple" onclick="window.open('/controle/portaria/paginas/funcionarios', '_self')">
                <div class="card-body text-center">
                    <img src="/elite/assets/security/svg/identity-card.svg" height="70">
                    <p class="card-text card-title">
                        FUNCIONÁRIOS
                    </p>
                </div>
            </div>
        </div>
        <div class="col-6">
            <div class="card ripple" onclick="window.open('/controle/portaria/paginas/veiculos', '_self')">
                <div class="card-body text-center">
                    <img src="/elite/assets/security/svg/car.svg" height="70">
                    <p class="card-text card-title">
                        VEÍCULOS
                    </p>
                </div>
            </div>
        </div>
    </div>
@endsection


@section('page-scripts')
@endsection
