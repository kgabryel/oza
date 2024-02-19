<?php

namespace App\Model\Form;

use App\Entity\ApiKey;

class ApiKeyDescription
{
    private ?string $description;

    public function __construct()
    {
        $this->description = null;
    }

    public static function fromEntity(ApiKey $entity): self
    {
        $description = new self();
        $description->setDescription($entity->getDescription());

        return $description;
    }

    public function getDescription(): ?string
    {
        return $this->description;
    }

    public function setDescription(?string $description): void
    {
        $this->description = $description;
    }
}
