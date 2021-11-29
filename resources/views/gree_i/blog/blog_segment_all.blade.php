@extends('gree_i.layout')

@section('content')
<div class="content-overlay"></div>
<div class="content-wrapper">
    <div class="content-header row">
        <div class="content-header-left col-12 mb-2 mt-1">
          <div class="row breadcrumbs-top">
            <div class="col-12">
              <h5 class="float-left pr-1 mb-0">{{ __('layout_i.menu_news') }}</h5>
            </div>
          </div>
        </div>
      </div>
    <div class="content-header row">
    </div>
    <div class="content-body">

        <div class="users-list-filter px-1">
            <form action="/posts/segment" id="searchTrip" method="GET">
                <div class="row border rounded py-2 mb-2">
                    <div class="col-12 col-sm-12 col-lg-10">
                        <label for="type">Setor</label>
                        <fieldset class="form-group">
                            <select class="form-control" name="setor">
                                <option value="">Todos</option>
                                <option value="1" @if (Session::get('filter_setor') == '1') selected @endif>ADM & RH</option>
                                <option value="2" @if (Session::get('filter_setor') == '2') selected @endif>Mkt interno</option>
                                <option value="3" @if (Session::get('filter_setor') == '3') selected @endif>Engenharia & Iso</option>
                                <option value="4" @if (Session::get('filter_setor') == '4') selected @endif>Pós venda & PeD</option>
                                <option value="5" @if (Session::get('filter_setor') == '5') selected @endif>Financeiro</option>
                                <option value="6" @if (Session::get('filter_setor') == '6') selected @endif>Outros setores</option>
                            </select>
                        </fieldset>
                    </div>
                    <div class="col-12 col-sm-12 col-lg-2 d-flex align-items-center">
                        <button type="submit" class="btn btn-primary btn-block glow users-list-clear mb-0">{{ __('news_i.lt_03') }}</button>
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
                                            <th>#</th>
                                            <th>{{ __('news_i.sp_04') }}</th>
                                            <th>{{ __('news_i.sp_01') }}</th>
                                            <th>{{ __('news_i.sp_02') }}</th>
                                            <th>{{ __('news_i.sp_03') }}</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php foreach ($post as $key) { ?>
                                        <tr>
                                            <td><?= $key->id ?></td>
                                            <td>
                                                <?php if ($key->category_id == 7 || $key->category_id == 6) { ?>
                                                    {{ __('news_i.resources_human') }}
                                                <?php } else if ($key->category_id == 100) { ?>
                                                    {{ __('news_i.marketing_internal') }}
                                                <?php } else if ($key->category_id == 2) { ?>
                                                    {{ __('news_i.enginer_iso') }}
                                                <?php } else if ($key->category_id == 1) { ?>
                                                    {{ __('news_i.pv_pd') }}
                                                <?php } else if ($key->category_id == 3) { ?>
                                                    {{ __('news_i.financy') }}
                                                <?php } else { ?>
                                                    {{ __('news_i.other_sectors') }}
                                                <?php } ?>
                                            </td>
                                            <td><?= $key->title_pt ?></td>
                                            <td><?= date('Y-m-d', strtotime($key->created_at)) ?></td>
                                            <td id="action"><a href="/post/single/<?= $key->id ?>"><i class="bx bx-show"></i></a></td>
                                        </tr>
                                        <?php } ?>
                                    </tbody>
                                </table>
                                <nav aria-label="Page navigation">
                                    <ul class="pagination justify-content-end">
                                        <?= $post->appends(getSessionFilters()[0]->toArray())->links(); ?>
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

        setInterval(() => {
            $("#mNews").addClass('sidebar-group-active active');
        }, 100);
    });
    </script>
@endsection