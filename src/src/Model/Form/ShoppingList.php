<?php

namespace App\Model\Form;

use App\Entity\ShoppingList\Position;
use App\Entity\ShoppingList\ShoppingList as Entity;
use App\Services\Transformer\ShoppingListPositionTransformer as FormPositionTransformer;
use App\Transformer\ShoppingListPositionTransformer;

class ShoppingList
{
    private ?string $name;
    private ?string $note;
    private array $positions;

    public function __construct()
    {
        $this->name = null;
        $this->note = null;
        $this->positions = [];
    }

    public static function fromEntity(Entity $entity, ShoppingListPositionTransformer $transformer): self
    {
        $list = new self();
        $list->setName($entity->getName());
        $list->setNote($entity->getNote());
        $list->setPositions(
            array_map(
                static fn(Position $position) => $transformer->reverseTransform(
                    FormPositionTransformer::toModelData($position)
                ),
                $entity->getPositions()->toArray()
            )
        );

        return $list;
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
     * @return ShoppingListPosition[]
     */
    public function getPositions(): array
    {
        return $this->positions;
    }

    public function setPositions(array $positions): void
    {
        $this->positions = $positions;
    }
}
