 
<!DOCTYPE html>
<html lang="pt-br" itemscope itemtype="https://schema.org/WebPage">
    <head>
        <!--METAS DA PAGINA-->
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1"/>

        <link rel="shortcut icon" href="/admin/app-assets/images/ico/favicon.png"/>

        <link href='https://fonts.googleapis.com/css?family=Source+Sans+Pro:400,700italic,600,300,900' rel='stylesheet' type='text/css'>
        <link rel="stylesheet" href="/css/boot.css" type="text/css" />
        <script src="/admin/app-assets/js/jquery-3.4.1.min.js"></script>
        <title>Sistema Gree de controle</title>


    </head>
    <body >

        <style>
    //.folha{page-break-after: always;}

    body {
        margin: 0;
        padding: 0;
        text-align: center;
        color: #000000;
        font-family: Arial, Helvetica, sans-serif;
        font-size: 10pt;
    }
    body p{
        margin: 0;
        line-height: 20px;
        padding: 0;
    }
    #defesa  {
        width: 800px;
        background: #FFFFFF;
        text-align: left;
        margin-top: 0;
        margin-right: auto;
        margin-bottom: 0;
        margin-left: auto;
        padding-top: 10px;
        padding-bottom: 10px;
    }
    #conteudo  {
        border: 1px solid #000;
    }

    #conteudo  h1#logo{

        margin: 0px;
        width: 200px;

        height:auto;

        float: left;

    }
    #conteudo  #dados_empresa{
        margin: 0px;
        min-height: 120px;
        width: 373px;
        float: left;

        //border-left-width:1px;
        //border-left-color:#000;
        //border-left-style:solid;


    }
    #conteudo  #dados_empresa h2{
        margin: 0px;

        width: 375px;
        float: left;

        line-height: 40px;
        text-align: center;
        font-family: Arial;
        font-size: 16pt;

        // border-right-width:1px;
        //border-right-color:#000;
        //border-right-style:solid;

    }
    #conteudo  #dados_empresa p{
        margin: 0px;
        min-height: 40px;
        width: 373px;
        float: left;

        line-height: 20px;
        text-align: left;
        padding: 5px;

        //border-right-width:1px;
        //border-right-color:#000;
        //border-right-style:solid;

        // border-top-width:1px;
        //border-top-color:#000;
        //border-top-style:solid;	


    }
    #conteudo  #dados_empresa p.menor{
        margin: 0px;
        height: 20px;
        width: 365px;
        float: left;
        line-height: 20px;
        text-align: left;
        padding-top: 5px;
        padding-right: 5px;
        padding-bottom: 0px;
        padding-left: 5px;

    }
    #conteudo  #dados_doc{
        margin: 0px;
        height: 120px;
        width: 221px;
        float: right;

    }
    #conteudo  #dados_doc h2{
        margin: 0px;
        height: 40px;
        width: 221px;
        float: left;

        line-height: 40px;
        text-align: center;
        font-family: Arial;
        font-size: 16pt;
    }
    #conteudo  #dados_doc p{
        margin: 0px;
        height: 40px;
        width: 222px;
        float: left;

        line-height: 20px;
        text-align: left;
        padding: 5px;

        border-top-width:1px;
        border-top-color:#000;
        border-top-style:solid;	

    }
    #conteudo  #dados_doc p.menor{
        margin: 0px;
        height: 20px;
        width: 222px;
        float: left;
        line-height: 20px;
        text-align: left;
        padding-top: 5px;
        padding-right: 5px;
        padding-bottom: 0px;
        padding-left: 5px;
        border-bottom-style: none;
    }


    #conteudo  #bloco{
        margin: 0px;
        height: auto;
        width: 798px;
        border-bottom-width: 1px;
        border-bottom-style: solid;
        border-bottom-color: #000;
        padding: 15px;
    }
    #conteudo  #bloco.noborder{
        border-bottom-style: none;
    }
    #conteudo  p.cinquenta{
        float: left;
        width: 380px;
    }
    #noprint  {
        display:none;
    }
    /* tables */
    table.tablesorter {
        font-family:arial;
        background-color: #CDCDCD;
        font-size: 10pt;
        width: 100%;
        text-align: left;
        margin-top: 0px;
        margin-right: 0pt;
        margin-bottom: 15px;
        margin-left: 0pt;
    }
    /* tables */
    table.tablesorter td{
        border: 1px solid #000;
    }
    table.tablesorter thead tr th, table.tablesorter tfoot tr th {
        background-color: #F0F0F0;
        border: 1px solid #FFF;
        font-size: 10pt;
        padding: 4px;
    }
    table.tablesorter tbody td {
        color: #3D3D3D;
        padding: 4px;
        background-color: #FFF;
        vertical-align: top;
    }
    table.tablesorter tbody tr.odd td {
        background-color:#F0F0F6;
    }

    .inv  {
        display: none;
    }

    .limpa_float  {
        clear: both;
        height:0px;
        line-height:0px;
    }

    body {

        margin      : 0;

        padding     : 0;

        text-align  : center;
        color       : #000000;
        font-family : Arial, Helvetica, sans-serif;
        font-size   : 10pt;

    }

    body p {

        margin      : 0;
        line-height : 20px;
        padding     : 0;

    }

    #defesa  {

        width          : 800px;

        background     : #FFFFFF;

        text-align     : left;

        margin-top     : 0;
        margin-right   : auto;
        margin-bottom  : 0;
        margin-left    : auto;

        padding-top    : 20px;
        padding-bottom : 20px;

    }

    #conteudo  {

        border : 1px solid #000;

    }

    #conteudo  h1#logo {

        width  : 200px;
        height : auto;
        margin : 0px;
        float  : left;

    }

    #conteudo  #dados_empresa {

        width      : 375px;
        min-height : 140px;

        margin     : 0px;

        float      : left;

        border-left-width:1px;
        border-left-color:#000;
        border-left-style:solid;

        border-right-width : 1px;
        border-right-color : #000;
        border-right-style : solid;	

    }

    #conteudo  #dados_empresa h2 {

        width              : 375px;

        margin             : 0px;

        float              : left;

        line-height        : 40px;

        text-align         : center;
        font-family        : Arial;
        font-size          : 16pt; 

    }

    #conteudo  #dados_empresa p {

        width       : 373px;
        min-height  : 40px;

        margin      : 0px;

        float       : left;

        line-height : 20px;
        text-align  : left;
        padding     : 5px;

        border-top-width   : 1px;
        border-top-color   : #000;
        border-top-style   : solid;	


    }

    #conteudo  #dados_empresa p.menor {

        width  		   : 365px;
        height 		   : 20px;

        margin 		   : 0px;

        float  		   : left;

        line-height    : 20px;
        text-align     : left;
        padding-top    : 5px;
        padding-right  : 5px;
        padding-bottom : 0px;
        padding-left   : 5px;

    }

    #conteudo  #dados_doc {

        width      : 221px;
        min-height : 140px;

        margin     : 0px;

        float      : right;



    }

    #conteudo  #dados_doc h2 {

        width       : 221px;
        height      : 40px;

        margin      : 0px;

        float       : left;

        line-height : 40px;
        text-align  : center;
        font-family : Arial;
        font-size   : 16pt;
    }

    #conteudo  #dados_doc p {

        width  			 : 222px;
        height 			 : 40px;

        margin 			 : 0px;

        float  			 : left;

        line-height 	 : 20px;
        text-align  	 : left;
        padding     	 : 5px;

        border-top-width : 1px;
        border-top-color : #000;
        border-top-style : solid;	

    }

    #conteudo  #dados_doc p.menor {

        width  		        : 222px;
        height              : 20px;

        margin		        : 0px;

        float 		        : left;

        text-align          : left;

        padding-top    		: 1px;
        padding-right  		: 5px;
        padding-left   		: 5px;

        border-bottom-style : none;

    }

    #conteudo  #bloco{
        margin: 0px;
        height: auto;
        width: 798px;
        border-bottom-width: 1px;
        border-bottom-style: solid;
        border-bottom-color: #000;
        padding: 15px;
    }
    #conteudo  #bloco.noborder{
        border-bottom-style: none;
    }
    #conteudo  p.cinquenta{
        float: left;
        width: 380px;
    }
    #noprint  {
        margin: 0px;
        height: auto;
        width: 770px;
        text-align: center;
        padding-top: 25px;
        padding-right: 15px;
        padding-bottom: 25px;
        padding-left: 15px;
    }
    /* tables */
    table.tablesorter {
        font-family:arial;
        background-color: #CDCDCD;
        font-size: 10pt;
        width: 100%;
        text-align: left;
        margin-top: 0px;
        margin-right: 0pt;
        margin-bottom: 15px;
        margin-left: 0pt;
    }
    table.tablesorter thead tr th, table.tablesorter tfoot tr th {
        background-color: #F0F0F0;
        border: 1px solid #FFF;
        font-size: 10pt;
        padding: 4px;
    }
    table.tablesorter tbody td {
        color: #3D3D3D;
        padding: 4px;
        background-color: #FFF;
        vertical-align: top;
    }
    table.tablesorter tbody tr.odd td {
        background-color:#F0F0F6;
    }

    .inv  {
        display: none;
    }

    .limpa_float  {
        clear: both;
        height:0px;
        line-height:0px;
    }
    strong{ font-weight: 600;}
