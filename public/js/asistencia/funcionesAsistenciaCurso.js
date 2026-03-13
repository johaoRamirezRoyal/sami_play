$(document).ready(function() {
    var tipoEvento = ((document.ontouchstart !== null) ? 'click' : 'touchstart');
    /*---------------------*/
    $(".si").click(function() {
        var id = $(this).attr('id');
        var id_log = $(this).attr('data-log');
        var fecha = $(this).attr('data-fecha');
        var asistencia = $(this).attr('data-asistencia');
        asistencia = (asistencia != '') ? asistencia : 0;
        marcarAsistencia(id, 1, id_log, fecha, asistencia);
    });
    /*---------------------*/
    $(".no").click(function() {
        var id = $(this).attr('id');
        var id_log = $(this).attr('data-log');
        var fecha = $(this).attr('data-fecha');
        var asistencia = $(this).attr('data-asistencia');
        asistencia = (asistencia != '') ? asistencia : 0;
        marcarAsistencia(id, 2, id_log, fecha, asistencia);
    });
    /*----------------------*/
    function marcarAsistencia(id, estado, id_log, fecha, asistencia) {
        try {
            $.ajax({
                url: '../vistas/ajax/asistencia/marcarAsistencia.php',
                type: 'POST',
                data: {
                    'id': id,
                    'estado': estado,
                    'id_log': id_log,
                    'fecha': fecha,
                    'asistencia': asistencia,
                },
                cache: false,
                dataType: 'json',
                success: function(resultado) {
                    if (resultado == 'ok') {
                        if (estado == 1) {
                            $(".no_" + id).removeClass('d-none');
                            $(".si_" + id).addClass('d-none');
                        }
                        if (estado == 2) {
                            $(".si_" + id).removeClass('d-none');
                            $(".no_" + id).addClass('d-none');
                        }
                    }
                }
            });
        } catch (evt) {
            alert(evt.message);
        }
    }
});