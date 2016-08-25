<?php
declare(strict_types = 1);

namespace Bacon\Console;


use Bacon\Config\Config;
use Bacon\Entity\Repository;
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
        $object = $input->getArgument('object');

        // outputs multiple lines to the console (adding "\n" at the end of each line)
        $output->writeln([
            'Importing ' . $object,
            '============',
        ]);


        $this->em = EntityManager::create(Config::get()['neo4jHost']);
        if ('user' === $object) {
            $this->handleUsers($output);
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
    private function handleUsers(OutputInterface $output)
    {
        $output->writeln('Fetching user details.');

        $controller = new \Bacon\Service\Crawler\Controllers\CrawlerController();
        $org = $controller->getData();

        $users = $org->getMembers()->all();
        //$users = $org->getMembers()->getAFew(4);

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


                // a users repositories
                $repos = $user->getRepos()->all();
                if (count($repos) > 0) {
                    $output->writeln('User has repos.');
                    foreach ($repos as $repo) {
                        $repoNode = $this->transformDTORepo2GraphRepo($repo);

                        // don't add if it exists
                        if (! $repositoryRepository->findOneBy('repositoryId', $repo->getId())) {
                            $userNode->contributeToRepository($repoNode);
                            $repositoryRepository->persist($repoNode);
                        }
                    }
                    $repositoryRepository->flush();
                }


                $userRepository->persist($userNode);

            }

            // todo get Locations from users and repos
            //$user->contributeToRepository($repository1);
            // transform  user repositories


            // create relation between users and repos
        }

        $userRepository->flush();

    }


    private function extractLocationsFromDTOUser(\Bacon\Service\Crawler\Dto\User $user)
    {

    }

    private function extractLocationsFromDTORepo(\Bacon\Service\Crawler\Dto\Repository $repository)
    {

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

        $node->setName((string)$user->getName());
        $node->setUsername((string)$user->getLogin());
        $node->setAvatar((string)$user->getUrl());
        $node->setBio((string)$user->getBio());

        return $node;
    }
}