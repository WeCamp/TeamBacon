<?php
declare(strict_types=1);

namespace Bacon\Console;

use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use GraphAware\Neo4j\Client\ClientBuilder;
use Bacon\Config\Config;

final class ClearStorageCommand extends Command
{
    protected function configure()
    {
        $this->setName('bacon:clear-storage')
            ->setDescription('Clears storage')
            ->setHelp('This clears the storage');
    }

    /**
     * @param InputInterface $input
     * @param OutputInterface $output
     * @return void
     */
    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $output->writeln([
            'Clear storage',
            '=============',
            '',
        ]);

        $client = ClientBuilder::create()
            ->addConnection('default', Config::get()['neo4jHost'])
            ->build();

        $client->run('MATCH (n) OPTIONAL MATCH (n)-[r]-() DELETE n,r');

        $output->writeln([
            'Storage cleared'
        ]);
    }
}
