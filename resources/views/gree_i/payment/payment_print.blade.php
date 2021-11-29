@extends('gree_i.layout')

@section('content')
    <link rel="stylesheet" type="text/css" href="/admin/app-assets/vendors/css/extensions/shepherd-theme-default.css">
    <link rel="stylesheet" type="text/css" href="/admin/app-assets/css/plugins/tour/tour.min.css">
    <link rel="stylesheet" type="text/css" href="/admin/assets/css/table_custom.css">
    <div class="content-overlay"></div>
    <div class="content-wrapper">
        <div class="content-header row">
            <div class="content-header-left col-12 mb-2 mt-1">
                <div class="row breadcrumbs-top">
                    <div class="col-12">
                        <h5 class="content-header-title float-left pr-1 mb-0">Pagamento</h5>
                        <div class="breadcrumb-wrapper col-12">
                            @if ($id == 0)
                                Nova solicitação
                            @else
                                Solicitação
                            @endif
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
                    <div class="card-content">
                        <div class="table-responsive">
                            <table class="table table-bordered mb-0">
                                <thead>
                                </thead>
                                <tbody>
                                <tr>
                                    <td colspan="7">
                                        <img class="img-fluid" src="/admin/app-assets/images/logo/logo_gree_blue.png" alt="">
                                        <br>
                                        <h4 class="ml-4 font-weight-bold">
                                            @if ($relationship)
                                                {{$r_payment->relationModules($relationship)['description']}}
                                                <div class="float-right text-center" style="position: relative;bottom: 15px;">#<?= $r_payment->code ?><br><small><b>ORIGEM:</b> #<?= $relationship->code ?></small></div>
                                            @else
                                                {{$r_payment->relationModules('')['description']}}
                                                #{{$r_payment->code}}
                                            @endif
                                        </h4>
                                    </td>
                                    <td>
                                        Data: <b><?= date('d/m/Y', strtotime($r_payment->created_at)) ?></b>
                                    </td>
                                </tr>
                                <tr>
                                    <td>Depto Solicitante:</td>
                                    <td class="text-bold-500"><?= $r_payment->users->sector_name ?></td>
                                    <td>Solicitante</td>
                                    <td colspan="2" class="text-bold-500"><?= $r_payment->users->short_name ?></td>
                                    <td>NF</td>
                                    <td colspan="2" class="text-bold-500">
                                        <?= $r_payment->nf_nmb ?>
                                    </td>
                                </tr>
                                <tr>
                                    <td>Conteúdo:</td>
                                    <td colspan="7" class="text-bold-500" style="text-transform: uppercase;">
                                        <?= $r_payment->description ?>
                                    </td>
                                </tr>
                                <tr>
                                    <td>Fornecedor/Beneficiário</td>
                                    <td colspan="7" class="text-bold-500" style="text-transform: uppercase;">
                                        <?= $r_payment->recipient ?>
                                    </td>
                                </tr>
                                <tr>
                                    <td rowspan="2">Valor total da Nota Fiscal</td>
                                    <td>Valor Bruto (R$)</td>
                                    <td colspan="7" class="text-bold-500 text-right">
                                        R$ <?= number_format($r_payment->sub_total,2, ',', '.') ?>
                                    </td>
                                </tr>
                                <tr>
                                    <td colspan="8" class="text-bold-500" id="amount-total-word">

                                    </td>
                                </tr>
                                <tr>
                                    <td>Solicitante</td>
                                    <td colspan="2" class="text-bold-500">
                                        <?= $r_payment->users->short_name ?> (<?= $r_payment->users->r_code ?>)
                                    </td>
                                    <td>Gerente Dpt</td>
                                    <td colspan="2" class="text-bold-500">
                                        @if ($mark_position->where('mark', 1)->first())
                                            @php
                                            $approv = $mark_position->where('mark', 1)->first()->users;
                                            @endphp
                                            {{$approv->short_name}} ({{$approv->r_code}})
                                        @endif
                                    </td>
                                    <td>Recebedor</td>
                                    <td colspan="1" class="text-bold-500">
                                        @if ($mark_position->where('mark', 2)->first())
                                            @php
                                                $approv = $mark_position->where('mark', 2)->first()->users;
                                            @endphp
                                            {{$approv->short_name}} ({{$approv->r_code}})
                                        @endif
                                    </td>
                                </tr>
                                <tr>
                                    <td>Vencimento</td>
                                    <td colspan="7" class="text-bold-500 text-center">
                                        <?= date('d/m/Y', strtotime($r_payment->due_date)) ?>
                                    </td>
                                </tr>
                                <tr>
                                    <td rowspan="2">Valor total para pagamento</td>
                                    <td>Valor Liquido (R$)</td>
                                    <td colspan="7" class="text-bold-500 text-right">
                                        R$ <?= number_format($r_payment->amount_liquid,2, ',', '.') ?>
                                    </td>
                                </tr>
                                <tr>
                                    <td colspan="8" class="text-bold-500" id="amount-liquid-word">

                                    </td>
                                </tr>
                                <tr>
                                    <td>Verificador Fiscal</td>
                                    <td class="text-bold-500">
                                        @if ($mark_position->where('mark', 3)->first())
                                            @php
                                                $approv = $mark_position->where('mark', 3)->first()->users;
                                            @endphp
                                            {{$approv->short_name}} ({{$approv->r_code}})
                                        @endif
                                    </td>
                                    <td>Verificador Contábil</td>
                                    <td class="text-bold-500">
                                        @if ($mark_position->where('mark', 4)->first())
                                            @php
                                                $approv = $mark_position->where('mark', 4)->first()->users;
                                            @endphp
                                            {{$approv->short_name}} ({{$approv->r_code}})
                                        @endif
                                    </td>
                                    <td>Gerente financeiro</td>
                                    <td class="text-bold-500">
                                        @if ($mark_position->where('mark', 5)->first())
                                            @php
                                                $approv = $mark_position->where('mark', 5)->first()->users;
                                            @endphp
                                            {{$approv->short_name}} ({{$approv->r_code}})
                                        @endif
                                    </td>
                                    <td>Diretor</td>
                                    <td class="text-bold-500">
                                        @if ($mark_position->where('mark', 6)->first())
                                            @php
                                                $approv = $mark_position->where('mark', 6)->first()->users;
                                            @endphp
                                            {{$approv->short_name}} ({{$approv->r_code}})
                                        @endif
                                    </td>
                                </tr>
                                <tr>
                                    <td rowspan="2" colspan="6" class="text-center">
                                        @if ($r_payment->optional)
                                            @if ($r_payment->p_method == 2)
                                                <?= nl2br('
                                                <div class="row">
                                                    <div class="col-md-8">' . $r_payment->optional . '</div>
                                                    <div class="col-md-4">Agência: ' . $r_payment->agency . ' <br> Conta: ' .
                                                        $r_payment->account . ' <br> Banco: ' . $r_payment->bank . ' <br>
                                                        CPF/CNPJ: ' . $r_payment->identity . '
                                                    </div>
                                                </div>
                                                ') ?>
                                          @else
                                            {{$r_payment->optional}}
                                          @endif
                                        @else
                                            @if ($r_payment->p_method == 2)
                                            <?= nl2br('Agência: ' . $r_payment->agency . ' <br> Conta: ' . $r_payment->account . ' <br> Banco: ' . $r_payment->bank . ' <br> CPF/CNPJ: ' . $r_payment->identity); ?>
                                            @else
                                                {{$r_payment->optional}}
                                            @endif
                                        @endif
                                    </td>
                                    <td rowspan="2" colspan="2" id="selectm">
                                        <p><span id="payment_1" class="cursor-pointer">( )</span> Boleto</p>
                                        <p><span id="payment_2" class="cursor-pointer">( )</span> Transferência / D.Automático</p>
                                        <p><span id="payment_3" class="cursor-pointer">( )</span> Caixa</p>
                                    </td>
                                </tr>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </section>

            @if ($r_payment->financy_r_payment_attach->count())
                <section class="request-files">
                    <div class="card">
                        <div class="card-header">
                            <h4 class="card-title">Anexos</h4>
                        </div>
                        <div class="card-content">
                            <div class="card-body">

                                <div class="form-group">
                                    <ul class="list-unstyled">

                                        @foreach ($r_payment->financy_r_payment_attach as $key)
                                            <p><a target="_blank" href="<?= $key->url ?>"><li class="list-inline-item"><?= $key->id ?>. <?= $key->name ?> (<?= readableBytes($key->size) ?>)</li></a></p>
                                        @endforeach

                                    </ul>
                                </div>

                            </div>
                        </div>
                    </div>

                </section>
            @endif

            <section class="request-bank">
                <div class="card">
                    <div class="card-header">
                        <h4 class="card-title">Dados bancários</h4>
                    </div>
                    <div class="card-content">
                        <div class="card-body">
                            <div class="row">
                                <div class="col-md-3">
                                    <fieldset class="form-group">
                                        <label for="agency">Agência</label>
                                        <input type="text" id="agency" readonly name="agency" value="<?= $r_payment->agency ?>" class="form-control" placeholder="0000">
                                    </fieldset>
                                </div>

                                <div class="col-md-3">
                                    <fieldset class="form-group">
                                        <label for="account">Conta</label>
                                        <input type="text" id="account" readonly name="account" value="<?= $r_payment->account ?>" class="form-control" placeholder="000000-0">
                                    </fieldset>
                                </div>

                                <div class="col-md-3">
                                    <fieldset class="form-group">
                                        <label for="bank">Banco</label>
                                        <input type="text" id="bank" readonly name="bank" value="<?= $r_payment->bank ?>" class="form-control" placeholder="Bradesco">
                                    </fieldset>
                                </div>

                                <div class="col-md-3">
                                    <fieldset class="form-group">
                                        <label for="identity">CPF/IDENTIDADE</label>
                                        <input type="text" id="identity" readonly name="identity" value="<?= $r_payment->identity ?>" class="form-control" placeholder="00000000000">
                                    </fieldset>
                                </div>

                                <div class="col-md-12">
                                    <fieldset class="form-group">
                                        <label for="cnpj">CNPJ (Apenas para empresa)</label>
                                        <input type="text" id="cnpj" readonly name="cnpj" value="<?= $r_payment->cnpj ?>" class="form-control" placeholder="00000000000">
                                    </fieldset>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

            </section>
        </div>
    </div>

    <div class="mb-2" style="text-align: center; position: fixed;left: 0;right: 0;bottom: 0;margin: 0 auto; z-index: 99;">
        <button type="button" onclick="print();" class="btn btn-primary mb-1">IMPRIMIR</button>
    </div>
    <script>

        function print() {
            $('.table').printThis({
                importCSS: true,            // import parent page css
                importStyle: false
            });
        }

        function getWords(obj, amout) {
            $.ajax({
                type: "GET",
                timeout: 10000,
                url: "/misc/currency-to-words?amount=" + amout,
                success: function (response) {
                    if (response.success) {
                        obj.html(response.words);
                    }
                }
            });
        }
        $(document).ready(function () {

            <?php if ($r_payment->sub_total) {?>
            getWords($("#amount-total-word"), '<?= number_format($r_payment->sub_total,2, ',', '.') ?>');
            <?php } ?>

            <?php if ($r_payment->amount_liquid) {?>
            getWords($("#amount-liquid-word"), '<?= number_format($r_payment->amount_liquid,2, ',', '.') ?>');
            <?php } ?>

            <?php if ($r_payment->p_method == 1) { ?>
            $("#payment_method").val(1);
            $("#payment_1").html("(X)");
            <?php } else if ($r_payment->p_method == 2) { ?>
            $("#payment_method").val(2);
            $("#payment_2").html("(X)");
            <?php } else if ($r_payment->p_method == 3) { ?>
            $("#payment_method").val(3);
            $("#payment_3").html("(X)");
            <?php } ?>

        });
    </script>
@endsection
