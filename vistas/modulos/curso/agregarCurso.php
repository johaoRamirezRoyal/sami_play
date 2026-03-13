<div class="modal fade" id="agregar_curso" tabindex="-1" role="modal" aria-hidden="true" aria-labelledby="exampleModalLabel">
    <div class="modal-dialog p-2" role="document">
        <div class="modal-content">
            <form method="POST" enctype="multipart/form-data">
                <input type="hidden" value="<?=$id_log?>" name="id_log">
                <div class="modal-header p-3">
                    <h4 class="modal-title text-play font-weight-bold">Agregar Curso</h4>
                </div>
                <div class="modal-body border-0">
                    <div class="col-gl-12 form-group">
                        <label class="font-weight-bold">Nombre <span class="text-danger">*</span></label>
                        <input type="text" class="form-control" name="nombre" required>
                    </div>
                    <div class="col-gl-12 form-group mt-2 text-right">
                        <button class="btn btn-danger btn-sm" type="button" data-dismiss="modal">
                            <i class="fa fa-times"></i>
                            &nbsp;
                            Cancelar
                        </button>
                        <button class="btn btn-play btn-sm" type="submit">
                            <i class="fa fa-save"></i>
                            &nbsp;
                            Guardar
                        </button>
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>