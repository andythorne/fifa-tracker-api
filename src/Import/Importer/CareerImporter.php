<?php

namespace App\Import\Importer;

use App\Entity\Game\Career\Career;
use App\Entity\Game\Nation;
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

    public function supports(Career $career)
    {
        return $career->getGameVersion()->getYear() <= 18;
    }

    public function import(Career $career, string $path)
    {
        $file = $path.'career_users.csv';

        $nationalityRepository = $this->objectManager->getRepository(Nation::class);

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
}
