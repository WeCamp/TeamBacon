'use strict';

var neo4jIframe = $('#neo4j')[0].contentWindow;

$(document).ready(function() {
    $('a[data-type=user]').click(
        function(e) {
            e.preventDefault();

            var userName = $(this).attr('data-username');

            $('#panel-username').val(userName);
            selectUser(userName);
        }
    );
    
    $('#coworkers-owns').click(function() {
        selectCoworkersOwns($('#panel-username').val());
    });

    $('#coworkers-subscribes-to').click(function() {
        selectCoworkersSubscribesTo($('#panel-username').val());
    });

    $('#lang-php').click(function() {
        language('PHP');
    });

    $('#lang-cobol').click(function() {
        language('COBOL');
    });
});


function executeQuery(query) {
    var codeMirror = neo4jIframe.$('.CodeMirror')[0].CodeMirror;
    var doc = codeMirror.getDoc();
    doc.setValue(query);
    setTimeout(function() {
        neo4jIframe.$('a.success').click();
    }, 500);
}

function selectUser(userName) {
    executeQuery('MATCH (u:User {username: "' + userName + '"})-[:SUBSCRIBES_TO]->(r) RETURN u,r limit 100');
}

function selectCoworkersOwns(userName) {
    executeQuery('MATCH (mike:User {username: "' + userName + '"})-[:OWNS]->(repo)<-[:SUBSCRIBES_TO]-(coWorker:User) RETURN mike, coWorker, repo');
}

function selectCoworkersSubscribesTo(userName) {
    executeQuery('MATCH (mike:User {username: "' + userName + '"})-[:SUBSCRIBES_TO]->(repo)<-[:SUBSCRIBES_TO]-(coWorker:User) RETURN mike, coWorker, repo');
}

function language(language) {
    executeQuery("MATCH (user: User) -[owns: OWNS]-> (repository: Repository) -[uses: USES]-> (language: Language {languageName: '" + language + "'}) return user, repository");
}
