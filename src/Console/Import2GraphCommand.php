<?php
declare(strict_types = 1);

namespace Bacon\Console;


use Bacon\Config\Config;
use Bacon\Entity\Repository;
use Bacon\Repository\Neo4jRepositoryRepository;
use Bacon\Repository\Neo4jUserRepository;
use Bacon\Service\Crawler\Bags\RepositoryBag;
use Bacon\Service\Crawler\Dto\User;
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
     * Fetch WeCamp members from cache or GitHub API
     *
     * @param OutputInterface $output
     * @return \Bacon\Service\Crawler\Bags\UserBag
     */
    private function handleUsers(OutputInterface $output)
    {
        $output->writeln('Fetching user details github api.');

        $controller = new \Bacon\Service\Crawler\Controllers\CrawlerController();
        $org = $controller->getData();

        //$users = $org->getMembers()->all();
        $users = $org->getMembers()->getAFew(4);

        if (! $users) {
            return $output->writeln('No users to import.');
        }
        $userRepository = new Neo4jUserRepository($this->em);
        $repositoryRepository = new Neo4jRepositoryRepository($this->em);

        $existingUserNodes = $userRepository->findAll();

        // keep only the ones that are not in the graph already
        $usersToPersist = array_filter($users, function ($user) use ($existingUserNodes) {
            $userNode = $this->transformDTOUser2GraphUser($user);
            foreach ($existingUserNodes as $existingUserNode) {
                return ! $existingUserNode->isEqualTo($userNode);
            }
        });


        // keep using the DTO User object because it contains more data
        foreach ($usersToPersist as $user) {

            /** @var  User $user */
            $userNode = $this->transformDTOUser2GraphUser($user);

            $this->handleUsersRepos($user->getRepos());

            $userRepository->persist($userNode);


            // todo get Locations from users and repos
            //$user->contributeToRepository($repository1);
            // transform  user repositories


            // create relation between users and repos
        }

        $userRepository->flush();

    }

    private function handleUsersRepos(RepositoryBag $repoBag)
    {
        $repos = $repoBag->all();
        foreach ($repos as $repo) {

            // todo implement existing check
            $repoNode = $this->transformDTORepo2GraphRepo($repo);




        }

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
        $node->setName($repository->getName());
        $node->setDescription($repository->getDescription());
        $node->setBlog($repository->getBlog());
        $node->setFullName($repository->getFullName());
        $node->setUrl($repository->getUrl());

        return $node;
    }

    /**
     * Trnsforms a DTO User into a Graph Node
     *
     * @param User $user
     * @return \Bacon\Entity\User
     */
    private function transformDTOUser2GraphUser(User $user)
    {
        $node = new \Bacon\Entity\User();

        $node->setName((string)$user->getName());
        $node->setUsername((string)$user->getLogin());
        $node->setAvatar((string)$user->getUrl());
        $node->setBio((string)$user->getBio());

        return $node;
    }
}