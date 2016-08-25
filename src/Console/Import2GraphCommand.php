<?php
declare(strict_types = 1);

namespace Bacon\Console;


use Bacon\Config\Config;
use Bacon\Repository\Neo4jRepositoryRepository;
use Bacon\Repository\Neo4jUserRepository;
use Bacon\Service\Crawler\Bags\RepositoryBag;
use Bacon\Service\Crawler\Dto\User;
use GraphAware\Neo4j\OGM\EntityManager;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputOption;

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

        $users = $org->getMembers()->all();

        if (!$users) {
            return $output->writeln('No users to import.');
        }
        $userRepository = new Neo4jUserRepository($this->em);
        $repositoryRepository = new Neo4jRepositoryRepository($this->em);

        foreach ($users as $user) {
            /** @var  User $user */
            $userNode = $this->transformUser($user);

            $usersRepos = $this->transformUsersRepos($user->getRepos());
            // persist the user


            $userRepository->persist($userNode);

            //$user->contributeToRepository($repository1);
            // transform  user repositories


            // persist repos

            // create relation between users and repos
        }

        $userRepository->flush();

    }

    private function transformUsersRepos(RepositoryBag $repoBag)
    {

    }

    /**
     * Trnsforms a DTO User into a Graph Node
     *
     * @param User $user
     * @return \Bacon\Entity\User
     */
    private function transformUser(User $user)
    {
        $node = new \Bacon\Entity\User();

        $node->setName((string) $user->getName());
        $node->setUsername((string) $user->getLogin());
        $node->setAvatar((string) $user->getUrl());
        $node->setBio((string) $user->getBio());

        return $node;
    }
}