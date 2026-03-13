<div class="modal fade" id="agregar_cortesia" tabindex="-1" role="modal" aria-hidden="true" aria-labelledby="exampleModalLabel">
    <div class="modal-dialog modal-lg p-2" role="document">
        <div class="modal-content">
            <form method="POST">
                <input type="hidden" value="<?= $id_log ?>" name="id_log">

                <div class="modal-header p-3">
                    <h4 class="modal-title text-success font-weight-bold">Agregar Registro de Cortesíaaaaa</h4>
                </div>

                <div class="modal-body border-0">
                    <div class="row p-3">
                        <div class="col-lg-12 form-group">
                            <label class="font-weight-bold">Nombre del acudiente <span class="text-danger">*</span></label>
                            <input type="text" name="acudiente" class="form-control" placeholder="Ingrese el nombre del acudiente" required>
                        </div>

                        <div class="col-lg-6 form-group">
                            <label class="font-weight-bold">Cantidad <span class="text-danger">*</span></label>
                            <input type="number" name="visitante" class="form-control" placeholder="Ingrese la cantidad de visitantes a cargo del acudiente" required>
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
                            <select name="duracion" class="form-control" id="duracion_cortesia" required>
                                <option value="" selected>Seleccionar</option>
                                <option value="0">30 min</option>
                                <option value="1">60 min</option>
                            </select>
                        </div>

                        <div class="col-lg-6 form-group">
                            <label class="font-weight-bold">Metodo de pago <span class="text-danger">*</span></label>
                            <select name="metodopagocortesia" class="form-control" id="metodopagocortesia" required>
                                <option value="" selected disabled>Seleccionar</option>
                                <option value="Efectivo">Efectivo</option>
                                <option value="Datafono">Datafono</option>
                                <option value="Transferencia">Transferencia</option>
                            </select>
                        </div>

                        <div class="col-lg-6 form-group">
                            <label class="font-weight-bold">Tipo de Cortesía <span class="text-danger">*</span></label>
                            <select name="tipocortesia" class="form-control" id="tipocortesia" required>
                                <option value="null" selected disabled>Seleccionar</option>
                                <option value="Buenavista">Buenavista</option>
                                <option value="Referido Socio">Referido Socio</option>
                                <option value="Publicidad">Publicidad</option>
                                <option value="Otro">Otro</option>
                            </select>
                        </div>

                        <div class="col-lg-6 form-group">
                            <label class="font-weight-bold">Hora de ingreso <span class="text-danger">*</span></label>
                            <input type="time" name="horaingreso" class="form-control" id="hora_ingreso_cortesia" required>
                        </div>

                        <div class="col-lg-6 form-group">
                            <label class="font-weight-bold">Hora de salida</label>
                            <input type="time" name="horasalida" class="form-control" id="hora_salida_cortesia" readonly>
                        </div>

                        <div class="col-lg-12 form-bold">
                            <label class="font-weight-bold">Valor a cancelar <span class="text-danger">*</span></label>
                            <input type="text" name="valor" class="form-control" id="valor_cortesia" required>
                        </div>

                        <div class="col-lg-12 form-group mt-2 text-right">
                            <button class="btn btn-danger btn-sm" type="button" data-dismiss="modal">
                                <i class="fa fa-times"></i>&nbsp; Cancelar
                            </button>
                            <button class="btn btn-success btn-sm" type="submit" name="submit_cortesia">
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
        function calcularHoraSalidaCortesia() {
            var horaIngreso = document.getElementById('hora_ingreso_cortesia').value;
            var duracion = parseInt(document.getElementById('duracion_cortesia').value);

            if (horaIngreso && !isNaN(duracion)) {
                var minutosDuracion = (duracion === 0) ? 30 : 60;

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
                document.getElementById('hora_salida_cortesia').value = horaSalida;
            } else {
                document.getElementById('hora_salida_cortesia').value = '';
            }
        }

        document.getElementById('hora_ingreso_cortesia').addEventListener('change', calcularHoraSalidaCortesia);
        document.getElementById('duracion_cortesia').addEventListener('change', calcularHoraSalidaCortesia);

        calcularHoraSalidaCortesia();
    });
</script>
