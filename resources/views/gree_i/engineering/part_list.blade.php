@extends('gree_i.layout')

@section('content')
<div class="content-overlay"></div>
<div class="content-wrapper">
    <div class="content-header row">
        <div class="content-header-left col-12 mb-2 mt-1">
          <div class="row breadcrumbs-top">
            <div class="col-12">
              <h5 class="content-header-title float-left pr-1 mb-0">Engenharia</h5>
              <div class="breadcrumb-wrapper col-12">
                Lista de peças
              </div>
            </div>
          </div>
        </div>
      </div>
    <div class="content-header row">
    </div>
    <div class="content-body">
        <div class="users-list-filter px-1">
            <form action="/engineering/part/all" id="searchTrip" method="GET">
                <div class="row border rounded py-2 mb-2">
                    <div class="col-12 col-lg-3">
                        <label for="product_category">Categoria do produto</label>
                        <fieldset class="form-group">
                            <select class="form-control" id="product_category" name="product_category">
                                <option value=""></option>
                                @foreach ($product_category as $key)
                                <option value="{{$key->id}}" @if ($product_category_id == $key->id) selected @endif >{{$key->name}}</option>
                                @endforeach
                            </select>
                        </fieldset>
                    </div>
                    <div class="col-12 col-lg-3 sub_1" @if (empty(Session::get('pf_product_sub_level_1'))) style="visibility:hidden;" @endif>
                        <label for="product_sub_level_1"></label>
                        <fieldset class="form-group">
                            <select class="form-control" id="product_sub_level_1" name="product_sub_level_1">
                                <option value="0"></option>
                                @if (!empty(Session::get('pf_product_sub_level_1')))
                                <option value="{{ $product_sub_level_1_id->id }}" selected>{{ $product_sub_level_1_id->name }}</option>
                                @endif
                            </select>
                        </fieldset>
                    </div>
                    <div class="col-12 col-lg-3 sub_2" @if (empty(Session::get('pf_product_sub_level_2'))) style="visibility:hidden;" @endif>
                        <label for="product_sub_level_2"></label>
                        <fieldset class="form-group">
                            <select class="form-control" id="product_sub_level_2" name="product_sub_level_2">
                                <option value="0"></option>
                                @if (!empty(Session::get('pf_product_sub_level_2')))
                                <option value="{{ $product_sub_level_2_id->id }}" selected>{{ $product_sub_level_2_id->name }}</option>
                                @endif
                            </select>
                        </fieldset>
                    </div>
                    <div class="col-12 col-lg-3 sub_3" @if (empty(Session::get('pf_product_sub_level_3'))) style="visibility:hidden;" @endif>
                        <label for="product_sub_level_3"></label>
                        <fieldset class="form-group">
                            <select class="form-control" id="product_sub_level_3" name="product_sub_level_3">
                                <option value="0"></option>
                                @if (!empty(Session::get('pf_product_sub_level_3')))
                                <option value="{{ $product_sub_level_3_id->id }}" selected>{{ $product_sub_level_3_id->name }}</option>
                                @endif
                            </select>
                        </fieldset>
                    </div>
                    <div class="col-12 col-lg-1">
                        <label for="warehouse">Armazém</label>
                        <fieldset class="form-group">
                            <input class="form-control" type="text" name="warehouse" id="warehouse" value="<?= Session::get('pf_warehouse') ?>">
                        </fieldset>
                    </div>
                    <div class="col-12 col-lg-2">
                        <label for="ncm">NCM</label>
                        <fieldset class="form-group">
                            <input class="form-control" type="text" name="ncm" id="ncm" value="<?= Session::get('pf_ncm') ?>">
                        </fieldset>
                    </div>
                    <div class="col-12 col-lg-3">
                        <label for="description">DESCRIÇÃO</label>
                        <fieldset class="form-group">
                            <input class="form-control" type="text" name="description" id="description" value="<?= Session::get('pf_description') ?>">
                        </fieldset>
                    </div>
                    <div class="col-12 col-lg-3">
                        <label for="code">Código</label>
                        <fieldset class="form-group">
                            <input class="form-control" type="text" name="code" id="code" value="<?= Session::get('pf_code') ?>">
                        </fieldset>
                    </div>
                    <div class="col-12 col-lg-3">
                        <label for="model">Modelo</label>
                        <fieldset class="form-group">
                            <input class="form-control" type="text" name="model" id="model" value="<?= Session::get('pf_model') ?>">
                        </fieldset>
                    </div>
                    <div class="col-12 col-lg-12 d-flex align-items-center">
                        <button type="submit" class="btn btn-primary btn-block glow users-list-clear mb-0">Filtrar lista</button>
                    </div>
                </div>
            </form>
        </div>
        <!-- users list start -->
        <section class="users-list-wrapper">
            <div class="users-list-table">
                <div class="card">
                    <div class="card-content">
                        <div class="card-body">
                            <!-- datatable start -->
                            <div class="table-responsive">
                                <table id="list-datatable" class="table">
                                    <thead>
                                        <tr>
                                            <th>Código</th>
                                            <th>Descrição</th>
                                            <th>Observação</th>
                                            <th>Status</th>
                                            <th>Ações</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php foreach ($parts as $key) { ?>
                                        <tr>
                                            <td><?= $key->code ?></td>
                                            <td><span data-toggle="popover" data-content="<?= strip_tags($key->description) ?>"><?= strWordCut($key->description, 25, "...") ?></span></td>
                                            <td><span data-toggle="popover" data-content="<?= strip_tags($key->observation) ?>"><?= strWordCut($key->observation, 25, "...") ?></span></td>
                                            <td>
                                                <?php if ($key->is_active == 0) { ?>
                                                    <span class="badge badge-light-danger">Desativado</span>
                                                <?php } else { ?>
                                                    <span class="badge badge-light-success">Ativo</span>
                                                <?php } ?>
                                            </td>
                                            <td>
                                                <div class="dropleft">
                                                    <span class="bx bx-dots-horizontal-rounded font-medium-3 nav-hide-arrow cursor-pointer" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false" role="menu"></span>
                                                    <div class="dropdown-menu dropdown-menu-right">
                                                        <a class="dropdown-item" href="/engineering/part/edit/<?= $key->id ?>"><i class="bx bx-edit-alt mr-1"></i> Editar</a>
                                                        @if ($key->is_active == 0)
                                                        <a class="dropdown-item" href="/engineering/part/status?id=<?= $key->id ?>&status=1"><i class="bx bx-check-square mr-1"></i> Ativar</a>
                                                        @else
                                                        <a class="dropdown-item" href="/engineering/part/status?id=<?= $key->id ?>&status=0"><i class="bx bxs-x-square mr-1"></i> Desativar</a>
                                                        @endif
                                                    </div>
                                                </div>
                                            </td>
                                        </tr>
                                        <?php } ?>
                                    </tbody>
                                </table>
                                <nav aria-label="Page navigation">
                                    <ul class="pagination justify-content-end">
                                        <?= $parts->appends([
                                                'product' => Session::get('pf_product_category'),
                                                'sub1' => Session::get('pf_product_sub_level_1'), 
                                                'sub2' => Session::get('pf_product_sub_level_2'), 
                                                'sub3' => Session::get('pf_product_sub_level_3'), 
                                                'code' => Session::get('pf_code'),
                                                'description' => Session::get('pf_description'),
                                                'ncm' => Session::get('pf_ncm'),
                                                'cest' => Session::get('pf_cest'),
                                                'warehouse' => Session::get('pf_warehouse'),
                                                'model' => Session::get('pf_model'),
                                            ])->links(); ?>
                                    </ul>
                                </nav>
                            </div>
                            <!-- datatable ends -->
                        </div>
                    </div>
                </div>
            </div>
        </section>
        <!-- users list ends -->
    </div>
