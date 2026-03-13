<?php
$categorias = $instancia->mostrarCategoriasControl();
?>
<div class="modal fade" id="agregar_" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
                                        <div class="modal-dialog" role="document">
                                            <div class="modal-content">
                                                <div class="modal-header">
                                                    <h4 class="modal-title text-play font-weight-bold" id="exampleModalLabel">Agregar Inventario</h4>
                                                </div>
                                                <div class="modal-body border-0">
                                                    <form method="POST">
                                                        <input type="hidden" name="id_articulo" value="">
                                                        <div class="row p-3">
                                                            <div class="col-lg-6 form-group">
                                                                <div class="font-weight-bold">
                                                                    <label>Nombre</label>
                                                                    <input type="text" class="form-control" name="nombre" required>
                                                                </div>
                                                            </div>
                                                            <div class="col-lg-6 form-group">
                                                                <div class="font-weight-bold">
                                                                    <label>Categoria</label>
                                                                    <select name="categoria" class="form-control" required>
                                                                        <option value="">Seleccione una opcion...</option>
                                                                        <?php
                                                                        foreach ($categorias as $categoria) {
                                                                            $id_categoria  = $categoria['id'];
                                                                            $nombre_categoria = $categoria['nombre'];
                                                                            ?>
                                                                            <option value="<?=$id_categoria?>"><?=$nombre_categoria?></option>
                                                                        <?php }?>
                                                                    </select>
                                                                </div>
                                                            </div>
                                                            <div class="col-lg-6 form-group">
                                                                <div class="font-weight-bold">
                                                                    <label>Cantidad</label>
                                                                    <input type="number" class="form-control" name="cantidad" required>
                                                                </div>
                                                            </div>
                                                        </div>
                                                        <div class="row p-3">
                                                            <div class="col form-group">
                                                                <div class="font-weight-bold">
                                                                    <label>Precio</label>
                                                                    <input type="text" class="form-control" name="precio" required>
                                                                </div>
                                                            </div>
                                                        </div>
                                                        <div class="row p-3">
                                                            <div class="col-lg-12 form-group">
                                                                <label class="font-weight-bold">Variaciones del producto</label>
                                                                <div id="variaciones-container">
                                                                    <div class="input-group mb-2">
                                                                        <input type="text" name="variaciones[]" class="form-control" placeholder="Ej: Color rojo, Talla M">
                                                                        <div class="input-group-append">
                                                                            <button class="btn btn-sm btn-success agregar-variacion" type="button">
                                                                                <i class="fas fa-plus"></i>
                                                                            </button>
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </div>
                                                        <!-- Select para el iva si o no -->
                                                        <div class="col-lg-6 mb-2">
                                                            <select name="iva" id="iva">
                                                                <option value="0">Sin IVA</option>
                                                                <option value="1">Con IVA del 19%</option>
                                                            </select>
                                                        </div>
                                                        <div class="col-lg-12 form-group text-right mt-2">
                                                            <button class="btn btn-danger btn-sm" data-dismiss="modal">
                                                                <i class="fa fa-times"></i>
                                                                &nbsp;
                                                                Cancelar
                                                            </button>
                                                            <button class="btn btn-play btn-sm" type="submit" name="agregar"> 
                                                                <i class="fas fa-solid fa-plus"></i> 
                                                                Agregar 
                                                            </button>
                                                        </div>
                                                    </form>
                                                </div>
                                            </div>
                                        </div>
                                        <script>
                                            $(document).ready(function(){
                                                $('#variacionCheck').change(function(){
                                                    if($(this).is(':checked')){
                                                        $('#campoVariacion').slideDown();
                                                    } else {
                                                        $('#campoVariacion').slideUp();
                                                    }
                                                });
                                            });
                                        </script>
                                        <script>
                                            // Función con JQuery, funciona de la siguiente forma (Para la persona del futuro que lo necesite :D):
                                            // El objetivo es agregar un nuevo campo de variaciones al formulario de agregar producto, para esto se usa el
                                            // boton agregar-variacion y el evento click de agregar-variacion
                                            // Se construye un nuevo campo de variaciones con el valor que se ingresa en el input de variaciones[]
                                            // Se agrega un nuevo botón a este campo, para eliminar las variaciones con la función .remove()
                                            $(document).on('click', '.agregar-variacion', function () {
                                                const nuevoCampo = `
                                                <div class="input-group mb-2">
                                                    <input type="text" name="variaciones[]" class="form-control" placeholder="Otra variación">
                                                    <div class="input-group-append">
                                                        <button class="btn btn-sm btn-danger eliminar-variacion" type="button">
                                                            <i class="fas fa-times"></i>
                                                        </button>
                                                    </div>
                                                </div>`;
                                                $('#variaciones-container').append(nuevoCampo);
                                            });

                                            $(document).on('click', '.eliminar-variacion', function () {
                                                $(this).closest('.input-group').remove();
                                            });
                                        </script>
                                    </div>

                                    