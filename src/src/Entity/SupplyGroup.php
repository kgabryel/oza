<?php

namespace App\Entity;

use App\Repository\SupplyGroupRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=SupplyGroupRepository::class)
 */
class SupplyGroup
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private int $id;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private string $name;
    /**
     * @ORM\ManyToMany(targetEntity=Supply::class, mappedBy="supplyGroups")
     * @ORM\OrderBy({"id" = "ASC"})
     */
    private Collection $supplies;
    /**
     * @ORM\ManyToOne(targetEntity=User::class, inversedBy="supplyGroups")
     * @ORM\JoinColumn(nullable=false)
     */
    private User $user;

    public function __construct()
    {
        $this->supplies = new ArrayCollection();
    }

    public function __toString()
    {
        return $this->name;
    }

    public function addSupply(Supply $supply): self
    {
        if (!$this->supplies->contains($supply)) {
            $this->supplies[] = $supply;
            $supply->addSupplyGroup($this);
        }

        return $this;
    }

    public function getId(): int
    {
        return $this->id;
    }

    public function getName(): string
    {
        return $this->name;
    }

    public function setName(string $name): self
    {
        $this->name = $name;

        return $this;
    }

    /**
     * @return Collection|Supply[]
     */
    public function getSupplies(): Collection
    {
        return $this->supplies;
    }

    public function getUser(): User
    {
        return $this->user;
    }

    public function setUser(User $user): self
    {
        $this->user = $user;

        return $this;
    }

    public function removeSupply(Supply $supply): self
    {
        if ($this->supplies->removeElement($supply)) {
            $supply->removeSupplyGroup($this);
        }

        return $this;
    }
}
