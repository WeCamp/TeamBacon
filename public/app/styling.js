$(document).ready(function() {
    resize();

    $(window).resize(function() {
        resize();
    });
});

function resize() {
    $('iframe[name=neo4j]').attr('height', ($(window).height() - 48) + 'px');
}
