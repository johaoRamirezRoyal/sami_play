$(document).ready(function() {
    var tipoEvento = ((document.ontouchstart !== null) ? 'click' : 'touchstart');
    /*-------------------------*/
    $(".inactivar").on(tipoEvento, function() {
        var id = $(this).attr('id');
        var log = $(this).attr('data-log');
        inactivar(id, log);
    });
    /*-------------------------*/
    $(".activar").on(tipoEvento, function() {
        var id = $(this).attr('id');
        var log = $(this).attr('data-log');
        activar(id, log);
    });
    /*-------------------------------------------------*/
    function inactivar(id, log) {
        try {
            $.ajax({
                url: '../vistas/ajax/dimension/inactivar.php',
                method: 'POST',
                data: {
                    'id': id,
                    'log': log,
                },
                cache: false,
                success: function(resultado) {
                    if (resultado == 'ok') {
                        $('.btni_' + id).addClass('d-none');
                        $('.btna_' + id).removeClass('d-none');
                        ohSnap("Inactivado correctamente!", {
                            color: "yellow",
                            'duration': '1000'
                        });
                    } else {
                        ohSnap("Error al inactivar!", {
                            color: "red",
                            'duration': '1000'
                        });
                    }
                }
            });
        } catch (evt) {
            alert(evt.message);
        }
    }
    /*-------------------------------------------------*/
    function activar(id, log) {
        try {
            $.ajax({
                url: '../vistas/ajax/dimension/activar.php',
                method: 'POST',
                data: {
                    'id': id,
                    'log': log,
                },
                cache: false,
                success: function(resultado) {
                    if (resultado == 'ok') {
                        $('.btni_' + id).removeClass('d-none');
                        $('.btna_' + id).addClass('d-none');
                        ohSnap("Activado correctamente!", {
                            color: "green",
                            'duration': '1000'
                        });
                    } else {
                        ohSnap("Error al activar!", {
                            color: "red",
                            'duration': '1000'
                        });
                    }
                }
            });
        } catch (evt) {
            alert(evt.message);
        }
    }
});