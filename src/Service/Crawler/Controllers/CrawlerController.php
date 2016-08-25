<?php
declare(strict_types=1);

namespace Bacon\Service\Crawler\Controllers;


use Bacon\Service\Crawler\Bags\LanguageBag;
use Bacon\Service\Crawler\Bags\RepositoryBag;
use Bacon\Service\Crawler\Bags\UserBag;
use Bacon\Service\Crawler\Dto\Language;
use Bacon\Service\Crawler\Dto\Organization;
use Bacon\Service\Crawler\Dto\Repository;
use Bacon\Service\Crawler\Dto\User;

class CrawlerController
{
    protected $useCachedResponses = true;
    protected $cacheDir;
    protected $apiURL = 'https://api.github.com/';
    protected $organization = 'wecamp';

    public function __construct()
    {
        $this->cacheDir = __DIR__ . '/../../../../cache/Crawler/';
    }

    /**
     * Mines a github organization for its repos and lots of the users info
     *
     * @return Organization
     */
    public function getData()
    {
        // Get the detailed organization information
        $contents = $this->makeRequest($this->apiURL . 'orgs/' . $this->organization);
        $orgObject = json_decode($contents);
        // Put that in a nice object
        $org = Organization::createFromObject($orgObject);

        // Find and add the organizations users and repos and assign them to the organization
        $org->setMembers($this->getOrganizationUsers($this->organization));
        $org->setRepos($this->getOrganizationRepos($this->organization));

        return $org;
    }

    /**
     * Gets all users associated with an organization, this will also drill down into them getting their
     * repo's, starred repo's, languages associated with those repo's and who they are following/followed by.
     *
     * Serious, if the data wasn't cached it would kill the internet.
     *
     * @param string $organization
     * @return UserBag
     */
    protected function getOrganizationUsers(string $organization)
    {
        // Get the summary list of organization members
        $contents = $this->makeRequest($this->apiURL . 'orgs/'. $organization .'/members');
        $membersObject = json_decode($contents);
        $userBag = new UserBag([]);

        // Loop through the summary data and use it to get more detailed information
        foreach ($membersObject as $object)
        {
            // Get the members detailed user information and put it in an object
            $contents = $this->makeRequest($this->apiURL . 'users/' . $object->login);
            $user = User::createFromJson($contents);

            // Get the repositories associated with this user
            $repoBag = $this->getReposFromUrl($this->apiURL . 'users/' . $object->login . '/repos');
            $starredRepoBag = $this->getReposFromUrl($this->apiURL . 'users/' . $object->login . '/starred');
            // And all the followed/following data
            $followingUserBag = $this->getUsersFromUrl($this->apiURL . 'users/' . $object->login . '/following');
            $followersUserBag = $this->getUsersFromUrl($this->apiURL . 'users/' . $object->login . '/followers');

            // Associate all this new data to our User object and add that to our UserBag
            $user->setFollowers($followersUserBag);
            $user->setFollowing($followingUserBag);
            $user->setRepos($repoBag);
            $user->setStarred($starredRepoBag);
            $userBag->add($user);
        }

        return $userBag;
    }

    /**
     * Creates a repo bag from data at the passed url, this method also gets the languages for the repo
     *
     * @param string $url
     * @return RepositoryBag
     */
    protected function getReposFromUrl(string $url)
    {
        // Get the data from the passed url
        $contents = $this->makeRequest($url);
        $repoObject = json_decode($contents);
        $repoBag = new RepositoryBag([]);

        // Loop through to get the languages of the repo's and add to a repo bag
        foreach ($repoObject as $object)
        {
            $repo = Repository::createFromObject($object);
            $repo = $this->getRepoLanguages($repo);
            $repoBag->add($repo);
        }

        return $repoBag;
    }

    /**
     * Get what languages are associated with a repo, this sets the languages in a passed Repository DTO
     * before returning it
     *
     * @param Repository $repository
     * @return Repository
     */
    protected function getRepoLanguages(Repository $repository)
    {
        // Get Language data
        $contents = $this->makeRequest($this->apiURL . 'repos/' . $repository->getFullName() . '/languages');
        $languageArray = json_decode($contents);
        $languageBag = new LanguageBag([]);

        // Creates a LanguageBag and associates that with the Repository DTO
        foreach ($languageArray as $language => $characters)
        {
            $languageBag->add(new Language($language, $characters));
        }
        $repository->setLang($languageBag);

        return $repository;
    }

    /**
     * Put summary user information into a UserBag, a lot of information will be missing from the User DTO's
     * due to the summary nature of the information
     *
     * @param string $url
     * @return UserBag
     */
    protected function getUsersFromUrl(string $url)
    {
        // Get data from URL, lets hope its users information!
        $contents = $this->makeRequest($url);
        $usersObject = json_decode($contents);
        $userBag = new UserBag([]);

        // Add that information to a UserBag
        foreach ($usersObject as $object)
        {
            $userBag->add(User::createFromObject($object));
        }

        return $userBag;
    }

    /**
     * Gets all repositories associated with an organization and get their language. Assuming the
     * organization doesn't have that many repo's this method isn't too intense
     *
     * @param string $organisation
     * @return RepositoryBag
     */
    protected function getOrganizationRepos(string $organization)
    {
        // Get the repo's belonging to the organization
        $contents = $this->makeRequest($this->apiURL . 'orgs/' . $organization . '/repos');
        $repoObject = json_decode($contents);
        $repoBag = new RepositoryBag([]);

        // Loop through them to get the repo's language before adding it to the RepositoryBag
        foreach ($repoObject as $object)
        {
            $repo = Repository::createFromObject($object);
            $repo = $this->getRepoLanguages($repo);
            $repoBag->add($repo);
        }

        return $repoBag;
    }

    /**
     * Makes GET Request to a passed url using guzzle. If useCachedResponses is true then this method
     * would check for cached responses and use that if it exists, otherwise it will make the request
     * and cache the response.
     *
     * @param string $url
     * @return string
     */
    protected function makeRequest(string $url)
    {
        // Check to see if the response from this url has been cached, returning that data if it has
        $hash = null;
        if ($this->useCachedResponses) {
            $hash = md5($url);
            if (file_exists($this->cacheDir . $hash)) {
                return file_get_contents($this->cacheDir . $hash);
            }
        }

        // Otherwise makes the request, always trying to get 100 of data when relevent.
        // We can use Basic Authentication here to get more information in the requests and be allowed
        // more requests per hour.
        // $client = new \GuzzleHttp\Client(['auth' => ['github_username', 'personal_access_token']]);
        $client = new \GuzzleHttp\Client();
        $res = $client->get($url . '?per_page=100');

        $contents = $res->getBody()->getContents();

        // Cache the response if necessary before returning the content
        if ($this->useCachedResponses && $hash) {
            file_put_contents($this->cacheDir . $hash, $contents);
        }

        return $contents;
    }
}