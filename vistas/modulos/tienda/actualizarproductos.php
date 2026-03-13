<div class="modal fade" id="actualizar_" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title text-play font-weight-bold" id="exampleModalLabel">Actualizar Inventario</h4>
            </div>
            <div class="modal-body border-0">
                <form method="POST">
                    <input type="hidden" id="id_articulo" name="id_articulo" value="">

                    <div class="row p-3">
                        <div class="col-lg-6 form-group">
                            <div class="font-weight-bold">
                                <label>Nombre</label>
                                <input type="text" class="form-control" id="nombre_producto" name="nombre" required>
                            </div>
                        </div>
                        <div class="col-lg-6 form-group">
                            <div class="font-weight-bold">
                                <label>Stock</label>
                                <input type="number" class="form-control" id="cantidad_producto" name="cantidad" required disabled>
                            </div>
                        </div>
                    </div>
                    <div class="row p-3">
                        <div class="col-lg-6 form-group">
                            <div class="font-weight-bold">
                                <label>Precio</label>
                                <input type="text" class="form-control" id="precio_producto" name="precio" required>
                            </div>
                        </div>
                        <div class="col-lg-6 form-group">
                            <div class="font-weight-bold">
                                <label>Cantidad para agregar</label>
                                <input type="text" class="form-control" id="cantidad_nueva" name="cantidad_nueva">
                            </div>
                        </div>
                    </div>
                    <div class="col-lg-12 form-group text-right mt-2">
                    <button class="btn btn-secondary btn-sm" data-dismiss="modal">
                                <i class="fa fa-times"></i>
                                &nbsp;
                                Cancelar
                            </button>
                        <button type="submit" name="eliminar" class="btn btn-danger btn-sm">
                            <i class="fas fa-solid fa-ban"></i> 
                            Eliminar
                        </button>    
                        <button class="btn btn-play btn-sm" type="submit" name="update">
                            <i class="fas fa-solid fa-plus"></i> Actualizar
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<script>
$('#actualizar_').on('show.bs.modal', function (event) {
    var button = $(event.relatedTarget); // Botón que activó el modal
    var id = button.data('id'); // Extrae la información de los atributos data-*
    var nombre = button.data('nombre');
    var cantidad = button.data('cantidad');
    var precio = button.data('precio');

    // Actualiza los valores en el modal
    var modal = $(this);
    modal.find('#id_articulo').val(id);
    modal.find('#nombre_producto').val(nombre);
    modal.find('#cantidad_producto').val(cantidad);
    modal.find('#precio_producto').val(precio);
});
</script>
