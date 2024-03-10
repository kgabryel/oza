<?php

namespace App\Services\Factory\QuickList;

use App\Entity\QuickList\ClipboardPosition;
use App\Entity\QuickList\Position;
use App\Services\Factory\EntityFactory;

class ClipboardPositionFactory extends EntityFactory
{
    public function create(Position $position): void
    {
        $clipboardPosition = new ClipboardPosition();
        $clipboardPosition->setUser($this->user)
            ->setContent($position->getContent());
        $this->entityManager->persist($clipboardPosition);
    }
}
