 
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
                    <h2>ORDEM DE COMPRA</h2>
                    <p>
                         <span>Razão Socia:</span> <strong><?= $authorized->name ?></strong>
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
                    <h2><span>N°</span> <strong><?= $ob->code ?></strong></h2>
                    <p><span>Cód. do Credenciado:</span> <strong><?= $authorized->code ?></strong></p>
                    <p><span>Data de Emissão:</span> <strong><?= date('d/m/Y', strtotime($ob->created_at)) ?></strong></p>
					<p class="menor"><span>Tipo:</span> <strong>@if($authorized->type == 1) Autorizado/Credenciado @elseif($authorized->type == 2) Tercerizado @else Revenda @endif</strong></p>
                </div>

            </div>                  
            <div class="limpar"></div>

            <div id="bloco">
                <p style=" font-size: 1.1em; font-weight: 600;">Dados da Credenciada</p>
                <br>
                <div class="limpar"></div>
                <span class="box">
                    <p>Nome: <strong><?= $authorized->name ?></strong></p>
                    <p>Telefone: <strong><?= $authorized->phone_1 ?></strong></p>
                    <p>Email: <strong><?= $authorized->email ?></strong></p>
					<p>Email em cópia: <strong><?= $authorized->email_copy ?></strong></p>
                    <p>Cidade: <strong><?= $authorized->city ?></strong></p>
                    <p>Estado: <strong><?= $authorized->state ?></strong></p>
                    <p>CPNJ: <strong><?= $authorized->identity ?></strong></p>
					<p><span>Endereço:</span> <strong><?= $authorized->address ?></strong> <span>CEP:</span> <strong><?= $authorized->zipcode ?></strong></p>
                </span>
                <span class="box box-larga no-margim">
                </span>
                <div class="limpar"></div>
            </div>

            <div id="bloco">
                <p style=" font-size: 1.1em; font-weight: 600;">Solicitação de peças</p>  
                @if (count($parts) > 0)              
                <div class="limpar"></div>
                    <div class=" fl-left" style=" width: 20%; text-align: center; background-color: #ccc; padding: 10px">Modelo</div>
                    <div class=" fl-left" style=" width: 30%; text-align: center; background-color: #ccc;  padding: 10px">Código/Nome da peça</div>
                    <div class=" fl-left" style=" width: 20%; text-align: center; background-color: #ccc;  padding: 10px">Descrição</div>
                    <div class=" fl-left" style=" width: 20%; text-align: center; background-color: #ccc;  padding: 10px">Quantidade</div>
                    <div class=" fl-left" style=" width: 10%; text-align: center; background-color: #ccc;  padding: 10px">Imagem</div>
                    <div class="limpar"></div>
                    @foreach ($parts as $key)
                        @if ($key->not_part == 1)
                           
                            @if(is_numeric($key->model))
                                <?php $pv = \App\Model\ProductAir::find($key->model); ?>
                                @if ($pv)
                                	<div class=" fl-left" style=" width: 20%; text-align: center; padding: 10px"><?= $pv->model ?></div>
								@else
									<div class=" fl-left" style=" width: 20%; text-align: center; padding: 10px">Não encontrado</div>
								@endif
                            @else 
                                <div class=" fl-left" style=" width: 20%; text-align: center; padding: 10px"><?= $key->model ?></div>
                            @endif    
                            <div class=" fl-left" style=" width: 30%; text-align: center; padding: 10px"><?= $key->part ?></div>
                        @else
                            <?php $p = \App\Model\ProductAir::find($key->model); ?>
                            @if ($p)
                                <div class=" fl-left" style=" width: 20%; text-align: center; padding: 10px"><?= $p->model ?></div>
                            @endif
                            <?php $pt = \App\Model\Parts::find($key->part); ?>
                            @if ($pt)
                                <div class=" fl-left" style=" width: 30%; text-align: center; padding: 10px"><?= $pt->description ?> (<?= $pt->code ?>)</div>
                            @else
                                <div class=" fl-left" style=" width: 30%; text-align: center; padding: 10px">-</div>
                            @endif
                        @endif
                        <div class=" fl-left" style=" width: 20%; text-align: center; padding: 10px"><?= $key->description ?></div>
                        <div class=" fl-left" style=" width: 20%; text-align: center; padding: 10px"><?= $key->quantity ?></div>
                        <div class=" fl-left" style=" width: 10%; text-align: center; padding: 10px">@if ($key->image)<a href="<?= $key->image ?>" target="_blank">Ver imagem</a> @else -- @endif</div>
                    <div class="limpar"></div>
                    @endforeach
                
                @else
                <p style="text-align: center">Não há pedido de peças</p>
                @endif
                        
                <div class="limpar"></div>
                <br>
            </div>
            
            <div id="bloco">      
                <p style=" font-size: 1.1em; font-weight: 600;">Informações complementares</p>
                <div class="limpar"></div>
                @if ($ob->optional) <p><?= $ob->optional ?></p> @else <p style="text-align: center">Não há informações</p> @endif
            </div>

            <div id="bloco" class="noborder">
                <p>Confirmo a realização do pedido de compra da(s) peças.<br />&nbsp;</p>
                <span class="box box-larga">
                <p class="cinquenta">Data: _____/_____/_____</p> 
                </span>
                <span class="box box-larga no-margim">
                <p class="cinquenta center">Assinatura</p>
                </span>
                <div class="limpar"></div>
                <br><br>
                <div>
                    <br class="limpa_float" />
                </div>
            </div>
            <!-- FIM DO CONTEÚDO -->
            
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