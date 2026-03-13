<div class="modal fade" id="agregar_registro" tabindex="-1" role="modal" aria-hidden="true" aria-labelledby="exampleModalLabel">
    <div class="modal-dialog modal-lg p-2" role="document">
        <div class="modal-content">
            <form method="POST">
                <input type="hidden" value="<?= $id_log ?>" name="id_log">

                <div class="modal-header p-3">
                    <h4 class="modal-title text-play font-weight-bold">Agregar Registro</h4>
                </div>

                <div class="modal-body border-0">
                    <div class="row p-3">
                        <div class="col-lg-6 form-group">
                            <label class="font-weight-bold">Nombre del acudiente <span class="text-danger">*</span></label>
                            <input type="text" name="acudiente" class="form-control" placeholder="Ingrese el nombre del acudiente" required>
                        </div>

                        <div class="col-lg-6 form-group">
                            <label class="font-weight-bold">Cantidad <span class="text-danger">*</span></label>
                            <input type="number" name="visitante" class="form-control" id="cantidad" placeholder="Ingrese la cantidad de visitantes" required>
                        </div>

                        <div class="col-lg-12 form-group">
                            <label class="font-weight-bold">Nombre de Visitante(s)<span class="text-danger">*</span></label>
                            <textarea name="nombres" class="form-control" placeholder="Ingrese el nombre de visitantes" id="nombres" rows="3" required></textarea>
                        </div>

                        <div class="col-lg-6 form-group">
                            <label class="font-weight-bold">Celular del acudiente <span class="text-danger">*</span></label>
                            <input type="tel" name="celular" class="form-control" placeholder="Celular" required>
                        </div>

                        <div class="col-lg-6 form-group">
                            <label class="font-weight-bold">Fecha de ingreso <span class="text-danger">*</span></label>
                            <input type="date" name="fechaingreso" class="form-control" value="<?= date('Y-m-d') ?>" required>
                        </div>

                        <div class="col-lg-6 form-group">
                            <label class="font-weight-bold">Duración <span class="text-danger">*</span></label>
                            <select name="duracion" class="form-control" id="duracion_registro" required>
                                <option value="0" selected disabled>Seleccionar</option>
                                <option value="1">30 min</option>
                                <option value="2">60 min</option>
                            </select>
                        </div>

                        <div class="col-lg-6 form-group">
                            <label class="font-weight-bold">Metodo de pago <span class="text-danger">*</span></label>
                            <select name="metodopago" class="form-control" id="metodopago_registro" required>
                                <option value="" selected disabled>Seleccionar</option>
                                <option value="Efectivo">Efectivo</option>
                                <option value="Datafono">Datafono</option>
                                <option value="Transferencia">Transferencia</option>
                            </select>
                        </div>

                        <div class="col-lg-6 form-group">
                            <label class="font-weight-bold">Hora de ingreso <span class="text-danger">*</span></label>
                            <input type="time" name="horaingreso" class="form-control" id="hora_ingreso_registro" required>
                        </div>

                        <div class="col-lg-6 form-group">
                            <label class="font-weight-bold">Hora de salida</label>
                            <input type="time" name="horasalida" class="form-control" id="hora_salida_registro" readonly>
                        </div>

                        <div class="col-lg-12 form-bold">
                            <label class="font-weight-bold">Valor a cancelar</label>
                            <input type="text" name="valor" class="form-control" id="valor_registro" readonly>
                        </div>

                        <div class="col-lg-12 form-group mt-2 text-right">
                            <button class="btn btn-danger btn-sm" type="button" data-dismiss="modal">
                                <i class="fa fa-times"></i>&nbsp; Cancelar
                            </button>
                            <button class="btn btn-success btn-sm" type="submit" name="submit_registro">
                                <i class="fa fa-save"></i>&nbsp; Guardar
                            </button>
                        </div>
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        function calcularHoraSalida() {
            var horaIngreso = document.getElementById('hora_ingreso_registro').value; 
            var duracion = parseInt(document.getElementById('duracion_registro').value); 
            var cantidadVisitantes = parseInt(document.getElementById('cantidad').value) || 1; // Tomamos la cantidad de visitantes
            var valorInput = document.getElementById('valor_registro'); 

            var minutosDuracion = 0;
            var valorCancelar = 0;

            if (duracion === 1) {
                minutosDuracion = 30; 
                valorCancelar = 28000; 
            } else if (duracion === 2) {
                minutosDuracion = 60; 
                valorCancelar = 38000; 
            }

            // Multiplicamos el valor por la cantidad de visitantes
            valorCancelar = valorCancelar * cantidadVisitantes;
            valorInput.value = valorCancelar;

            if (horaIngreso && minutosDuracion > 0) {
                var partes = horaIngreso.split(':');
                var hora = parseInt(partes[0]); 
                var minutos = parseInt(partes[1]); 

                minutos += minutosDuracion;

                if (minutos >= 60) {
                    hora += Math.floor(minutos / 60);
                    minutos = minutos % 60;
                }

                if (hora >= 24) {
                    hora -= 24;
                }

                var horaSalida = ('0' + hora).slice(-2) + ':' + ('0' + minutos).slice(-2);
                document.getElementById('hora_salida_registro').value = horaSalida;
            } else {
                document.getElementById('hora_salida_registro').value = ''; 
            }
        }

        // Escuchar cambios en cantidad de visitantes también
        document.getElementById('hora_ingreso_registro').addEventListener('change', calcularHoraSalida);
        document.getElementById('duracion_registro').addEventListener('change', calcularHoraSalida);
        document.getElementById('cantidad').addEventListener('input', calcularHoraSalida); // Cambio aquí

        calcularHoraSalida();
    });
</script>

