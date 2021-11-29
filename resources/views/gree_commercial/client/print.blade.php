@php
    function findCollect($id = null, $data, $type, $column = '') {
        if ($type == 1) {
            foreach ($data as $key) {
                if ($key['product_sales_id'] == $id)
                    return true;
            }
            return false;
        } else if ($type == 2) {
            if ($data[$column] != $id)
                return true;
            else
                return false;
        }
    }
@endphp

<style>
    .td-title {
        background-color: #f6f6f6;
        text-transform: capitalize !important;
    }
    .td-text-white {
        color: white;
    }
    .td-color-black {
        background-color: #000;
    }
    .td-color-red {
        background-color: rgb(145, 0, 0);
    }
    .td-bold {
        font-weight: 600;
        text-transform: capitalize !important;
    }
    .td-text-center {
        text-align: center;
    }
    .td-font-11 {
        font-size: 6px;
        text-transform: uppercase;
    }
    .td-font-13 {
        font-size: 8px;
        text-transform: uppercase;
    }
    .td-font-14 {
        font-size: 8px;
        text-transform: uppercase;
    }
    .td-font-17 {
        font-size: 13px;
        text-transform: uppercase;
    }
    .table>tbody>tr>td {
        padding: 4px;
        vertical-align: inherit;
        border: 1px solid #ddd;
    }
    .table-bordered > tbody > tr > td {
        border: 1px solid #bbb;
    }
    table {
        font-family: Arial, Helvetica, sans-serif;
        border-collapse: collapse;
        border-spacing: 0;
    }
    .spacer-10 {
        margin-top: 10px;
    }
    .spacer-20 {
        margin-top: 20px;
    }
    .spacer-30 {
        margin-top: 30px;
    }
    .spacer-50 {
        margin-top: 50px;
    }

    .row {
        width: 800px;
        margin: auto;
        zoom: 1.5;
    }

    .checkbtn {
        position: relative;
        bottom: 4px;
    }

    @media print {
        .row {
            zoom: 1;
        }
    }
