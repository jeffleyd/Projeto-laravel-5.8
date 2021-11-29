@extends('gree_i.layout')

@section('content')
    <h2 class="content-heading">Todos atendimentos</h2>
    <div class="row">
        <div class="col-md-12">
            <!-- Normal Form -->
            <div class="block">
                <div class="block-header block-header-default">
                    <h3 class="block-title">Lista</h3>
                </div>
                <div class="block-content">
                    
                </div>
            </div>
            <!-- END Normal Form -->

        </div>

    </div>

    <script>
    $(document).ready(function () {

        $("#mSac").addClass("open");
        $("#mSacServices").addClass("open");
        $("#mSacServicesList").addClass("active");
        
    });
    </script>
@endsection