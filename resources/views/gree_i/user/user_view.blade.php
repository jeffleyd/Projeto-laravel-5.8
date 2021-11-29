@extends('gree_i.layout')

@section('content')
<div class="content-overlay"></div>
    <div class="content-wrapper">
        <div class="content-header row">
        </div>
        <div class="content-body">
            <!-- users view start -->
            <section class="users-view">
                <!-- users view media object start -->
                <div class="row">
                    <div class="col-12 col-sm-7">
                        <div class="media mb-2">
                            <a class="mr-1" href="#">
                                <img id="cardPic" src="/media/avatars/avatar10.jpg" alt="users view avatar" class="users-avatar-shadow rounded-circle" height="64" width="64">
                            </a>
                            <div class="media-body pt-25">
                                <h4 class="media-heading"><span class="users-view-name"><?= $name ?></span></h4>
                                <span>Matricula:</span>
                                <span class="users-view-id"><?= $r_code ?></span>
                            </div>
                        </div>
                    </div>
                    <div class="col-12 col-sm-5 px-0 d-flex justify-content-end align-items-center px-1 mb-2">
                        <a href="mailto:<?= $email ?>" class="btn btn-sm mr-25 border text-primary"><i class="bx bx-envelope font-small-3"></i></a>
                        <a href="/chat/main" class="btn btn-sm mr-25 border text-primary"><i class="bx bxs-chat font-small-3"></i></a>
                    </div>
                </div>
                <!-- users view media object ends -->
                <!-- users view card data start -->
                <div class="row">
                    <div class="col-12 col-md-6">
                        <div class="card">
                            <div class="card-content">
                                <div class="card-body">
                                    <div class="row">
                                        <div class="col-12 col-md-12">
                                            <table class="table table-borderless">
                                                <tbody>
                                                    <tr>
                                                        <td>Nome completo:</td>
                                                        <td><?= $name_full ?></td>
                                                    </tr>
                                                    <tr>
                                                        <td>Data de anivesário:</td>
                                                        <td class="users-view-latest-activity"><?= date('Y-m-d', strtotime($birthday)) ?></td>
                                                    </tr>
                                                    <tr>
                                                        <td>Email:</td>
                                                        <td class="users-view-verified"><?= $email ?></td>
                                                    </tr>
                                                    <tr>
                                                        <td>Telefone:</td>
                                                        <td class="users-view-role"><?= $phone ?></td>
                                                    </tr>
                                                </tbody>
                                            </table>
                                        </div>
                                        
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-12 col-md-6">
                        <div class="card">
                            <div class="card-content">
                                <div class="card-body">
                                    <div class="row">
                                        <div class="col-12 col-md-12">
                                            <table class="table table-borderless">
                                                <tbody>
                                                    <tr>
                                                        <td>Sede de atuação:</td>
                                                        <td>
                                                            <?php switch ($gree_id) {
                                                                case 1:
                                                                    echo "Gree China (zhuhai)";
                                                                break;
                                                                case 2:
                                                                    echo "Gree Brazil (Manaus)";
                                                                break;
                                                                case 3:
                                                                    echo "Gree Brazil (Sao Paulo)";
                                                                break;
                                                            } ?>
                                                        </td>
                                                    </tr>
                                                    <tr>
                                                        <td>Setor de atuação:</td>
                                                        <td class="users-view-latest-activity"><?= __('layout_i.'. $sector->name .'') ?></td>
                                                    </tr>
                                                    <tr>
                                                        <td>Cargo de atuação:</td>
                                                        <td class="users-view-verified"><?= $office ?></td>
                                                    </tr>
                                                    <tr>
                                                        <td>Última atividade:</td>
                                                        <td class="users-view-verified">
                                                            <?php $log = App\Model\LogAccess::where('r_code', $r_code)->orderBy('created_at', 'DESC')->first(); ?>
                                                            <?php if ($log) { ?>
                                                            <?= date('Y-m-d', strtotime($log->created_at)) ?>
                                                            <?php } ?>
                                                    </td>
                                                    </tr>
                                                </tbody>
                                            </table>
                                        </div>
                                        
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

            </section>
            <!-- users view ends -->
        </div>
    </div>

    <script>
    $(document).ready(function () {
        $("#cardInfo").css('background-image', 'url(<?php if ($gree_id == 1) { echo Request::root() ."/media/photos/gree_china.jpg"; } else { echo Request::root() ."/media/photos/gree_brazil.jpg"; } ?>)');
        $("#cardPic").attr('src', '<?php if (!empty($picture)) { echo $picture; } else { echo Request::root() ."/media/avatars/avatar10.jpg"; } ?>');
       
    });
    </script>
@endsection