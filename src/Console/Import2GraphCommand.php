<?php
declare(strict_types = 1);

namespace Bacon\Console;


use Bacon\Config\Config;
use Bacon\Entity\Language;
use Bacon\Entity\Location;
use Bacon\Entity\Repository;
use Bacon\Repository\Neo4jLanguageRepository;
use Bacon\Repository\Neo4jLocationRepository;
use Bacon\Repository\Neo4jRepositoryRepository;
use Bacon\Repository\Neo4jUserRepository;
use Bacon\Service\Crawler\Dto\User as IncomingUser;
use GraphAware\Neo4j\OGM\EntityManager;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Input\InputArgument;

/**
 * Class Import2GraphCommand
 * Imports DTO objects from Github or other sources into the Graph
 *
 */
class Import2GraphCommand extends Command
{

    private $em;
    private $languages = [];
    private $locations = [];
    private $userRepo;
    private $languageRepo;
    private $locationRepo;
    private $users = [];
    private $follows = [];

    protected function configure()
    {
        $this
            ->setName('bacon:import-github')
            ->setDescription('import objects from github')
            ->setHelp('Imports objects from github into the Graph and transforms the DTO\'s to OGM ')
            ->addArgument('object', InputArgument::REQUIRED,
                'Please provide an object to import. Available options: user|repository|Language ...');
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        ini_set('memory_limit', '-1');
        $object = $input->getArgument('object');

        // outputs multiple lines to the console (adding "\n" at the end of each line)
        $output->writeln([
            'Importing ' . $object,
            '============',
        ]);


        $this->em = EntityManager::create(Config::get()['neo4jHost']);
        $this->userRepo = new Neo4jUserRepository($this->em);
        $this->languageRepo = new Neo4jLanguageRepository($this->em);
        $this->locationRepo = new Neo4jLocationRepository($this->em);



        if ('user' === $object) {
            $output->writeln('Fetching user details.');
            $controller = new \Bacon\Service\Crawler\Controllers\CrawlerController();
            $org = $controller->getData();

            $this->handleUsers($output, $org);
            $this->handleLocation($output, $org);
            $this->handleFollowingUsers($output);
            $this->handleFollowers($output, $org);
            $this->handleFollowing($output, $org);
            $this->handleLanguages($output, $org);
        }
        if ('followers' === $object) {
            $controller = new \Bacon\Service\Crawler\Controllers\CrawlerController();
            $org = $controller->getData();

            $this->handleFollowers($output, $org);
        }
        if ('following' === $object) {
            $controller = new \Bacon\Service\Crawler\Controllers\CrawlerController();
            $org = $controller->getData();

            $this->handleFollowing($output, $org);
        }

        $output->writeln('All done.');
    }


    /**
     * Fetch WeCamp members from cache or GitHub API,
     * transform and persist them
     *
     * @param OutputInterface $output
     * @return \Bacon\Service\Crawler\Bags\UserBag
     */
    private function handleUsers(OutputInterface $output, $org)
    {
        $controller = new \Bacon\Service\Crawler\Controllers\CrawlerController();
        $org = $controller->getData();

        $users = $org->getMembers()->all();

        $output->writeln('Found ' . count($users) . ' users.');
        if (! $users) {
            return $output->writeln('No users to import.');
        }

        $userRepository = new Neo4jUserRepository($this->em);
        $repositoryRepository = new Neo4jRepositoryRepository($this->em);

        // all data is delivered in one big blob
        foreach ($users as $user) {
            /** @var  IncomingUser $user */

            // don't add if user exists
            if (! $userRepository->findOneBy('username', $user->getLogin())) {
                $userNode = $this->transformDTOUser2GraphUser($user);

                // repositories a user owns
                $repos = $user->getRepos()->all();
                $repoCount = count($repos);
                if ($repoCount > 0) {
                    $output->writeln('Handling ' . $user->getLogin() . ' user owns ' . $repoCount . ' repos.');
                    foreach ($repos as $repo) {
                        $repoNode = $repositoryRepository->findOneBy('repositoryId', $repo->getId());
                        if (! $repoNode) {
                            // create a new one
                            $repoNode = $this->transformDTORepo2GraphRepo($repo);
                            $repositoryRepository->persist($repoNode);
                        }
                        $userNode->ownsRepository($repoNode);
                    }
                    $repositoryRepository->flush();
                }

                // repositories a user subscribed to
                $repos = $user->getSubscription()->all();
                $repoCount = count($repos);
                if ($repoCount > 0) {
                    $output->writeln('User subscibed to ' . $repoCount . ' repos.');
                    foreach ($repos as $repo) {
                        $repoNode = $repositoryRepository->findOneBy('repositoryId', $repo->getId());
                        if (! $repoNode) {
                            // create a new one
                            $repoNode = $this->transformDTORepo2GraphRepo($repo);
                            $repositoryRepository->persist($repoNode);
                        }
                        $userNode->subsribesToRepository($repoNode);
                    }
                    $repositoryRepository->flush();
                }

                // repositories a user starred
                $repos = $user->getStarred()->all();
                $repoCount = count($repos);
                if ($repoCount > 0) {
                    $output->writeln('User starred ' . $repoCount . ' repos.');
                    foreach ($repos as $repo) {
                        $repoNode = $repositoryRepository->findOneBy('repositoryId', $repo->getId());
                        if (! $repoNode) {
                            // create a new one
                            $repoNode = $this->transformDTORepo2GraphRepo($repo);
                            $repositoryRepository->persist($repoNode);
                        }
                        $userNode->starRepository($repoNode);
                    }
                    $repositoryRepository->flush();
                }

                $this->users[$userNode->getUsername()] = $userNode;
                $userRepository->persist($userNode);
                $userRepository->flush();
                $output->writeln('Flushing');

            }
        }
    }


