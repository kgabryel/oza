<?php

namespace App\Services\Factory\QuickList;

use App\Entity\QuickList\Position;
use App\Entity\QuickList\QuickList;
use App\Model\Form\QuickListPosition;
use App\Model\Form\QuickListPosition as Model;
use Doctrine\ORM\EntityManagerInterface;

class PositionFactory
{
    private EntityManagerInterface $entityManager;
    private QuickList $list;

    public function __construct(EntityManagerInterface $entityManager)
    {
        $this->entityManager = $entityManager;
    }

    public function create(QuickListPosition $positionData): void
    {
        $position = new Position();
        $position->setContent($positionData->getPosition())
            ->setList($this->list);
        $this->entityManager->persist($position);
    }

    public function setList(QuickList $list): void
    {
        $this->list = $list;
    }

    public function createFromModel(Model $position): Position
    {
        $positionEntity = new Position();
        $positionEntity->setContent($position->getPosition());
        if ($position->isChecked()) {
            $positionEntity->check();
        }
        $this->entityManager->persist($positionEntity);

        return $positionEntity;
    }
}
