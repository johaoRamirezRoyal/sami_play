<?php
require_once CONTROL_PATH . 'visitante' . DS . 'ControlVisitante.php';

$instancia = ControlVisitante::singleton_visitante();
$productos = $instancia->mostrarProductosControl();
?>

<div class="modal fade" id="producto" tabindex="-1" role="modal" aria-hidden="true" aria-labelledby="exampleModalLabel">
    <div class="modal-dialog modal-lg p-2" role="document">
        <div class="modal-content">
            <form method="POST">
                <input type="hidden" value="<?= $id_log ?>" name="id_log">

                <div class="modal-header p-3">
                    <h4 class="modal-title text-success font-weight-bold">Comprar producto</h4>
                </div>

                <div class="modal-body border-0">
                    <div class="row p-3">
                        <div class="col-lg-6 form-group">
                            <label class="font-weight-bold">Producto <span class="text-danger">*</span></label>
                            <select name="nombre" id="producto-select" class="form-control" required>
                                <option value="" selected>Seleccionar</option>
                                <?php foreach ($productos as $producto): ?>
                                    <option value="<?= htmlspecialchars($producto['nombre']) ?>" 
                                            data-precio="<?= htmlspecialchars($producto['precio']) ?>">
                                        <?= htmlspecialchars($producto['nombre']) ?>
                                    </option>
                                <?php endforeach; ?>
                            </select>
                        </div>

                        <div class="col-lg-6 form-group">
                            <label class="font-weight-bold">Precio <span class="text-danger">*</span></label>
                            <input type="text" id="" name="precio" class="form-control" placeholder="Precio" required >
                        </div>

                        <div class="col-lg-6 form-group">
                            <label class="font-weight-bold">Cantidad <span class="text-danger">*</span></label>
                            <input type="number" name="cantidad" class="form-control" placeholder="Ingrese la cantidad" required>
                        </div>
                       
                        <div class="col-lg-6 form-group">
                            <label class="font-weight-bold">Método de pago <span class="text-danger">*</span></label>
                            <select name="metodopago" class="form-control" required>
                                <option value="" selected readonly>Seleccionar</option>
                                <option value="Efectivo">Efectivo</option>
                                <option value="Datafono">Datafono</option>
                                <option value="Transferencia">Transferencia</option>
                            </select>
                        </div>

                        <div class="col-lg-12 form-group mt-2 text-right">
                            <button class="btn btn-danger btn-sm" type="button" data-dismiss="modal">
                                <i class="fa fa-times"></i>&nbsp; Cancelar
                            </button>
                            <button class="btn btn-success btn-sm" type="submit" name="comprar">
                                 <i class="fa-solid fa-check-to-slot"></i>&nbsp; Comprar
                            </button>
                        </div>
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>