</style>
<div class="row" style="width: 800px; margin: auto">
    <div class="col-sm-12">
        {{-- são 7 linhas --}}
        <table class="table table-bordered">
            <tbody>
            <tr>
                <td rowspan="4" style="text-align: center;">
                    <img src="/media/logo.png" height="30" alt="" style="padding: 2px;">
                </td>
                <td colspan="8" rowspan="3" class="td-text-center td-font-17 td-bold">
                    FICHA CADASTRAL - COMERCIAL
                </td>
            </tr>
            <tr>
                <td colspan="2" class="td-font-13">
                    <b>ID:</b> {{$header->where('command', 'client_id_rev')->first()->value}}
                </td>
            </tr>
            <tr>
                <td colspan="2" class="td-font-13">
                    <b>REV:</b> {{$header->where('command', 'client_date_rev')->first()->value}}
                </td>
            </tr>
            <tr>
                <td colspan="8" class="td-font-13 td-text-white td-text-center td-color-red td-bold">
                    Em atendimento ao Procedimento PCM-{{$header->where('command', 'client_pcm_rev')->first()->value}}
                </td>
                <td class="td-font-13">
                    <b>FL:</b> {{$header->where('command', 'client_qtd_paper')->first()->value}}
                </td>
                <td class="td-font-13">
                    <b>Rev:</b> {{$header->where('command', 'client_number_rev')->first()->value}}
                </td>
            </tr>
            <tr>
                <td class="td-title td-font-13 td-bold td-text-center">
                    Elaborador:
                </td>
                <td colspan="4" class="td-font-13 td-text-center">
                    {{$header->where('command', 'client_user_creater')->first()->value}}
                </td>
                <td class="td-title td-font-13 td-bold td-text-center">
                    Verificador:
                </td>
                <td colspan="2" class="td-font-13 td-text-center">
                    {{$header->where('command', 'client_user_verify')->first()->value}}
                </td>
                <td class="td-title td-font-13 td-bold td-text-center">
                    Aprovador:
                </td>
                <td colspan="2" class="td-font-13 td-text-center">
                    {{$header->where('command', 'client_user_approval')->first()->value}}
                </td>
            </tr>
            <tr>
                <td colspan="12" class="td-text-center" >
                    <div class="inline-labels">
                        <label style="margin: 0px 125px 0px 0px;"><input type="radio" data-is-checked="1"  name="is_active" value="0" @if($client->client_version->count() == 0)checked="" @elseif ($client->client_version->last()->version == 1)checked=""  @endif><span></span> <span class="td-font-11 checkbtn">NOVO CLIENTE</span></label>
                        <label style="margin: 0px 125px 0px 0px;"><input type="radio" data-is-checked="0"  name="is_active" value="1" @if($client->client_version->count() != 0) @if ($client->client_version->last()->version != 1) checked="" @endif @endif><span></span>  <span class="td-font-11 checkbtn">ATUALIZAÇÃO</span></label>
                    </div>
                </td>
            </tr>
            <tr>
                <td class="td-title td-font-14 td-bold">
                    Razão Social / Nome:
                </td>
                <td class="td-font-14" colspan="11">
                    {{$client->company_name}}
                </td>
            </tr>
            <tr>
                <td class="td-title td-font-14 td-bold">
                    Nome Fantasia:
                </td>
                <td class="td-font-14" colspan="5">
                    {{$client->fantasy_name}}
                </td>
                <td class="td-title td-font-14 td-bold">
                    Grupo:
                </td>
                <td class="td-font-14" colspan="5">
                    @if ($client->client_group->count() > 0)
                        {{$client->client_group->first()->name}}
                    @endif
                </td>
            </tr>
            <tr>
                <td class="td-title td-font-14 td-bold">
                    CNPJ / RG nº:
                </td>
                <td class="td-font-14 td-text-center" colspan="5">
                    {{$client->identity}}
                </td>
                <td class="td-title td-font-14 td-bold">
                    I.E nº:
                </td>
                <td class="td-font-14 td-text-center" colspan="2">
                    {{$client->state_registration}}
                </td>
                <td class="td-title td-font-14 td-bold">
                    I.M nº:
                </td>
                <td class="td-font-14 td-text-center" colspan="2">
                    {{$client->municipal_registration}}
                </td>
            </tr>
            <tr>
                <td class="td-title td-font-14 td-bold">
                    Endereço:
                </td>
                <td class="td-font-14 td-text-center" colspan="4">
                    {{$client->address}}
                </td>
                <td class="td-title td-font-14 td-bold">
                    Cidade:
                </td>
                <td class="td-font-14 td-text-center">
                    {{$client->city}}
                </td>
                <td class="td-title td-font-14 td-bold">
                    Estado:
                </td>
                <td class="td-font-14 td-text-center">
                    {{$client->state}}
                </td>
                <td class="td-title td-font-14 td-bold">
                    CEP:
                </td>
                <td class="td-font-14 td-text-center">
                    {{$client->zipcode}}
                </td>
            </tr>
            <tr>
                <td class="td-title td-font-14 td-bold" colspan="2">
                    Código e Descrição da Atividade Econômica Principal:
                </td>
                <td class="td-font-11" colspan="4">
                    {{$client->code_description_ativity}}
                </td>
                <td class="td-title td-font-14 td-bold">
                    Insc. Suframa:
                </td>
                <td class="td-font-14 td-text-center" colspan="5">
                    {{$client->suframa_registration}}
                </td>
            </tr>
            <tr>
                <td class="td-title td-font-14 td-bold" colspan="2">
                    Regime de Tributação:
                </td>
                <td class="td-font-11 td-text-center" colspan="10">
                    <div class="inline-labels">
                        <label style="margin: 0px 20px 0px 0px;"><input type="radio" data-is-checked="1" name="tribute" value="1" @if ($client->tax_regime == 1) checked="" @endif><span></span> <span class="td-font-11 checkbtn">LUCRO REAL</span></label>
                        <label style="margin: 0px 20px 0px 0px;"><input type="radio" data-is-checked="0" name="tribute" value="2" @if ($client->tax_regime == 2) checked="" @endif><span></span>  <span class="td-font-11 checkbtn">RESUMIDO</span></label>
                        <label style="margin: 0px 20px 0px 0px;"><input type="radio" data-is-checked="0" name="tribute" value="3" @if ($client->tax_regime == 3) checked="" @endif><span></span>  <span class="td-font-11 checkbtn">SIMPLES</span></label>
                        <label style="margin: 0px 20px 0px 0px;"><input type="radio" data-is-checked="0" name="tribute" value="3" @if ($client->tax_regime == 4) checked="" @endif><span></span>  <span class="td-font-11 checkbtn">PESSOA FÍSICA</span></label>
                    </div>
                </td>
            </tr>
            <tr>
                <td class="td-title td-font-14 td-bold" colspan="2">
                    Regime especial ou ICMS por ST:
                </td>
                <td class="td-font-14 td-text-center" colspan="10">
                    {{$client->especial_regime_icms_per_st}}
                </td>
            </tr>
            <tr>
                <td class="td-title td-font-14 td-bold td-text-center" colspan="6">
                    Local de faturamento (Cobrança)
                </td>
                <td class="td-title td-font-14 td-bold td-text-center" colspan="6">
                    Local de entrega dos produtos (CD)
                </td>
            </tr>
            <tr>
                <td class="td-title td-font-14 td-bold">
                    CNPJ / RG nº:
                </td>
                <td class="td-font-14" colspan="5">
                    {{$client->billing_location_identity}}
                </td>
                <td class="td-font-14" colspan="6">
                    {{$client->delivery_location_identity}}
                </td>
            </tr>
            <tr>
                <td class="td-title td-font-14 td-bold">
                    I.E nº:
                </td>
                <td class="td-font-14" colspan="5">
                    {{$client->billing_location_state_registration}}
                </td>
                <td class="td-font-14" colspan="6">
                    {{$client->delivery_location_state_registration}}
                </td>
            </tr>
            <tr>
                <td class="td-title td-font-14 td-bold">
                    Endereço:
                </td>
                <td class="td-font-14" colspan="5">
                    {{$client->billing_location_address}}
                </td>
                <td class="td-font-14" colspan="6">
                    {{$client->delivery_location_address}}
                </td>
            </tr>
            <tr>
                <td class="td-title td-font-14 td-bold">
                    Cidade/UF:
                </td>
                <td class="td-font-14" colspan="5">
                    {{$client->billing_location_city_state}}
                </td>
                <td class="td-font-14" colspan="6">
                    {{$client->delivery_location_city_state}}
                </td>
            </tr>
            <tr>
                <td class="td-title td-font-14 td-bold td-text-center" rowspan="5">
                    Pessoa Contato
                </td>
                <td class="td-title td-font-14 td-bold td-text-center" colspan="4">
                    COMPRAS
                </td>
                <td class="td-title td-font-14 td-bold td-text-center" colspan="4">
                    FINANCEIRO
                </td>
                <td class="td-title td-font-14 td-bold td-text-center" colspan="4">
                    LOGÍSTICA
                </td>
            </tr>
            <tr>
                <td style="width: 40px" class="td-title td-font-14 td-bold" colspan="1">
                    Nome-1
                </td>
                <td class="td-font-11" colspan="3">
                    @if ($client->client_peoples_contact->count() > 0)
                        @php ($contact = $client->client_peoples_contact->where('type_contact', 1)->first()) @endphp
                        @if ($contact)
                            {{$contact->name}}
                        @endif
                    @endif
                </td>
                <td style="width: 40px" class="td-title td-font-14 td-bold" colspan="1">
                    Nome-2
                </td>
                <td class="td-font-11" colspan="3">
                    @if ($client->client_peoples_contact->count() > 0)
                        @php ($contact = $client->client_peoples_contact->where('type_contact', 2)->first()) @endphp
                        @if ($contact)
                            {{$contact->name}}
                        @endif
                    @endif
                </td>
                <td style="width: 40px" class="td-title td-font-14 td-bold" colspan="1">
                    Nome-3
                </td>
                <td class="td-font-11" colspan="2">
                    @if ($client->client_peoples_contact->count() > 0)
                        @php ($contact = $client->client_peoples_contact->where('type_contact', 3)->first()) @endphp
                        @if ($contact)
                            {{$contact->name}}
                        @endif
                    @endif
                </td>
            </tr>
            <tr>
                <td class="td-title td-font-14 td-bold">
                    Cargo
                </td>
                <td class="td-font-11" colspan="3">
                    @if ($client->client_peoples_contact->count() > 0)
                        @php ($contact = $client->client_peoples_contact->where('type_contact', 1)->first()) @endphp
                        @if ($contact)
                            {{$contact->office}}
                        @endif
                    @endif
                </td>
                <td class="td-title td-font-14 td-bold">
                    Cargo
                </td>
                <td class="td-font-11" colspan="3">
                    @if ($client->client_peoples_contact->count() > 0)
                        @php ($contact = $client->client_peoples_contact->where('type_contact', 2)->first()) @endphp
                        @if ($contact)
                            {{$contact->office}}
                        @endif
                    @endif
                </td>
                <td class="td-title td-font-14 td-bold">
                    Cargo
                </td>
                <td class="td-font-11" colspan="3">
                    @if ($client->client_peoples_contact->count() > 0)
                        @php ($contact = $client->client_peoples_contact->where('type_contact', 3)->first()) @endphp
                        @if ($contact)
                            {{$contact->office}}
                        @endif
                    @endif
                </td>
            </tr>
            <tr>
                <td class="td-title td-font-14 td-bold">
                    E-mail:
                </td>
                <td class="td-font-11" colspan="3">
                    @if ($client->client_peoples_contact->count() > 0)
                        @php ($contact = $client->client_peoples_contact->where('type_contact', 1)->first()) @endphp
                        @if ($contact)
                            {{$contact->email}}
                        @endif
                    @endif
                </td>
                <td class="td-title td-font-14 td-bold">
                    E-mail:
                </td>
                <td class="td-font-11" colspan="3">
                    @if ($client->client_peoples_contact->count() > 0)
                        @php ($contact = $client->client_peoples_contact->where('type_contact', 2)->first()) @endphp
                        @if ($contact)
                            {{$contact->email}}
                        @endif
                    @endif
                </td>
                <td class="td-title td-font-14 td-bold">
                    E-mail:
                </td>
                <td class="td-font-11" colspan="3">
                    @if ($client->client_peoples_contact->count() > 0)
                        @php ($contact = $client->client_peoples_contact->where('type_contact', 3)->first()) @endphp
                        @if ($contact)
                            {{$contact->email}}
                        @endif
                    @endif
                </td>
            </tr>
            <tr>
                <td class="td-title td-font-14 td-bold">
                    Fone:
                </td>
                <td class="td-font-11" colspan="3">
                    @if ($client->client_peoples_contact->count() > 0)
                        @php ($contact = $client->client_peoples_contact->where('type_contact', 1)->first()) @endphp
                        @if ($contact)
                            {{$contact->phone}}
                        @endif
                    @endif
                </td>
                <td class="td-title td-font-14 td-bold">
                    Fone:
                </td>
                <td class="td-font-11" colspan="3">
                    @if ($client->client_peoples_contact->count() > 0)
                        @php ($contact = $client->client_peoples_contact->where('type_contact', 2)->first()) @endphp
                        @if ($contact)
                            {{$contact->phone}}
                        @endif
                    @endif
                </td>
                <td class="td-title td-font-14 td-bold">
                    Fone:
                </td>
                <td class="td-font-11" colspan="3">
                    @if ($client->client_peoples_contact->count() > 0)
                        @php ($contact = $client->client_peoples_contact->where('type_contact', 3)->first()) @endphp
                        @if ($contact)
                            {{$contact->phone}}
                        @endif
                    @endif
                </td>
            </tr>
            <tr>
                <td class="td-title td-font-14 td-bold td-text-center" rowspan="5">
                    Nome e CPF do Proprietário ou dos sócios:
                </td>
                <td class="td-title td-font-14 td-bold td-text-center" colspan="6">
                    NOME COMPLETO
                </td>
                <td class="td-title td-font-14 td-bold td-text-center" colspan="4">
                    CPF
                </td>
            </tr>
            <tr>
                <td class="td-font-14" colspan="6">
                    @if ($client->client_owner_and_partner->count() > 0)
                        @php ($contact = $client->client_owner_and_partner->first()) @endphp
                        @if ($contact)
                            {{$contact->name}}
                        @else
                            <div class="spacer-10"></div>
                        @endif
                    @else
                        <div class="spacer-10"></div>
                    @endif
                </td>
                <td class="td-font-14" colspan="5">
                    @if ($client->client_owner_and_partner->count() > 0)
                        @php ($contact = $client->client_owner_and_partner->first()) @endphp
                        @if ($contact)
                            {{$contact->identity}}
                        @else
                            <div class="spacer-10"></div>
                        @endif
                    @else
                        <div class="spacer-10"></div>
                    @endif
                </td>
            </tr>
            <tr>
                <td class="td-font-14" colspan="6">
                    @if ($client->client_owner_and_partner->count() > 0)
                        @php ($contact = $client->client_owner_and_partner->slice(1)->first()) @endphp
                        @if ($contact)
                            {{$contact->name}}
                        @else
                            <div class="spacer-10"></div>
                        @endif
                    @else
                        <div class="spacer-10"></div>
                    @endif
                </td>
                <td class="td-font-14" colspan="5">
                    @if ($client->client_owner_and_partner->count() > 0)
                        @php ($contact = $client->client_owner_and_partner->slice(1)->first()) @endphp
                        @if ($contact)
                            {{$contact->identity}}
                        @else
                            <div class="spacer-10"></div>
                        @endif
                    @else
                        <div class="spacer-10"></div>
                    @endif
                </td>
            </tr>
            <tr>
                <td class="td-font-14" colspan="6">
                    @if ($client->client_owner_and_partner->count() > 0)
                        @php ($contact = $client->client_owner_and_partner->slice(2)->first()) @endphp
                        @if ($contact)
                            {{$contact->name}}
                        @else
                            <div class="spacer-10"></div>
                        @endif
                    @else
                        <div class="spacer-10"></div>
                    @endif
                </td>
                <td class="td-font-14" colspan="5">
                    @if ($client->client_owner_and_partner->count() > 0)
                        @php ($contact = $client->client_owner_and_partner->slice(2)->first()) @endphp
                        @if ($contact)
                            {{$contact->identity}}
                        @else
                            <div class="spacer-10"></div>
                        @endif
                    @else
                        <div class="spacer-10"></div>
                    @endif
                </td>
            </tr>
            <tr>
                <td class="td-font-14" colspan="6">
                    @if ($client->client_owner_and_partner->count() > 0)
                        @php ($contact = $client->client_owner_and_partner->slice(3)->first()) @endphp
                        @if ($contact)
                            {{$contact->name}}
                        @else
                            <div class="spacer-10"></div>
                        @endif
                    @else
                        <div class="spacer-10"></div>
                    @endif
                </td>
                <td class="td-font-14" colspan="5">
                    @if ($client->client_owner_and_partner->count() > 0)
                        @php ($contact = $client->client_owner_and_partner->slice(3)->first()) @endphp
                        @if ($contact)
                            {{$contact->identity}}
                        @else
                            <div class="spacer-10"></div>
                        @endif
                    @else
                        <div class="spacer-10"></div>
                    @endif
                </td>
            </tr>
            <tr>
                <td class="td-title td-font-14 td-bold">
                    Capital Social:
                </td>
                <td class="td-font-14 td-text-center" colspan="4">
                    <b>R$ {{number_format($client->social_capital, 2, ',', '.')}}</b>
                </td>
                <td class="td-title td-font-14 td-bold" colspan="3">
                    Junta Com. (NIRE) nº:
                </td>
                <td class="td-font-14 td-text-center" colspan="4">
                    {{$client->nire_number}}
                </td>
            </tr>
            <tr>
                <td class="td-title td-font-14 td-bold" rowspan="2">
                    Tipo do Cliente:
                </td>
                <td class="td-font-14" colspan="4">
                    <label style="margin: 0px 0px 0px 20px;"><input type="radio" data-is-checked="1" name="type_client" value="0" @if($client->type_client == 1) checked="" @endif><span></span> <span class="td-font-11 checkbtn">VAREJO REGIONAL</span></label>
                    <br><label style="margin: 0px 0px 0px 20px;"><input type="radio" data-is-checked="0" name="type_client" value="1" @if($client->type_client == 2) checked="" @endif><span></span>  <span class="td-font-11 checkbtn">VAREJO REGIONAL (ABERTURA)</span></label>
                </td>
                <td class="td-font-14" colspan="4">
                    <label style="margin: 0px 0px 0px 20px;"><input type="radio" data-is-checked="0" name="type_client" value="0" @if($client->type_client == 7) checked="" @endif><span></span> <span class="td-font-11 checkbtn">E-COMMERCE</span></label>
                    <br><label style="margin: 0px 0px 0px 20px;"><input type="radio" data-is-checked="0" name="type_client" value="1" @if($client->type_client == 3) checked="" @endif><span></span>  <span class="td-font-11 checkbtn">ESPECIALIZADO REGIONAL</span></label>
                </td>
                <td class="td-font-14" colspan="3">
                    <label><input type="radio" data-is-checked="0" name="type_client" value="0" @if($client->type_client == 4) checked="" @endif><span></span> <span class="td-font-11 checkbtn">ESPECIALIZADO NACIONAL</span></label>
                    <br><label><input type="radio" data-is-checked="0" name="type_client" value="1" @if($client->type_client == 8) checked="" @endif><span></span>  <span class="td-font-11 checkbtn">VIP</span></label>
                </td>
            </tr>
            <tr>
                <td class="td-font-14" colspan="4">
                    <label style="margin: 0px 0px 0px 20px;"><input type="radio" data-is-checked="0" name="type_client" value="0" @if($client->type_client == 5) checked="" @endif><span></span> <span class="td-font-11 checkbtn">REFRIGERISTA NACIONAL</span></label>
                    <br><label style="margin: 0px 0px 0px 20px;"><input type="radio" data-is-checked="0" name="type_client" value="0" @if($client->type_client == 6) checked="" @endif><span></span> <span class="td-font-11 checkbtn">VAREJO NACIONAL</span></label>
                </td>
                <td class="td-font-14" colspan="8">
                    <label style="margin: 0px 0px 0px 20px;"><input type="radio" data-is-checked="0" name="type_client" value="0" @if($client->type_client == 9) checked="" @endif><span></span> <span class="td-font-11 checkbtn">COLABORADOR / PARCEIRO</span></label>
                </td>
            </tr>
            <tr>
                @php $client_on_product_sales = collect($client['client_on_product_sales']); @endphp
                <td class="td-title td-font-14 td-bold">
                    Produtos vendidos:
                </td>
                <td class="td-font-14" colspan="4">
                    <label style="margin: 0px 40px 0px 20px;"><input type="checkbox" data-is-checked="1" name="product_sales" value="0" @if(findCollect(1, $client_on_product_sales, 1)) checked="" @endif><span></span> <span class="td-font-11 checkbtn">AR CONDICIONADO - USO DOMÉSTICO</span></label>
                    <br><label style="margin: 0px 50px 0px 20px;"><input type="checkbox" data-is-checked="0" name="product_sales" value="1" @if(findCollect(2, $client_on_product_sales, 1))  checked="" @endif><span></span>  <span class="td-font-11 checkbtn">ELETRODMÉSTICO</span></label>
                </td>
                <td class="td-font-14" colspan="4">
                    <label style="margin: 0px 50px 0px 20px;"><input type="checkbox" data-is-checked="1" name="product_sales" value="0" @if(findCollect(3, $client_on_product_sales, 1))  checked="" @endif><span></span> <span class="td-font-11 checkbtn">MAQUINA CHILLER</span></label>
                    <br><label style="margin: 0px 50px 0px 20px;"><input type="checkbox" data-is-checked="0" name="product_sales" value="1" @if(findCollect(4, $client_on_product_sales, 1))  checked="" @endif><span></span>  <span class="td-font-11 checkbtn">NÃO É REVENDA</span></label>
                </td>
                <td class="td-font-14" colspan="3">
                    <label style="margin: 0px 50px 0px 20px;"><input type="checkbox" data-is-checked="0" name="product_sales" value="0" @if(findCollect(5, $client_on_product_sales, 1))  checked="" @endif><span></span> <span class="td-font-11 checkbtn">VRF</span></label>
                    <br><label style="margin: 0px 50px 0px 20px;"><input type="checkbox" data-is-checked="0" name="product_sales" value="1" @if(findCollect(6, $client_on_product_sales, 1))  checked="" @endif><span></span>  <span class="td-font-11 checkbtn">OUTRO</span></label>
                </td>
            </tr>
            <tr>
                <td colspan="12" class="td-font-14 td-text-white td-text-center td-color-black td-bold">
                    Informações Básicas do Cliente
                </td>
            </tr>
            <tr>
                <td class="td-title td-font-14 td-text-center" colspan="3">
                    Quantas filiais (loja e CD e sede) no Brasil?
                </td>
                <td class="td-font-14" colspan="9">
                    {{$client->quantity_filial_cds}}
                </td>
            </tr>
            <tr>
                <td class="td-font-14 td-text-center" colspan="12" style="text-transform: none">
                    <b>ATENÇÃO! Em caso de confirmação positiva, e se a filial tiver operações com a Gree, deve-se preencher outra ficha para a filial.</b>
                </td>
            </tr>
            <tr>
                <td class="td-title td-font-14 td-text-center" colspan="2">
                    Qual Faturamento geral nos últimos anos?
                </td>
                <td class="td-title td-font-14 td-text-center" colspan="3">
                    Quantas unidades de ar.cond foram vendidas nos últimos anos?
                </td>
                <td class="td-title td-font-14 td-text-center" colspan="3">
                    Qual Faturamento de ar.cond nos últimos anos?
                </td>
                <td class="td-title td-font-14 td-text-center" colspan="2">
                    Qual o volume de compra?
                </td>
                <td class="td-title td-font-14 td-text-center" colspan="2">
                    A empresa trabalha com IMPORTAÇÃO direta?
                </td>
            </tr>
            <tr>
                <td class="td-font-14 td-text-center" colspan="2">
                    {{$client->billing_last_years}}
                </td>
                <td class="td-font-14 td-text-center" colspan="3">
                    {{$client->units_air_sold_last_years}}
                </td>
                <td class="td-font-14 td-text-center" colspan="3">
                    {{$client->billing_air_last_years}}
                </td>
                <td class="td-font-14 td-text-center" colspan="2">
                    {{$client->purchase_volume}}
                </td>
                <td class="td-font-14 td-text-center" colspan="2">
                    <label><input type="radio" data-is-checked="1" name="import" value="0" @if($client->works_import == 1) checked="" @endif><span></span> <span class="td-font-11 checkbtn">SIM</span></label>
                    <label style="margin: 0px 10px 0px 0px;"><input type="radio" data-is-checked="0" name="import" value="1" @if($client->works_import == 0) checked="" @endif><span></span>  <span class="td-font-11 checkbtn">NÃO</span></label>
                </td>
            </tr>
            <tr>
                <td colspan="12" class="td-font-14 td-text-white td-text-center td-color-black td-bold">
                    Check list de documentos OBRIGATÓRIOS - Anexos
                </td>
            </tr>
            <tr>
                @php
                    $client_documents = collect($client['client_documents']);
                @endphp
                <td style="padding: 0px 55px" class="td-font-14" colspan="12">
                    <div style="float: left; margin: 5px 0;">
                        <label style="margin-right: 10px"><input type="checkbox" data-is-checked="1" name="document" value="0" @if ($client_documents->count() > 0) @if(findCollect(0, $client_documents, 2, 'contract_social')) checked="" @endif @endif><span></span> <span class="td-font-11 checkbtn">CONTRATO SOCIAL E ALTERAÇÕES CONTRATUAIS</span></label>
                        <br><label style="margin-right: 10px"><input type="checkbox" data-is-checked="1" name="document" value="1" @if ($client_documents->count() > 0) @if(findCollect(0, $client_documents, 2, 'balance_equity_dre_flow')) checked="" @endif @endif><span></span>  <span class="td-font-11 checkbtn">BALANÇO PATRIMONIAL/DRE/FLUXO DE CAIXA</span></label>
                    </div>

                    <div style="float: left; margin: 5px 0;">
                        <label style="margin-right: 10px"><input type="checkbox" data-is-checked="0" name="document" value="0" @if ($client_documents->count() > 0) @if(findCollect(null, $client_documents, 2, 'declaration_regime')) checked="" @endif @endif><span></span> <span class="td-font-11 checkbtn">DECLARAÇÃO DE REGIME DE TRIBUTAÇÃO</span></label>
                        <br><label style="margin-right: 10px"><input type="checkbox" data-is-checked="0" name="document" value="1" @if ($client_documents->count() > 0) @if(findCollect(null, $client_documents, 2, 'card_cnpj')) checked="" @endif @endif><span></span>  <span class="td-font-11">CARTÃO CNPJ (Receita Federal)</span></label>
                    </div>

                    <div style="float: left; margin: 5px 0;">
                        <label style="margin-right: 10px"><input type="checkbox" data-is-checked="1" name="document" value="0" @if ($client_documents->count() > 0) @if(findCollect(null, $client_documents, 2, 'card_ie')) checked="" @endif @endif><span></span> <span class="td-font-11 checkbtn">CARTÃO DE INSCRIÇÃO ESTADUAL</span></label>
                        <br><label style="margin-right: 10px"><input type="checkbox" data-is-checked="0" name="document" value="1" @if ($client_documents->count() > 0) @if(findCollect(null, $client_documents, 2, 'apresentation_commercial')) checked="" @endif @endif><span></span>  <span class="td-font-11 checkbtn">APRESENTAÇÃO COMERCIAL</span></label>
                    </div>

                    <div style="float: left; margin: 5px 0;">
                        <label style="margin-right: 10px"><input type="checkbox" data-is-checked="1" name="document" value="0" @if ($client_documents->count() > 0) @if(findCollect(null, $client_documents, 2, 'proxy_representation_legal')) checked="" @endif @endif><span></span> <span class="td-font-11 checkbtn">PROCURAÇÃO DOS REPRESENTANTES LEGAIS</span></label>
                    </div>

                </td>
            </tr>
            <tr>
                <td class="td-title td-font-14 td-text-center" colspan="4">
                    Responsável pela Verificação
                </td>
                <td class="td-title td-font-14 td-text-center" colspan="4">
                    Data de Verificação
                </td>
                <td class="td-title td-font-14 td-text-center" colspan="4">
                    Assinatura do Verificador
                </td>
            </tr>
            <tr>
                <td class="td-font-14 td-text-center" colspan="4">
                    <div class="spacer-30"></div>
                </td>
                <td class="td-font-14 td-text-center" colspan="4">
                    <div class="spacer-30"></div>
                </td>
                <td class="td-font-14 td-text-center" colspan="4">
                    <div class="spacer-30"></div>
                </td>
            </tr>
            <tr>
                <td colspan="12" class="td-font-14 td-text-white td-text-center td-color-black td-bold">
                    Aprovações Gree Manaus
                </td>
            </tr>
            <tr>
                <td class="td-title td-font-14 td-text-center" colspan="3">
                    Representante
                </td>
                <td class="td-title td-font-14 td-text-center" colspan="3">
                    Depto. Comercial
                </td>
                <td class="td-title td-font-14 td-text-center" colspan="3">
                    Depto. Financeiro
                </td>
                <td class="td-title td-font-14 td-text-center" colspan="3">
                    Diretor Comercial
                </td>
            </tr>
            <tr>
                <td class="td-font-14 td-text-center" colspan="3">
                    @if ($client->salesman_imdt_approv == 1)
                        @php ($salesman = $client->salesman()->first()) @endphp
                        @if ($salesman)
                            {{$salesman->short_name}}
                        @else
                            <div class="spacer-30"></div>
                        @endif
                    @endif
                </td>
                <td class="td-font-14 td-text-center" colspan="3">
                    @if ($client->commercial_is_approv == 1)
                        @php ($analyze = $client->client_commercial_analyze()->orderBy('id', 'DESC')->first()) @endphp
                        @if ($analyze)
                            @php ($direction = $analyze->user()->first()) @endphp
                            @if ($direction)
                                {{$direction->short_name}} ({{$direction->r_code}})
                            @else
                                <div class="spacer-30"></div>
                            @endif
                        @else
                            <div class="spacer-30"></div>
                        @endif
                    @endif
                </td>
                <td class="td-font-14 td-text-center" colspan="3">
                    @if ($client->financy_approv == 1)
                        @php ($analyze = $client->client_financy_analyze()->orderBy('id', 'DESC')->first()) @endphp
                        @if ($analyze)
                            @php ($direction = $analyze->user()->first()) @endphp
                            @if ($direction)
                                {{$direction->short_name}} ({{$direction->r_code}})
                            @else
                                <div class="spacer-30"></div>
                            @endif
                        @else
                            <div class="spacer-30"></div>
                        @endif
                    @endif
                </td>
                <td class="td-font-14 td-text-center" colspan="3">
                    <div class="spacer-30"></div>
                </td>
            </tr>
            <tr>
                <td colspan="12" class="td-font-13 td-text-white td-text-center td-color-red td-bold">
                    GREE ELECTRICS APPLAINCES DO BRASIL LTDA
                </td>
            </tr>
            <tr>
                <td class="td-font-13 td-text-center td-bold" colspan="12">
                    CNPJ: 03.519.135/0001-56 IE: 06.200.291-0
                    <br />Av. dos Oitis, 6360 - Distrito Industrial II. CEP: 69007-002
                    <br />Telefone: (92) 2123-6900 - Manaus / AM
                </td>
            </tr>
            </tbody>
        </table>
        <div class="spacer-50"></div>
    </div>
</div>
<script src="/admin/app-assets/js/jquery-3.4.1.min.js"></script>
<script src="/js/printThis.js"></script>
<script>
    $(document).ready(function () {

        $("#client").addClass('page-arrow active-page');

        $('input[type="checkbox"], input[type="radio"]').click(function (e) {

            location.reload();
        });
    });
</script>
