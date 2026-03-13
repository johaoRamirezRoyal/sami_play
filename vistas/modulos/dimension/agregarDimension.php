<!--Agregar usuario-->
<div class="modal fade" id="agregar_dimension" tabindex="-1" role="modal" aria-hidden="true" aria-labelledby="exampleModalLabel">
    <div class="modal-dialog modal-lg p-2" role="document">
        <div class="modal-content">
            <form method="POST" enctype="multipart/form-data">
                <input type="hidden" value="<?=$id_log?>" name="id_log">
                <div class="modal-header p-3">
                    <h4 class="modal-title text-play font-weight-bold">Agregar Dimension</h4>
                </div>
                <div class="modal-body border-0">
                    <div class="row  p-3">
                        <div class="col-lg-6 form-group">
                            <label class="font-weight-bold">Nombre de dimension <span class="text-danger">*</span></label>
                            <input type="text" class="form-control" name="nombre" required>
                        </div>
                        <div class="col-lg-6 form-group">
                            <label class="font-weight-bold">Foto</label>
                            <div class="custom-file pmd-custom-file-filled">
                                <input type="file" class="custom-file-input file_input" name="foto" accept=".png, .jpg, .jpeg">
                                <label class="custom-file-label file_label" for="customfilledFile"></label>
                            </div>
                        </div>
                        <div class="col-lg-12 form-group">
                            <label class="font-weight-bold">Rese&ntilde;a</label>
                            <textarea class="form-control" name="observacion" rows="5"></textarea>
                        </div>
                        <div class="col-lg-12 form-group text-right mt-2">
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
                </div>
            </form>
        </div>
    </div>
</div>
