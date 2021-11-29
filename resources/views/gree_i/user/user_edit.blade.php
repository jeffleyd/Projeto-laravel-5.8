@extends('gree_i.layout')

@section('content')
    <link rel="stylesheet" type="text/css" href="/admin/app-assets/vendors/css/pickers/pickadate/pickadate.css">
    <link rel="stylesheet" type="text/css" href="/admin/app-assets/vendors/css/extensions/shepherd-theme-default.css">
    <link rel="stylesheet" type="text/css" href="/admin/app-assets/css/plugins/tour/tour.min.css">
    <div class="content-overlay"></div>
    <div class="content-wrapper">
        <div class="content-header row">
            <div class="content-header-left col-12 mb-2 mt-1">
                <div class="row breadcrumbs-top">
                    <div class="col-12">
                        <h5 class="content-header-title float-left pr-1 mb-0">Colaborador</h5>
                        <div class="breadcrumb-wrapper col-12">
                            <?php if ($rcode == Session::get('r_code')) { ?>
                            Meu perfil
                            <?php } else if ($rcode == 0) { ?>
                            Criando um novo usuário
                            <?php } else { ?>
                            Editando usuário: #<?= $rcode ?>
                            <?php } ?>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="content-header row">
        </div>
        <div class="content-body">
            <!-- users edit start -->
            <section class="users-edit">

                <div class="card">
                    <div class="card-content">
                        <div class="card-body">
                            <ul class="nav nav-tabs mb-2" role="tablist">
                                <li class="nav-item">
                                    <a class="nav-link d-flex align-items-center active" id="account-tab" data-toggle="tab" href="#account" aria-controls="account" role="tab" aria-selected="true">
                                        <i class="bx bx-user mr-25"></i><span class="d-none d-sm-block">Dados da conta</span>
                                    </a>
                                </li>
                                <li class="nav-item">
                                    <a class="nav-link d-flex align-items-center" id="immediate-tab" data-toggle="tab" href="#immediate" aria-controls="immediate" role="tab" aria-selected="false">
                                        <i class="bx bx-info-circle mr-25"></i><span class="d-none d-sm-block">Chefe imediato</span>
                                    </a>
                                </li>
                                @if ($rcode == Session::get('r_code'))
                                    <li class="nav-item" >
                                        <a class="nav-link d-flex align-items-center" id="tofa-tab" data-toggle="tab" href="#tofa" aria-controls="tofa" role="tab" aria-selected="false">
                                            <i class="bx bx-lock-alt mr-25"></i><span class="d-none d-sm-block">Autenticação em 2 fatores</span>
                                        </a>
                                    </li>
                                @endif
                                @if ($rcode == Session::get('r_code'))
                                    <li class="nav-item" >
                                        <a class="nav-link d-flex align-items-center" id="holiday-tab" data-toggle="tab" href="#holiday" aria-controls="holiday" role="tab" aria-selected="false">
                                            <i class="bx bxs-plane mr-25"></i><span class="d-none d-sm-block">Modo férias</span>
                                        </a>
                                    </li>
                                @endif
                            </ul>
                            <div class="tab-content">
                                <div class="tab-pane active fade show" id="account" aria-labelledby="account-tab" role="tabpanel">
                                    <form class="needs-validation" action="/user/edit/do" id="submitEdit" method="post" enctype="multipart/form-data">
                                        <input type="hidden" name="is_new" value="<?php if ($rcode == 0) { ?>1<?php } else { ?>0<?php } ?>">
                                        <input type="hidden" name="data_input" id="data_input">
                                        <!-- users edit media object start -->
                                        <div class="media mb-2">
                                            <a class="mr-2" href="#">
                                                <img src="<?php if (!empty($picture)) { echo $picture; } else { ?>/media/avatars/avatar10.jpg<?php } ?>" alt="users avatar" id="avatar" class="users-avatar-shadow rounded-circle" height="64" width="64">
                                            </a>
                                            <div class="media-body">
                                                <h4 class="media-heading">Foto de perfil</h4>
                                                <input style="display: none" type="file" name="picture" id="picture" accept="image/x-png,image/gif,image/jpeg">
                                                <div class="col-12 px-0 d-flex">
                                                    <a href="#" class="btn btn-sm btn-primary mr-25 changePic">Trocar</a>
                                                    <a href="#" class="btn btn-sm btn-light-secondary resetPic">Resetar</a>
                                                </div>
                                            </div>
                                        </div>
                                        <!-- users edit media object ends -->
                                        <!-- users edit account form start -->
                                        <div class="row">
                                            <div class="col-12 col-sm-12">
                                                <div class="form-group">
                                                    <div class="controls">
                                                        <label>Setor de atuação</label>
                                                        <select class="form-control" id="sector" name="sector" required>
                                                            <option value=""></option>
                                                            <?php foreach ($sector as $key) { ?>
                                                            <option value="<?= $key->id ?>" <?php if ($key->id == $sectorid){ echo "selected"; } ?> ><?= __('layout_i.'. $key->name .'') ?></option>
                                                            <?php } ?>
                                                        </select>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="col-12 col-sm-6">
                                                <div class="form-group">
                                                    <div class="controls">
                                                        <label for="registration">Matricula</label>
                                                        <input type="number" <?php if ($rcode == Session::get('r_code')) { ?>disabled<?php } else { ?> id="registration" name="registration" <?php } ?> value="<?= $rcode ?>" class="form-control" placeholder="0000" required>
                                                    </div>
                                                </div>
                                                <?php if ($rcode == Session::get('r_code')) { ?>
                                                <input type="hidden" value="<?= $rcode ?>" name="registration">
                                                <?php } ?>
                                                <div class="form-group">
                                                    <div class="controls">
                                                        <label for="first_name">Primeiro nome</label>
                                                        <input type="text" id="first_name" name="first_name" class="form-control" value="<?= $first_name ?>" placeholder="Jhon" required>
                                                    </div>
                                                </div>
                                                <div class="form-group">
                                                    <div class="controls">
                                                        <label for="reg_email">E-mail</label>
                                                        <input type="email" id="reg_email" name="reg_email" class="form-control" value="<?= $email ?>" placeholder="jhon.doe@gree-am.com.br" required>
                                                    </div>
                                                </div>
                                                <div class="form-group">
                                                    <div class="controls">
                                                        <label for="phone">Telefone</label>
                                                        <input type="number" id="phone" name="phone" class="form-control" value="<?= $phone ?>" placeholder="9291434215" required>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="col-12 col-sm-6">
                                                <div class="form-group">
                                                    <label>Sede de operação</label>
                                                    <select class="form-control" id="gree" name="gree" required>
                                                        <option value=""></option>
                                                        <option value="1" <?php if (1 == $greeid){ echo "selected"; } ?> >Gree China (zhuhai)</option>
                                                        <option value="2" <?php if (2 == $greeid){ echo "selected"; } ?> >Gree Brazil (Manaus)</option>
                                                        <option value="3" <?php if (3 == $greeid){ echo "selected"; } ?> >Gree Brazil (Sao Paulo)</option>
                                                    </select>
                                                </div>
                                                <div class="form-group">
                                                    <label>Sobrenome</label>
                                                    <input type="text" class="form-control" id="last_name" name="last_name" value="<?= $last_name ?>" placeholder="Doe" required>
                                                </div>
                                                <div class="form-group">
                                                    <label for="office">Cargo</label>
                                                    <input type="text" id="office" name="office" value="<?= $office ?>" class="form-control" placeholder="Gerente internacional" required>
                                                </div>
                                                <div class="form-group">
                                                    <div class="controls">
                                                        <label for="birthday">Data de anivesário</label>
                                                        <input type="text" id="birthday" name="birthday" value="<?= date('Y-m-d', strtotime($birthday)) ?>" class="form-control" required>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="col-12 col-sm-12">
                                                <div class="form-group">
                                                    <label>Senha</label>
                                                    <input type="text" class="form-control" id="password" name="password" placeholder="*******">
                                                </div>
                                            </div>
                                            <?php if ($rcode != Session::get('r_code')) { ?>
                                            <div class="col-12 col-sm-12">
                                                <div class="form-group">
                                                    <label>Filtro tipo de linha</label>
                                                    <select class="form-control" id="filter_line" name="filter_line" required>
                                                        <option value="1" <?php if (1 == $filter_line){ echo "selected"; } ?> >Todos os dados</option>
                                                        <option value="2" <?php if (2 == $filter_line){ echo "selected"; } ?> >Dados comerciais</option>
                                                    </select>
                                                    <p><small class="text-muted">Caso escolha "Dados comerciais", não poderá filtrar todos os dados ou dados residencias.</small></p>
                                                </div>
                                            </div>
											
											<?php if($rcode != 0) { ?>
                                            <div class="col-12 col-sm-12">
                                                <label class="mb-1">Autenticação 2 fatores</label>
                                                <ul class="list-unstyled mb-0">
                                                    <li class="d-inline-block mr-2 mb-1">
                                                      <fieldset>
                                                            <div class="radio">
                                                                <input type="radio" class="radio-auth" id="radio1" name="is_otpauth" value="1" @if ($otpauth != '') checked="" @endif disabled>
                                                                <label for="radio1">Habilitado</label>
                                                            </div>
                                                      </fieldset>
                                                    </li>
                                                    <li class="d-inline-block mr-2 mb-1">
                                                        <fieldset>
                                                            <div class="radio">
                                                                <input type="radio" class="radio-auth" id="radio2" name="is_otpauth" value="2" @if ($otpauth == '') checked="" @endif>
                                                                <label for="radio2">Desabilitado</label>
                                                            </div>
                                                        </fieldset>
                                                    </li>
                                                </ul>
                                            </div>
                                            <?php } ?>
											
                                            <div class="col-12 text-center mt-2">
                                                <ul class="list-unstyled mb-0 border p-2">
                                                    <li class="d-inline-block mr-2">
                                                        <fieldset>
                                                            <div class="custom-control custom-radio">
                                                                <input type="radio" class="custom-control-input" value="1" <?php if ($is_active == 1) { ?> checked=""<?php } else { ?><?php } ?> name="user_active" id="active" checked="">
                                                                <label class="custom-control-label" for="active">Ativo</label>
                                                            </div>
                                                        </fieldset>
                                                    </li>
                                                    <li class="d-inline-block mr-2">
                                                        <fieldset>
                                                            <div class="custom-control custom-radio">
                                                                <input type="radio" class="custom-control-input" value="0" <?php if ($is_active == 0) { ?> checked=""<?php } else { ?><?php } ?> name="user_active" id="desactive">
                                                                <label class="custom-control-label" for="desactive">Desativado</label>
                                                            </div>
                                                        </fieldset>
                                                    </li>
                                                </ul>
                                            </div>
                                            <div class="col-12">
                                                <div class="table-responsive">
                                                    <table class="table mt-1">
                                                        <thead>
                                                        <tr>
                                                            <th>Permissão modular</th>
                                                            <th>Usará <i class="bx bxs-help-circle" style="position: relative;top: 2px; left: 5px;" data-toggle="tooltip" data-placement="top" data-original-title="Ativa permissão para usuário"></i></th>
                                                            <th>Gestor <i class="bx bxs-help-circle" style="position: relative;top: 2px; left: 5px;" data-toggle="tooltip" data-placement="top" data-original-title="Usuário irá gerênciar as solicitações dessa permissão"></i></th>
                                                            <th>Aprovar <i class="bx bxs-help-circle" style="position: relative;top: 2px; left: 5px;" data-toggle="tooltip" data-placement="top" data-original-title="Usuário poderá aprovar solicitações dessa permissão"></i></th>
                                                            <th>Grade <i class="bx bxs-help-circle" style="position: relative;top: 2px; left: 5px;" data-toggle="tooltip" data-placement="top" data-original-title="Nivel de hierarquia do usuário na permissão"></i></th>
                                                        </tr>
                                                        </thead>
                                                        <tbody>
                                                        <?php $i = 0; ?>
                                                        <?php foreach ($perm as $key) { ?>
                                                        <?php  $perm_user =  App\Model\UserOnPermissions::where('perm_id', $key->id)->where('user_r_code', $rcode)->first(); ?>
                                                        <input type="hidden" name="perm_id[<?= $i ?>]" value="<?= $key->id ?>">
                                                        <tr>
                                                            <td><?= $key->name ?> <i class="bx bxs-help-circle" style="position: relative;top: 2px; left: 5px;" data-toggle="tooltip" data-placement="top" data-original-title="<?= $key->description ?>"></i></td>
                                                            <td>
                                                                <div class="checkbox"><input type="checkbox" id="perm_active<?= $i ?>" class="checkbox-input" name="perm_active[<?= $i ?>]" value="1" <?php if ($perm_user) { ?>checked=""<?php } ?>>
                                                                    <label for="perm_active<?= $i ?>"></label>
                                                                </div>
                                                            </td>
                                                            <td>
                                                                <div class="checkbox"><input type="checkbox" id="perm_manager<?= $i ?>" class="checkbox-input" name="perm_manager[<?= $i ?>]" id="perm_manager_<?= $i ?>" value="1" <?php if ($perm_user) { ?><?php if ($perm_user->grade == 99) { ?>checked=""<?php } }?>>
                                                                    <label for="perm_manager<?= $i ?>"></label>
                                                                </div>
                                                            </td>
                                                            <td>
                                                                <div class="checkbox"><input type="checkbox" id="perm_approv<?= $i ?>" class="checkbox-input" name="perm_approv[<?= $i ?>]" value="1" <?php if ($perm_user) { ?><?php if ($perm_user->can_approv == 1) { ?>checked=""<?php } }?>>
                                                                    <label for="perm_approv<?= $i ?>"></label>
                                                                </div>
                                                            </td>
                                                            <td>
                                                                <div class="grade_<?= $i ?>" <?php if ($perm_user) { ?><?php if ($perm_user->grade == 99) { ?>style="display:none"<?php } }?>>
                                                                    <select class="form-control" id="perm_grade[<?= $i ?>]" name="perm_grade[<?= $i ?>]">
                                                                        <option value="1" <?php if ($perm_user) { ?><?php if ($perm_user->grade == 99 or $perm_user->grade == 1) { ?>selected=""<?php } } ?>>Auxiliar - Grade 1</option>
                                                                        <option value="2" <?php if ($perm_user) { ?><?php if ($perm_user->grade == 2) { ?>selected=""<?php } } ?>>Operador - Grade 2</option>
                                                                        <option value="3" <?php if ($perm_user) { ?><?php if ($perm_user->grade == 3) { ?>selected=""<?php } } ?>>Assistente - Grade 3</option>
                                                                        <option value="4" <?php if ($perm_user) { ?><?php if ($perm_user->grade == 4) { ?>selected=""<?php } } ?>>Analista - Grade 4</option>
                                                                        <option value="5" <?php if ($perm_user) { ?><?php if ($perm_user->grade == 5) { ?>selected=""<?php } } ?>>Lider - Grade 5</option>
                                                                        <option value="6" <?php if ($perm_user) { ?><?php if ($perm_user->grade == 6) { ?>selected=""<?php } } ?>>Supervisor - Grade 6</option>
                                                                        <option value="7" <?php if ($perm_user) { ?><?php if ($perm_user->grade == 7) { ?>selected=""<?php } } ?>>Coordenador - Grade 7</option>
                                                                        <option value="8" <?php if ($perm_user) { ?><?php if ($perm_user->grade == 8) { ?>selected=""<?php } } ?>>Gerente - Grade 8</option>
                                                                        <option value="9" <?php if ($perm_user) { ?><?php if ($perm_user->grade == 9) { ?>selected=""<?php } } ?>>Diretor - Grade 9</option>
                                                                        <option value="10" <?php if ($perm_user) { ?><?php if ($perm_user->grade == 10) { ?>selected=""<?php } } ?>>Presidente - Grade 10</option>
                                                                        <option value="11" <?php if ($perm_user) { ?><?php if ($perm_user->grade == 11) { ?>selected=""<?php } } ?>>Verificador Contábil</option>
                                                                        <option value="12" <?php if ($perm_user) { ?><?php if ($perm_user->grade == 12) { ?>selected=""<?php } } ?>>Verificador Fiscal</option>
                                                                    </select>
                                                                </div>
                                                            </td>
                                                        </tr>
                                                        <?php $i++; ?>
                                                        <?php } ?>
                                                        </tbody>
                                                    </table>
                                                </div>
                                            </div>
                                            <?php } ?>
                                        </div>
                                        <!-- users edit account form ends -->

                                        <button type="submit" id="updaccount" class="btn btn-primary glow mb-1 mb-sm-0 mr-0 mr-sm-1" style="width: 100%;"><?php if ($rcode == 0) { ?>Nova conta<?php } else { ?>Atualizar conta<?php } ?></button>

                                    </form>
                                </div>
                                <div class="tab-pane fade show" id="immediate" aria-labelledby="immediate-tab" role="tabpanel">
                                    <!-- users edit Info form start -->
                                    <?php if ($rcode != Session::get('r_code')) { ?>
                                    <div class="row">
                                        <div class="col-10">
                                            <div class="form-group">
                                                <label></label>
                                                <select class="js-select2 form-control" id="c_rcode" name="c_rcode" style="width: 100%;" data-placeholder="Pesquise o nome ou matricula..." multiple>
                                                    <option></option>
                                                    <?php foreach ($usersall as $key) { ?>
                                                    <option value="<?= $key->r_code ?>"><?= $key->first_name ." ". $key->last_name ?> (<?= $key->r_code ?>)</option>
                                                    <?php } ?>
                                                </select>
                                            </div>
                                        </div>
                                        <div class="col-2">
                                            <a href="#" style="margin-top: 20px !important;" class="btn btn-info" id="addboss">Adicionar</a>
                                        </div>
                                    </div>

                                    <?php } else { ?>
                                    <div class="row">
                                        <div class="col-12">
                                            Essa pessoa(s) abaixo responde por todos as suas solicitações de aprovação!
                                        </div>
                                    </div>
                                    <?php } ?>
                                    <div class="row imdts"></div>
                                    <!-- users edit Info form ends -->
                                </div>
                                @if ($rcode == Session::get('r_code'))
                                    <div class="tab-pane fade show" id="tofa" aria-labelledby="tofa-tab" role="tabpanel">
                                        <div class="row">
                                            <div class="col-12 mb-2">
                                                <ul class="list-unstyled mb-0 border p-2">
                                                    <li class="d-inline-block mr-2">
                                                        <fieldset>
                                                            <div class="custom-control custom-radio">
                                                                <input type="radio" onclick="toFALoad(1)" class="custom-control-input" value="1" <?php if ($otpauth) { ?> checked=""<?php } else { ?><?php } ?> name="active_otp" id="activeotp">
                                                                <label class="custom-control-label" for="activeotp">Ativar</label>
                                                            </div>
                                                        </fieldset>
                                                    </li>
                                                    <li class="d-inline-block mr-2">
                                                        <fieldset>
                                                            <div class="custom-control custom-radio">
                                                                <input type="radio" onclick="toFALoad(0)" class="custom-control-input" value="0" <?php if ($otpauth == null) { ?> checked=""<?php } else { ?><?php } ?> name="active_otp" id="desactiveotp">
                                                                <label class="custom-control-label" for="desactiveotp">Desativa</label>
                                                            </div>
                                                        </fieldset>
                                                    </li>
                                                </ul>
                                            </div>
                                            <div class="col-4">
                                                <p>Você precisará de um aplicativo para autenticar, pesquise na Play Store ou na Apple Store.</p>
                                                <div><b>Google Authenticator</b></div>
                                                <div>ou</div>
                                                <div><b>2fas</b></div>
                                            </div>
                                            <div class="col-8 loadqrcode">
                                            </div>
                                        </div>
                                    </div>
                                @endif
                                @if ($rcode == Session::get('r_code'))
                                    <div class="tab-pane fade show" id="holiday" aria-labelledby="holiday-tab" role="tabpanel">
                                        <form action="/user/edit/holiday/do" method="post" id="hdform">
                                            <input type="hidden" id="hd_data" name="hd_data" value="[]">
                                            <div class="row">
                                                <div class="col-12 mb-2">
                                                    <ul class="list-unstyled mb-0 border p-2">
                                                        <b>1.</b> Ao ativar o modo férias, você irá conceder suas permissões escolhidas para um determinado colaborador e após o tempo de término das ferias ou desativação da mesma, as permissões irão voltar para você.
                                                        <br><b>2.</b> Para excluir a pessoa com permissão, você precisará apertar em cima da pessoa que deu a permissão e confirmar.
                                                    </ul>
                                                </div>

                                                <div class="col-12 col-sm-12">
                                                    <div class="form-group">
                                                        <div class="controls">
                                                            <label>Avisar todos</label>
                                                            <select class="form-control" id="holiday_notify" name="holiday_notify">
                                                                <option value="2">Não</option>
                                                                <option value="1">Sim</option>
                                                            </select>
                                                        </div>
                                                    </div>
                                                </div>

                                                <div class="col-12 col-sm-12 holiday_msg" style="display:none">
                                                    <div class="form-group">
                                                        <div class="controls">
                                                            <label>Mensagem para aviso</label>
                                                            <textarea type="text" class="form-control" id="holiday_msg" name="holiday_msg"></textarea>
                                                        </div>
                                                    </div>
                                                </div>

                                                <div class="col-12 col-sm-4">
                                                    <div class="form-group">
                                                        <div class="controls">
                                                            <label>Status</label>
                                                            <select class="form-control" id="holiday_status" name="holiday_status">
                                                                <option value="2" @if ($is_holiday == 0) selected @endif>Desativado</option>
                                                                <option value="1" @if ($is_holiday == 1) selected @endif>Ativo</option>
                                                            </select>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="col-12 col-sm-8">
                                                    <div class="form-group">
                                                        <div class="controls">
                                                            <label>Data de término</label>
                                                            <input type="text" class="form-control date-mask" id="holiday_date" value="{{$holiday_date_end}}" name="holiday_date">
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="col-12 col-sm-4">
                                                    <div class="form-group">
                                                        <div class="controls">
                                                            <label>Permissões</label>
                                                            <select class="js-select23 form-control" id="holiday_perm" name="holiday_perm" style="width:100%" multiple>
                                                                @foreach(Session::get('permissoes_usuario')->where('can_approv', 1) as $perm)
                                                                    <option data-name="{{config('gree.permissions')[$perm->perm_id]['name']}}" value="{{$perm->perm_id}}">{{config('gree.permissions')[$perm->perm_id]['name']}}</option>
                                                                @endforeach
                                                            </select>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="col-12 col-sm-6">
                                                    <div class="form-group">
                                                        <div class="controls">
                                                            <label>Colaborador</label>
                                                            <select class="js-select22 form-control" id="holiday_user" name="holiday_user" style="width: 100%;" data-placeholder="Pesquise o nome ou matricula..." multiple>
                                                                <option></option>
                                                                <?php foreach ($usersall as $key) { ?>
                                                                <option data-picture="{{$key->picture}}" data-name="{{$key->first_name}}" value="<?= $key->r_code ?>"><?= $key->first_name ." ". $key->last_name ?> (<?= $key->r_code ?>)</option>
                                                                <?php } ?>
                                                            </select>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="col-12 col-sm-2">
                                                    <button type="button" onclick="addHR()" class="btn btn-primary btn-block mt-2"><i class="bx bx-plus"></i></button>
                                                </div>

                                                <div class="col-12">
                                                    <div class="row hlpeoples">

                                                    </div>
                                                </div>

                                                <div class="col-12">
                                                    <button type="button" id="btnholiday" class="btn btn-primary glow mb-1 mb-sm-0 mr-0 mr-sm-1" style="width: 100%;">Enviar informações</button>
                                                </div>
                                            </div>
                                        </form>
                                    </div>
                                @endif
                            </div>
                        </div>
                    </div>
                </div>
            </section>
            <!-- users edit ends -->
        </div>
    </div>
    <div class="customizer d-none d-md-block" id="ActiveTraine">
        <a class="customizer-toggle" href="#"><i class="bx bx-question-mark white"></i></a>
    </div>

    <script src="/admin/app-assets/vendors/js/extensions/shepherd.min.js"></script>
    <script src="/js/StepsTour.js"></script>
    <script>
        var arr_ar = [];
        // tour initialize
        var tour = new Shepherd.Tour({
            classes: 'shadow-md bg-purple-dark',
            scrollTo: true
        });
		
		var r_code = {{$rcode}};

        AddSteps(1, '<?= __('training_i.page_8') ?>', '#account-tab bottom');
        AddSteps(0, '<?= __('training_i.page_9') ?>', '#immediate-tab bottom');

        function readURL(input) {
            if (input.files && input.files[0]) {
                var reader = new FileReader();

                reader.onload = function (e) {
                    $('#avatar').attr('src', e.target.result);
                }

                reader.readAsDataURL(input.files[0]);
            }
        }
        function reloadHR() {

            $('.hlpeoples').html('');
            $('#hd_data').val('[]');
            var list = '';
            for (var i = 0; i < arr_ar.length; i++) {
                var row = arr_ar[i];

                var perms = row['perms'].map(function (value, index, array) {

                    return value.name;

                });

                list += '<div class="col-3 mt-1" onclick="deleteHR('+ i +');">';
                list += '<a href="javascript: void(0);">';
                list += '<div class="media">';
                list += '<img src="'+ row.picture +'" class="rounded mr-75" alt="profile image" height="80" width="80">';
                list += '<div class="media-body mt-25">';
                list += '<div class="col-12 px-0 d-flex flex-sm-row flex-column justify-content-start">';
                list += '<h6>'+ row.name +'</h6>';
                list += '</div>';
                list += '<p class="text-muted"><b><small>'+ perms.join(', ') +'</small></b><br><small>'+ row.r_code +'</small></p>';
                list += '</div>';
                list += '</div>';
                list += '</a>';
                list += '</div>';
            }

            $('.hlpeoples').html(list);
            $('#hd_data').val(JSON.stringify(arr_ar));
        }

        function addHR() {
            if ($(".js-select22").select2('data').length == 0) {
                return $error('Você precisa escolher uma pessoa para adicionar.');
            } else {
                for (var i = 0; i < arr_ar.length; i++) {
                    var find = arr_ar[i];
                    if (find.r_code == $(".js-select22").select2('data')[0].id) {
                        return $error('Colaborador já existe na lista.');
                    }
                }

                var perms = $(".js-select23").select2('data').map(function (value, index, array) {
                    return {'name': value.text, 'id': value.id};
                });

                arr_ar.push({
                    'perms': perms,
                    'r_code': $(".js-select22").select2('data')[0].id,
                    'name': $(".js-select22").find(':selected').attr('data-name'),
                    'picture': $(".js-select22").find(':selected').attr('data-picture')
                });

                $(".js-select22, .js-select23").val(0).trigger("change");
                reloadHR();
            }
        }
        function deleteHR(index) {
            Swal.fire({
                title: 'Tem certeza disso?',
                text: "Você irá remover a pessoa da permissão!",
                type: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#3085d6',
                cancelButtonColor: '#d33',
                confirmButtonText: 'Confirmar!',
                cancelButtonText: 'Cancelar',
                confirmButtonClass: 'btn btn-primary',
                cancelButtonClass: 'btn btn-danger ml-1',
                buttonsStyling: false,
            }).then(function (result) {
                if (result.value) {
                    arr_ar.splice(index, 1);
                    reloadHR();
                }
            })
        }
        $(document).ready(function () {
			
			$(".radio-auth").change(function(){

                var val = $(".radio-auth:checked").val();

                if(val == 2) {

                    Swal.fire({
                        title: 'Resetar autenticação de 2 fatores',
                        text: "Você realmente deseja resetar autenticação de 2 fatores do usuário?",
                        type: 'warning',
                        showCancelButton: true,
                        confirmButtonColor: '#3085d6',
                        cancelButtonColor: '#d33',
                        confirmButtonText: 'Confirmar',
                        cancelButtonText: 'Cancelar',
                        confirmButtonClass: 'btn btn-primary',
                        cancelButtonClass: 'btn btn-danger ml-1',
                        buttonsStyling: false,
                    }).then(function (result) {
                        if (result.value) {
                            block();
                            window.location.href = '/user/reset/auth/'+r_code;
                        } else {
                            $("input[name=is_otpauth][value=1]").prop('checked', true);
                            $("input[name=is_otpauth][value=2]").prop('checked', false);
                        }
                    });
                }
            });

            $('#holiday_notify').change(function() {
                if ($('#holiday_notify').val() == 1) {
                    $('.holiday_msg').show();
                } else {
                    $('.holiday_msg').hide();
                }
            });

            @if(count($user_holiday) > 0)
            @foreach ($user_holiday as $key)
            @php $row = json_decode($key, true); @endphp
            @php $usr = $usersall->where('r_code', $row['r_code'])->first(); @endphp
            arr_ar.push({
                'perms': [
                        @foreach ($row['perms'] as $prms)
                    {
                        'name': '{{$prms['name']}}',
                        'id': {{$prms['id']}},
                    },
                    @endforeach
                ],
                'r_code': '{{$row['r_code']}}',
                'name': '@if($usr) {{$usr->first_name}} @endif',
                'picture': '@if($usr) {{$usr->picture}} @endif'
            });
            @endforeach
            reloadHR();
            @endif

            // function to remove tour on small screen
            window.resizeEvt;
            if ($(window).width() > 576) {
                $('#ActiveTraine').on("click", function () {
                    clearTimeout(window.resizeEvt);
                    tour.start();
                })
            }
            else {
                $('#ActiveTraine').on("click", function () {
                    clearTimeout(window.resizeEvt);
                    tour.cancel()
                    window.resizeEvt = setTimeout(function () {
                        alert("Tour only works for large screens!");
                    }, 250);
                })
            }

            $('#btnholiday').click(function() {

                if ($('#holiday_status').val() == 1) {
                    if (arr_ar.length == 0) {
                        return $error('Você precisa adicionar pessoas com as permissões para ativar o modo férias.');
                    } else {

                        block();
                        $('#hdform').submit();
                    }
                } else {
                    block();
                    $('#hdform').submit();
                }
            });

            $(".js-select2").select2({
                maximumSelectionLength: 1,
            });
            $(".js-select22").select2({
                maximumSelectionLength: 1,
            });
            $(".js-select23").select2();
            $('#birthday').daterangepicker({
                singleDatePicker: true,
                showDropdowns: true,
                locale: {
                    format: 'YYYY-MM-DD'
                },
                minYear: 1901,
                maxYear: parseInt(moment().format('YYYY'),10)
            });

            $("#submitEdit").submit(function (e) {
                var form = $(".needs-validation");
                if (form[0].checkValidity() === false) {
                    e.preventDefault();
                } else {
                    block();
                }

            });

            $(".changePic").click(function (e) {
                $('#picture').trigger('click');
            });

            $(".resetPic").click(function (e) {
                $('#picture').val('');
                $('#avatar').attr('src', '<?php if (!empty($picture)) { echo $picture; } else { ?>/media/avatars/avatar10.jpg<?php } ?>');
            });

            $("#picture").change(function(){
                readURL(this);
            });
        });
    </script>
    <script src="/admin/app-assets/js/scripts/forms/form-tooltip-valid.min.js"></script>
    <script>
        var ArrayImmediate = new Array();

        function toFALoad(isactive) {
            block();
            ajaxSend('/2fa/update', {active_otp: isactive}, 'POST', '5000').then(function(result){
                unblock();
                if (result.html != '')
                    $(".loadqrcode").html(result.html);
                else
                    $(".loadqrcode").html('');
            }).catch(function(err){
                unblock();
                $error(err.message)
            })
        }

        function deleteImd(index) {
            <?php if ($rcode != Session::get('r_code')) { ?>
            Swal.fire({
                title: 'Tem certeza disso?',
                text: "Você irá remover o imediato chefe!",
                type: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#3085d6',
                cancelButtonColor: '#d33',
                confirmButtonText: 'Confirmar!',
                cancelButtonText: 'Cancelar',
                confirmButtonClass: 'btn btn-primary',
                cancelButtonClass: 'btn btn-danger ml-1',
                buttonsStyling: false,
            }).then(function (result) {
                if (result.value) {
                    ArrayImmediate.splice(index, 1);
                    ReloadImmediate();
                    Swal.fire(
                        {
                            type: "success",
                            title: 'Removido',
                            text: 'Imediado foi removido.',
                            confirmButtonClass: 'btn btn-success',
                        }
                    )
                }
            })
            <?php } ?>
        }

        function ReloadImmediate() {
            var list = "";
            for(var i = 0; i < ArrayImmediate.length; i++) {
                var arrayObj = ArrayImmediate[i];
                list += '<div class="col-2 mt-1" onclick="deleteImd('+ i +');">';
                list += '<a href="javascript: void(0);">';
                list += '<div class="media">';
                list += '<img src="'+ arrayObj.picture +'" class="rounded mr-75" alt="profile image" height="64" width="64">';
                list += '<div class="media-body mt-25">';
                list += '<div class="col-12 px-0 d-flex flex-sm-row flex-column justify-content-start">';
                list += '<h6>'+ arrayObj.name +'</h6>';
                list += '</div>';
                list += '<p class="text-muted"><small>'+ arrayObj.r_code +'</small></p>';
                list += '</div>';
                list += '</div>';
                list += '</a>';
                list += '</div>';
            }
            $(".imdts").html(list);
            $("#data_input").val(JSON.stringify(ArrayImmediate));
        }
        $(document).ready(function () {

            <?php if ($rcode == 0) { ?>
            setInterval(() => {
                $("#mAdmin").addClass('sidebar-group-active active');
                $("#mUser").addClass('sidebar-group-active active');
                $("#mUserNew").addClass('active');
            }, 100);
            <?php } ?>

            <?php if (Session::has('error')) { ?>
            error(<?= Session::get('error') ?>');
            <?php } Session::forget('error'); ?>
            <?php if (Session::has('success')) { ?>
            success('<?= Session::get('success') ?>');
            <?php } Session::forget('success'); ?>

            <?php if (!empty($immediates)) { ?>
            <?php foreach ($immediates as $key) { ?>
            ArrayImmediate.push({
                "r_code" : '<?= $key->r_code ?>',
                "name" : '<?= getENameF($key->r_code) ?>',
                "picture" : '<?php if ($key->picture) { echo $key->picture; } else { echo $img = '/media/avatars/avatar10.jpg'; } ?>',
            });

            ReloadImmediate();
            <?php } ?>
            <?php } ?>

            $('.date-mask').pickadate({
                formatSubmit: 'yyyy-mm-dd',
                format: 'yyyy-mm-dd',
                today: 'Hoje',
                clear: 'Limpar',
                close: 'Fechar',
                monthsFull: ['Janeiro', 'Fevereiro', 'Março', 'Abril', 'Maio', 'Junho', 'Julho', 'Agosto', 'Setembro', 'Outubro', 'Novembro', 'Dezembro'],
                monthsShort: ['Jan', 'Fev', 'Mar', 'Abr', 'Mai', 'Jun', 'Jul', 'Ago', 'Set', 'Out', 'Nov', 'Dez'],
                weekdaysFull: ['Domingo', 'Segunda-Feira', 'Terça-Feira', 'Quarta-Feira', 'Quinta-Feira', 'Sexta-Feira', 'Sábado'],
                weekdaysShort: ['Dom', 'Seg', 'Ter', 'Qua', 'Qui', 'Sex', 'Sab'],
            });

            $("#addboss").click(function (e) {
                if ($("#c_rcode").val() == "") {
                    error('Preencha o número de matricula do imediato.');
                } else {
                    var rcode = $(".js-select2").select2('data')[0]['id'];
                    $('.js-select2').val(0).trigger("change");
                    block();
                    $.ajax({
                        type: "POST",
                        timeout: 5000,
                        url: "/user/ajax/immediate",
                        data: {c_rcode:rcode, registration:$("#registration").val()},
                        success: function (response) {
                            unblock();
                            if (response.success) {
                                for(var i = 0; i < ArrayImmediate.length; i++) {
                                    var arrayObj = ArrayImmediate[i];
                                    if (arrayObj.r_code == rcode) {

                                        error('Você já adicionou esse imediato a sua lista.');
                                        return;
                                    }
                                }

                                success('Imediato disponível para anexar ao cadastro.');

                                ArrayImmediate.push({
                                    "r_code" : response.r_code,
                                    "name" : response.name,
                                    "picture" : response.picture,
                                });


                                ReloadImmediate();
                            } else {
                                error(response.error);
                            }

                        },
                        error: function(jqXHR, textStatus){
                            if(textStatus == 'timeout')
                            {

                                error('Ocorreu um erro na sua conexão, tente novamente!');
                            }
                        }
                    });
                }

            });

            <?php $a = 0; ?>
            <?php foreach ($perm as $key) { ?>
            $("#perm_manager<?= $a ?>").click(function (e) {
                if ($("#perm_manager<?= $a ?>").prop("checked")) {
                    $(".grade_<?= $a ?>").hide();
                } else {
                    $(".grade_<?= $a ?>").show();
                }

            });
            <?php $a++; ?>
            <?php } ?>

        });
    </script>
@endsection
