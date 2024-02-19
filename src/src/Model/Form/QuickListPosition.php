<?php

namespace App\Model\Form;

use App\Entity\QuickList\Position;

class QuickListPosition
{
    private bool $checked;
    private ?string $position;

    public function __construct()
    {
        $this->position = null;
        $this->checked = false;
    }

    public static function fromEntity(Position $entity): self
    {
        $position = new QuickListPosition();
        $position->setChecked($entity->isChecked());
        $position->setPosition($entity->getContent());

        return $position;
    }

    public function isChecked(): bool
    {
        return $this->checked;
    }

    public function setChecked(bool $checked): void
    {
        $this->checked = $checked;
    }

    public function getPosition(): ?string
    {
        return $this->position;
    }

    public function setPosition(?string $position): void
    {
        $this->position = $position;
    }
}
