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
* Tag
* Language


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


* Team : BELONGS_TO  -> Organisation
* Team - HAS_ACCESS_TO -> Repository
    * property: type, values: read, write, read-and-write

* Organisation : OWNS -> Repository

    
* Tag
* Language


Create relation

 (Keanu)-[:ACTED_IN {roles:['Neo']}]->(TheMatrixReloaded),


Transformer:
User->login -> User->username

