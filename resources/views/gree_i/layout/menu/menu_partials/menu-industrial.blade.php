                <?php if (hasPerm(14)) { ?>
                <li id="mIndustrial" class="dropdown nav-item" data-menu="dropdown"><a class="dropdown-toggle nav-link" href="#"><i class="menu-livicon" data-icon="wrench"></i><span>Industrial</span></a>
                    <ul class="dropdown-menu">
                        <?php if (hasPerm(15)) { ?>
                        <li id="mEngineering" class="dropdown dropdown-submenu" data-menu="dropdown-submenu"><a class="dropdown-item align-items-center dropdown-toggle" href="#" data-toggle="dropdown"><i class="bx bx-right-arrow-alt"></i>Engenharia</a>
                            <ul class="dropdown-menu">
                                <li id="mEngineeringNewItem" data-menu=""><a class="dropdown-item align-items-center" href="/engineering/product/edit/0" data-toggle="dropdown"><i class="bx bx-right-arrow-alt"></i>Novo produto</a>
                                </li>
                                <li id="mEngineeringAllItem" data-menu=""><a class="dropdown-item align-items-center" href="/engineering/product/all" data-toggle="dropdown"><i class="bx bx-right-arrow-alt"></i>Todos produtos</a>
                                </li>
                                <li id="mEngineeringNewPart" data-menu=""><a class="dropdown-item align-items-center" href="/engineering/part/edit/0" data-toggle="dropdown"><i class="bx bx-right-arrow-alt"></i>Nova peça</a>
                                </li>
                                <li id="mEngineeringAllPart" data-menu=""><a class="dropdown-item align-items-center" href="/engineering/part/all" data-toggle="dropdown"><i class="bx bx-right-arrow-alt"></i>Todos peças</a>
                                </li>
								<li id="mEngineeringImportPart" data-menu=""><a class="dropdown-item align-items-center" href="/engineering/part/import" data-toggle="dropdown"><i class="bx bx-right-arrow-alt"></i>Importar peças</a>
								</li>
								<li id="mEngineeringAllTypes" data-menu=""><a class="dropdown-item align-items-center" href="/engineering/type" data-toggle="dropdown"><i class="bx bx-right-arrow-alt"></i>Tipos de linhas</a>
                                </li>
                            </ul>
                        </li>
                        <?php } ?>
                    </ul>
                </li>
                <?php } ?>