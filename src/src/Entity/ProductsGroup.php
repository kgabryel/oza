<?php

namespace App\Entity;

use App\Entity\ShoppingList\ClipboardPosition;
use App\Entity\ShoppingList\Position;
use App\Repository\ProductsGroupRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Stringable;

/**
 * @ORM\Entity(repositoryClass=ProductsGroupRepository::class)
 */
class ProductsGroup implements Stringable
{
    /**
     * @ORM\ManyToOne(targetEntity=Unit::class)
     * @ORM\JoinColumn(nullable=false)
     */
    private Unit $baseUnit;
    /**
     * @ORM\OneToMany(targetEntity=ClipboardPosition::class, mappedBy="group")
     * @ORM\OrderBy({"id" = "DESC"})
     */
    private Collection $clipboardPositions;
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private int $id;
    /**
     * @ORM\ManyToOne(targetEntity=Photo::class)
     */
    private ?Photo $mainPhoto;
    /**
     * @ORM\Column(type="string", length=255)
     */
    private string $name;
    /**
     * @ORM\Column(type="text", nullable=true)
     */
    private ?string $note;
    /**
     * @ORM\OneToMany(targetEntity=Photo::class, mappedBy="productsGroup")
     * @ORM\OrderBy({"id" = "DESC"})
     */
    private Collection $photos;
    /**
     * @ORM\OneToMany(targetEntity=Position::class, mappedBy="group", cascade={"all"})
     * @ORM\OrderBy({"id" = "DESC"})
     */
    private Collection $positions;
    /**
     * @ORM\ManyToMany(targetEntity=Product::class, mappedBy="groups")
     * @ORM\OrderBy({"name" = "ASC"})
     */
    private Collection $products;
    /**
     * @ORM\OneToMany(targetEntity=Shopping::class, mappedBy="group", cascade={"all"})
     * @ORM\OrderBy({"date" = "DESC"})
     */
    private Collection $shopping;
    /**
     * @ORM\OneToMany(targetEntity=Supply::class, mappedBy="group", cascade={"all"})
     * @ORM\OrderBy({"id" = "DESC"})
     */
    private Collection $supplies;
    /**
     * @ORM\ManyToOne(targetEntity=Unit::class, inversedBy="groups")
     * @ORM\JoinColumn(nullable=false)
     */
    private Unit $unit;
    /**
     * @ORM\ManyToOne(targetEntity=User::class, inversedBy="groups")
     * @ORM\JoinColumn(nullable=false)
     */
    private User $user;

    public function __construct()
    {
        $this->products = new ArrayCollection();
        $this->positions = new ArrayCollection();
        $this->shopping = new ArrayCollection();
        $this->supplies = new ArrayCollection();
        $this->clipboardPositions = new ArrayCollection();
        $this->photos = new ArrayCollection();
    }

    public function __toString(): string
    {
        return $this->name;
    }

    public function addClipboardPosition(ClipboardPosition $clipboardPosition): self
    {
        if (!$this->clipboardPositions->contains($clipboardPosition)) {
            $this->clipboardPositions[] = $clipboardPosition;
            $clipboardPosition->setGroup($this);
        }

        return $this;
    }

    public function addPhoto(Photo $photo): self
    {
        if (!$this->photos->contains($photo)) {
            $this->photos[] = $photo;
            $photo->setProductsGroup($this);
        }

        return $this;
    }

    public function addProduct(Product $product): self
    {
        if (!$this->products->contains($product)) {
            $this->products[] = $product;
            $product->addGroup($this);
        }

        return $this;
    }

    public function addShopping(Shopping $shopping): self
    {
        if (!$this->shopping->contains($shopping)) {
            $this->shopping[] = $shopping;
            $shopping->setGroup($this);
        }

        return $this;
    }

    public function addShoppingListPosition(Position $position): self
    {
        if (!$this->positions->contains($position)) {
            $this->positions[] = $position;
            $position->setGroup($this);
        }

        return $this;
    }

    public function addSupply(Supply $supply): self
    {
        if (!$this->supplies->contains($supply)) {
            $this->supplies[] = $supply;
            $supply->setGroup($this);
        }

        return $this;
    }

    public function getBaseUnit(): Unit
    {
        return $this->baseUnit;
    }

    public function setBaseUnit(Unit $baseUnit): self
    {
        $this->baseUnit = $baseUnit;

        return $this;
    }

    /**
     * @return Collection|ClipboardPosition[]
     */
    public function getClipboardPositions(): Collection
    {
        return $this->clipboardPositions;
    }

    public function getId(): int
    {
        return $this->id;
    }

    public function getMainPhoto(): ?Photo
    {
        return $this->mainPhoto;
    }

    public function setMainPhoto(?Photo $mainPhoto): self
    {
        $this->mainPhoto = $mainPhoto;

        return $this;
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

    public function getNote(): ?string
    {
        return $this->note;
    }

    public function setNote(?string $note): self
    {
        $this->note = $note;

        return $this;
    }

    /**
     * @return Collection<int, Photo>
     */
    public function getPhotos(): Collection
    {
        return $this->photos;
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

    /**
     * @return Collection|Position[]
     */
    public function getShoppingListPositions(): Collection
    {
        return $this->positions;
    }

    /**
     * @return Collection|Supply[]
     */
    public function getSupplies(): Collection
    {
        return $this->supplies;
    }

    public function getSupply(): ?Supply
    {
        if ($this->supplies->count() === 0) {
            return null;
        }

        return $this->supplies->first();
    }

    public function getUnit(): Unit
    {
        return $this->unit;
    }

    public function setUnit(Unit $unit): self
    {
        $this->unit = $unit;

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

    public function removeClipboardPosition(ClipboardPosition $clipboardPosition): self
    {
        if ($this->clipboardPositions->removeElement($clipboardPosition)) {
            $clipboardPosition->setGroup(null);
        }

        return $this;
    }

    public function removePhoto(Photo $photo): self
    {
        if ($this->photos->removeElement($photo)) {
            $photo->setProductsGroup(null);
        }

        return $this;
    }

    public function removeProduct(Product $product): self
    {
        if ($this->products->removeElement($product)) {
            $product->removeGroup($this);
        }

        return $this;
    }

    public function removeSupply(Supply $supply): self
    {
        $this->supplies->removeElement($supply);

        return $this;
    }
}
