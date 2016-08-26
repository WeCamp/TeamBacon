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

* Organisation : OWNS -> Repository

* Repository : USES -> Language


## Useful neo4j / cyher links
* [http://www.remwebdevelopment.com/blog/sql/some-basic-and-useful-cypher-queries-for-neo4j-201.html]()
* [https://www.sitepoint.com/discover-graph-databases-neo4j-php/]()

Delete all nodes and relations from the repo

    MATCH (n)
    OPTIONAL MATCH (n)-[r]-()
    DELETE n,r
    
    
## Some smaple queries for this graph

list 100 users
    
    MATCH (u:User) RETURN u.username LIMIT 100
    
    MATCH (u:User {username: "skoop"})-[:SUBSCRIBES_TO]->(r) RETURN u,r limit 100
    
    MATCH (u:User {username: "skoop"})-[:OWNS]->(r) RETURN u,r limit 100
    

list  repos of user with username x
    
    MATCH (u:User {username: "mvriel"})-[:SUBSCRIBES_TO]->(r) RETURN u,r limit 100
    
