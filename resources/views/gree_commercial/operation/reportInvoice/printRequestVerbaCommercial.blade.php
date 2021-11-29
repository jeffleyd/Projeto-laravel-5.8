
<style>
    .td-title {
        background-color: #f6f6f6;
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
    }
    .td-text-center {
        text-align: center;
    }
    .td-font-11 {
        font-size: 6px;
    }
    .td-font-13 {
        font-size: 8px;
    }
    .td-font-14 {
        font-size: 8px;
    }
    .td-font-17 {
        font-size: 13px;
    }
    .td-uppercase {
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
        <table class="table table-bordered" style="width: 800px;">
            <tbody>
            <tr>
                <td rowspan="4" style="text-align: center;">
                    <img src="/media/logo.png" height="30" alt="" style="padding: 2px;">
                </td>
                <td colspan="8" rowspan="3" class="td-title td-text-center td-font-17 td-bold">
                    FORMULÁRIO DE SOLICITAÇÃO - VERBAS COMERCIAIS
                </td>
            </tr>
            <tr>
                <td colspan="3" class="td-font-13">
                    <b>ID:</b> FGM-PCM-013
                </td>
            </tr>
            <tr>
                <td colspan="3" class="td-font-13">
                    <b>DATA:</b> 23/12/2020
                </td>
            </tr>
            <tr>
                <td colspan="9" class="td-font-13 td-text-white td-text-center td-color-red td-bold">
                    Em atendimento ao Procedimento PCM-001
                </td>
                <td class="td-font-13">
                    <b>FL:</b> 01
                </td>
                <td class="td-font-13">
                    <b>Rev:</b> 001
                </td>
            </tr>
            <tr>
                <td class="td-title td-font-13 td-bold td-text-center">
                    Elaborador:
                </td>
                <td colspan="4" class="td-font-13 td-text-center">
                    TAIANE FRANÇA
                </td>
                <td class="td-title td-font-13 td-bold td-text-center">
                    Verificador:
                </td>
                <td colspan="2" class="td-font-13 td-text-center">
                    KELLYANE BRITO
                </td>
                <td class="td-title td-font-13 td-bold td-text-center">
                    Aprovador:
                </td>
                <td colspan="3" class="td-font-13 td-text-center">
                    CHEN JIAN JUN
                </td>
            </tr>
            <tr>
                <td colspan="12" class="td-font-14 td-text-white td-text-center td-color-black td-bold">
                    Preenchimento GREE (Uso interno)
                </td>
            </tr>
            <tr>
                <td class="td-title td-font-14 td-bold" colspan="2">
                    Controle Interno GREE:
                </td>
                <td class="td-font-14 td-bold td-text-center" colspan="2">
                    {{$budget->code}}
                </td>
                <td class="td-title td-font-14 td-bold" colspan="2">
                    Cód do cliente:
                </td>
                <td class="td-font-14 td-bold td-text-center" colspan="2">
                    {{$budget->client_code}}
                </td>
                <td class="td-title td-font-14 td-bold" colspan="2">
                    Data final:
                </td>
                <td class="td-font-14 td-bold td-text-center" colspan="2">
                    @if($budget->date_final) {{date('d/m/Y', strtotime($budget->date_final))}} @endif
                </td>
            </tr>
            <tr>
                <td colspan="12" class="td-font-14 td-text-white td-text-center td-color-black td-bold">
                    Dados do Cliente ou Benceficiário
                </td>
            </tr>
            <tr>
                <td class="td-title td-font-14 td-bold">
                    Razão social:
                </td>
                <td class="td-font-14 td-bold td-uppercase" colspan="5">
                    {{$budget->client_company_name}}
                </td>
                <td class="td-title td-font-14 td-bold">
                    Nome fantasia:
                </td>
                <td class="td-font-14 td-bold td-uppercase" colspan="5">
                    {{$budget->client_fantasy_name}}
                </td>
            </tr>
            <tr>
                <td class="td-title td-font-14 td-bold">
                    CNPJ:
                </td>
                <td class="td-font-14 td-bold" colspan="3">
                    {{$budget->client_identity}}
                </td>
                <td class="td-title td-font-14 td-bold">
                    Inscrição estadual:
                </td>
                <td class="td-font-14 td-bold" colspan="3">
                    {{$budget->client_state_registration}}
                </td>
                <td class="td-title td-font-14 td-bold">
                    Telefone/Contato:
                </td>
                <td class="td-font-14 td-bold" colspan="3">
                    {{$budget->client_peoples_contact_phone}}
                </td>
            </tr>
            <tr>
                <td class="td-title td-font-14 td-bold">
                    Endereço:
                </td>
                <td class="td-font-14 td-bold td-uppercase" colspan="5">
                    {{$budget->client_address}}
                </td>
                <td class="td-title td-font-14 td-bold">
                    Bairro:
                </td>
                <td class="td-font-14 td-bold td-uppercase" colspan="5">
                    {{$budget->client_district}}
                </td>
            </tr>
            <tr>
                <td class="td-title td-font-14 td-bold">
                    CEP:
                </td>
                <td class="td-font-14 td-bold" colspan="4">
                    {{$budget->client_zipcode}}
                </td>
                <td class="td-title td-font-14 td-bold">
                    Cidade:
                </td>
                <td class="td-font-14 td-bold td-uppercase" colspan="4">
                    {{$budget->client_city}}
                </td>
                <td class="td-title td-font-14 td-bold">
                    UF:
                </td>
                <td class="td-font-14 td-bold td-uppercase">
                    {{$budget->client_state}}
                </td>
            </tr>
            <tr>
                <td class="td-title td-font-14 td-bold">
                    Data da solicitação:
                </td>
                <td class="td-font-14 td-bold td-text-center" colspan="2">
                    {{date('d/m/Y', strtotime($budget->created_at))}}
                </td>
                <td class="td-font-14" colspan="9">
                    <div style="display: flex; justify-content: space-around">
                        <span style="position: relative;top: 5px;"><b>Tipo de documento:</b></span>
                        <label>
                            <input type="radio" data-is-checked="0" name="type_document" value="1" @if($budget->type_documents == 1) checked="" @endif>
                            <span class="td-font-11 checkbtn">NF débito</span>
                        </label>

                        <label>
                            <input type="radio" data-is-checked="0" name="type_document" value="1" @if($budget->type_documents == 2) checked="" @endif>
                            <span class="td-font-11 checkbtn">NF devolução</span>
                        </label>

                        <label>
                            <input type="radio" data-is-checked="0" name="type_document" value="1" @if($budget->type_documents == 3) checked="" @endif>
                            <span class="td-font-11 checkbtn">NF Produto</span>
                        </label>

                        <label>
                            <input type="radio" data-is-checked="0" name="type_document" value="1" @if($budget->type_documents == 4) checked="" @endif>
                            <span class="td-font-11 checkbtn">Pedido do cliente</span>
                        </label>
                    </div>
                </td>
            </tr>
            <tr>
                <td colspan="12" class="td-font-14 td-text-white td-text-center td-color-black td-bold">
                    Forma de pagamento da solicitação
                </td>
            </tr>
            <tr style="text-align: center;">
                <td class="td-font-14" colspan="12">
                    <div style="display: flex; justify-content: space-around">
                        <label>
                            <input type="radio" data-is-checked="0" name="type_payment" value="1" @if($budget->type_payment == 1) checked="" @endif>
                            <span class="td-font-11 checkbtn">PRODUTO</span>
                        </label>

                        <label>
                            <input type="radio" data-is-checked="0" name="type_payment" value="1" @if($budget->type_payment == 2) checked="" @endif>
                            <span class="td-font-11 checkbtn">DESCONTO EM DUPLICATA/TÍTULO EM ABERTO</span>
                        </label>

                        <label>
                            <input type="radio" data-is-checked="0" name="type_payment" value="1" @if($budget->type_payment == 3) checked="" @endif>
                            <span class="td-font-11 checkbtn">TRANSAÇÃO BANCÁRIA</span>
                        </label>
                    </div>
                </td>
            </tr>
            <tr>
                <td colspan="12" class="td-font-14 td-text-white td-text-center td-color-black td-bold">
                    Dados da Solicitação para pagamento da verba (Deve ser preenchido independente do tipo do pagamento)
                </td>
            </tr>
            <tr>
                <td class="td-title td-font-14 td-bold" colspan="2">
                    Tipo de Verba:
                </td>
                <td class="td-font-14" colspan="10">
                    <div style="display: flex; justify-content: space-around">
                        <label>
                            <input type="radio" data-is-checked="0" name="type_verba" value="1" @if($budget->type_budget == 1) checked="" @endif>
                            <span class="td-font-11 checkbtn">VPC</span>
                        </label>

                        <label>
                            <input type="radio" data-is-checked="0" name="type_verba" value="1" @if($budget->type_budget == 2) checked="" @endif>
                            <span class="td-font-11 checkbtn">REBATE</span>
                        </label>

                        <label>
                            <input type="radio" data-is-checked="0" name="type_verba" value="1" @if($budget->type_budget == 3) checked="" @endif>
                            <span class="td-font-11 checkbtn">BONIFICAÇÃO</span>
                        </label>

                        <label>
                            <input type="radio" data-is-checked="0" name="type_verba" value="1" @if($budget->type_budget == 4) checked="" @endif>
                            <span class="td-font-11 checkbtn">VERBAS CONTRATUAIS</span>
                        </label>

                        <label>
                            <input type="radio" data-is-checked="0" name="type_verba" value="1" @if($budget->type_budget == 5) checked="" @endif>
                            <span class="td-font-11 checkbtn">DESCONTO</span>
                        </label>
                    </div>
                </td>
            </tr>
            <tr>
                <td class="td-title td-font-14 td-bold td-text-center" colspan="6">
                    Descrição da solicitação:
                </td>
                <td class="td-title td-font-14 td-bold td-text-center">
                    Quantidade
                </td>
                <td class="td-title td-font-14 td-bold td-text-center">
                    Unidade
                </td>
                <td class="td-title td-font-14 td-bold td-text-center" colspan="2">
                    Preço Unit.
                </td>
                <td class="td-title td-font-14 td-bold td-text-center" colspan="2">
                    Subtotal
                </td>
            </tr>
            @php
            $total = 0.00;
            @endphp
            @if($budget->budget_commercial_itens->count())
                @foreach($budget->budget_commercial_itens as $item)
                    @php
                    $total += $item->sub_total;
                    @endphp
                <tr>
                    <td class="td-font-11 td-text-center" colspan="6">
                        {{$item->description}}
                    </td>
                    <td class="td-font-11 td-text-center">
                        {{$item->quantity}}
                    </td>
                    <td class="td-font-11 td-text-center">
                        {{$item->unity}}
                    </td>
                    <td class="td-font-11 td-text-center" colspan="2">
                        R$ {{number_format($item->price_unit, 2, ',', '.')}}
                    </td>
                    <td class="td-font-11 td-text-center" colspan="2">
                        R$ {{number_format($item->sub_total, 2, ',', '.')}}
                    </td>
                </tr>
                @endforeach
            @else
                @for($i = 0; $i < 11; $i++)
                    <tr>
                        <td class="td-font-11 td-text-center" colspan="6">
                        </td>
                        <td class="td-font-11 td-text-center">
                            0
                        </td>
                        <td class="td-font-11 td-text-center">
                        </td>
                        <td class="td-font-11 td-text-center" colspan="2">
                        </td>
                        <td class="td-font-11 td-text-center" colspan="2">
                        </td>
                    </tr>
                @endfor
            @endif
            <tr>
                <td class="td-font-11 td-text-center" colspan="6">
                </td>
                <td class="td-title td-font-11 td-text-center td-bold" colspan="4">
                    Valor Total
                </td>
                <td class="td-font-11 td-text-center td-bold" colspan="2">
                    R$ {{number_format($total, 2, ',', '.')}}
                </td>
            </tr>
            <tr>
                <td colspan="12" class="td-font-14 td-text-white td-text-center td-color-black td-bold">
                    Inserir aqui os dados da nota discal para desconto em duplicata
                </td>
            </tr>
            <tr>
                <td class="td-title td-font-14 td-bold td-text-center" colspan="2">
                    Nº Nota fiscal Gree:
                </td>
                <td class="td-title td-font-14 td-bold td-text-center" colspan="2">
                    Nº da parcela:
                </td>
                <td class="td-title td-font-14 td-bold td-text-center" colspan="2">
                    Data do vencimento:
                </td>
                <td class="td-title td-font-14 td-bold td-text-center" colspan="3">
                    Valor da parcela:
                </td>
                <td class="td-title td-font-14 td-bold td-text-center" colspan="3">
                    Valor do desconto:
                </td>
            </tr>
            @if($budget->budget_commercial_duplicates->count())
                @foreach ($budget->budget_commercial_duplicates as $key)
                <tr>
                    <td class="td-font-11 td-text-center" colspan="2">
                        {{$key->nf_number}}-{{$key->nf_serie}}
                    </td>
                    <td class="td-font-11 td-text-center" colspan="2">
                        {{$key->parcel_number}}
                    </td>
                    <td class="td-font-11 td-text-center" colspan="2">
                        {{date('d/m/Y', strtotime($key->due_date))}}
                    </td>
                    <td class="td-font-11 td-text-center" colspan="3">
                        {{number_format($key->parcel_price, 2, ',', '.')}}
                    </td>
                    <td class="td-font-11 td-text-center" colspan="3">
                        {{number_format($key->price_descoint, 2, ',', '.')}}
                    </td>
                </tr>
                @endforeach
            @else
                @for($i = 0; $i < 4; $i++)
                    <tr>
                        <td class="td-font-11 td-text-center" colspan="2">
                            0
                        </td>
                        <td class="td-font-11 td-text-center" colspan="2">
                        </td>
                        <td class="td-font-11 td-text-center" colspan="2">
                        </td>
                        <td class="td-font-11 td-text-center" colspan="3">
                        </td>
                        <td class="td-font-11 td-text-center" colspan="3">
                        </td>
                    </tr>
                @endfor
            @endif
            <tr>
                <td colspan="12" class="td-font-14 td-text-white td-text-center td-color-red td-bold">
                    Inserir aqui os dados para pagamento via transferência bancária
                </td>
            </tr>
            <tr>
                <td class="td-title td-font-14 td-bold" colspan="2">
                    Banco:
                </td>
                <td class="td-font-14" colspan="2">
                    {{$budget->transf_bank}}
                </td>
                <td class="td-title td-font-14 td-bold" colspan="2">
                    Agência:
                </td>
                <td class="td-font-14" colspan="2">
                    {{$budget->transf_agency}}
                </td>
                <td class="td-title td-font-14 td-bold" colspan="2">
                    Conta:
                </td>
                <td class="td-font-14" colspan="2">
                    {{$budget->transf_account}}
                </td>
            </tr>
            <tr>
                <td class="td-title td-font-14 td-bold" colspan="2">
                    Titular da conta:
                </td>
                <td class="td-font-14" colspan="4">
                    {{$budget->transf_people_name}}
                </td>
                <td class="td-title td-font-14 td-bold" colspan="2">
                    CNPJ:
                </td>
                <td class="td-font-14" colspan="4">
                    {{$budget->transf_identity}}
                </td>
            </tr>
            <tr>
                <td colspan="12" class="td-font-14 td-text-white td-text-center td-color-black td-bold">
                    Motivo/Evidências/Observações gerais da solicitação
                </td>
            </tr>
            <tr>
                <td colspan="12" class="td-font-11 td-text-center">
                    {{$budget->observation}}
                </td>
            </tr>
            <tr>
                <td colspan="12" class="td-font-14 td-text-white td-text-center td-color-black td-bold">
                    Informações importantes
                </td>
            </tr>
            <tr>
                <td class="td-font-11 td-text-center" colspan="12">
                    * Base de calculo de VPC será sempre valor líquido do faturamento ou pedido (Dedução de impostos, fretes e custos adicionais com clientes).
                </td>
            </tr>
            <tr>
                <td class="td-font-11 td-text-center" colspan="12">
                    * Após o recebimento deste documento a Gree tem até 45 dias para análise liquidação do pagamento desta verba.
                </td>
            </tr>
            <tr>
                <td colspan="12" class="td-font-14 td-text-white td-text-center td-color-black td-bold">
                    Aprovação - Assinaturas
                </td>
            </tr>
            <tr>
                <td class="td-title td-font-14 td-text-center" colspan="2">
                    CLIENTE/REPRESENTANTE
                </td>
                <td class="td-title td-font-14 td-text-center" colspan="3">
                    RESPONSÁVEL COMERCIAL
                </td>
                <td class="td-title td-font-14 td-text-center" colspan="2">
                    FINANCEIRO RECEBEDOR
                </td>
                <td class="td-title td-font-14 td-text-center" colspan="2">
                    FINANCEIRO VERIFICADOR
                </td>
                <td class="td-title td-font-14 td-text-center" style="width: 139px;" colspan="3">
                    GERENTE FINANCEIRO
                </td>
            </tr>
            <tr>
                <td class="td-font-14 td-text-center" colspan="2">
                    <div class="spacer-30"></div>
                </td>
                <td class="td-font-14 td-text-center" colspan="3">
                    <div class="spacer-30"></div>
                </td>
                <td class="td-font-14 td-text-center" colspan="2">
                    <div class="spacer-30"></div>
                </td>
                <td class="td-font-14 td-text-center" colspan="2">
                    <div class="spacer-30"></div>
                </td>
                <td class="td-font-14 td-text-center" colspan="3">
                    <div class="spacer-30"></div>
                </td>
            </tr>
            <tr>
                <td class="td-font-14 td-title" colspan="2">
                    <span style="float: left;position: absolute;">DATA: </span> <span style="display: flex;justify-content: center;">_____/_____/_____</span>
                </td>
                <td class="td-font-14 td-title" colspan="3">
                    <span style="float: left;position: absolute;">DATA: </span> <span style="display: flex;justify-content: center;">_____/_____/_____</span>
                </td>
                <td class="td-font-14 td-title" colspan="2">
                    <span style="float: left;position: absolute;">DATA: </span> <span style="display: flex;justify-content: center;">_____/_____/_____</span>
                </td>
                <td class="td-font-14 td-title" colspan="2">
                    <span style="float: left;position: absolute;">DATA: </span> <span style="display: flex;justify-content: center;">_____/_____/_____</span>
                </td>
                <td class="td-font-14 td-title" colspan="3">
                    <span style="float: left;position: absolute;">DATA: </span> <span style="display: flex;justify-content: center;">_____/_____/_____</span>
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
