<!--Agregar usuario-->
<div class="modal fade" id="agregar_periodo" tabindex="-1" role="modal" aria-hidden="true" aria-labelledby="exampleModalLabel">
    <div class="modal-dialog modal-lg p-2" role="document">
        <div class="modal-content">
            <form method="POST">
                <input type="hidden" value="<?=$id_log?>" name="id_log">
                <div class="modal-header p-3">
                    <h4 class="modal-title text-play font-weight-bold">Agregar Periodo</h4>
                </div>
                <div class="modal-body border-0">
                    <div class="row  p-3">
                        <div class="col-lg-6 form-group">
                            <label class="font-weight-bold">Periodo No. <span class="text-danger">*</span></label>
                            <input type="text" class="form-control numeros" name="numero" required>
                        </div>
                        <div class="col-lg-6 form-group">
                            <label class="font-weight-bold">A&ntilde;o Escolar <span class="text-danger">*</span></label>
                            <select name="anio" class="form-control" required>
                                <option value="" select>Seleccione una opcion...</option>
                                <?php
                                foreach ($datos_anio as $anio) {
                                    $id_anio  = $anio['id'];
                                    $anio_nom = $anio['anio'];
                                    ?>
                                    <option value="<?=$id_anio?>"><?=$anio_nom?></option>
                                <?php }?>
                            </select>
                        </div>
                        <div class="col-lg-6 form-group">
                            <label class="font-weight-bold">Fecha Inicio <span class="text-danger">*</span></label>
                            <input type="date" class="form-control" name="fecha_inicio" required>
                        </div>
                        <div class="col-lg-6 form-group">
                            <label class="font-weight-bold">Fecha Fin <span class="text-danger">*</span></label>
                            <input type="date" class="form-control" name="fecha_fin" required>
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
