<?php if (hasPerm(23)) { ?>
<li id="mJuridical" class="dropdown nav-item" data-menu="dropdown"><a class="dropdown-toggle nav-link" href="#"><i class="menu-livicon" data-icon="legal"></i><span>Jurídico</span></a>
    <ul class="dropdown-menu">
        <li id="mJuridicalMonitor" data-menu=""><a class="dropdown-item align-items-center" href="/juridical/process/monitor" data-toggle="dropdown"><i class="bx bx-right-arrow-alt"></i>Monitoramento</a></li>
        <li id="mJuridicalProcess" class="dropdown dropdown-submenu" data-menu="dropdown-submenu"><a class="dropdown-item align-items-center" href="#" data-toggle="dropdown"><i class="bx bx-right-arrow-alt"></i>Processos</a>
            <ul class="dropdown-menu">
                <li id="mJuridicalProcessNew" data-menu=""><a class="dropdown-item align-items-center" href="/juridical/process/register/0" data-toggle="dropdown"><i class="bx bx-right-arrow-alt"></i>Novo processo</a></li>
                <li id="mJuridicalProcessList" data-menu=""><a class="dropdown-item align-items-center" href="/juridical/process/list" data-toggle="dropdown"><i class="bx bx-right-arrow-alt"></i>Todos processos</a></li>
                <li id="mJuridicalProcessCost" data-menu=""><a class="dropdown-item align-items-center" href="/juridical/process/cost/list" data-toggle="dropdown"><i class="bx bx-right-arrow-alt"></i>Custos de processo</a></li>
            </ul>
        </li>
        <li id="mJuridicalLawFirm" class="dropdown dropdown-submenu" data-menu="dropdown-submenu"><a class="dropdown-item align-items-center" href="#" data-toggle="dropdown"><i class="bx bx-right-arrow-alt"></i>Escritórios de advocacia</a>
            <ul class="dropdown-menu">
                <li id="mJuridicalLawFirmNew" data-menu=""><a class="dropdown-item align-items-center" href="/juridical/law/firm/register/0" data-toggle="dropdown"><i class="bx bx-right-arrow-alt"></i>Novo escritório</a></li>
                <li id="mJuridicalLawFirmList" data-menu=""><a class="dropdown-item align-items-center" href="/juridical/law/firm/list" data-toggle="dropdown"><i class="bx bx-right-arrow-alt"></i>Todos escritórios</a></li>
                <li id="mJuridicalLawFirmCost" data-menu=""><a class="dropdown-item align-items-center" href="/juridical/law/cost/list" data-toggle="dropdown"><i class="bx bx-right-arrow-alt"></i>Custos de escritório</a></li>
            </ul>
        </li>
        <li id="mJuridicalTypes" class="dropdown dropdown-submenu" data-menu="dropdown-submenu"><a class="dropdown-item align-items-center" href="#" data-toggle="dropdown"><i class="bx bx-right-arrow-alt"></i>Cadastro de Tipos</a>
            <ul class="dropdown-menu">
                <li id="mJuridicalTypeAction" data-menu=""><a class="dropdown-item align-items-center" href="/juridical/type/action/list" data-toggle="dropdown"><i class="bx bx-right-arrow-alt"></i>Tipos de Ação</a></li>
                <li id="mJuridicalTypeDocument" data-menu=""><a class="dropdown-item align-items-center" href="/juridical/type/documents/list" data-toggle="dropdown"><i class="bx bx-right-arrow-alt"></i>Tipos de Documentos</a></li>
                <li id="mJuridicalTypeCost" data-menu=""><a class="dropdown-item align-items-center" href="/juridical/type/cost/list" data-toggle="dropdown"><i class="bx bx-right-arrow-alt"></i>Tipos de Custo</a></li>
            </ul>
        </li>
        <li id="mJuridicalImportProcess" data-menu=""><a class="dropdown-item align-items-center" href="/juridical/process/import" data-toggle="dropdown"><i class="bx bx-right-arrow-alt"></i>Importar processos</a></li>
    </ul>
</li>
<?php } ?>