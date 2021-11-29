<!DOCTYPE html>
<!--[if lt IE 7]>  <html class="ie ie6 lte9 lte8 lte7 no-js"> <![endif]-->
<!--[if IE 7]>     <html class="ie ie7 lte9 lte8 lte7 no-js"> <![endif]-->
<!--[if IE 8]>     <html class="ie ie8 lte9 lte8 no-js">      <![endif]-->
<!--[if IE 9]>     <html class="ie ie9 lte9 no-js">           <![endif]-->
<!--[if gt IE 9]>  <html class="no-js">                       <![endif]-->
<!--[if !IE]><!--> <html class="no-js">                       <!--<![endif]-->
<head>

    @include('gree_commercial.layout.parts.meta')

    @include('gree_commercial.layout.css.theme')
    @yield('content_css')
    <style>
        .submenu {
            background-image: none !important;
        }
        .growl-success {
            background-color: green;
            color:white;
        }
        .growl-error {
            background-color: red;
            color:white;
        }
        .select2-container--open {
            z-index: 9999999
        }
        #table-col-center {
            vertical-align: middle !important;
            text-align: center;
        }
        .blockOverlay {
            z-index: 9999 !important;
        }
        .blockMsg {
            z-index: 9999 !important;
        }
    </style>
</head>
<body>
	<div id="container" class="clearfix">

        <!-- ********************************************
         * SIDEBAR MAIN:                            *
         *                                          *
         * the part which contains the main         *
         * navigation, logo, search and more...     *
         ******************************************** -->

         @include('gree_commercial.layout.parts.sidebar.sidebar')

        <div id="main" class="clearfix">

            <!-- ********************************************
             * MAIN HEADER:                             *
             *                                          *
             * the part which contains the breadcrumbs, *
             * dropdown menus, toggle sidebar button    *
             ******************************************** -->

			<header id="header-main">
            	<div class="header-main-top">
                	<div class="pull-left">

                    	<!-- * This is the responsive logo * -->
                    	<img id="logo-small" src="/admin/app-assets/images/logo/logo_gree.png">
                    </div>
                    <div class="pull-right">

                    	<!-- * This is the trigger that will show/hide the menu * -->
                        <!-- * if the layout is in responsive mode              * -->

						<a href="#" id="responsive-menu-trigger">
                        	<i class="fa fa-bars"></i>
                        </a>
                    </div>
                </div><!-- End #header-main-top -->
                <div class="header-main-bottom">
                	<div class="pull-left">
                        @yield('breadcrumb')
                    </div>
                    <div class="pull-right">
                        @yield('version')
                    </div>
                </div><!-- End #header-main-bottom -->
            </header><!-- End #header-main -->

            <div id="content" class="clearfix">

                @yield('content')

                <!-- ********************************************
                * fOOTER MAIN:                             *
                *                                          *
                * the part which contains things like      *
                * chat, buttons, copyright and             *
                * dropup menu(s).                          *
                ******************************************** -->
                @include('gree_commercial.layout.parts.footer')

            </div><!-- End #content -->
    	</div><!-- End #main -->
     </div><!-- End #container -->

     <!-- Lockscreen -->
     @include('gree_commercial.layout.parts.lockscreen')
</body>

    @include('gree_commercial.layout.js.themeScripts')
    @yield('content_js')
</html>
