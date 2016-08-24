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

    public function getData()
    {
        $contents = $this->makeRequest($this->apiURL . 'orgs/' . $this->organization);
        $orgObject = json_decode($contents);
        $org = Organization::createFromObject($orgObject);

        $userBag = $this->getOrganisationUsers($this->organization);
        $repoBag = $this->getOrganisationRepos($this->organization);

        $org->setMembers($userBag);
        $org->setRepos($repoBag);

        return $org;
    }

    protected function getOrganisationUsers($organisation)
    {
        $contents = $this->makeRequest($this->apiURL . 'orgs/'. $organisation .'/members');
        $membersObject = json_decode($contents);
        $userBag = new UserBag([]);
        foreach ($membersObject as $object)
        {
            $contents = $this->makeRequest($this->apiURL . 'users/' . $object->login);
            $user = User::createFromJson($contents);
            $contents = $this->makeRequest($this->apiURL . 'users/' . $object->login . '/repos');
            $repoObject = json_decode($contents);
            $repoBag = new RepositoryBag([]);
            foreach ($repoObject as $object)
            {
                $repoBag->add(Repository::createFromObject($object));
            }
            $user->setRepos($repoBag);
            $userBag->add($user);
        }

        return $userBag;
    }

    protected function getOrganisationRepos($organisation)
    {
        $contents = $this->makeRequest($this->apiURL . 'orgs/' . $organisation . '/repos');
        $repoObject = json_decode($contents);
        $repoBag = new RepositoryBag([]);
        foreach ($repoObject as $object)
        {
            $repo = Repository::createFromObject($object);

            $contents = $this->makeRequest($this->apiURL . 'repos/' . $organisation . '/' . $repo->getName() . '/languages');
            $languageArray = json_decode($contents);
            $languageBag = new LanguageBag([]);
            foreach ($languageArray as $language => $characters)
            {
                $languageBag->add(new Language($language, $characters));
            }
            $repo->setLang($languageBag);
            $repoBag->add($repo);
        }

        return $repoBag;
    }

    protected function makeRequest($url)
    {
        $hash = null;
        if ($this->useCachedResponses) {
            $hash = md5($url);
            if (file_exists($this->cacheDir . $hash)) {
                return file_get_contents($this->cacheDir . $hash);
            }
        }

        $client = new \GuzzleHttp\Client();
        $client = new \GuzzleHttp\Client(['auth' => ['cgoosey1', 'fd02b8c1505ab24e1f56352a3ced11f1d69fd376']]);
        $res = $client->get($url . '?per_page=100');

        $contents = $res->getBody()->getContents();

        if ($this->useCachedResponses && $hash) {
            file_put_contents($this->cacheDir . $hash, $contents);
        }

        return $contents;
    }
}