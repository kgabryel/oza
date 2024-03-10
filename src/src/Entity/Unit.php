<?php

namespace App\Entity;

use App\Entity\ShoppingList\ClipboardPosition;
use App\Entity\ShoppingList\Position;
use App\Repository\UnitRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Stringable;

/**
 * @ORM\Entity(repositoryClass=UnitRepository::class)
 * @ORM\Table(name="units")
 */
class Unit implements Stringable
{
    /**
     * @ORM\OneToMany(targetEntity=ClipboardPosition::class, mappedBy="unit", orphanRemoval=true)
     * @ORM\OrderBy({"id" = "DESC"})
     */
    private Collection $clipboardPositions;
    /**
     * @ORM\Column(type="float", nullable=true)
     */
    private ?float $converter;
    /**
     * @ORM\OneToMany(targetEntity=ProductsGroup::class, mappedBy="unit", cascade={"all"})
     * @ORM\OrderBy({"name" = "ASC"})
     */
    private Collection $groups;
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private int $id;
    /**
     * @ORM\OneToMany(targetEntity=Position::class, mappedBy="unit", cascade={"all"})
     * @ORM\OrderBy({"id" = "DESC"})
     */
    private Collection $listPositions;
    /**
     * @ORM\ManyToOne(targetEntity=Unit::class, inversedBy="units", fetch="EAGER")
     */
    private ?Unit $main;
    /**
     * @ORM\Column(type="string", length=30)
     */
    private string $name;
    /**
     * @ORM\OneToMany(targetEntity=Product::class, mappedBy="unit", cascade={"all"})
     * @ORM\OrderBy({"name" = "ASC"})
     */
    private Collection $products;
    /**
     * @ORM\OneToMany(targetEntity=Shopping::class, mappedBy="unit", cascade={"all"})
     * @ORM\OrderBy({"date" = "DESC"})
     */
    private Collection $shopping;
    /**
     * @ORM\Column(type="string", length=10)
     */
    private string $shortcut;
    /**
     * @ORM\OneToMany(targetEntity=SupplyAlert::class, mappedBy="unit", orphanRemoval=true)
     * @ORM\OrderBy({"id" = "DESC"})
     */
    private Collection $supplyAlerts;
    /**
     * @ORM\OneToMany(targetEntity=SupplyPart::class, mappedBy="unit", orphanRemoval=true)
     * @ORM\OrderBy({"id" = "DESC"})
     */
    private Collection $supplyParts;
    /**
     * @ORM\OneToMany(targetEntity=Unit::class, mappedBy="main", cascade={"all"})
     * @ORM\OrderBy({"name" = "ASC"})
     */
    private Collection $units;
    /**
     * @ORM\ManyToOne(targetEntity=User::class, inversedBy="units")
     * @ORM\JoinColumn(nullable=false)
     */
    private User $user;

    public function __construct()
    {
        $this->units = new ArrayCollection();
        $this->products = new ArrayCollection();
        $this->groups = new ArrayCollection();
        $this->listPositions = new ArrayCollection();
        $this->shopping = new ArrayCollection();
        $this->clipboardPositions = new ArrayCollection();
        $this->supplyAlerts = new ArrayCollection();
        $this->supplyParts = new ArrayCollection();
    }

    public function __toString()
    {
        return "$this->name ($this->shortcut)";
    }

    public function addClipboardPosition(ClipboardPosition $clipboardPosition): self
    {
        if (!$this->clipboardPositions->contains($clipboardPosition)) {
            $this->clipboardPositions[] = $clipboardPosition;
            $clipboardPosition->setUnit($this);
        }

        return $this;
    }

    public function addGroup(ProductsGroup $group): self
    {
        if (!$this->groups->contains($group)) {
            $this->groups[] = $group;
            $group->setUnit($this);
        }

        return $this;
    }

    public function addListPosition(Position $listPosition): self
    {
        if (!$this->listPositions->contains($listPosition)) {
            $this->listPositions[] = $listPosition;
            $listPosition->setUnit($this);
        }

        return $this;
    }

