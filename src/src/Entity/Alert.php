<?php

namespace App\Entity;

use App\Repository\AlertRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Stringable;

/**
 * @ORM\Entity(repositoryClass=AlertRepository::class)
 */
class Alert implements Stringable
{
    /**
     * @ORM\Column(type="text")
     */
    private string $description;
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private int $id;
    /**
     * @ORM\Column(type="boolean")
     */
    private bool $isActive;
    /**
     * @ORM\OneToMany(targetEntity=SupplyAlert::class, mappedBy="alert", cascade={"all"})
     * @ORM\OrderBy({"id" = "DESC"})
     */
    private Collection $supplyAlerts;
    /**
     * @ORM\ManyToOne(targetEntity=AlertType::class, inversedBy="alerts")
     * @ORM\JoinColumn(nullable=false)
     */
    private AlertType $type;
    /**
     * @ORM\ManyToOne(targetEntity=User::class, inversedBy="alerts")
     * @ORM\JoinColumn(nullable=false)
     */
    private User $user;

    public function __construct()
    {
        $this->supplyAlerts = new ArrayCollection();
    }

    public function __toString()
    {
        return sprintf('%s [%s]', $this->description, $this->type->getName());
    }

    public function activate(): self
    {
        $this->isActive = true;

        return $this;
    }

    public function addSupplyAlert(SupplyAlert $supplyAlert): self
    {
        if (!$this->supplyAlerts->contains($supplyAlert)) {
            $this->supplyAlerts[] = $supplyAlert;
            $supplyAlert->setAlert($this);
        }

        return $this;
    }

    public function deactivate(): self
    {
        $this->isActive = false;

        return $this;
    }

    public function getDescription(): string
    {
        return $this->description;
    }

    public function setDescription(string $description): self
    {
        $this->description = $description;

        return $this;
    }

    public function getId(): int
    {
        return $this->id;
    }

    /**
     * @return Collection|SupplyAlert[]
     */
    public function getSupplyAlerts(): Collection
    {
        return $this->supplyAlerts;
    }

    public function getType(): AlertType
    {
        return $this->type;
    }

    public function setType(AlertType $type): self
    {
        $this->type = $type;

        return $this;
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

    public function isActive(): bool
    {
        return $this->isActive;
    }

    public function removeSupplyAlert(SupplyAlert $supplyAlert): self
    {
        $this->supplyAlerts->removeElement($supplyAlert);

        return $this;
    }
}
