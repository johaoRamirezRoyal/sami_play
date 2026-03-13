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
                            <label class="font-weight-bold">Documento <span class="text-danger">*</span></label>
                            <input type="text" class="form-control numeros" name="documento" required>
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
                            <label class="font-weight-bold">Telefono</label>
                            <input type="text" class="form-control numeros" name="telefono">
                        </div>
                        <div class="col-lg-6 form-group">
                            <label class="font-weight-bold">Correo</label>
                            <input type="email" class="form-control" name="correo">
                        </div>
                        <div class="col-lg-6 form-group">
                            <label class="font-weight-bold">Usuario <span class="text-danger">*</span></label>
                            <input type="text" class="form-control" name="user" id="user" required>
                        </div>
                        <div class="col-lg-6 form-group">
                            <label class="font-weight-bold">Perfil <span class="text-danger">*</span></label>
                            <select class="form-control" name="perfil" required>
                                <option selected value="">Seleccione una opcion...</option>
                                <?php
                                foreach ($datos_perfil as $perfiles) {
                                    $id_perfil  = $perfiles['id'];
                                    $nom_perfil = $perfiles['nombre'];

                                    if ($id_perfil != 1 && $id_perfil != 3) {
                                        ?>
                                        <option value="<?=$id_perfil?>"><?=$nom_perfil?></option>
                                        <?php
                                    }
                                }
                                ?>
                            </select>
                        </div>
                        <div class="col-lg-6 form-group">
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