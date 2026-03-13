$(document).ready(function() {
    var tipoEvento = ((document.ontouchstart !== null) ? 'click' : 'touchstart');
    /*-------------------------*/
    $(".unselect").click(function() {
        $(".check").attr('checked', false);
    });
});