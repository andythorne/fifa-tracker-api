<?php

namespace App\Import\Importer;

use App\Entity\Game\Career\Career;
use App\Entity\Game\Core\Team;
use App\Entity\Game\Import\Import;
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

    public function import(Import $import, string $path)
    {
        $file = $path.'teams.csv';

        $careerTeamRepository = $this->objectManager->getRepository(Team::class);

        foreach ($this->csvProcessor->readLine($file) as $row) {
            $teamId = (int) $row['teamid'];

            /** @var Team $currentRecord */
            $currentRecord = $careerTeamRepository->findOneByGameId($teamId);

            if (!$currentRecord instanceof Team) {
                yield new Team(
                    $import->getCareer()->getGameVersion(),
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

    public function supports(Career $career): bool
    {
        return $career->getGameVersion()->getYear() <= 18;
    }

    public function cleanup(): array
    {
        return [
            Team::class,
        ];
    }
}