</div>

    <script>
    $(document).ready(function () {
        $('[data-toggle="popover"]').popover({
            placement: 'right',
            trigger: 'hover',
        });
        $('#list-datatable').DataTable( {
            searching: false,
            paging: false,
            ordering:false,
            lengthChange: false,
            language: {
                search: "{{ __('layout_i.dtbl_search') }}",
                zeroRecords: "{{ __('layout_i.dtbl_zero_records') }}",
                info: "{{ __('layout_i.dtbl_info') }}",
                infoEmpty: "{{ __('layout_i.dtbl_info_empty') }}",
                infoFiltered: "{{ __('layout_i.dtbl_info_filtred') }}",
            }
        });

        $("#product_category").change(function (e) { 
            $("#product_sub_level_1").load("/misc/product/category/1/"+ $("#product_category").val(), function (response, status, request) {
                $(".sub_1").attr('style', 'visibility:visible');
                $(".sub_2").attr('style', 'visibility:hidden');
                $(".sub_3").attr('style', 'visibility:hidden');
            });
            
        });

        $("#product_sub_level_1").change(function (e) { 
            $("#product_sub_level_2").load("/misc/product/category/2/"+ $("#product_sub_level_1").val(), function (response, status, request) {
                $(".sub_1").attr('style', 'visibility:visible');
                if ($("#product_sub_level_2").html() != '<option value="0"></option>') {
                    $(".sub_2").attr('style', 'visibility:visible');
                } else {
                    $(".sub_2").attr('style', 'visibility:hidden');
                }
                $(".sub_3").attr('style', 'visibility:hidden');
            });
            
        });

        $("#product_sub_level_2").change(function (e) { 
            $("#product_sub_level_3").load("/misc/product/category/3/"+ $("#product_sub_level_2").val(), function (response, status, request) {
                $(".sub_1").attr('style', 'visibility:visible');
                $(".sub_2").attr('style', 'visibility:visible');
                if ($("#product_sub_level_3").html() != '<option value="0"></option>') {
                    $(".sub_3").attr('style', 'visibility:visible');
                } else {
                    $(".sub_3").attr('style', 'visibility:hidden');
                }
            });
            
        });

        setInterval(() => {
            $("#mIndustrial").addClass('sidebar-group-active active');
            $("#mEngineering").addClass('sidebar-group-active active');
            $("#mEngineeringAllPart").addClass('active');

        }, 100);

    });
    </script>
@endsection