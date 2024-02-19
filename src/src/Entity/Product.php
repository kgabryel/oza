<?php

namespace App\Entity;

use App\Entity\ShoppingList\ClipboardPosition;
use App\Entity\ShoppingList\Position;
use App\Repository\ProductRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Stringable;

/**
 * @ORM\Entity(repositoryClass=ProductRepository::class)
 */
class Product implements Stringable
{
    /**
     * @ORM\Column(type="string", length=13, nullable=true)
     */
    private ?string $barcode;
    /**
     * @ORM\ManyToOne(targetEntity=Brand::class, inversedBy="products")
     */
    private ?Brand $brand;
    /**
     * @ORM\OneToMany(targetEntity=ClipboardPosition::class, mappedBy="product")
     * @ORM\OrderBy({"id" = "DESC"})
     */
    private Collection $clipboardPositions;
    /**
     * @ORM\Column(type="float", nullable=true)
     */
    private ?float $defaultAmount;
    /**
     * @ORM\ManyToMany(targetEntity=ProductsGroup::class, inversedBy="products")
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
     * @ORM\OneToMany(targetEntity=Photo::class, mappedBy="product")
     * @ORM\OrderBy({"id" = "DESC"})
     */
    private Collection $photos;
    /**
     * @ORM\OneToMany(targetEntity=Position::class, mappedBy="product", cascade={"all"})
     * @ORM\OrderBy({"id" = "DESC"})
     */
    private Collection $positions;
    /**
     * @ORM\OneToMany(targetEntity=Shopping::class, mappedBy="product", cascade={"all"})
     * @ORM\OrderBy({"date" = "DESC"})
     */
    private Collection $shopping;
    /**
     * @ORM\OneToMany(targetEntity=SupplyPart::class, mappedBy="product")
     * @ORM\OrderBy({"id" = "DESC"})
     */
    private Collection $supplyParts;
    /**
     * @ORM\ManyToOne(targetEntity=Unit::class, inversedBy="products")
     * @ORM\JoinColumn(nullable=false)
     */
    private Unit $unit;
    /**
     * @ORM\ManyToOne(targetEntity=User::class, inversedBy="products")
     * @ORM\JoinColumn(nullable=false)
     */
    private User $user;

    public function __construct()
    {
        $this->positions = new ArrayCollection();
        $this->groups = new ArrayCollection();
        $this->shopping = new ArrayCollection();
        $this->clipboardPositions = new ArrayCollection();
        $this->supplyParts = new ArrayCollection();
        $this->photos = new ArrayCollection();
    }

    public function __toString(): string
    {
        if ($this->brand === null) {
            return $this->name;
        }
        return sprintf('%s [%s]', $this->name, $this->brand->getName());
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

    public function addClipboardPosition(ClipboardPosition $clipboardPosition): self
    {
        if (!$this->clipboardPositions->contains($clipboardPosition)) {
            $this->clipboardPositions[] = $clipboardPosition;
            $clipboardPosition->setProduct($this);
        }

        return $this;
    }

    public function addGroup(ProductsGroup $group): self
    {
        if (!$this->groups->contains($group)) {
            $this->groups[] = $group;
            $group->addProduct($this);
        }

        return $this;
    }

    public function addPhoto(Photo $photo): self
    {
        if (!$this->photos->contains($photo)) {
            $this->photos[] = $photo;
            $photo->setProduct($this);
        }

        return $this;
    }

    public function addPosition(Position $position): self
    {
        if (!$this->positions->contains($position)) {
            $this->positions[] = $position;
            $position->setProduct($this);
        }

        return $this;
    }

    public function addShopping(Shopping $shopping): self
    {
        if (!$this->shopping->contains($shopping)) {
            $this->shopping[] = $shopping;
            $shopping->setProduct($this);
        }

        return $this;
    }

    public function addSupplyPart(SupplyPart $supplyPart): self
    {
        if (!$this->supplyParts->contains($supplyPart)) {
            $this->supplyParts[] = $supplyPart;
            $supplyPart->setProduct($this);
        }

        return $this;
    }

    public function clearGroups(): self
    {
        foreach ($this->groups as $group) {
            $this->groups->removeElement($group);
            $group->removeProduct($this);
        }

        return $this;
    }

    public function getBarcode(): ?string
    {
        return $this->barcode;
    }

    public function setBarcode(?string $barcode): self
    {
        $this->barcode = $barcode;

        return $this;
    }

    public function getBrand(): ?Brand
    {
        return $this->brand;
    }

    public function setBrand(?Brand $brand): self
    {
        $this->brand = $brand;

        return $this;
    }

    /**
     * @return Collection|ClipboardPosition[]
     */
    public function getClipboardPositions(): Collection
    {
        return $this->clipboardPositions;
    }

    public function getDefaultAmount(): ?float
    {
        return $this->defaultAmount;
    }

    public function setDefaultAmount(?float $defaultAmount): self
    {
        $this->defaultAmount = $defaultAmount;

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

    public function getMainPhoto(): ?Photo
    {
        return $this->mainPhoto;
    }

    public function setMainPhoto(?Photo $mainPhoto): self
    {
        $this->mainPhoto = $mainPhoto;

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
     * @return Collection|Position[]
     */
    public function getPositions(): Collection
    {
        return $this->positions;
    }

    /**
     * @return Collection|Shopping[]
     */
    public function getShopping(): Collection
    {
        return $this->shopping;
    }

    /**
     * @return Collection|SupplyPart[]
     */
    public function getSupplyParts(): Collection
    {
        return $this->supplyParts;
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
        if (
            $this->clipboardPositions->removeElement($clipboardPosition)
            && $clipboardPosition->getProduct() === $this
        ) {
            $clipboardPosition->setProduct(null);
        }

        return $this;
    }

    public function removeGroup(ProductsGroup $group): self
    {
        if ($this->groups->contains($group)) {
            $this->groups->removeElement($group);
            $group->removeProduct($this);
        }

        return $this;
    }

    public function removePhoto(Photo $photo): self
    {
        if ($this->photos->removeElement($photo)) {
            // set the owning side to null (unless already changed)
            if ($photo->getProduct() === $this) {
                $photo->setProduct(null);
            }
        }

        return $this;
    }

    public function removePosition(Position $position): self
    {
        if ($this->positions->contains($position)) {
            $this->positions->removeElement($position);
            // set the owning side to null (unless already changed)
            if ($position->getProduct() === $this) {
                $position->setProduct(null);
            }
        }

        return $this;
    }

    public function removeShopping(Shopping $shopping): self
    {
        if ($this->shopping->contains($shopping)) {
            $this->shopping->removeElement($shopping);
            // set the owning side to null (unless already changed)
            if ($shopping->getProduct() === $this) {
                $shopping->setProduct(null);
            }
        }

        return $this;
    }

    public function removeSupplyPart(SupplyPart $supplyPart): self
    {
        if ($this->supplyParts->removeElement($supplyPart) && $supplyPart->getProduct() === $this) {
            $supplyPart->setProduct(null);
        }

        return $this;
    }
}
