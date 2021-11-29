    <nav class="header-navbar navbar-expand-lg navbar navbar-with-menu navbar-fixed bg-primary navbar-brand-center">
        <div class="navbar-header d-xl-block d-none">
            <ul class="nav navbar-nav flex-row">
                <li class="nav-item"><a class="navbar-brand" href="/news">
                        <div class="brand-logo"><img class="logo" src="/admin/app-assets/images/logo/logo_gree.png"></div>
                        <h2 class="brand-text mb-0"></h2>
                    </a></li>
            </ul>
        </div>
        <div class="navbar-wrapper">
            <div class="navbar-container content">
                <div class="navbar-collapse" id="navbar-mobile">
                    <div class="mr-auto float-left bookmark-wrapper d-flex align-items-center">
                        <ul class="nav navbar-nav">
                            <li class="nav-item mobile-menu mr-auto"><a class="nav-link nav-menu-main menu-toggle" href="#"><i class="bx bx-menu"></i></a></li>
                        </ul>
                    </div>
                    <ul class="nav navbar-nav float-right d-flex align-items-center">
                        <li class="dropdown dropdown-language nav-item" id="Userlang">
							<a class="dropdown-toggle nav-link" id="dropdown-flag" href="#" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                <?php if (Session::get('lang') == 'pt-br') { ?>
                                <i class="flag-icon flag-icon-br"></i><span class="selected-language d-lg-inline d-none">Português</span>
                                <?php } else { ?>
                                <i class="flag-icon flag-icon-us"></i><span class="selected-language d-lg-inline d-none">English</span>
                                <?php } ?>
							</a>
                            <div class="dropdown-menu" aria-labelledby="dropdown-flag">
								<a class="dropdown-item" href="/locale/pt-br" data-language="br"><i class="flag-icon flag-icon-br mr-50"></i>Português</a>
								<a class="dropdown-item" href="/locale/en" data-language="en"><i class="flag-icon flag-icon-us mr-50"></i>English</a>
							</div>
                        </li>
                        <li class="nav-item d-none d-lg-block" id="UserFull"><a class="nav-link nav-link-expand"><i class="ficon bx bx-fullscreen"></i></a></li>
                        <li class="dropdown dropdown-notification nav-item" id="Usernotify"><a class="nav-link nav-link-label" href="#" data-toggle="dropdown"><i class="ficon bx bx-bell"></i><span class="badge badge-pill badge-danger badge-up" style="display: none">0</span></a>
                            <ul class="dropdown-menu dropdown-menu-media dropdown-menu-right">
                                <li class="dropdown-menu-header">
                                    <div class="dropdown-header px-1 py-75 d-flex justify-content-between"><span class="notification-title notifyTotal"></span><span class="text-bold-400 cursor-pointer readNotify">{{ __('layout_i.srt_mark_all') }}</span></div>
                                </li>
                                <li class="scrollable-container media-list notify">
                                    
                                </li>
                                <li class="dropdown-menu-footer"><a class="dropdown-item p-50 text-primary justify-content-center" href="/user/notifications">{{ __('layout_i.srt_see_all') }}</a></li>
                            </ul>
                        </li>
                        <li class="dropdown dropdown-user nav-item" id="Userinfo"><a class="dropdown-toggle nav-link dropdown-user-link" href="#" data-toggle="dropdown">
                                <div class="user-nav d-lg-flex d-none"><span class="user-name">{{ getNameFormated() }}</span><span class="user-status"><b>{{ __('layout_i.registration') }}</b> {{ session('r_code') }}</span></div><span><img style="object-fit: cover;" class="round" src="<?php if (empty(Session::get('picture'))) { ?>/media/avatars/avatar10.jpg<?php } else { ?>{{ Session::get('picture') }}<?php } ?>" alt="avatar" height="40" width="40"></span>
                            </a>
                            <div class="dropdown-menu dropdown-menu-right pb-0">
								<a class="dropdown-item" href="/user/edit/<?= Session::get('r_code') ?>">
									<i class="bx bx-user mr-50"></i> {{ __('layout_i.menu_my_profile') }}
								</a>
								<a class="dropdown-item" href="/task/view/my">
									<i class="bx bx-check-square mr-50"></i> {{ __('layout_i.menu_my_task') }}
								</a>
								<a class="dropdown-item" href="/logout">
									<i class="bx bx-power-off mr-50"></i> {{ __('layout_i.srt_exit') }}
								</a>
                            </div>
                        </li>
                    </ul>
                </div>
            </div>
        </div>
    </nav>