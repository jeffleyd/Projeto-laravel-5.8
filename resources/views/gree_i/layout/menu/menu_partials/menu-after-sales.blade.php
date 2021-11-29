                
                
                <?php if (hasPerm(6)) { ?>
                <li id="mAfterSales" class="dropdown nav-item" data-menu="dropdown"><a class="dropdown-toggle nav-link" href="#"><i class="menu-livicon" data-icon="headphones"></i><span>Pós-venda</span></a>
                    <ul class="dropdown-menu">
                        <li id="mSac" class="dropdown dropdown-submenu" data-menu="dropdown-submenu"><a class="dropdown-item align-items-center dropdown-toggle" href="#" data-toggle="dropdown"><i class="bx bx-right-arrow-alt"></i>SAC</a>
                            <ul class="dropdown-menu">
                                <li id="mSacDashboard" data-menu=""><a class="dropdown-item align-items-center" href="/sac/monitor" data-toggle="dropdown"><i class="bx bx-right-arrow-alt"></i>Monitoramento</a>
                                </li>
								<li id="mSacAuthorizedMonitor" data-menu=""><a class="dropdown-item align-items-center" href="/sac/authorized/monitor" data-toggle="dropdown"><i class="bx bx-right-arrow-alt"></i>Monitoramento Credenciadas</a>
                                </li>
                                <li id="mSacOSNew" data-menu=""><a class="dropdown-item align-items-center" href="/sac/warranty/edit/0" data-toggle="dropdown"><i class="bx bx-right-arrow-alt"></i>Novo protocolo</a>
                                </li>
                                <li id="mSacAll" data-menu=""><a class="dropdown-item align-items-center" href="/sac/warranty/all" data-toggle="dropdown"><i class="bx bx-right-arrow-alt"></i>Lista de protocolos</a>
                                </li>
								<li id="mSacOSAll" data-menu=""><a class="dropdown-item align-items-center" href="/sac/problemcategory" data-toggle="dropdown"><i class="bx bx-right-arrow-alt"></i>Categoria de Problemas</a>
                                </li>
                                <li id="mSacClient" data-menu=""><a class="dropdown-item align-items-center" href="/sac/client/edit/0" data-toggle="dropdown"><i class="bx bx-right-arrow-alt"></i>Novo cliente</a>
                                </li>
                                <li id="mSacClientAll" data-menu=""><a class="dropdown-item align-items-center" href="/sac/client/all" data-toggle="dropdown"><i class="bx bx-right-arrow-alt"></i>Todos clientes</a>
                                </li>
                                <?php if (hasPermApprov(6)) { ?>
                                <li id="mSacAuthorized" data-menu=""><a class="dropdown-item align-items-center" href="/sac/authorized/edit/0" data-toggle="dropdown"><i class="bx bx-right-arrow-alt"></i>Nova autorizada</a>
                                </li>
                                <li id="mSacAuthorizedAll" data-menu=""><a class="dropdown-item align-items-center" href="/sac/authorized/all" data-toggle="dropdown"><i class="bx bx-right-arrow-alt"></i>Todas autorizadas</a>
                                </li>
                                <?php } ?>
                            </ul>
                        </li>
						<?php if (hasPerm(16)) { ?>
                        <li id="mTAssist" class="dropdown dropdown-submenu" data-menu="dropdown-submenu"><a class="dropdown-item align-items-center dropdown-toggle" href="#" data-toggle="dropdown"><i class="bx bx-right-arrow-alt"></i>Assist. Técnica</a>
                            <ul class="dropdown-menu">
								<li id="mSacOsDashboard" data-menu=""><a class="dropdown-item align-items-center" href="/sac/warranty/os/monitor" data-toggle="dropdown"><i class="bx bx-right-arrow-alt"></i>Monitoramento</a>
                                </li>
								<?php if (hasPermApprov(16)) { ?>
                                <li id="mTAssistOSPartApprov" data-menu=""><a class="dropdown-item align-items-center" href="/sac/warranty/approv" data-toggle="dropdown"><i class="bx bx-right-arrow-alt"></i>Aprovar envio de peças</a>
                                </li>
								<?php } ?>
                                <li id="mTAssistOsAll" data-menu=""><a class="dropdown-item align-items-center" href="/sac/warranty/os/all" data-toggle="dropdown"><i class="bx bx-right-arrow-alt"></i>Todas O.S</a>
                                </li>
								<li id="mTAssistRemittance" data-menu=""><a class="dropdown-item align-items-center" href="/sac/assistance/remittance/all" data-toggle="dropdown"><i class="bx bx-right-arrow-alt"></i>Remessa de peças</a>
								</li>
								<li id="mTAssistCosts" data-menu=""><a class="dropdown-item align-items-center" href="/sac/assistance/warranty/os/costs/all" data-toggle="dropdown"><i class="bx bx-right-arrow-alt"></i>Central de custos</a></li>
                                <?php if (hasPermApprov(6) and Session::get('filter_line') == 1) { ?>
                                <li id="mTAssistOsPaid" data-menu=""><a class="dropdown-item align-items-center" href="/sac/warranty/os/paid" data-toggle="dropdown"><i class="bx bx-right-arrow-alt"></i>Pagar O.S</a>
                                </li>
								<?php } ?>
								<?php if (hasPermManager(6)) { ?>
                                <li id="mTAssistOB" data-menu=""><a class="dropdown-item align-items-center" href="/sac/warranty/ob" data-toggle="dropdown"><i class="bx bx-right-arrow-alt"></i>Ordem de compra</a>
                                </li>
                                <?php } ?>
                            </ul>
                        </li>
						<?php } ?>
                        <?php if (hasPerm(17)) { ?>
                        <li id="mSacExpedition" class="dropdown dropdown-submenu" data-menu="dropdown-submenu"><a class="dropdown-item align-items-center dropdown-toggle" href="#" data-toggle="dropdown"><i class="bx bx-right-arrow-alt"></i>Expedição</a>
                            <ul class="dropdown-menu">
                                <li id="mSacExpeditionPending" data-menu=""><a class="dropdown-item align-items-center" href="/sac/expedition/pending" data-toggle="dropdown"><i class="bx bx-right-arrow-alt"></i>Peças para envio</a>
                                </li>
                                <li id="mSacExpeditionTrack" data-menu=""><a class="dropdown-item align-items-center" href="/sac/expedition/track" data-toggle="dropdown"><i class="bx bx-right-arrow-alt"></i>Peças que chegaram ou a caminho </a>
                                </li>
                            </ul>
                        </li>
                        <?php } ?>
                        <li id="mSacMap" data-menu=""><a class="dropdown-item align-items-center" href="/sac/map/global" data-toggle="dropdown"><i class="bx bx-right-arrow-alt"></i>Mapa global</a>
                        </li>
                        <?php if (hasPermApprov(6)) { ?>
                        <li id="mSacComunication" class="dropdown dropdown-submenu" data-menu="dropdown-submenu"><a class="dropdown-item align-items-center dropdown-toggle" href="#" data-toggle="dropdown"><i class="bx bx-right-arrow-alt"></i>Comunicação</a>
                            <ul class="dropdown-menu">
                                <li id="mSacComunicationAuthorized" data-menu=""><a class="dropdown-item align-items-center" href="/sac/comunication/authorized/all" data-toggle="dropdown"><i class="bx bx-right-arrow-alt"></i>Notificar autorizadas</a>
                                </li>
                                <li id="mSacComunicationFaq" data-menu=""><a class="dropdown-item align-items-center" href="/sac/faq/all" data-toggle="dropdown"><i class="bx bx-right-arrow-alt"></i>Perguntas frequentes</a>
                                </li>
                            </ul>
                        </li>
						<li id="mSacRegister" class="dropdown dropdown-submenu" data-menu="dropdown-submenu"><a class="dropdown-item align-items-center dropdown-toggle" href="#" data-toggle="dropdown"><i class="bx bx-right-arrow-alt"></i>Site cadastros</a>
                            <ul class="dropdown-menu">
                                <li id="mSacRegisterShop" data-menu=""><a class="dropdown-item align-items-center" href="/sac/register/shop/all" data-toggle="dropdown"><i class="bx bx-right-arrow-alt"></i>Lojas</a>
                                </li>
								<li id="mSacRegisterShopPart" data-menu=""><a class="dropdown-item align-items-center" href="/sac/register/shop-parts/all" data-toggle="dropdown"><i class="bx bx-right-arrow-alt"></i>Lojas de peças</a>
                                </li>
								<li id="mSacRegisterSalesman" data-menu=""><a class="dropdown-item align-items-center" href="/sac/register/salesman/all" data-toggle="dropdown"><i class="bx bx-right-arrow-alt"></i>Representante</a>
                                </li>
                            </ul>
                        </li>
                        <?php } ?>
						
						<?php if (hasPermApprov(19)) { ?>
						<li id="mQRCode" class="dropdown dropdown-submenu" data-menu="dropdown-submenu"><a class="dropdown-item align-items-center dropdown-toggle" href="#" data-toggle="dropdown"><i class="bx bx-right-arrow-alt"></i>QR Code</a>
                            <ul class="dropdown-menu">
                                
                                <li id="mQRCodeListApprov" data-menu=""><a class="dropdown-item align-items-center" href="/qr_code/list/approv" data-toggle="dropdown"><i class="bx bx-right-arrow-alt"></i>Aprovar Solicitações</a>
                                </li>
                                <li id="mQRCodeListAll" data-menu=""><a class="dropdown-item align-items-center" href="/qr_code/list/all" data-toggle="dropdown"><i class="bx bx-right-arrow-alt"></i>Todas as Solicitações</a>                                </li>
                            </ul>
                        </li>
                        <?php } ?>
						
                        <?php if (hasPermApprov(16)) { ?>
                        <li id="mSACConfing" data-menu=""><a class="dropdown-item align-items-center" href="/sac/config" data-toggle="dropdown"><i class="bx bx-right-arrow-alt"></i>Configurações</a>
                        </li>
                        <?php } ?>
                    </ul>
                </li>
                <?php } ?>