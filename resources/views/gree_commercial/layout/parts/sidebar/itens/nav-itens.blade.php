
    <ul>
		<li id="operation">
            <a href="#">Operacional <i class="fa fa-bar-chart-o"></i> <i class="fa fa-caret-left"></i></a>
            <!-- * sub menu * -->
            <ul>
                <li id="dashboard_general">
                    <a href="/commercial/operation/dashboard/general">Painel geral</a>
                </li>
				<li id="reportInvoice">
                    <a href="#">Apuração de faturamento <i class="fa fa-caret-left"></i></a>
                    <ul class="submenu">
                        <li id="reportInvoiceAll">
                            <a href="/commercial/operation/report/invoice">Todas apurações</a>
                        </li>
                        <li id="budgetCommercialAll">
                            <a href="/commercial/sales/budget/list">Verb. Comerciais</a>
                        </li>
						<li id="budgetCommercialApprov">
                            <a href="/commercial/sales/budget/list/analyze">Verb. Comerciais aprovação</a>
                        </li>
                        <li id="NFsPendingImport">
                            <a href="/commercial/operation/nfs/pendings/import">Importar NF em aberto</a>
                        </li>
                    </ul>
                </li>
				<li id="orderInvoice">
                    <a href="/commercial/operation/order/invoice">Pedidos faturados</a>
                </li>
				<li id="saleVerification">
                    <a href="/commercial/export/report/sale/client/response/list">Apuração de vendas</a>
                </li>
            </ul>
        </li>
        <li class="seperator"><!-- * seperator line * --></li>
		<li id="orderSale">
            <a href="#">Pedidos de vendas <i class="fa fa-pencil-square-o"></i> <i class="fa fa-caret-left"></i></a>
            <!-- * sub menu * -->
            <ul>
                <li id="orderAdjust">
                    <a href="/commercial/order/programation/month/list">Reajuste mensal</a>
                </li>
                <li id="programation">
					<a href="#">Programações <i class="fa fa-caret-left"></i></a>
                    <ul class="submenu">
                        <li id="programationAll">
                            <a href="/commercial/programation/all">Todas</a>
                        </li>
                        <li id="programationMacro">
                            <a href="/commercial/programation/macro">Programação Macro</a>
                        </li>
                    </ul>
                </li>
                <li id="order">
                    <a href="#">Pedido Programado <i class="fa fa-caret-left"></i></a>
                    <ul class="submenu">
                        <li id="orderAll">
                            <a href="/commercial/order/all">Todos</a>
                        </li>
                        @if (hasPermApprov(20))
                            <li id="orderApprov">
                                <a href="/commercial/order/approv">Aprovar</a>
                            </li>
                        @endif
                    </ul>
                </li>
                <li id="orderConfirmed">
                    <a href="#">Pedido Não Programado <i class="fa fa-caret-left"></i></a>
                    <ul class="submenu">
                        <li id="orderConfirmedNew">
                            <a href="/commercial/order/confirmed/new">Novo</a>
                        </li>
                        <li id="orderConfirmedAll">
                            <a href="/commercial/order/confirmed/all">Todos</a>
                        </li>
                        @if (hasPermApprov(20))
                            <li id="orderConfirmedApprov">
                                <a href="/commercial/order/confirmed/approv">Aprovar</a>
                            </li>
                        @endif
                    </ul>
                </li>
				<li id="orderInvoice">
                    <a href="/commercial/operation/order/invoice">Pedidos faturados</a>
                </li>
				<li id="orderImport">
                    <a href="/commercial/order/import">Importar pedidos</a>
                </li>
            </ul>
        </li>
        <li class="seperator"><!-- * seperator line * --></li>
        <li id="salesman">
            <a href="#">Representantes <i class="fa fa-briefcase"></i> <i class="fa fa-caret-left"></i></a>

            <!-- * sub menu * -->
            <ul>
                <li id="salesmanEdit">
                    <a href="/commercial/salesman/edit/0">Criar novo</a>
                </li>
                <li id="salesmanAll">
                    <a href="/commercial/salesman/list">Listar todos</a>
                </li>
            </ul>
        </li>
        <li class="seperator"><!-- * seperator line * --></li>
        <li id="client">
            <a href="#">Clientes <i class="fa fa-user"></i> <i class="fa fa-caret-left"></i></a>

            <!-- * sub menu * -->
            <ul>
                <li id="clientGroup">
                    <a href="/commercial/client/group/list">Grupos</a>
                </li>
                <li id="clientEdit">
                    <a href="/commercial/client/edit/0">Criar novo</a>
                </li>
                <li id="clientAll">
                    <a href="/commercial/client/list">Listar todos</a>
                </li>
                @if (hasPermApprov(20) or hasPermApprov(23))
				<li id="clientApprov">
                    <a href="/commercial/client/list/analyze">Solicitações de aprovação</a>
                </li>
                @endif
				<li id="clientConditions">
                    <a href="#">Condições comerciais <i class="fa fa-caret-left"></i></a>
                    <ul class="submenu">
                        <li id="clientConditionsTable">
                            <a href="/commercial/client/conditions/table">Tabela de preço</a>
                        </li>
                        <li id="clientConditionsRule">
                            <a href="/commercial/client/conditions/rules">Regra de preço</a>
                        </li>
                    </ul>
                </li>
				<li id="clientImport">
                    <a href="/commercial/client/import">Importação de clientes</a>
                </li>
            </ul>
        </li>
		<li class="seperator"><!-- * seperator line * --></li>
        <li id="product">
            <a href="#">Produtos <i class="fa fa-folder-o"></i> <i class="fa fa-caret-left"></i></a>

            <!-- * sub menu * -->
            <ul>
                <li id="productGroup">
                    <a href="/commercial/product/group/list">Grupos</a>
                </li>
                <li id="productSet">
                    <a href="#">Conjuntos <i class="fa fa-caret-left"></i></a>

                    <ul class="submenu">
                        <li id="productSetEdit">
                            <a href="/commercial/product/set/edit/0">Criar novo</a>
                        </li>
                        <li id="productSetAll">
                            <a href="/commercial/product/set/list">Listar todos</a>
                        </li>
                    </ul>
                </li>
            </ul>
        </li>
		<li class="seperator"><!-- * seperator line * --></li>
		<li id="settings">
            <a href="/commercial/settings">Configurações</a>

        </li>
    </ul>
