#Graph model

##Nodes
* User
    * email
    * name
    * username (unique)
    * avatar
    
* Repository
    * name
    
* Organisation
    * id (unique)
    * etc.
    
* Team
* Language
* Location


##Relationships
* User : OWNS -> Repository
* User : CONTRIBUTES_TO -> Repository
* User : WATCHES -> Repository
* User : STARS -> Repository
    * property: number of stars

* User : FOLLOWS -> User
* User : BELONGS_TO -> Team
* User : BELONGS_TO -> Organisation
    * property: year

* Repository : IS_FORK_OF -> Repository

* User : IS_LOCATED_IN -> Location
* Organisation : IS_LOCATED_IN -> Location

* Team : BELONGS_TO  -> Organisation
* Team - HAS_ACCESS_TO -> Repository
    * property: type, values: read, write, read-and-write

* Organisation : OWNS -> Repository

* Repository : USES -> Language


## Useful neo4j / cyher links
* [http://www.remwebdevelopment.com/blog/sql/some-basic-and-useful-cypher-queries-for-neo4j-201.html]()
* [https://www.sitepoint.com/discover-graph-databases-neo4j-php/]()