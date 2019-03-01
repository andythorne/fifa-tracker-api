<?php

namespace App\Import\Importer;

use App\Entity\Game\Career\Career;
use App\Entity\Game\Career\CareerTeam;
use App\Import\CsvProcessor;
use Doctrine\Common\Persistence\ObjectManager;

class TeamImporter implements ImporterInterface
{
    /** @var ObjectManager */
    private $objectManager;

    /** @var CsvProcessor */
    private $csvProcessor;

    public function __construct(ObjectManager $objectManager, CsvProcessor $csvProcessor)
    {
        $this->objectManager = $objectManager;
        $this->csvProcessor = $csvProcessor;
    }

    public function import(Career $career, string $path)
    {
        $file = $path.'teams.csv';

        $careerTeamRepository = $this->objectManager->getRepository(CareerTeam::class);

        foreach ($this->csvProcessor->readLine($file) as $row) {
            $teamId = (int) $row['teamid'];
            $currentRecord = $careerTeamRepository->findOneBy([
                'gameId' => $teamId,
            ]);

            if (!$currentRecord instanceof CareerTeam) {
                yield new CareerTeam(
                    $career,
                    $teamId,
                    $row['teamname'],
                    $row['foundationyear'],
                    sprintf('#%02x%02x%02x', (int) $row['teamcolor1r'], (int) $row['teamcolor1g'], (int) $row['teamcolor1b']),
                    sprintf('#%02x%02x%02x', (int) $row['teamcolor2r'], (int) $row['teamcolor2g'], (int) $row['teamcolor2b']),
                    sprintf('#%02x%02x%02x', (int) $row['teamcolor3r'], (int) $row['teamcolor3g'], (int) $row['teamcolor3b'])
                );
            }
        }
    }

    public function supports(Career $career)
    {
        return $career->getGameVersion()->getYear() <= 18;
    }
}
