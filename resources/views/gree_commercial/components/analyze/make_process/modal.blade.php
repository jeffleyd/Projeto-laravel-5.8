<div class="modal fade" id="modal_add_approv" tabindex="-1" aria-modal="true" role="dialog">
    <div class="modal-dialog modal-dialog-scrollable modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header bg-primary">
                <h5 class="modal-title white modal_add_approv_title"></h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <i class="bx bx-x"></i>
                </button>
            </div>
            <div class="modal-body">
                <div class="row">
                    <div class="col-12 col-md-12 col-sm-12">
                        <input type="hidden" id="user_index">
                        <div class="form-group">
                            <label>Aprovador</label>
                            <select class="select-approv-add form-control" style="width: 100%;" id="r_code_approv"  multiple></select>
                        </div>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-light-secondary" data-dismiss="modal">
                    <i class="bx bx-x d-block d-sm-none"></i>
                    <span class="d-none d-sm-block">Fechar</span>
                </button>
                <button type="button" class="btn btn-primary ml-1" id="btn_add_approv_position">
                    <i class="bx bx-check d-block d-sm-none"></i>
                    <span class="d-none d-sm-block">Adicionar</span>
                </button>
            </div>
        </div>
    </div>
</div>
