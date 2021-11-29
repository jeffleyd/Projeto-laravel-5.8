

    <!-- BEGIN: Main Menu-->
    <div class="header-navbar navbar-expand-sm navbar navbar-horizontal navbar-fixed navbar-light navbar-without-dd-arrow" role="navigation" data-menu="menu-wrapper">
        <div class="navbar-header d-xl-none d-block">
            <ul class="nav navbar-nav flex-row">
                <li class="nav-item mr-auto"><a class="navbar-brand" href="/news">
                        <div class="brand-logo"><img class="logo" src="/admin/app-assets/images/logo/logo_gree_blue.png" /></div>
                        <h2 class="brand-text mb-0"></h2>
                    </a></li>
                <li class="nav-item nav-toggle"><a class="nav-link modern-nav-toggle pr-0" data-toggle="collapse"><i class="bx bx-x d-block d-xl-none font-medium-4 primary toggle-icon"></i></a></li>
            </ul>
        </div>
        <div class="shadow-bottom"></div>
        <!-- Horizontal menu content-->
        <div class="navbar-container main-menu-content" data-menu="menu-container">
            <!-- include includes/mixins-->
            <ul class="nav navbar-nav" id="main-menu-navigation" data-menu="menu-navigation" data-icon-style="filled">
                
                @include('gree_i.layout.menu.menu_partials.menu-news')
                @include('gree_i.layout.menu.menu_partials.menu-adm')
				@include('gree_i.layout.menu.menu_partials.menu-commercial')
				
				@include('gree_i.layout.menu.menu_partials.menu-rh')
                
				@include('gree_i.layout.menu.menu_partials.menu-after-sales')

                @include('gree_i.layout.menu.menu_partials.menu-industrial')

                {{-- @include('gree_i.layout.menu.menu_partials.menu-financy') --}}

                @include('gree_i.layout.menu.menu_partials.menu-ti')
				
				@include('gree_i.layout.menu.menu_partials.menu-juridical')
				
				@include('gree_i.layout.menu.menu_partials.menu-logistics')
				
            </ul>
        </div>
        <!-- /horizontal menu content-->
    </div>
    <!-- END: Main Menu-->
