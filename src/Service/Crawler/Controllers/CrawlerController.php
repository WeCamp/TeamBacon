<?php
declare(strict_types=1);

namespace Bacon\Service\Crawler\Controllers;


use Bacon\Service\Crawler\Bags\UserBag;
use Bacon\Service\Crawler\Dto\Organization;
use Bacon\Service\Crawler\Dto\User;

class CrawlerController
{
    protected $useCachedResponses = true;
    protected $cacheDir = __DIR__ . '/../../../../cache/Crawler/';
    protected $organizationURL = 'https://api.github.com/orgs/wecamp';

    public function getData()
    {
        $contents = $this->makeRequest($this->organizationURL);
        $orgObject = json_decode($contents);
        $org = Organization::createFromObject($orgObject);

        $content = $this->makeRequest($this->organizationURL . '/members?per_page=100');
        $membersObject = json_decode($content);
        $userBag = new UserBag([]);
        foreach ($membersObject as $object)
        {
            $userBag->add(User::createFromObject($object));
        }

        print_r($userBag);
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
        $res = $client->get($url);

        $contents = $res->getBody()->getContents();

        if ($this->useCachedResponses && $hash) {
            file_put_contents($this->cacheDir . $hash, $contents);
        }

        return $contents;
    }
}