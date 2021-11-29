<div class="row mt-1">
    <div class="col-lg-6">
        <div class="card widget-notification">
            <div class="card-header border-bottom py-75" style="padding-left: 0rem;padding-right: 0rem;">
                <h6>APROVADORES</h6>
                <div class="task-header d-flex justify-content-between align-items-center w-100">
                    <select class="select-approv form-control" style="width: 100%;" id="r_code" name="r_code" multiple></select>
                    <span class="dropdown ml-md-2">
                        <button type="button" class="btn btn-icon rounded-circle btn-light-primary" id="btn_add_approv">
                            <i class="bx bx-plus"></i>
                        </button>
                    </span>
                </div>
            </div>
            <div class="card-body p-0">
                <ul class="list-group list-group-flush" id="list_approv">
                    <li class="list-group-item list-group-item-action handle" style="padding-left: 0.5rem">
                        <div class="list-left d-flex">
                            <div class="list-content">
                                <span class="list-title">Não há aprovadores adicionados!</span>
                            </div>
                        </div>
                    </li>
                </ul>
            </div>
        </div>
    </div>
    <div class="col-lg-6">
        <div class="card widget-notification">
            <div class="card-header border-bottom py-75" style="padding-left: 0rem;padding-right: 0rem;">
                <h6>OBSERVADORES</h6>
                <div class="task-header d-flex justify-content-between align-items-center w-100">
                    <select class="select-observers form-control" style="width: 100%;" id="observers_r_code" name="observers_r_code" multiple></select>
                    <span class="dropdown ml-md-2">
                        <button type="button" class="btn btn-icon rounded-circle btn-light-primary" id="btn_add_observers">
                            <i class="bx bx-plus"></i>
                        </button>
                    </span>
                </div>
            </div>
            <div class="card-body p-0">
                <ul class="list-group list-group-flush" id="list_observers">
                    <li class="list-group-item list-group-item-action" style="padding-left: 0.5rem">
                        <div class="list-left d-flex">
                            <div class="list-content">
                                <span class="list-title">Não há observadores adicionados!</span>
                            </div>
                        </div>
                    </li>
                </ul>
            </div>
        </div>
    </div>
</div>
