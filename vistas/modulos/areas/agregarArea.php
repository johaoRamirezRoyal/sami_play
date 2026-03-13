<!-- Agregar Area -->
<div class="modal fade" id="agregar_area" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-md" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title text-primary font-weight-bold" id="exampleModalLabel">Agregar Area</h5>
            </div>
            <form method="POST">
                <div class="modal-body border-0">
                    <div class="row p-2">
                        <div class="col-lg-12">
                            <input type="hidden" value="<?= $id_super_empresa ?>" name="super_empresa">
                            <input type="hidden" value="<?= $id_log ?>" name="id_log">
                            <div class="form-group">
                                <label class="font-weight-bold">Nombre <span class="text-danger">*</span></label>
                                <input type="text" class="form-control" required name="nombre" maxlength="50" minlength="1">
                            </div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer border-0">
                    <button type="button" class="btn btn-danger btn-sm" data-dismiss="modal">
                        <i class="fa fa-times"></i>
                        &nbsp;
                        Cerrar
                    </button>
                    <button type="submit" class="btn btn-primary btn-sm">
                        <i class="fa fa-save"></i>
                        &nbsp;
                        Registrar
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>