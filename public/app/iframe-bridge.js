'use strict';

var neo4jIframe = $('#neo4j')[0].contentWindow;

$(document).ready(function() {
    $('a[data-type=user]').click(
        function(e) {
            e.preventDefault();
            selectUser($(this).attr('data-username'));
        }
    );
});

function selectUser(userName) {
    var codeMirror = neo4jIframe.$('.CodeMirror')[0].CodeMirror;
    var doc = codeMirror.getDoc();
    doc.setValue('MATCH (u:User {username: "' + userName + '"})-[:SUBSCRIBES_TO]->(r) RETURN u,r limit 100');
    setTimeout(function() {
        neo4jIframe.$('a.success').click();
    }, 500);
}
