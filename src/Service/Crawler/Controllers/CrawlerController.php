<?php
declare(strict_types=1);

namespace Bacon\Service\Crawler\Controllers;


use Bacon\Service\Crawler\Bags\RepositoryBag;
use Bacon\Service\Crawler\Bags\UserBag;
use Bacon\Service\Crawler\Dto\Organization;
use Bacon\Service\Crawler\Dto\Repository;
use Bacon\Service\Crawler\Dto\User;

class CrawlerController
{
    protected $useCachedResponses = true;
    protected $cacheDir = __DIR__ . '/../../../../cache/Crawler/';
    protected $apiURL = 'https://api.github.com/';
    protected $organization = 'wecamp';

    public function getData()
    {
        $contents = $this->makeRequest($this->apiURL . 'orgs/' . $this->organization);
        $orgObject = json_decode($contents);
        $org = Organization::createFromObject($orgObject);

        $userBag = $this->getUsers($this->organization);
        $repoBag = $this->getRepos($this->organization);

        $org->setMembers($userBag);
        $org->setRepos($repoBag);

        return $org;
    }

    protected function getUsers($organisation)
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

    protected function getRepos($organisation)
    {
        $contents = $this->makeRequest($this->apiURL . 'orgs/' . $organisation . '/repos');
        $repoObject = json_decode($contents);
        $repoBag = new RepositoryBag([]);
        foreach ($repoObject as $object)
        {
            $repoBag->add(Repository::createFromObject($object));
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
        $res = $client->get($url . '?per_page=100');

        $contents = $res->getBody()->getContents();

        if ($this->useCachedResponses && $hash) {
            file_put_contents($this->cacheDir . $hash, $contents);
        }

        return $contents;
    }
}