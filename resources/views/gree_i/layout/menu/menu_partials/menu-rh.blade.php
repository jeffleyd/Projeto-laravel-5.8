<li id="mRH" class="dropdown nav-item" data-menu="dropdown"><a class="dropdown-toggle nav-link" href="#" data-toggle="dropdown"><i class="menu-livicon" data-icon="briefcase"></i><span>RH</span></a>
    <ul class="dropdown-menu">
    <?php if (hasPerm(10)) { ?>
    <li id="mHomeOffice" class="dropdown dropdown-submenu" data-menu="dropdown-submenu"><a class="dropdown-item align-items-center dropdown-toggle" href="#" data-toggle="dropdown"><i class="bx bx-right-arrow-alt"></i>{{ __('layout_i.menu_homeoffice') }}</a>
        <ul class="dropdown-menu">
            <li id="mHomeOfficeNew" data-menu=""><a class="dropdown-item align-items-center" href="/home-office" data-toggle="dropdown"><i class="bx bx-right-arrow-alt"></i>{{ __('layout_i.menu_homeoffice_cron') }}</a>
            </li>
            <li id="mHomeOfficeMy" data-menu=""><a class="dropdown-item align-items-center" href="/home-office/my" data-toggle="dropdown"><i class="bx bx-right-arrow-alt"></i>{{ __('layout_i.menu_homeoffice_report') }}</a>
            </li>
            <?php if (hasPermApprov(10) or hasPermManager(10)) { ?>
            <li id="mHomeOfficeData" data-menu=""><a class="dropdown-item align-items-center" href="/home-office/data" data-toggle="dropdown"><i class="bx bx-right-arrow-alt"></i>{{ __('layout_i.menu_homeoffice_data') }}</a>
            </li>
            <li id="mHomeOfficeOnline" data-menu=""><a class="dropdown-item align-items-center" href="/home-office/online" data-toggle="dropdown"><i class="bx bx-right-arrow-alt"></i>{{ __('layout_i.menu_homeoffice_online') }}</a>
            </li>
            <?php } ?>
        </ul>
    </li>
    <?php } ?>
    <li id="mHourExtra" class="dropdown dropdown-submenu" data-menu="dropdown-submenu"><a class="dropdown-item align-items-center dropdown-toggle" href="#" data-toggle="dropdown"><i class="bx bx-right-arrow-alt"></i>Hora Extra</a>
        <ul class="dropdown-menu">
            <li id="mHourExtraNew" data-menu=""><a class="dropdown-item align-items-center" href="/hour-extra/new" data-toggle="dropdown"><i class="bx bx-right-arrow-alt"></i>Nova hora extra</a>
            </li>
            <li id="mHourExtraMy" data-menu=""><a class="dropdown-item align-items-center" href="/hour-extra/my" data-toggle="dropdown"><i class="bx bx-right-arrow-alt"></i>Minhas horas extras</a>
            </li>
			
			<?php if (hasPermApprov(24)) { ?>
            <li id="mHourExtraApprov" data-menu=""><a class="dropdown-item align-items-center" href="/hour-extra/approv" data-toggle="dropdown"><i class="bx bx-right-arrow-alt"></i>Aprovar solicitação</a>
            </li>
			<?php } ?>
			<?php if (hasPermManager(24)) { ?>
            <li id="mHourExtraAll" data-menu=""><a class="dropdown-item align-items-center" href="/hour-extra/all" data-toggle="dropdown"><i class="bx bx-right-arrow-alt"></i>Todas solicitações</a>
            </li>
			<?php } ?>
        </ul>
    </li>
		<?php if (hasPerm(25)) { ?>
		<li id="mQuestion" class="dropdown dropdown-submenu" data-menu="dropdown-submenu"><a class="dropdown-item align-items-center dropdown-toggle" href="#" data-toggle="dropdown"><i class="bx bx-right-arrow-alt"></i>Prova de recrutamento</a>
			<ul class="dropdown-menu">
				<li id="mQuestionNew" data-menu="">
					<a class="dropdown-item align-items-center" href="/recruitment/question/new/0" data-toggle="dropdown"><i class="bx bx-right-arrow-alt"></i>Nova prova</a>
				</li>
				<li id="mQuestionAll" data-menu="">
					<a class="dropdown-item align-items-center" href="/recruitment/question/all" data-toggle="dropdown"><i class="bx bx-right-arrow-alt"></i>Todos as provas</a>
				</li>
			</ul>
		</li>
		<?php } ?>
		<?php if (hasPerm(24)) { ?>
        <li id="mNotifyCollaborator" class="dropdown dropdown-submenu" data-menu="dropdown-submenu"><a class="dropdown-item align-items-center dropdown-toggle" href="#" data-toggle="dropdown"><i class="bx bx-right-arrow-alt"></i>Liberar avisos e Notificar</a>
            <ul class="dropdown-menu">
                <?php if (hasPermManager(24)) { ?>
                <li id="mLiberateCollaborator" data-menu="">
                    <a class="dropdown-item align-items-center" href="/notify/collaborator/liberate" data-toggle="dropdown"><i class="bx bx-right-arrow-alt"></i>Liberar avisos para colaborador</a>
                </li>
                <?php } ?>
                <li id="mNewNotifyCollaborator" data-menu="">
                    <a class="dropdown-item align-items-center" href="/notify/collaborator/new" data-toggle="dropdown"><i class="bx bx-right-arrow-alt"></i>Notificar colaborador</a>
                </li>
            </ul>
        </li>
        <?php } ?>
    </ul>
</li>
