# Six Degrees

## Configuration
In order to run an application you have to configure it. For that configuration file needs to be created (you can just copy content of `local.php.dist`):

    ./config/local.php
    
that returns an array, e.g.

    <?php
    
    return [
        'neo4jHost' => 'http://neo4j:neo4jneo4j@192.168.99.100:7474'
    ];

## CLI script 
There are command line scripts to import and clear data from GitHub into the graph: `scripts/cli`.
In order to use it you first have to make it executable, like so
    
    chmod a+x scripts/cli

### Import data

    ./scripts/cli bacon:import-github user

It requires a few parameters, see `src/Console/Import2GraphCommand.php` for the options.
The command above will import the users.

### Clear data

    ./scripts/cli bacon:clear-storage

## Endpoints

We provide the following endpoints:

Get a list of all users

    /api/users

Get a list of 1 user

    /api/users/{id}
