$(document).ready(function() {
    var tipoEvento = ((document.ontouchstart !== null) ? 'click' : 'touchstart');
    /*-------------------------*/
    $(".inactivar").on(tipoEvento, function() {
        var id_opcion = $(this).attr('id');
        var id_perfil = $(this).attr('data-perfil');
        var id_log = $(this).attr('data-log');
        inactivarOpcion(id_opcion, id_perfil, id_log);
    });
    $(".listado").on("click", ".inactivar", function() {
        var id_opcion = $(this).attr('id');
        var id_perfil = $(this).attr('data-perfil');
        var id_log = $(this).attr('data-log');
        inactivarOpcion(id_opcion, id_perfil, id_log);
    });
    /*---------------------------*/
    $(".activar").on(tipoEvento, function() {
        var id_opcion = $(this).attr('id');
        var id_perfil = $(this).attr('data-perfil');
        var id_log = $(this).attr('data-log');
        activarOpcion(id_opcion, id_perfil, id_log);
    });
    $(".listado").on("click", ".activar", function() {
        var id_opcion = $(this).attr('id');
        var id_perfil = $(this).attr('data-perfil');
        var id_log = $(this).attr('data-log');
        activarOpcion(id_opcion, id_perfil, id_log);
    });
    /*--------------------------------------------*/
    function inactivarOpcion(id_opcion, id_perfil, id_log) {
        try {
            $.ajax({
                url: '../vistas/ajax/permisos/inactivar.php',
                method: 'POST',
                data: {
                    'id_opcion': id_opcion,
                    'id_perfil': id_perfil,
                    'id_log': id_log,
                },
                cache: false,
                success: function(resultado) {
                    if (resultado == 'ok') {
                        $('.opcion_' + id_opcion).removeClass('active inactivar').addClass('activar');
                        $('.opcion_' + id_opcion + ' i').removeClass('fa-times').addClass('fa-check');
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
    /*--------------------------------------------*/
    function activarOpcion(id_opcion, id_perfil, id_log) {
        try {
            $.ajax({
                url: '../vistas/ajax/permisos/activar.php',
                method: 'POST',
                data: {
                    'id_opcion': id_opcion,
                    'id_perfil': id_perfil,
                    'id_log': id_log,
                },
                cache: false,
                success: function(resultado) {
                    if (resultado == 'ok') {
                        $('.opcion_' + id_opcion + '').removeClass('activar').addClass('active inactivar');
                        $('.opcion_' + id_opcion + ' i').removeClass('fa-check').addClass('fa-times');
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