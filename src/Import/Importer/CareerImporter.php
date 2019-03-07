<?php

namespace App\Import\Importer;

use App\Entity\Game\Career\Career;
use App\Entity\Game\Import\Import;
use App\Import\CsvProcessor;
use App\Repository\Game\Core\NationRepository;
use Doctrine\Common\Persistence\ObjectManager;

class CareerImporter extends AbstractCsvImporter
{
    protected static $csvFile = 'career_users.csv';

    /** @var ObjectManager */
    private $objectManager;

    /** @var NationRepository */
    private $nationRepository;

    public function __construct(ObjectManager $objectManager, NationRepository $nationRepository, CsvProcessor $csvProcessor)
    {
        parent::__construct($csvProcessor);

        $this->nationRepository = $nationRepository;
        $this->objectManager = $objectManager;
    }

    public function supports(Career $career): bool
    {
        return $career->getGameVersion()->getYear() <= 18;
    }

    protected function processRow(Import $import, array $row): ?object
    {
        $nationality = $this->nationRepository->findOneByGame(
            $import->getCareer()->getGameVersion(),
            $row['nationalityid']
        );
        $this->objectManager->persist($nationality);

        $career = $import->getCareer();
        $career->updateFromSave(
            (int) $row['userid'],
            $row['firstname'],
            $row['surname'],
            $nationality
        );

        return $career;
    }
}
