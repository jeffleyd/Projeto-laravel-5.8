@extends('gree_i.layout')

@section('content')
<div class="content-overlay"></div>
<div class="content-wrapper">
    <div class="content-header row">
        <div class="content-header-left col-12 mb-2 mt-1">
          <div class="row breadcrumbs-top">
            <div class="col-12">
              <h5 class="content-header-title float-left pr-1 mb-0">Permitir usuários</h5>
              <div class="breadcrumb-wrapper col-12">

                Selecione 1 ou mais pessoas para visualizar todas informações do módulo: <b>{{config('gree.permissions_module')[$mdl]}}</b>
              </div>
            </div>
          </div>
        </div>
      </div>
    <div class="content-header row">
    </div>
    <div class="content-body">
        <form action="/financy/permission/module_do" method="POST">
            <input type="hidden" name="mdl" value="{{$mdl}}">
        <div class="card">
            <div class="card-content">
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-12">
                            <div class="form-group">
                                <label for="r_code">Usuários</label>
                                <select class="js-select2 form-control" id="r_code" name="r_code[]" style="width: 100%;" data-placeholder="Pesquise o nome ou matricula..." multiple>
                                <option></option>
                                <?php if (!empty($colab)) { ?>
                                    <?php foreach ($colab as $key) { ?>
                                        <option value="<?= $key->r_code ?>"><?= $key->first_name ." ". $key->last_name ?> (<?= $key->r_code ?>)</option>
                                    <?php } ?>
                                <?php } ?>    
                                </select>
                            </div>
                        </div>
                        <div class="col-md-12 mt-1">
                            <button type="submit" class="btn btn-primary" style="width: 100%;">Atualizar usuários</button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
</form>

    <script>
    $(document).ready(function () {
        $(".js-select2").select2();
        @if (count($selected) > 0)
            $('.js-select2').val([
            @foreach ($selected as $key)
                '<?= $key->r_code ?>',
            @endforeach
            ]).trigger('change');
        @endif

    });
    </script>
@endsection