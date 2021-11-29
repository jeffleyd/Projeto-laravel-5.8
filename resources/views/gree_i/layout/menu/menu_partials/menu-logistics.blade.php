<?php if (hasPerm(26)) { ?>
<li id="mLogistics" class="dropdown nav-item" data-menu="dropdown"><a class="dropdown-toggle nav-link" href="#"><i class="menu-livicon" data-icon="truck"></i><span>Logística</span></a>
    <ul class="dropdown-menu">        
        <li id="mLogisticsEntryExit" class="dropdown dropdown-submenu" data-menu="dropdown-submenu"><a class="dropdown-item align-items-center" href="#" data-toggle="dropdown"><i class="bx bx-right-arrow-alt"></i>Entrada & Saída</a>
            <ul class="dropdown-menu">
                <li id="mLogisticsEntryExitRequestCargoTranspList" data-menu=""><a class="dropdown-item align-items-center" href="/logistics/request/cargo/transport/list" data-toggle="dropdown"><i class="bx bx-right-arrow-alt"></i>Solic. transporte de carga</a></li>
                <li id="mLogisticsEntryExitApprovList" data-menu=""><a class="dropdown-item align-items-center" href="/logistics/request/cargo/transport/approv/list" data-toggle="dropdown"><i class="bx bx-right-arrow-alt"></i>Aprovar Solic. transporte de carga</a></li>
                <li id="mLogisticsEntryExitVisitorServiceList" data-menu=""><a class="dropdown-item align-items-center" href="/logistics/request/visitor/service/list" data-toggle="dropdown"><i class="bx bx-right-arrow-alt"></i>Solic. Visitante & P.Serviço</a></li>
                <li id="mLogisticsEntryExitVisitorServiceApprovList" data-menu=""><a class="dropdown-item align-items-center" href="/logistics/request/visitor/service/list/approv" data-toggle="dropdown"><i class="bx bx-right-arrow-alt"></i>Aprovar Solic. Visitante & P.Serviço</a></li>
				<li id="mLogisticsMonitorScheduler" data-menu=""><a class="dropdown-item align-items-center" href="/logistics/request/visitor/cargo/monitor" data-toggle="dropdown"><i class="bx bx-right-arrow-alt"></i>Solicitações agendadas</a></li>
                <li id="mEntryExiReceivementMonitor" data-menu=""><a class="dropdown-item align-items-center" href="/receivement/monitor" data-toggle="dropdown"><i class="bx bx-right-arrow-alt"></i>Receb. Monitoramento</a>
            </ul>
        </li>
		<?php if (hasPermManager(26)) { ?>
        <li id="mLogisticsWarehouse" data-menu=""><a class="dropdown-item align-items-center" href="/logistics/warehouse/list" data-toggle="dropdown"><i class="bx bx-right-arrow-alt"></i>Galpões</a></li>
		<li id="mLogisticsWarehouseItens" data-menu=""><a class="dropdown-item align-items-center" href="/logistics/warehouse/entry/exit/items/list" data-toggle="dropdown"><i class="bx bx-right-arrow-alt"></i>Itens - Entrada & Saída</a></li>
		<?php } ?>
        <li id="mLogisticsTransporter" class="dropdown dropdown-submenu" data-menu="dropdown-submenu"><a class="dropdown-item align-items-center" href="#" data-toggle="dropdown"><i class="bx bx-right-arrow-alt"></i>Transportadoras</a>
            <ul class="dropdown-menu">
                <li id="mLogisticsTransporterList" data-menu=""><a class="dropdown-item align-items-center" href="/logistics/transporter/list" data-toggle="dropdown"><i class="bx bx-right-arrow-alt"></i>Todas transportadoras</a></li>
                <li id="mLogisticsTransporterDrivers" data-menu=""><a class="dropdown-item align-items-center" href="/logistics/transporter/driver/list" data-toggle="dropdown"><i class="bx bx-right-arrow-alt"></i>Motoristas</a></li>
                <li id="mLogisticsTransporterVehicle" data-menu=""><a class="dropdown-item align-items-center" href="/logistics/transporter/vehicle/list" data-toggle="dropdown"><i class="bx bx-right-arrow-alt"></i>Veículos</a></li>
                <li id="mLogisticsTransporterCarts" data-menu=""><a class="dropdown-item align-items-center" href="/logistics/transporter/cart/list" data-toggle="dropdown"><i class="bx bx-right-arrow-alt"></i>Carretas</a></li>
            </ul>
        </li>
        <li id="mLogisticsContainer" data-menu=""><a class="dropdown-item align-items-center" href="/logistics/container/list" data-toggle="dropdown"><i class="bx bx-right-arrow-alt"></i>Containers</a></li>
        <li id="mLogisticsEntryExitGuards" data-menu=""><a class="dropdown-item align-items-center" href="/logistics/security/guard/list" data-toggle="dropdown"><i class="bx bx-right-arrow-alt"></i>Vigilantes</a></li>
		<li id="mLogisticsEntryExitSuppliers" data-menu=""><a class="dropdown-item align-items-center" href="/logistics/supplier/list" data-toggle="dropdown"><i class="bx bx-right-arrow-alt"></i>Fornecedores</a></li>
    </ul>
</li>
<?php } ?>