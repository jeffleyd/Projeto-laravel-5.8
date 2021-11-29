@extends('gree_i.layout')

@section('content')
<div class="content-overlay"></div>
<div class="content-wrapper">
    <div class="content-header row">
        <div class="content-header-left col-12 mb-2 mt-1">
          <div class="row breadcrumbs-top">
            <div class="col-12">
              <h5 class="content-header-title float-left pr-1 mb-0">{{ __('layout_i.menu_news') }}</h5>
              <div class="breadcrumb-wrapper col-12">
                {{ __('news_i.lp_01') }}
              </div>
            </div>
          </div>
        </div>
      </div>
    <div class="content-header row">
    </div>
    <div class="content-body">
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
                                            <th>{{ __('news_i.lp_02') }}</th>
                                            <th>{{ __('news_i.lp_03') }}</th>
                                            <th>{{ __('news_i.lp_04') }}</th>
                                            <th>{{ __('news_i.lp_05') }}</th>
                                            <th>{{ __('news_i.lp_06') }}</th>
                                            <th>{{ __('news_i.lp_07') }}</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php foreach ($post as $key) { ?>
                                        <tr>
                                            <td><?= $key->id ?></td>
                                            <td><?= $key->title_pt ?></td>
                                            <td><a target="_blank" href="/user/view/<?= $key->r_code ?>"><?= getENameF($key->r_code); ?></td>
                                            <td>
                                                <?php if ($key->is_publish == 1) { ?>
                                                    <span class="badge badge-light-success">{{ __('news_i.lp_08') }}</span>
                                                <?php } else { ?>
                                                    <span class="badge badge-light-danger">{{ __('news_i.lp_09') }}</span>
                                                <?php } ?>
                                            </td>
                                            <td><?= date('Y-m-d', strtotime($key->created_at)) ?></td>
                                            <td><?= date('Y-m-d', strtotime($key->updated_at)) ?></td>
                                            <td id="action"><a href="/blog/edit/<?= $key->id ?>"><i class="bx bx-edit-alt"></i></a></td>
                                        </tr>
                                        <?php } ?>
                                    </tbody>
                                </table>
                                <nav aria-label="Page navigation">
                                    <ul class="pagination justify-content-end">
                                        <?= $post->render(); ?>
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
                    $("#mBlogAll").addClass('active');
                }, 100);

    });
    </script>
@endsection