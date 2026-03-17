<div class="modal fade" id="agregar_indicador_csv" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title font-weight-bold text-play" id="exampleModalLabel">Agregar indicador con CSV</h5>
            </div>
            <div class="modal-body">
                <form method="POST" enctype="multipart/form-data" id="formImportarExcel">
                    <input type="hidden" name="id_log" value="<?= $id_log ?>">
                    <div class="row p-2">
                        <div class="col-lg-12 form-group">
                            <label class="font-weight-bold">
                                Selecciona un archivo Excel <span class="text-danger">*</span>
                            </label>

                            <div class="custom-file-input-wrapper">
                                <label for="archivo" class="custom-file-label-btn">
                                    <i class="fa fa-upload"></i> Seleccionar archivo
                                </label>

                                <span id="file-name" class="file-name">
                                    Ningún archivo seleccionado
                                </span>

                                <input
                                    type="file"
                                    name="archivo_excel"
                                    id="archivo"
                                    accept=".xls,.xlsx,.csv"
                                    required>
                            </div>
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
                                    <option value="<?= $id_dimension ?>"><?= $nom_dimension ?></option>
                                <?php } ?>
                            </select>
                        </div>
                        <div class="col-lg-6 form-group">
                            <label class="font-weight-bold">Periodo Escolar <span class="text-danger">*</span></label>
                            <select name="periodo_actual" class="form-control" required>
                                <option value="" selected>Seleccione una opcion...</option>
                                <?php
                                foreach ($datos_periodo as $periodo) {
                                    $id_periodo  = $periodo['id'];
                                    $nom_periodo = $periodo['numero'] . ' - ' . $periodo['anio'];

                                    $selected_periodo = ($datos_curso['periodo_actual'] == $id_periodo) ? 'selected' : '';
                                ?>
                                    <option value="<?= $id_periodo ?>" <?= $selected_periodo ?>><?= $nom_periodo ?></option>
                                <?php } ?>
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
                                    <option value="<?= $id_grupo ?>"><?= $nom_grupo ?></option>
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
                            <button class="btn btn-play btn-sm" type="submit" name="subir_csv">
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

<script>
    document.getElementById('archivo').addEventListener('change', function(e) {
        const fileName = e.target.files.length > 0 ?
            e.target.files[0].name :
            'Ningún archivo seleccionado';

        document.getElementById('file-name').textContent = fileName;
    });
</script>