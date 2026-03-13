<div class="modal fade" id="agregar_pago" tabindex="-1" role="modal" aria-hidden="true" aria-labelledby="exampleModalLabel">
    <div class="modal-dialog modal-lg p-2" role="document">
        <div class="modal-content">
            <form method="POST" >
                <input type="hidden" value="<?=$id_log?>" name="id_log">
                <div class="modal-header p-3">
                    <h4 class="modal-title text-play font-weight-bold">Agregar Pago</h4>
                </div>
                <div class="modal-body border-0">
                    <div class="row  p-3">
                        <div class="col-lg-6 form-group">
                            <label class="font-weight-bold">Estudiante <span class="text-danger">*</span></label>
                            <select name="estudiante" class="form-control" required>
                                <option value="" selected>Seleccione una opcion...</option>
                                <?php
                                foreach ($datos_estudiantes as $estudiante) {
                                    $id_estudiante  = $estudiante['id_user'];
                                    $nom_estudiante = $estudiante['nombre'] . ' ' . $estudiante['apellido'];
                                    ?>
                                    <option value="<?=$id_estudiante?>"><?=$nom_estudiante?></option>
                                    <?php
                                }
                                ?>
                            </select>
                        </div>
                        <div class="col-lg-6 form-group">
                            <label class="font-weight-bold">Fecha de pago <span class="text-danger">*</span></label>
                            <input type="date" name="fecha_pago" class="form-control" value="<?=date('Y-m-d')?>" >
                        </div>
                        <div class="col-lg-6 form-group">
                            <label class="font-weight-bold">A&ntilde;o a pagar <span class="text-danger">*</span></label>
                            <select name="anio" class="form-control" required>
                                <option value="" selected>Seleccione una opcion...</option>
                                <?php
                                $anio = date('Y');
                                for ($i = $anio - 2; $i <= 2050; $i++) {echo "<option value='" . $i . "'>" . $i . "</option>";}?>
                            </select>
                    </div>
                    <div class="col-lg-12 form-group">
                        <label class="font-weight-bold">Meses a pagar <span class="text-danger">*</span></label>
                        <div class="form-inline">
                            <?php
                            foreach ($datos_meses as $mes) {
                                $id_mes  = $mes['id'];
                                $nom_mes = $mes['nombre'];
                                ?>
                                <div class="custom-control custom-switch col-lg-2">
                                  <input type="checkbox" class="custom-control-input" id="<?=$id_mes?>" value="<?=$id_mes?>" name="mes[]">
                                  <label class="custom-control-label" for="<?=$id_mes?>"><?=$nom_mes?></label>
                              </div>
                          <?php }?>
                      </div>
                  </div>
                  <div class="col-lg-12 form-group mt-2 text-right">
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