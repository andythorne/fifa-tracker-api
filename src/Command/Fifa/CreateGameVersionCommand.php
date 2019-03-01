<?php

namespace App\Command\Fifa;

use App\Entity\Game\GameVersion;
use Doctrine\Common\Persistence\ObjectManager;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;

class CreateGameVersionCommand extends Command
{
    protected static $defaultName = 'fifa:create:game-version';

    /** @var ObjectManager */
    private $objectManager;

    public function __construct(ObjectManager $objectManager)
    {
        parent::__construct(null);

        $this->objectManager = $objectManager;
    }

    protected function configure()
    {
        $this
            ->setDescription('Create a new game version')
            ->addArgument('year', InputArgument::REQUIRED, 'Game version year/code')
            ->addArgument('name', InputArgument::REQUIRED, 'Friendly name of game version')
        ;
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $io = new SymfonyStyle($input, $output);

        $year = $input->getArgument('year');
        $name = $input->getArgument('name');

        $version = new GameVersion($year);
        $version->setName($name);

        $this->objectManager->persist($version);
        $this->objectManager->flush();

        $io->success('You have a new command! Now make it your own! Pass --help to see your options.');
    }
}
