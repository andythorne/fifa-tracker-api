<?php

namespace App\Command\Fifa;

use App\Entity\Game\Import\Career;
use App\Entity\Game\GameVersion;
use App\Entity\User;
use App\Import\SaveGameImportProcessor;
use Doctrine\Common\Persistence\ObjectManager;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;

class ImportSavedGameCommand extends Command
{
    protected static $defaultName = 'fifa:import:saved-game';

    /** @var SaveGameImportProcessor */
    private $importProcessor;
    /** @var ObjectManager */
    private $objectManager;

    public function __construct(ObjectManager $objectManager, SaveGameImportProcessor $importProcessor)
    {
        parent::__construct(null);
        $this->importProcessor = $importProcessor;
        $this->objectManager = $objectManager;
    }

    protected function configure()
    {
        $this
            ->setDescription('Add a short description for your command')
            ->addArgument('path', InputArgument::REQUIRED, 'Path to saved game data')
            ->addOption('career', null, InputArgument::OPTIONAL, 'Game year version')
            ->addOption('user', null, InputOption::VALUE_OPTIONAL, 'User email to add to')
            ->addOption('game-year', null, InputOption::VALUE_OPTIONAL, 'Game year version')
            ->addOption('name', null, InputOption::VALUE_OPTIONAL, 'Name of the new career')
        ;
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $io = new SymfonyStyle($input, $output);

        $path = $input->getArgument('path');

        $careerRepository = $this->objectManager->getRepository(Career::class);

        if ($careerInput = $input->getOption('career')) {
            $career = $careerRepository->find($careerInput);
        } else {
            if (!$input->hasParameterOption(['game-year', 'user', 'name'])) {
                throw new \RuntimeException('Need game year, email and name to create a new career');
            }
            /** @var GameVersion $gameVersion */
            $gameVersion = $this->objectManager->getRepository(GameVersion::class)->findOneBy([
                'year' => $input->getOption('game-year'),
            ]);

            /** @var User $user */
            $user = $this->objectManager->getRepository(User::class)->findOneBy([
                'email' => $input->getOption('user'),
            ]);

            $career = new Career($gameVersion, $user);
            $career->setName(
                $input->getOption('name')
            );
        }

        // check if we already have a career in progress

        $io->writeln('Importing Game!');
        $this->importProcessor->importSavedGameData($career, $path);

        $io->success('Imported Game!');
    }
}