</style>


    <div id="defesa">

        <div id="conteudo">

            <div id="topo_os" style="float:left;border-bottom-width:1px;border-bottom-color:#000;border-bottom-style:solid;">
                <h1 id="logo">
                    <img src="/media/logo_pb.jpg"/>
                    <span class="inv">Gree - Marca Mundial em Ar Condicionado</span></h1>

                <div id="dados_empresa">
                    <h2>ORDEM DE SERVI??O</h2>
                    <p>
                         <span>Raz??o Socia:</span> <strong><?= $authorized->name ?></strong>
                        <br />
                        <span>Endereco:</span> <strong><?= $authorized->address ?></strong>
                        <br /><span>CEP:</span> <strong><?= $authorized->zipcode ?></strong> 
                        <span>Cidade:</span> <strong><?= $authorized->city ?></strong> 
                        <span>Estado:</span> <strong><?= $authorized->state ?></strong>
                        <br /><span>CPNJ:</span> <strong><?= $authorized->identity ?></strong>
						<br /><span>Telefone:</span> <strong><?= $authorized->phone_1 ?> </strong>
                    </p>
                </div>

                <div id="dados_doc">
                    <h2>@if ($os->code)<span>N??</span> <strong><?= $os->code ?></strong>@else - @endif</h2>
                    <p><span>C??d. do Credenciado:</span> <strong><?= $authorized->code ?></strong><br>
                    <span>Data de Emiss??o:</span> <strong style='margin-left:10%'><?= date('d/m/Y', strtotime($os->created_at)) ?></strong><br>
                    <span>Data de Reclama????o:</span> <strong><?= date('d/m/Y', strtotime($protocol->created_at)) ?></strong>
                    <span>Nota fiscal:</span> <strong><?= $protocol->number_nf ?></strong>
					@if ($os->code_origin)<br><span>Origem:</span> <strong>{{$os->code_origin}}</strong>@endif
					@if ($protocol->origin == 3)<br><span></span><strong>RECLAME AQUI</strong>@endif
                    </p>
                </div>

            </div>                  
            <div class="limpar"></div>

            <div id="bloco">
                <p style=" font-size: 1.1em; font-weight: 600;">Dados do Consumidor</p>
                <div class="limpar"></div>
                <span class="box box-larga">
                    <p>Nome: <strong><?= $client->name ?></strong></p>
                    <p class="cinquenta">Telefone: <strong><?= $client->phone ?></strong></p>
					 <p><span>Endere??o:</span> <strong><?= $protocol->address ?></strong></p>
					<p><span>Complemento:</span> <strong><?= $protocol->complement ?></strong></p>
                </span>
                <span class="box box-larga no-margim">
                    <span>Protocolo:</span> <strong><?= $protocol->code ?></strong>
					@php
					$who_install = [
						0 => 'N??o informado',
						1 => 'Particular',
						2 => 'Credenciado'
					];
					@endphp
					<br><span>Instalado por:</span> <strong><?= $who_install[$protocol->installed_by] ?></strong>
                    <br><span>Loja:</span> <strong><?= $protocol->shop ?></strong>
                    <br><span>Data da compra:</span> <strong><?= date('d/m/Y', strtotime($protocol->buy_date)) ?></strong>
                </span>
                <div class="limpar"></div>
            </div>

            <div id="bloco">
                <p style=" font-size: 1.1em; font-weight: 600;">Equipamento(s) realizado(s) assit??ncia t??cnica</p>  
                @if (count($sac_models) > 0)              
                <div class="limpar"></div>
                    <div class=" fl-left" style=" width: 20%; text-align: center; background-color: #ccc; padding: 10px">Segmento</div>
                    <div class=" fl-left" style=" width: 30%; text-align: center; background-color: #ccc;  padding: 10px">Modelo</div>
                    <div class=" fl-left" style=" width: 50%; text-align: center; background-color: #ccc;  padding: 10px">S??rie</div>
                    <div class="limpar"></div>
                    @foreach ($sac_models as $key)
                        <?php 
                        $sub1 = \App\Model\ProductSubLevel1::find($key->product_sub_level_1_id);
                        $sub2 = \App\Model\ProductSubLevel2::find($key->product_sub_level_2_id);
                        ?>
                        <div class=" fl-left" style=" width: 20%; text-align: center; padding: 10px">@if ($sub1) <?= $sub1->name ?> @endif @if ($sub2) <br><?= $sub2->name ?> @endif</div>
                        <div class=" fl-left" style=" width: 30%; text-align: center; padding: 10px"><?= $key->model ?></div>
                        <div class=" fl-left" style=" width: 50%; text-align: center; padding: 10px"><?= $key->smp_serial_number ?></div>
                    <div class="limpar"></div>
                    @endforeach
                
                @else
                <p style="text-align: center">N??o h?? modelos vinculados</p>
                @endif
                        
                <div class="limpar"></div>
                <br>
            </div>

            <div id="bloco">
                <p style=" font-size: 1.1em; font-weight: 600;">Solicita????o de pe??as</p>  
                @if (count($parts) > 0)              
                <div class="limpar"></div>
                    <div class=" fl-left" style=" width: 10%; text-align: center; background-color: #ccc; padding: 10px">Quantidade</div>
                    <div class=" fl-left" style=" width: 30%; text-align: center; background-color: #ccc;  padding: 10px">C??digo da pe??a</div>
                    <div class=" fl-left" style=" width: 30%; text-align: center; background-color: #ccc;  padding: 10px">Nome da pe??a</div>
                    <div class=" fl-left" style=" width: 30%; text-align: center; background-color: #ccc;  padding: 10px">Motivo da pe??a</div>
                    <div class="limpar"></div>
                    @foreach ($parts as $key)
                        <div class=" fl-left" style=" width: 10%; text-align: center; padding: 10px"><?= $key->quantity ?></div>
                        <div class=" fl-left" style=" width: 30%; text-align: center; padding: 10px"><?= $key->code ?></div>
                        <div class=" fl-left" style=" width: 30%; text-align: center; padding: 10px"><?= $key->parts_description ?></div>
                        <div class=" fl-left" style=" width: 30%; text-align: center; padding: 10px"><?= $key->description ?></div>
                    <div class="limpar"></div>
                    @endforeach
                
                @else
                <p style="text-align: center">N??o h?? pedido de pe??as</p>
                @endif
                        
                <div class="limpar"></div>
                <br>
            </div>
            
            <div id="bloco">      
                <p style=" font-size: 1.1em; font-weight: 600;">Defeito constatado</p>
                <div class="limpar"></div>
                                    <p><?= $protocol->description ?></p>
                            </div>
              			
            <div id="bloco">
                <p style=" font-size: 1.1em; font-weight: 600;">An??lise t??cnica</p>
                <div class="limpar"></div>
                <p><?= $os->description ?></p>

                <br>
                <span class="box box-larga">
                    <p class="cinquenta"><span>Nome do t??cnico:</span> <strong><?= $os->expert_name ?></strong></p>
                </span>
                <span class="box box-larga no-margim">
                    <p class="cinquenta"><span>Telefone do t??cnico:</span> <strong><?= $os->expert_phone ?></strong></p>
                </span>
                <div class="limpar"></div>
            </div>

            <div id="bloco">
                <p>Recebi o aparelho e acess??rios em perfeitas condi????es de funcionamento.<br />&nbsp;</p>
                <span class="box box-larga">
                <p class="cinquenta">Data: _____/_____/_____</p> 
                </span>
                <span class="box box-larga no-margim">
                <p class="cinquenta center">Assinatura do cliente</p>
                </span>
                <div class="limpar"></div>
                <br><br>
                <div>
                    <br class="limpa_float" />
                </div>
            </div>

            <div id="bloco">
                <p style=" font-size: 1.1em; font-weight: 600;">Dados de pagamento</p>
                <div class="limpar"></div>
                <p><?= $protocol->paid_info ?></p>

                <br>
                <span class="box box-larga">
                </span>
                <span class="box box-larga no-margim" style="float: right;text-align: right;">
                    <p class="cinquenta"><span>Custo da m??o de obra:</span> <strong>R$ <?= number_format($parts->sum('total'), 2, ',', '.'); ?></strong></p>
                    <p class="cinquenta"><span>Custo da visita:</span> <strong>R$ <?= number_format($os->visit_total, 2, ',', '.'); ?></strong></p>
                    <p class="cinquenta"><span>Total do pagamento:</span> <strong>R$ <?= number_format($os->total, 2, ',', '.'); ?></strong></p>
                </span>
                <div class="limpar"></div>
            </div>
            <!-- FIM DO CONTE??DO -->
            
        </div>

        <div style="position: fixed;
        bottom: 0;
        text-align: center;
        left: 0;
        right: 0;
        padding: 12px;">
            <button name="imprimir" style="width: 95px;
            padding: 5px;
            background: #2c6de9;
            color: white;
            border: 1px #05235d;
            border-radius: 9px;" type="button" value="Imprimir" onclick="print()">Imprimir</button>
            
            <a href="/sac/warranty/os/model/<?= $os->id ?>" style="padding: 5px;background: #0ac770;color: white;border: 1px #05235d;border-radius: 9px;">Editar Modelo</a>
        </div>
    </div>
    <!--FIM DA DEFESA -->
            <script>
            $(document).ready(function () {
                function print() {
                    $('#defesa').printThis({
                        importCSS: true,            // import parent page css
                        importStyle: true 
                    });
                }
            });
</script>
<script src="/js/printThis.js"></script>
    </body>
</html>