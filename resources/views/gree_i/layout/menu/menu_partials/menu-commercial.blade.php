<?php if (hasPerm(20)) { ?>
<li id="mCommercial" class="dropdown nav-item" data-menu="dropdown"><a class="dropdown-toggle nav-link" href="#"><i class="menu-livicon" data-icon="users"></i><span>Comercial</span></a>
                    <ul class="dropdown-menu">
						<li id="mCommercialOperation" class="dropdown"><a class="dropdown-item align-items-center" href="/commercial/operation/dashboard/general" data-toggle="dropdown"><i class="bx bx-right-arrow-alt"></i>Operacional</a>
                        </li>
						<li id="mCommercialOperation" class="dropdown"><a class="dropdown-item align-items-center" href="/comercial/operacao/login" target="_blank"><i class="bx bx-right-arrow-alt"></i>Gerência & Coordenadores</a>
						</i>
                        <li id="mCommercialPromoter" class="dropdown dropdown-submenu" data-menu="dropdown-submenu"><a class="dropdown-item align-items-center" href="#" data-toggle="dropdown"><i class="bx bx-right-arrow-alt"></i>Promotor</a>
                            <ul class="dropdown-menu">
                                <li id="mCommercialPromoterMonitor" data-menu=""><a class="dropdown-item align-items-center" href="/commercial/promoter/monitor" data-toggle="dropdown"><i class="bx bx-right-arrow-alt"></i>Monitoramento</a>
                                </li>
                                <li id="mCommercialPromoterRoutes" data-menu=""><a class="dropdown-item align-items-center" href="/commercial/promoter/routes/all" data-toggle="dropdown"><i class="bx bx-right-arrow-alt"></i>Planejar rotas</a>
                                </li>
                                <li id="mCommercialPromoterRequestItem" data-menu=""><a class="dropdown-item align-items-center" href="/commercial/promoter/request/item/all" data-toggle="dropdown"><i class="bx bx-right-arrow-alt"></i>Solic. de item</a>
                                </li>
                                <li id="mCommercialPromoterUsers" data-menu=""><a class="dropdown-item align-items-center" href="/commercial/promoter/user/all" data-toggle="dropdown"><i class="bx bx-right-arrow-alt"></i>Usuários</a>
                                </li>
                            </ul>
                        </li>
                        {{-- <li id="mTIMaintenance" data-menu=""><a class="dropdown-item align-items-center" href="/ti/maintenance/todo" data-toggle="dropdown"><i class="bx bx-right-arrow-alt"></i>Manutenção de hardware</a>
                        </li> --}}
                    </ul>
                </li>
<?php } ?>