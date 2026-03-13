<!--Agregar usuario-->
<div class="modal fade" id="agregar_usuario" tabindex="-1" role="modal" aria-hidden="true" aria-labelledby="exampleModalLabel">
    <div class="modal-dialog modal-lg p-2" role="document">
        <div class="modal-content">
            <form method="POST" enctype="multipart/form-data" id="form_usuario">
                <input type="hidden" value="<?=$id_log?>" name="id_log">
                <div class="modal-header p-3">
                    <h4 class="modal-title text-play font-weight-bold">Agregar Usuario</h4>
                </div>
                <div class="modal-body border-0">
                    <div class="row  p-3">
                        <div class="col-lg-6 form-group">
                            <label class="font-weight-bold">Tipo de Documento <span class="text-danger">*</span></label>
                            <select class="form-control" name="tipo_doc" id="tipo_doc" required>
                                <option value="" selected>Seleccione una opcion...</option>
                                <option value="T.I">T.I</option>
                                <option value="C.C">C.C</option>
                                <option value="R.C">R.C</option>
                            </select>
                        </div>
                        <div class="col-lg-6 form-group">
                            <label class="font-weight-bold">Documento <span class="text-danger">*</span></label>
                            <input type="text" class="form-control numeros" name="documento" id="user" required>
                        </div>
                        <div class="col-lg-6 form-group">
                            <label class="font-weight-bold">Nombre <span class="text-danger">*</span></label>
                            <input type="text" class="form-control letras" name="nombre" required>
                        </div>
                        <div class="col-lg-6 form-group">
                            <label class="font-weight-bold">Apellido</label>
                            <input type="text" class="form-control letras" name="apellido">
                        </div>
                        <div class="col-lg-6 form-group">
                            <label class="font-weight-bold">Curso <span class="text-danger">*</span></label>
                            <select class="form-control" name="curso" required>
                                <option value="" selected>Seleccione una opcion...</option>
                                <?php
                                foreach ($datos_curso as $curso) {
                                    $id_curso  = $curso['id'];
                                    $nom_curso = $curso['nombre'];
                                    ?>
                                    <option value="<?=$id_curso?>"><?=$nom_curso?></option>
                                    <?php
                                }
                                ?>
                            </select>
                        </div>
                        <div class="col-lg-6 form-group">
                            <label class="font-weight-bold">Genero <span class="text-danger">*</span></label>
                            <select name="genero" class="form-control" required>
                                <option value="" selected>Seleccione una opcion...</option>
                                <option value="1">Masculino</option>
                                <option value="2">Femenino</option>
                            </select>
                        </div>
                        <div class="col-lg-12 form-group">
                            <label class="font-weight-bold">Foto perfil</label>
                            <div class="custom-file pmd-custom-file-filled">
                                <input type="file" class="custom-file-input file_input" name="foto" accept=".png, .jpg, .jpeg">
                                <label class="custom-file-label file_label" for="customfilledFile"></label>
                            </div>
                        </div>
                        <div class="col-lg-12 form-group text-right mt-2">
                            <button class="btn btn-danger btn-sm" data-dismiss="modal">
                                <i class="fa fa-times"></i>
                                &nbsp;
                                Cancelar
                            </button>
                            <button type="submit" class="btn btn-play btn-sm" id="enviar">
                                <i class="fa fa-save"></i>
                                &nbsp;
                                Registrar
                            </button>
                        </div>
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>