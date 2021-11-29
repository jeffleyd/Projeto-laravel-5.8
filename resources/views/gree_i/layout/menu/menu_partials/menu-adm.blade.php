                
                <li id="mAdmin" class="dropdown nav-item" data-menu="dropdown"><a class="dropdown-toggle nav-link" href="#" data-toggle="dropdown"><i class="menu-livicon" data-icon="morph-folder"></i><span>{{ __('layout_i.menu_admin') }}</span></a>
                    <ul class="dropdown-menu">
                        <?php if (hasPermManager(2)) { ?>
						<li id="mUser" class="dropdown dropdown-submenu" data-menu="dropdown-submenu"><a class="dropdown-item align-items-center dropdown-toggle" href="#" data-toggle="dropdown"><i class="bx bx-right-arrow-alt"></i>{{ __('layout_i.menu_users') }}</a>
                            <ul class="dropdown-menu">
                                <li id="mUserNew" data-menu=""><a class="dropdown-item align-items-center" href="/user/edit/0" data-toggle="dropdown"><i class="bx bx-right-arrow-alt"></i>{{ __('layout_i.menu_users_new') }}</a>
                                </li>
                                <li id="mUserList" data-menu=""><a class="dropdown-item align-items-center" href="/user/list" data-toggle="dropdown"><i class="bx bx-right-arrow-alt"></i>{{ __('layout_i.menu_users_list') }}</a>
                                </li>
                                <li id="mUserLog" data-menu=""><a class="dropdown-item align-items-center" href="/user/log" data-toggle="dropdown"><i class="bx bx-right-arrow-alt"></i>{{ __('layout_i.menu_users_log') }}</a>
                                </li>
                            </ul>
                        </li>
                        <?php } ?>
						<li id="mEntryExit" class="dropdown dropdown-submenu" data-menu="dropdown-submenu"><a class="dropdown-item align-items-center dropdown-toggle" href="#" data-toggle="dropdown"><i class="bx bx-right-arrow-alt"></i>Entrada & Saída</a>
                            <ul class="dropdown-menu">
                                <li id="mEntryExitVisitant" data-menu=""><a class="dropdown-item align-items-center" href="/logistics/request/visitor/service/list" data-toggle="dropdown"><i class="bx bx-right-arrow-alt"></i>Solic. Visita/Terceiros</a>
                                </li>
                                <li id="mEntryExitEmployees" data-menu=""><a class="dropdown-item align-items-center" href="/adm/entry-exit/employees/list" data-toggle="dropdown"><i class="bx bx-right-arrow-alt"></i>Solic. Funcionários</a>
                                </li>
                                <li id="mEntryExitVehicles" data-menu=""><a class="dropdown-item align-items-center" href="/adm/entry-exit/vehicles/list" data-toggle="dropdown"><i class="bx bx-right-arrow-alt"></i>Solic. Veículos</a>
                                </li>
                                <li id="mEntryExitApprov" data-menu=""><a class="dropdown-item align-items-center" href="#" data-toggle="modal" data-target="#admRequestEntryExit" data-toggle="dropdown"><i class="bx bx-right-arrow-alt"></i>Aprovar solicitações</a>
                                </li>
								<?php if (hasPermManager(27)) { ?>
                                <li id="mEntryExitRentVehicles" data-menu=""><a class="dropdown-item align-items-center" href="/adm/entry-exit/rent/vehicles/list" data-toggle="dropdown"><i class="bx bx-right-arrow-alt"></i>Veículos Alugados</a>
                                </li>
								<li id="mEntryExitApproversVisitant" data-menu=""><a class="dropdown-item align-items-center" href="/logistics/request/visitor/service/approvers" data-toggle="dropdown"><i class="bx bx-right-arrow-alt"></i>Aprovadores Visitante & Prestador</a>
                                </li>
								<?php } ?>
                            </ul>
                        </li>

                        <li id="mTrip" class="dropdown dropdown-submenu" data-menu="dropdown-submenu"><a class="dropdown-item align-items-center dropdown-toggle" href="#" data-toggle="dropdown"><i class="bx bx-right-arrow-alt"></i>{{ __('layout_i.menu_trip') }}</a>
                            <ul class="dropdown-menu">
                                <li id="mTripDashboard" data-menu=""><a class="dropdown-item align-items-center" href="/trip/dashboard" data-toggle="dropdown"><i class="bx bx-right-arrow-alt"></i>{{ __('layout_i.menu_trip_dashboard') }}</a>
                                </li>
                                <li id="mTripNew" data-menu=""><a class="dropdown-item align-items-center" href="/trip/new" data-toggle="dropdown"><i class="bx bx-right-arrow-alt"></i>{{ __('layout_i.menu_trip_new') }}</a>
                                </li>
                                <li id="mTripMy" data-menu=""><a class="dropdown-item align-items-center" href="/trip/my" data-toggle="dropdown"><i class="bx bx-right-arrow-alt"></i>{{ __('layout_i.menu_trip_my') }}</a>
                                </li>
                                <?php if (hasPermManager(1) or hasPermApprov(1)) { ?>
								<li id="mTripAll" data-menu=""><a class="dropdown-item align-items-center" href="/trip/all" data-toggle="dropdown"><i class="bx bx-right-arrow-alt"></i>{{ __('layout_i.menu_trip_all') }}</a>
                                </li>
                                <?php } ?>
								<li id="mTripView" data-menu=""><a class="dropdown-item align-items-center" href="/trip/view" data-toggle="dropdown"><i class="bx bx-right-arrow-alt"></i>{{ __('layout_i.menu_trip_view') }}</a>
                                </li>
                                <?php if (hasPermManager(1) or hasPermApprov(1)) { ?>
								<li id="mTripViewApprov" data-menu=""><a class="dropdown-item align-items-center" href="/trip/view/all" data-toggle="dropdown"><i class="bx bx-right-arrow-alt"></i>{{ __('layout_i.menu_trip_view_approv') }}</a>
                                </li>
                                <li id="mTripAgency" data-menu=""><a class="dropdown-item align-items-center" href="/trip/agency" data-toggle="dropdown"><i class="bx bx-right-arrow-alt"></i>{{ __('layout_i.menu_trip_agency') }}</a>
                                </li>
                                <li id="mTripCredits" data-menu=""><a class="dropdown-item align-items-center" href="/trip/credits" data-toggle="dropdown"><i class="bx bx-right-arrow-alt"></i>{{ __('layout_i.menu_trip_credit') }}</a>
                                </li>
<li id="mTripApprovers" data-menu=""><a class="dropdown-item align-items-center" href="/misc/components/analyze/create/approvers?namespace=App\Model\TripPlan" data-toggle="dropdown"><i class="bx bx-right-arrow-alt"></i>Adicionar aprovadores</a></li>
                                <?php } ?>
                                <?php if (hasPerm(1)) { ?>
								<li id="mTripExport" data-menu=""><a class="dropdown-item align-items-center" href="/trip/export/view" data-toggle="dropdown"><i class="bx bx-right-arrow-alt"></i>{{ __('layout_i.menu_trip_export') }}</a>
                                </li>
                                <?php } ?>
                            </ul>
                        </li>
						<li id="mFinancyLending" class="dropdown dropdown-submenu" data-menu="dropdown-submenu"><a class="dropdown-item align-items-center dropdown-toggle" href="#" data-toggle="dropdown"><i class="bx bx-right-arrow-alt"></i>{{ __('layout_i.menu_lending') }}</a>
                            <ul class="dropdown-menu">
                                <li id="mFinancyLendingReport" data-menu=""><a class="dropdown-item align-items-center" href="/financy/lending/dashboard" data-toggle="dropdown"><i class="bx bx-right-arrow-alt"></i>{{ __('layout_i.menu_lending_report') }}</a>
                                </li>
                                <li id="mFinancyLendingNew" data-menu=""><a class="dropdown-item align-items-center" href="/financy/lending/new" data-toggle="dropdown"><i class="bx bx-right-arrow-alt"></i>{{ __('layout_i.menu_lending_new') }}</a>
                                </li>
                                <li id="mFinancyLendingMy" data-menu=""><a class="dropdown-item align-items-center" href="/financy/lending/my" data-toggle="dropdown"><i class="bx bx-right-arrow-alt"></i>{{ __('layout_i.menu_lending_my') }}</a>
                                </li>
								<li id="mFinancyLendingApprov" data-menu=""><a class="dropdown-item align-items-center" href="/financy/lending/approv" data-toggle="dropdown"><i class="bx bx-right-arrow-alt"></i>{{ __('layout_i.menu_lending_approv') }}</a>
                                </li>
                                <?php if (hasPermManager(9)) { ?>
								<li id="mFinancyLendingAll" data-menu=""><a class="dropdown-item align-items-center" href="/financy/lending/all" data-toggle="dropdown"><i class="bx bx-right-arrow-alt"></i>{{ __('layout_i.menu_lending_all') }}</a>
                                </li>
                                <?php } ?>
								<?php if (hasPermApprov(18)) { ?>
								<li id="mFinancyLendingPerm" data-menu=""><a class="dropdown-item align-items-center" href="/financy/permission/module?mdl=1" data-toggle="dropdown"><i class="bx bx-right-arrow-alt"></i>Permitir usuários</a>
                                </li>
                                <?php } ?>
                                <?php if (hasPerm(9)) { ?>
                                <li id="mFinancyLendingExport" data-menu=""><a class="dropdown-item align-items-center" href="/financy/lending/export" data-toggle="dropdown"><i class="bx bx-right-arrow-alt"></i>{{ __('layout_i.menu_lending_export') }}</a>
                                <?php } ?>

                                <li id="mFinancyAccountability" class="dropdown dropdown-submenu" data-menu="dropdown-submenu"><a class="dropdown-item align-items-center dropdown-toggle" href="#" data-toggle="dropdown"><i class="bx bx-right-arrow-alt"></i>Prestação de contas</a>
                                    <ul class="dropdown-menu">
                                        
										<li id="mFinancyAccountabilityNew" data-menu=""><a class="dropdown-item align-items-center" href="/financy/accountability/edit/0" data-toggle="dropdown"><i class="bx bx-right-arrow-alt"></i>Nova prestação</a></li>
										<li id="mFinancyAccountabilityMy" data-menu=""><a class="dropdown-item align-items-center" href="/financy/accountability/my" data-toggle="dropdown"><i class="bx bx-right-arrow-alt"></i>Minhas prestações</a></li>
										@if (hasPermManager(22))
										<li id="mFinancyAccountabilityAll" data-menu=""><a class="dropdown-item align-items-center" href="/financy/accountability/all" data-toggle="dropdown"><i class="bx bx-right-arrow-alt"></i>Todas prestações</a></li>
										@endif
										<li id="mFinancyAccountabilityAll" data-menu=""><a class="dropdown-item align-items-center" href="/financy/accountability/approv" data-toggle="dropdown"><i class="bx bx-right-arrow-alt"></i>Aprovar prestações</a></li>
										@if (hasPermApprov(22) and hasPermManager(22))
										    <li id="mFinancyPaymentPerm" data-menu=""><a class="dropdown-item align-items-center" href="/financy/permission/module?mdl=4" data-toggle="dropdown"><i class="bx bx-right-arrow-alt"></i>Permitir usuários</a>
										@endif
										@if (hasPermManager(22))
                                        <li id="mFinancyAccountabilityApprovers" data-menu=""><a class="dropdown-item align-items-center" href="/misc/components/analyze/create/approvers?namespace=App\Model\FinancyAccountability" data-toggle="dropdown"><i class="bx bx-right-arrow-alt"></i>Adicionar aprovadores</a></li>
										@endif
                                    </ul>
                                </li>
                                @if (hasPermManager(22))
                                    <li id="mFinancyAccountabilityListDebtors" data-menu=""><a class="dropdown-item align-items-center" href="/financy/list/debtors" data-toggle="dropdown"><i class="bx bx-right-arrow-alt"></i>Listar Devedores</a></li>
                                @endif
								@if (hasPermManager(22))
                                    <li id="mFinancyLendingLimit" data-menu=""><a class="dropdown-item align-items-center" href="/financy/lending/limit" data-toggle="dropdown"><i class="bx bx-right-arrow-alt"></i>Limite de empréstimo</a></li>
                                @endif
                                @if (hasPermManager(22))
                                    <li id="mFinancyLendingApprovers" data-menu=""><a class="dropdown-item align-items-center" href="/misc/components/analyze/create/approvers?namespace=App\Model\FinancyLending" data-toggle="dropdown"><i class="bx bx-right-arrow-alt"></i>Adicionar aprovadores</a></li>
                                @endif    
                            </ul>
                        </li>
                        <?php if (hasPerm(12)) { ?>
                        <li id="mFinancyRefund" class="dropdown dropdown-submenu" data-menu="dropdown-submenu"><a class="dropdown-item align-items-center dropdown-toggle" href="#" data-toggle="dropdown"><i class="bx bx-right-arrow-alt"></i>Reembolso</a>
                            <ul class="dropdown-menu">
                                <li id="mFinancyRefundNew" data-menu=""><a class="dropdown-item align-items-center" href="/financy/refund/edit/0" data-toggle="dropdown"><i class="bx bx-right-arrow-alt"></i>Novo reembolso</a>
                                </li>
                                <li id="mFinancyRefundMy" data-menu=""><a class="dropdown-item align-items-center" href="/financy/refund/my" data-toggle="dropdown"><i class="bx bx-right-arrow-alt"></i>Meus reembolsos</a>
                                </li>
                                <li id="mFinancyRefundApprov" data-menu=""><a class="dropdown-item align-items-center" href="/financy/refund/approv" data-toggle="dropdown"><i class="bx bx-right-arrow-alt"></i>Aprovar reembolso</a>
                                </li>
                                <?php if (hasPermManager(12) ) { ?>
                                <li id="mFinancyRefundAll" data-menu=""><a class="dropdown-item align-items-center" href="/financy/refund/all" data-toggle="dropdown"><i class="bx bx-right-arrow-alt"></i>Todos reembolsos</a>
                                </li>
                                <?php } ?>
								<?php if (hasPermApprov(18)) { ?>
								<li id="mFinancyRefundPerm" data-menu=""><a class="dropdown-item align-items-center" href="/financy/permission/module?mdl=2" data-toggle="dropdown"><i class="bx bx-right-arrow-alt"></i>Permitir usuários</a>
                                </li>
                                <?php } ?>
								@if (hasPermManager(22))
                                    <li id="mFinancyRefundApprovers" data-menu=""><a class="dropdown-item align-items-center" href="/misc/components/analyze/create/approvers?namespace=App\Model\FinancyRefund" data-toggle="dropdown"><i class="bx bx-right-arrow-alt"></i>Adicionar aprovadores</a></li>
                                @endif 
                            </ul>
                        </li>
                        <?php } ?>
                        <?php if (hasPerm(11)) { ?>
                        <li id="mFinancyPayment" class="dropdown dropdown-submenu" data-menu="dropdown-submenu"><a class="dropdown-item align-items-center dropdown-toggle" href="#" data-toggle="dropdown"><i class="bx bx-right-arrow-alt"></i>Pagamento</a>
                            <ul class="dropdown-menu">
                                <!--<li id="mFinancyPaymentNew" data-menu=""><a class="dropdown-item align-items-center" href="/financy/payment/edit/0" data-toggle="dropdown"><i class="bx bx-right-arrow-alt"></i>Nova solicitação</a>
                                </li>-->
                                <li id="mFinancyPaymentMy" data-menu=""><a class="dropdown-item align-items-center" href="/financy/payment/my" data-toggle="dropdown"><i class="bx bx-right-arrow-alt"></i>Minhas solicitações</a>
                                </li>
                                <?php if (hasPermApprov(11)) { ?>
                                <li id="mFinancyPaymentApprov" data-menu=""><a class="dropdown-item align-items-center" href="/financy/payment/approv" data-toggle="dropdown"><i class="bx bx-right-arrow-alt"></i>Aprovar pagamentos</a>
                                </li>
								<li id="mFinancyPaymentSupervisor" data-menu=""><a class="dropdown-item align-items-center" href="/financy/payment/supervisor/approv" data-toggle="dropdown"><i class="bx bx-right-arrow-alt"></i>Antecipar Análise Fiscal</a>
                                </li>
                                <?php } ?>
                                <?php if (hasPermManager(11)) { ?>
                                <li id="mFinancyPaymentTransfer" data-menu=""><a class="dropdown-item align-items-center" href="/financy/payment/transfer" data-toggle="dropdown"><i class="bx bx-right-arrow-alt"></i>Transferir pagamento</a>
                                </li>
                                <?php } ?>
                                <?php if (hasPermManager(11)) { ?>
                                <li id="mFinancyPaymentAll" data-menu=""><a class="dropdown-item align-items-center" href="/financy/payment/all" data-toggle="dropdown"><i class="bx bx-right-arrow-alt"></i>Todos pagamentos</a>
                                </li>
								<?php if (hasPermApprov(18)) { ?>
								<li id="mFinancyPaymentPerm" data-menu=""><a class="dropdown-item align-items-center" href="/financy/permission/module?mdl=3" data-toggle="dropdown"><i class="bx bx-right-arrow-alt"></i>Permitir usuários</a>
                                </li>
                                <?php } ?>
								<li id="mFinancyPaymentExport" data-menu=""><a class="dropdown-item align-items-center" href="/financy/payment/export" data-toggle="dropdown"><i class="bx bx-right-arrow-alt"></i>Exportar pagamentos</a>
                                </li>
                                <?php } ?>
                            </ul>
                        </li>
                        <?php } ?>
						<li id="mrequests" class="dropdown dropdown-submenu" data-menu="dropdown-submenu"><a class="dropdown-item align-items-center dropdown-toggle" href="#" data-toggle="dropdown"><i class="bx bx-right-arrow-alt"></i>Solic. P/ Aprovação</a>
                            <ul class="dropdown-menu">
                                <li id="mrequestsNew" data-menu=""><a class="dropdown-item align-items-center" href="/administration/generic/request/view" data-toggle="dropdown"><i class="bx bx-right-arrow-alt"></i>Nova solicitação</a>
                                </li>
                                <li id="mrequestsAll" data-menu=""><a class="dropdown-item align-items-center" href="/administration/generic/request/list" data-toggle="dropdown"><i class="bx bx-right-arrow-alt"></i>Minhas solicitações</a>
                                </li>
								<li id="mrequestsApprov" data-menu=""><a class="dropdown-item align-items-center" href="/administration/generic/request/approv" data-toggle="dropdown"><i class="bx bx-right-arrow-alt"></i>Minhas aprovações</a>
                                </li>
								<li id="mrequestsObs" data-menu=""><a class="dropdown-item align-items-center" href="/administration/generic/request/observer" data-toggle="dropdown"><i class="bx bx-right-arrow-alt"></i>Acompanhar Solic.</a>
                                </li>
                                <li id="mrequestsSearch" data-menu=""><a data-toggle="modal" data-target="#admRequestSearch" class="dropdown-item align-items-center" href="javascript:void(0)" data-toggle="dropdown"><i class="bx bx-right-arrow-alt"></i>Buscar Código</a>
                                </li>
                            </ul>
                        </li>
						<li id="mTask" class="dropdown dropdown-submenu" data-menu="dropdown-submenu"><a class="dropdown-item align-items-center dropdown-toggle" href="#" data-toggle="dropdown"><i class="bx bx-right-arrow-alt"></i>{{ __('layout_i.menu_project') }}</a>
                            <ul class="dropdown-menu">
                                <?php if (hasPermApprov(3)) { ?>
                                <li id="mTaskNew" data-menu=""><a class="dropdown-item align-items-center" href="/task/0" data-toggle="dropdown"><i class="bx bx-right-arrow-alt"></i>{{ __('layout_i.menu_project_new') }}</a>
                                </li>
                                <?php } ?>
                                <li id="mTaskMy" data-menu=""><a class="dropdown-item align-items-center" href="/task/view/my" data-toggle="dropdown"><i class="bx bx-right-arrow-alt"></i>{{ __('layout_i.menu_project_my') }}</a>
                                </li>
                                <li id="mTaskExport" data-menu=""><a class="dropdown-item align-items-center" href="/task/view/export" data-toggle="dropdown"><i class="bx bx-right-arrow-alt"></i>{{ __('layout_i.menu_project_export') }}</a>
                                </li>
                            </ul>
                        </li>
                        <?php if (hasPerm(13)) { ?>
                        <li id="msurvey" class="dropdown dropdown-submenu" data-menu="dropdown-submenu"><a class="dropdown-item align-items-center dropdown-toggle" href="#" data-toggle="dropdown"><i class="bx bx-right-arrow-alt"></i>Pesquisas (Survey)</a>
                            <ul class="dropdown-menu">
                                <li id="msurveyNew" data-menu=""><a class="dropdown-item align-items-center" href="/survey/edit/0" data-toggle="dropdown"><i class="bx bx-right-arrow-alt"></i>Nova pesquisa</a>
                                </li>
                                <li id="msurveyAll" data-menu=""><a class="dropdown-item align-items-center" href="/survey/all" data-toggle="dropdown"><i class="bx bx-right-arrow-alt"></i>Todas pesquisas</a>
                                </li>
                                <li id="msurveyAnswer" data-menu=""><a class="dropdown-item align-items-center" href="/survey/answers" data-toggle="dropdown"><i class="bx bx-right-arrow-alt"></i>Respostas</a>
                                </li>
                            </ul>
                        </li>
                        <?php } ?>
						<li id="mReservate" class="dropdown dropdown-submenu" data-menu="dropdown-submenu">
							<a class="dropdown-item align-items-center dropdown-toggle"
								href="/administration/reservation/meetroom" data-toggle="dropdown">
								<i class="bx bx-right-arrow-alt">
								</i>Reservar Sala de Reunião
							</a>
							<ul class="dropdown-menu">
								<li id="mReservateView" data-menu=""><a class="dropdown-item align-items-center"
										href="/administration/reservation/meetroom" data-toggle="dropdown">
										<i class="bx bx-right-arrow-alt"></i>Quadro de Horário</a>
								</li>
								<li id="rmrAnalyzeView" data-menu=""><a class="dropdown-item align-items-center"
										href="/administration/reservation/meetroom/analyze" data-toggle="dropdown">
										<i class="bx bx-right-arrow-alt"></i>Aprovar Reserva</a>
								</li>
							</ul>
						</li>
						
						@include('gree_i.layout.menu.menu_partials.menu-surveys')
                    </ul>
                </li>