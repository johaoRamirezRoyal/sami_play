$(document).ready(function() {
    var tipoEvento = ((document.ontouchstart !== null) ? 'click' : 'touchstart');
    /*-------------------------*/
    $(".inactivar").on(tipoEvento, function() {
        var id = $(this).attr('id');
        inactivarUsuario(id);
    });
    $(".buscar").on("click", ".inactivar", function() {
        var id = $(this).attr('id');
        inactivarUsuario(id);
    });
    /*---------------------------*/
    $(".activar").on(tipoEvento, function() {
        var id = $(this).attr('id');
        activarUsuario(id)
    });
    $(".buscar").on("click", ".activar", function() {
        var id = $(this).attr('id');
        activarUsuario(id)
    });
    /*---------------------------*/
    $(".restablecer").on(tipoEvento, function() {
        var id = $(this).attr('id');
        passwordNew(id)
    });
    /*--------------------------------------------*/
    $("#enviar").on(tipoEvento, function(e) {
        e.preventDefault();
        var user = $("#user").val();
        if (user == '') {
            ohSnap("Usuario Vacio!", {
                color: "red",
                'duration': '1000'
            });
        } else {
            consultarDocumento(user)
        }
    });
    /*-------------------------------------------------*/
    function inactivarUsuario(id) {
        try {
            $.ajax({
                url: '../vistas/ajax/usuarios/inactivar.php',
                method: 'POST',
                data: {
                    'id': id
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
    function activarUsuario(id) {
        try {
            $.ajax({
                url: '../vistas/ajax/usuarios/activar.php',
                method: 'POST',
                data: {
                    'id': id
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
    /*-------------------------------------------------*/
    function consultarDocumento(id) {
        try {
            $.ajax({
                url: '../vistas/ajax/usuarios/documento.php',
                method: 'POST',
                data: {
                    'id': id
                },
                cache: false,
                success: function(resultado) {
                    if (resultado == 'ok') {
                        $("#form_usuario").submit();
                    } else {
                        ohSnap("Usuario ya registrado", {
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
    function passwordNew(id) {
        try {
            $.ajax({
                url: '../vistas/ajax/usuarios/password.php',
                method: 'POST',
                data: {
                    'id': id
                },
                cache: false,
                success: function(resultado) {
                    if (resultado == 'ok') {
                        ohSnap("Contraseña restablecida!", {
                            color: "green",
                            'duration': '1000'
                        });
                    } else {
                        ohSnap("Error al restablecer!", {
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