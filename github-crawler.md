#Github Crawler

###Behaviour
Given an organization name (currently set in \src\Service\Crawler\Controllers\CrawlerController.php) this script will fetch lots of information around the organization.

###Mined Data
* Organization
    * blog url
    * description
    * id
    * location
    * members
        * bio
        * blog
        * id
        * followers
            * id
            * login
            * url
            * avatar
        * following
            * id
            * login
            * url
            * avatar
        * location
        * login
        * name
        * repos
            * blog url
            * description
            * full name
            * id
            * language
                language
                characters
            * name
            * url
        * starred
            * blog url
            * description
            * full name
            * id
            * language
                language
                characters
            * name
            * url
        * url
        * avatar
    * name
    * repos
        * blog url
        * description
        * full name
        * id
        * language
            language
            characters
        * name
        * url
    * url

### Notes
* Under Organization -> members -> followers/following creates a User DTO, but due to the summary nature of the 
information given by github only a few of the parameters are present.
* All information is cached in cache/Crawler, if the crawler makes a new request it will try and cache that 
information unless useCachedResponses is set to false.
* Not all information is available from github without authenticating, and some information may be missing depending
on the authenticating users access levels. For best results it is best to use Basic Authentication which can be enabled
in \src\Service\Crawler\Controllers\CrawlerController.php in makeRequest($url)
    
### Todo
* Use config for github credentials and useCachedResponse