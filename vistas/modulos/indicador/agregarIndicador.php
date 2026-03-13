<div class="modal fade" id="agregar_indicador" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-lg" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title font-weight-bold text-play" id="exampleModalLabel">Agregar indicador</h5>
      </div>
      <div class="modal-body">
        <form method="POST">
          <input type="hidden" name="id_log" value="<?=$id_log?>">
          <div class="row p-2">
            <div class="col-lg-6 form-group">
              <label class="font-weight-bold">Nombre <span class="text-danger">*</span></label>
              <input type="text" class="form-control" name="nom_indicador" required>
            </div>
            <div class="col-lg-6 form-group">
              <label class="font-weight-bold">Dimension <span class="text-danger">*</span></label>
              <select name="dimension" class="form-control" required>
                <option value="" selected>Selecciona una opcion...</option>
                <?php
                foreach ($datos_dimension as $dimension) {
                  $id_dimension  = $dimension['id'];
                  $nom_dimension = $dimension['nombre'];
                  ?>
                  <option value="<?=$id_dimension?>"><?=$nom_dimension?></option>
                <?php }?>
              </select>
            </div>
            <div class="col-lg-6 form-group">
              <label class="font-weight-bold">Grupo <span class="text-danger">*</span></label>
              <select name="grupo" required class="form-control">
                <option value="" selected>Selecciona una opcion...</option>
                <?php
                foreach ($datos_grupo as $grupo) {
                  $id_grupo  = $grupo['id'];
                  $nom_grupo = $grupo['nombre'];
                  ?>
                  <option value="<?=$id_grupo?>"><?=$nom_grupo?></option>
                  <?php
                }
                ?>
              </select>
            </div>
            <div class="form-group col-lg-12 text-right mt-2">
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
</div>
