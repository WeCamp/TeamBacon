# Team Bacon

Some hopefully useful documentation.

## CLI script 
There's a command line script to import data from github into the graph: `scripts/cli`.
In order to use it you first have to make it executable, like so
    
    chmod a+x scripts/cli

You can call it like this: 

    ./scripts/cli bacon:import-github

It requires a few parameters, see in `src/Console/Import2GraphCommand.php`