    public function addProduct(Product $product): self
    {
        if (!$this->products->contains($product)) {
            $this->products[] = $product;
            $product->setUnit($this);
        }

        return $this;
    }

    public function addShopping(Shopping $shopping): self
    {
        if (!$this->shopping->contains($shopping)) {
            $this->shopping[] = $shopping;
            $shopping->setUnit($this);
        }

        return $this;
    }

    public function addSupplyAlert(SupplyAlert $supplyAlert): self
    {
        if (!$this->supplyAlerts->contains($supplyAlert)) {
            $this->supplyAlerts[] = $supplyAlert;
            $supplyAlert->setUnit($this);
        }

        return $this;
    }

    public function addSupplyPart(SupplyPart $supplyPart): self
    {
        if (!$this->supplyParts->contains($supplyPart)) {
            $this->supplyParts[] = $supplyPart;
            $supplyPart->setUnit($this);
        }

        return $this;
    }

    public function addUnit(self $unit): self
    {
        if (!$this->units->contains($unit)) {
            $this->units[] = $unit;
            $unit->setMain($this);
        }

        return $this;
    }

    /**
     * @return Collection|ClipboardPosition[]
     */
    public function getClipboardPositions(): Collection
    {
        return $this->clipboardPositions;
    }

    public function getConverter(): ?float
    {
        return $this->converter;
    }

    public function setConverter(?float $converter): self
    {
        $this->converter = $converter;

        return $this;
    }

    /**
     * @return Collection|ProductsGroup[]
     */
    public function getGroups(): Collection
    {
        return $this->groups;
    }

    public function getId(): int
    {
        return $this->id;
    }

    /**
     * @return Collection|Position[]
     */
    public function getListPositions(): Collection
    {
        return $this->listPositions;
    }

    public function getName(): string
    {
        return $this->name;
    }

    public function setName(string $name): self
    {
        $this->name = ucfirst($name);

        return $this;
    }

    /**
     * @return Collection|Product[]
     */
    public function getProducts(): Collection
    {
        return $this->products;
    }

    /**
     * @return Collection|Shopping[]
     */
    public function getShopping(): Collection
    {
        return $this->shopping;
    }

    public function getShortcut(): string
    {
        return $this->shortcut;
    }

    public function setShortcut(string $shortcut): self
    {
        $this->shortcut = $shortcut;

        return $this;
    }

    /**
     * @return Collection|SupplyAlert[]
     */
    public function getSupplyAlerts(): Collection
    {
        return $this->supplyAlerts;
    }

    /**
     * @return Collection|SupplyPart[]
     */
    public function getSupplyParts(): Collection
    {
        return $this->supplyParts;
    }

    /**
     * @return Collection|self[]
     */
    public function getUnits(): Collection
    {
        return $this->units;
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

    public function removeClipboardPosition(ClipboardPosition $clipboardPosition): self
    {
        $this->clipboardPositions->removeElement($clipboardPosition);

        return $this;
    }

    public function removeGroup(ProductsGroup $group): self
    {
        $this->groups->removeElement($group);

        return $this;
    }

    public function removeListPosition(Position $listPosition): self
    {
        $this->listPositions->removeElement($listPosition);

        return $this;
    }

    public function removeProduct(Product $product): self
    {
        $this->products->removeElement($product);

        return $this;
    }

    public function removeShopping(Shopping $shopping): self
    {
        $this->shopping->removeElement($shopping);

        return $this;
    }

    public function removeSupplyAlert(SupplyAlert $supplyAlert): self
    {
        $this->supplyAlerts->removeElement($supplyAlert);

        return $this;
    }

    public function removeSupplyPart(SupplyPart $supplyPart): self
    {
        $this->supplyParts->removeElement($supplyPart);

        return $this;
    }

    public function removeUnit(self $unit): self
    {
        if ($this->units->removeElement($unit)) {
            $unit->setMain(null);
        }

        return $this;
    }

    public function getMain(): ?self
    {
        return $this->main;
    }

    public function setMain(?self $main): self
    {
        $this->main = $main;

        return $this;
    }
}
