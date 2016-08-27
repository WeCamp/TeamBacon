$(document).ready(function() {
    resize();

    $(window).resize(function() {
        resize();
    });
});

function resize() {
    $('#neo4j').attr('height', ($(window).height() - 48) + 'px');
    $('.sidebar').attr('style', 'height: ' + ($(window).height() - 48) + 'px;');
}
