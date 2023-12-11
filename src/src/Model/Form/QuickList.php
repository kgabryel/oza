<?php

namespace App\Model\Form;

use App\Entity\QuickList\Position;
use App\Entity\QuickList\QuickList as Entity;

class QuickList
{
    private ?string $name;
    private ?string $note;
    /**
     * @var QuickListPosition[]
     */
    private array $positions;

    public function __construct()
    {
        $this->name = '';
        $this->note = '';
        $this->positions = [];
    }

    public static function fromEntity(Entity $entity): self
    {
        $model = new self();
        $model->setName($entity->getName());
        $model->setNote($entity->getNote());
        $model->setPositions(
            array_map(
                static fn(Position $position): QuickListPosition => QuickListPosition::fromEntity($position),
                $entity->getPositions()->toArray()
            )
        );

        return $model;
    }

    public function getName(): ?string
    {
        return $this->name;
    }

    public function setName(?string $name): void
    {
        $this->name = $name;
    }

    public function getNote(): ?string
    {
        return $this->note;
    }

    public function setNote(?string $note): void
    {
        $this->note = $note;
    }

    /**
     * @return QuickListPosition[]
     */
    public function getPositions(): array
    {
        return array_filter($this->positions, static fn($position): bool => $position->getPosition() !== null);
    }

    /**
     * @param QuickListPosition[] $positions
     */
    public function setPositions(array $positions): void
    {
        $this->positions = array_filter($positions, static fn($position): bool => $position->getPosition() !== null);
    }
}
