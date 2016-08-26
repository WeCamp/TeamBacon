$(document).ready(function() {
    $('a[data-type=user]').click(
        function(e) {
            e.preventDefault();
            selectUser($(this).attr('data-username'));
        }
    );
});

function selectUser(userName) {
    $('#neo4j')[0].contentWindow.$('div[class="CodeMirror cm-s-neo CodeMirror-wrap"]').click();
    $('#neo4j')[0].contentWindow.$('#editor textarea').val(
        'MATCH (u:User {username: "' + userName + '"})-[:SUBSCRIBES_TO]->(r) RETURN u,r limit 100'
    );
}

