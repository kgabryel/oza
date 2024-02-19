<?php

namespace App\DataFixtures;

use App\Entity\AlertType;
use App\Repository\AlertTypeRepository;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;

class AlertTypes extends Fixture
{
    private AlertTypeRepository $alertTypeRepository;

    public function __construct(AlertTypeRepository $alertTypeRepository)
    {
        $this->alertTypeRepository = $alertTypeRepository;
    }

    public function load(ObjectManager $manager): void
    {
        $types = [
            [
                'Brak',
                'error'
            ],
            [
                'Końcówka',
                'warning'
            ],
            [
                'Jeżeli jest promocja',
                'success'
            ],
            [
                'Informacja',
                'info'
            ],
            [
                'Przy okazji',
                ''
            ]
        ];
        foreach ($types as $type) {
            $entity = $this->create($type);
            if ($entity === null) {
                continue;
            }
            $manager->persist($entity);
        }
        $manager->flush();
    }

    private function create(array $data): ?AlertType
    {
        $alertType = $this->alertTypeRepository->findOneBy([
            'name' => $data[0]
        ]);
        if ($alertType !== null) {
            return null;
        }
        $type = new AlertType();
        $type->setName($data[0]);
        $type->setType($data[1]);

        return $type;
    }
}
