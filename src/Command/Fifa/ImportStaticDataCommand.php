<?php

namespace App\Command\Fifa;

use App\Entity\Game\GameVersion;
use App\Entity\Game\Nation;
use App\Entity\Game\PlayerName;
use App\Import\CsvProcessor;
use Doctrine\Common\Persistence\ObjectManager;
use Doctrine\ORM\EntityManager;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;

class ImportStaticDataCommand extends Command
{
    protected static $defaultName = 'fifa:import:static-data';

    /** @var EntityManager */
    private $objectManager;

    /** @var CsvProcessor */
    private $csvProcessor;

    public function __construct(ObjectManager $objectManager, CsvProcessor $csvProcessor)
    {
        parent::__construct(null);

        $this->objectManager = $objectManager;
        $this->csvProcessor = $csvProcessor;
    }

    protected function configure()
    {
        $this
            ->setDescription('Import static data from csvs to the database')
            ->addArgument('path', InputArgument::REQUIRED, 'Path to the static files')
        ;
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $gameVersionRepo = $this->objectManager->getRepository(GameVersion::class);

        $path = rtrim($input->getArgument('path'), '/').'/';

        if (!is_dir($path)) {
            throw new \RuntimeException('path must be an accessible directory');
        }

        $io = new SymfonyStyle($input, $output);

        /** @var GameVersion $gameVersion */
        foreach ($gameVersionRepo->findAll() as $i => $gameVersion) {
            // Nations
            foreach ($this->readData($path.$gameVersion->getYear().'_nations.csv') as $data) {
                $nation = new Nation(
                    $gameVersion,
                    (int) $data[6],
                    $data[1],
                    strlen($data[0]) === 2 ? $data[0] : null,
                    $data[3] === '1'
                );

                $this->objectManager->persist($nation);

                if ($i % 100 === 0) {
                    $this->objectManager->flush();
                    $this->objectManager->clear(Nation::class);
                }
            }

            $this->objectManager->flush();
            $io->success('Imported nations for '.$gameVersion->getName());

            // Player Names
            foreach ($this->csvProcessor->readLine($path.$gameVersion->getYear().'_playernames.csv') as $i => $data) {
                $playerName = new PlayerName(
                    $gameVersion,
                    (int) $data[1],
                    $data[0]
                );

                $this->objectManager->persist($playerName);

                if ($i % 100 === 0) {
                    $this->objectManager->flush();
                    $this->objectManager->clear(PlayerName::class);
                }
            }

            $this->objectManager->flush();
            $io->success('Imported playernames for '.$gameVersion->getName());
        }
    }
}
