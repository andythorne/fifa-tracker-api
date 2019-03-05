<?php

namespace App\Import\Importer;

use App\Entity\Game\Career\Career;
use App\Entity\Game\Core\Nation;
use App\Entity\Game\Import\Import;
use App\Import\CsvProcessor;
use Doctrine\Common\Persistence\ObjectManager;

class CareerImporter implements ImporterInterface
{
    public const ROW_MAP = [
    ];

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
        $file = $path.'career_users.csv';

        $nationalityRepository = $this->objectManager->getRepository(Nation::class);
        $career = $import->getCareer();

        foreach ($this->csvProcessor->readLine($file) as $row) {
            $career->updateFromSave(
                (int) $row['userid'],
                $row['firstname'],
                $row['surname'],
                $nationalityRepository->findOneByGameId($row['nationalityid'])
            );

            yield $career;
        }
    }

    public function supports(Career $career): bool
    {
        return $career->getGameVersion()->getYear() <= 18;
    }

    public function cleanup(): array
    {
        return [];
    }
}
