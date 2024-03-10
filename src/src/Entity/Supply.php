<?php

namespace App\Entity;

use App\Repository\SupplyRepository;
use App\Utils\UnitUtils;
use DateTime;
use DateTimeInterface;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Stringable;

/**
 * @ORM\Entity(repositoryClass=SupplyRepository::class)
 */
class Supply implements Stringable
{
    /**
     * @ORM\OneToMany(targetEntity=SupplyAlert::class, mappedBy="supply", cascade={"all"})
     * @ORM\OrderBy({"amount" = "ASC"})
     */
    private Collection $alerts;
    /**
     * @ORM\Column(type="text", nullable=true)
     */
    private ?string $description;
    /**
     * @ORM\ManyToOne(targetEntity=ProductsGroup::class, inversedBy="supplies")
     * @ORM\JoinColumn(nullable=false)
     */
    private ProductsGroup $group;
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private int $id;
    /**
     * @ORM\ManyToMany(targetEntity=SupplyGroup::class, inversedBy="supplies")
     * @ORM\OrderBy({"id" = "DESC"})
     */
    private Collection $supplyGroups;
    /**
     * @ORM\OneToMany(targetEntity=SupplyPart::class, mappedBy="supply", orphanRemoval=true)
     * @ORM\OrderBy({"dateOfConsumption" = "ASC"})
     */
    private Collection $supplyParts;
    /**
     * @ORM\Column(type="datetime")
     */
    private DateTimeInterface $updatedAt;

    public function __construct()
    {
        $this->alerts = new ArrayCollection();
        $this->updatedAt = new DateTime();
        $this->supplyGroups = new ArrayCollection();
        $this->supplyParts = new ArrayCollection();
    }

    public function __toString()
    {
        return $this->getGroup()->getName();
    }

    public function getGroup(): ProductsGroup
    {
        return $this->group;
    }

    public function setGroup(ProductsGroup $group): self
    {
        $this->group = $group;

        return $this;
    }

    public function addAlert(SupplyAlert $alert): self
    {
        if (!$this->alerts->contains($alert)) {
            $this->alerts[] = $alert;
            $alert->setSupply($this);
        }

        return $this;
    }

    public function addSupplyGroup(SupplyGroup $supplyGroup): self
    {
        if (!$this->supplyGroups->contains($supplyGroup)) {
            $this->supplyGroups[] = $supplyGroup;
        }

        return $this;
    }

    public function addSupplyPart(SupplyPart $supplyPart): self
    {
        if (!$this->supplyParts->contains($supplyPart)) {
            $this->supplyParts[] = $supplyPart;
            $supplyPart->setSupply($this);
        }

        return $this;
    }

    public function clearGroups(): self
    {
        /** @var SupplyGroup $group */
        foreach ($this->supplyGroups as $group) {
            $this->supplyGroups->removeElement($group);
            $group->removeSupply($this);
        }

        return $this;
    }

    /**
     * @return Collection|Alert[]
     */
    public function getAlerts(): Collection
    {
        return $this->alerts;
    }

    public function getAmount(): float
    {
        $amount = 0;
        $unit = $this->getGroup()->getBaseUnit();
        foreach ($this->getSupplyParts() as $part) {
            $amount += UnitUtils::parseAmount(
                $part->getAmount() * $part->getPart(),
                $part->getUnit(),
                $unit
            );
        }

        return round($amount, 2);
    }

    /**
     * @return Collection|SupplyPart[]
     */
    public function getSupplyParts(): Collection
    {
        return $this->supplyParts;
    }

    public function getDescription(): ?string
    {
        return $this->description;
    }

    public function setDescription(?string $description): self
    {
        $this->description = $description;

        return $this;
    }

    public function getId(): int
    {
        return $this->id;
    }

    /**
     * @return Collection|SupplyGroup[]
     */
    public function getSupplyGroups(): Collection
    {
        return $this->supplyGroups;
    }

    public function getUpdatedAt(): ?DateTimeInterface
    {
        return $this->updatedAt;
    }

    public function setUpdatedAt(DateTimeInterface $updatedAt): self
    {
        $this->updatedAt = $updatedAt;

        return $this;
    }

    public function removeAlert(SupplyAlert $alert): self
    {
        $this->alerts->removeElement($alert);

        return $this;
    }

    public function removeSupplyGroup(SupplyGroup $supplyGroup): self
    {
        $this->supplyGroups->removeElement($supplyGroup);

        return $this;
    }

    public function removeSupplyPart(SupplyPart $supplyPart): self
    {
        $this->supplyParts->removeElement($supplyPart);

        return $this;
    }
}
