    <aside id="sidebar-main" class="sidebar">

        <div class="sidebar-logo">
            <img style="margin-top:15px" src="/admin/app-assets/images/logo/logo_gree.png">
        </div><!-- End .sidebar-logo -->

        
        @include('gree_commercial.layout.parts.sidebar.itens.login-info')
                
        <div class="sidebar-line"><!-- A seperator line --></div>
        
        <!-- ********** -->
        <!-- NEW MODULE -->
        <!-- ********** -->
                
        <div class="sidebar-module"> 
            <nav class="sidebar-nav-v1">
                @include('gree_commercial.layout.parts.sidebar.itens.nav-itens')
            </nav><!-- End .sidebar-nav-v1 --> 
        </div><!-- End .sidebar-module --> 
        
        <div class="sidebar-line"><!-- A seperator line --></div> 
        
    </aside><!-- End aside --> 