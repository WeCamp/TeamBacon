<?php
declare(strict_types = 1);

namespace Bacon\Console;


use Bacon\Service\Crawler\Dto\User;
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
        $userBag = $org->getMembers();

        $users = $org->getMembers()->all();
        $graphUsers = [];

        foreach ($users as $user) {
            $graphUsers[] = $this->transformUser($user);
        }
    }

    /**
     * Trnsforms a DTO User into a Graph Node
     *
     * @param User $user
     */
    private function transformUser(User $user)
    {
        $node = new \Bacon\Entity\User();
        // User->login -> User->username

        $node->setName($user->getName());
        var_dump($node);
    }
}