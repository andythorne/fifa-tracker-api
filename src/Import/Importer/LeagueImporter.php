<?php

namespace App\Import\Importer;

use App\Entity\Game\Career\Career;
use App\Entity\Game\Core\League;
use App\Entity\Game\Core\Nation;
use App\Entity\Game\Import\Import;
use App\Import\CsvProcessor;
use Doctrine\Common\Persistence\ObjectManager;

class LeagueImporter implements ImporterInterface
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
        $file = $path.'leagues.csv';

        $leagueRepository = $this->objectManager->getRepository(League::class);
        $nationRepository = $this->objectManager->getRepository(Nation::class);

        foreach ($this->csvProcessor->readLine($file) as $row) {
            $id = (int) $row['leagueid'];

            /** @var League $currentRecord */
            $currentRecord = $leagueRepository->findOneBy([
                'gameId' => $id,
                'gameVersion' => $import->getCareer()->getGameVersion(),
            ]);

            if (!$currentRecord instanceof League) {
                $nation = $nationRepository->findOneBy([
                    'gameId' => (int) $row['countryid'],
                    'gameVersion' => $import->getCareer()->getGameVersion(),
                ]);

                yield new League(
                    $import->getCareer()->getGameVersion(),
                    $id,
                    $row['leaguename'],
                    $row['level'],
                    $nation
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
            Nation::class,
            League::class,
        ];
    }
}