    private function handleFollowers(OutputInterface $output, $org)
    {
        $output->writeln('Getting followers details.');
        $users = $org->getMembers()->all();

        $userRepository = new Neo4jUserRepository($this->em);

        foreach ($users as $dtoUser) {
            if ($dtoUser->getLogin() === 'skoop') {
                continue;
            }

            $user = $this->getUser($dtoUser->getLogin());

            if ($dtoUser->getFollowers()->count() > 0) {
                $output->writeln('Adding ' . $dtoUser->getFollowers()->count() . ' followers to ' . $dtoUser->getLogin());
                foreach ($dtoUser->getFollowers() as $dtoFollower) {
                    $follower = $this->getUser($dtoFollower->getLogin());
                    if (!$follower) {
                        $follower = $this->transformDTOUser2GraphUser($dtoFollower);
                        $this->users[$dtoFollower->getLogin()] = $follower;
                        $userRepository->persist($follower);
                    }

                    $user->setFollowedBy($follower);
                    $userRepository->persist($user);
                }
            }

        }
        
        $output->writeln('Flushing followers');
        $userRepository->flush();
    }

    private function handleFollowing(OutputInterface $output, $org)
    {
        $output->writeln('Getting following details.');
        $users = $org->getMembers()->all();
        $userRepository = new Neo4jUserRepository($this->em);

        foreach ($users as $dtoUser)
        {
            $user = $this->getUser($dtoUser->getLogin());

            if ($dtoUser->getFollowing()->count() > 0) {
                $output->writeln('Adding ' . $dtoUser->getFollowing()->count() . ' following users to ' . $dtoUser->getLogin());
                foreach ($dtoUser->getFollowing() as $dtoFollowing) {
                    $following = $this->getUser($dtoFollowing->getLogin());
                    if (!$following) {
                        $following = $this->transformDTOUser2GraphUser($dtoFollowing);
                        $this->users[$dtoFollowing->getLogin()] = $following;
                        $userRepository->persist($following);
                    }

                    $user->setFollowedBy($following);
                    $userRepository->persist($user);
                }
            }
        }

        $output->writeln('Flushing followings');
        $userRepository->flush();
    }

    private function getUser($username)
    {
        $userRepository = new Neo4jUserRepository($this->em);
        if (!isset($this->users[$username])) {
            $user = $userRepository->findOneBy('username', $username);
            if ($user)
            {
                $this->users[$username] = $user;

                return $user;
            }

            return false;
        }

        return $this->users[$username];
    }

    private function handleLanguages(OutputInterface $output, $org)
    {
        $output->writeln('Fetching language details.');

        $repositoryRepository = new Neo4jRepositoryRepository($this->em);

        $users = $org->getMembers()->all();

        // Extract and create Languages
        $this->extractRepoLanguagesFromDTOUsers($users);

        // all data is delivered in one big blob
        foreach ($users as $user) {
            // repositories a user owns
            $repos = $user->getRepos()->all();
            $repoCount = count($repos);
            if ($repoCount > 0) {
                foreach ($repos as $repo) {
                    if ($repo->getLang()->count() > 0) {
                        $repository = $repositoryRepository->findOneBy('repositoryId', $repo->getId());
                        $output->writeln('Adding ' . $repo->getLang()->count() . ' languages to ' . $repo->getFullName());
                        foreach ($repo->getLang() as $language) {
                            if ($this->languages[$language->getLanguage()]) {
                                $repository->useLanguage($this->languages[$language->getLanguage()]);
                            }
                        }
                        $repositoryRepository->persist($repository);
                    }

                }
                $output->writeln('Flushing repo\'s with languages');
                $repositoryRepository->flush();
            }
        }
    }


    private function handleLocation(OutputInterface $output, $org)
    {
        $output->writeln('Adding location details.');

        $userRepository = new Neo4jUserRepository($this->em);

        $users = $org->getMembers()->all();

        // Extract and create Languages
        $this->extractLocationsFromDTOUsers($users);

        // all data is delivered in one big blob
        foreach ($users as $dtoUser) {

            $this->storeFollows($dtoUser->getFollowers()->all());
            $this->storeFollows($dtoUser->getFollowing()->all());

            if ($dtoUser->getLocation() && $this->locations[$dtoUser->getLocation()] instanceof Location) {
                $output->writeln('Updating ' . $dtoUser->getLogin() . ' location to ' . $dtoUser->getLocation());
                $user = $this->getUser($dtoUser->getLogin());
                $user->setLocation($this->locations[$dtoUser->getLocation()]);
                $userRepository->persist($user);
            }
        }
        $output->writeln('Flushing user\'s with location');
        $userRepository->flush();
    }

    private function storeFollows($users)
    {
        foreach ($users as $user)
        {
            $following = $this->getUser($user->getLogin());
            if (!$following && !isset($this->follows[$user->getLogin()])) {
                $following = $this->transformDTOUser2GraphUser($user);
                $this->users[$user->getLogin()] = $following;
                $this->follows[$user->getLogin()] = $following;
            }
        }
    }

    private function handleFollowingUsers($output)
    {
        $output->writeln('Adding follower/ing users');
        foreach ($this->follows as $user)
        {
            $this->userRepo->persist($user);
        }
        $this->userRepo->flush();
        $output->writeln('Finished adding follower/ing users');
    }

    private function extractLocationsFromDTOUsers(array $users)
    {
        foreach ($users as $user) {
            $location = $user->getLocation();
            if (! isset($this->locations[(string)$location])) {
                $locationNode = new Location();
                $locationNode->setLocation((string)$location);
                $this->locationRepo->persist($locationNode);
                $this->locations[(string)$location] = $locationNode;
            }
        }
        $this->locationRepo->flush();
    }

    private function extractRepoLanguagesFromDTOUsers(array $users)
    {
        foreach ($users as $user) {
            foreach ($user->getRepos() as $repo) {
                foreach ($repo->getLang() as $DTOLanguage) {
                    if (! isset($this->languages[(string)$DTOLanguage->getLanguage()])) {
                        $languageNode = new Language();
                        $languageNode->setLanguageName((string)$DTOLanguage->getLanguage());
                        $this->languageRepo->persist($languageNode);
                        $this->languages[(string)$DTOLanguage->getLanguage()] = $languageNode;
                    }
                }
            }
        }
        $this->languageRepo->flush();
    }

    /**
     * Transform a DTO Repository into a Graph node
     *
     * @param \Bacon\Service\Crawler\Dto\Repository $repository
     * @return Repository
     */
    private function transformDTORepo2GraphRepo(\Bacon\Service\Crawler\Dto\Repository $repository)
    {
        $node = new Repository();
        $node->setRepositoryId($repository->getId());
        $node->setName((string)$repository->getName());
        $node->setDescription((string)$repository->getDescription());
        $node->setBlog((string)$repository->getBlog());
        $node->setFullName((string)$repository->getFullName());
        $node->setUrl((string)$repository->getUrl());

        return $node;
    }

    /**
     * Trnsforms a DTO User into a Graph Node
     *
     * @param IncomingUser $user
     * @return \Bacon\Entity\User
     */
    private function transformDTOUser2GraphUser(IncomingUser $user)
    {
        $node = new \Bacon\Entity\User();

        $node->setName((string) ($user->getName()?: $user->getLogin()));
        $node->setUsername((string)$user->getLogin());
        $node->setAvatar((string)$user->getAvatar());
        $node->setBio((string)$user->getBio());

        return $node;
    }
}