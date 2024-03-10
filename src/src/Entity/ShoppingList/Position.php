<?php

namespace App\Entity\ShoppingList;

use App\Entity\Product;
use App\Entity\ProductsGroup;
use App\Entity\Shop;
use App\Entity\Unit;
use App\Model\PositionDto;
use App\Repository\ShoppingList\PositionRepository;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=PositionRepository::class)
 * @ORM\Table(name="shopping_lists_positions")
 */
class Position
{
    /**
     * @ORM\Column(type="boolean")
     */
    private bool $checked;
    /**
     * @ORM\Column(type="text", nullable=true)
     */
    private ?string $description;
    /**
     * @ORM\ManyToOne(targetEntity=ProductsGroup::class, inversedBy="positions", fetch="EAGER")
     */
    private ?ProductsGroup $group;
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private int $id;
    /**
     * @ORM\ManyToOne(targetEntity=ShoppingList::class, inversedBy="positions")
     * @ORM\JoinColumn(nullable=false)
     */
    private ShoppingList $list;
    /**
     * @ORM\ManyToOne(targetEntity=Product::class, inversedBy="positions", fetch="EAGER")
     */
    private ?Product $product;
    /**
     * @ORM\ManyToOne(targetEntity=Shop::class, inversedBy="positions")
     */
    private ?Shop $shop;
    /**
     * @ORM\ManyToOne(targetEntity=Unit::class, inversedBy="listPositions")
     * @ORM\JoinColumn(nullable=false)
     */
    private Unit $unit;
    /**
     * @ORM\Column(type="float")
     */
    private float $unitValue;

    public function __construct()
    {
        $this->checked = false;
    }

    public function check(): self
    {
        $this->checked = true;

        return $this;
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

    public function getGroup(): ?ProductsGroup
    {
        return $this->group;
    }

    public function setGroup(?ProductsGroup $group): self
    {
        $this->group = $group;

        return $this;
    }

    public function getId(): int
    {
        return $this->id;
    }

    public function getList(): ShoppingList
    {
        return $this->list;
    }

    public function setList(ShoppingList $list): self
    {
        $this->list = $list;

        return $this;
    }

    public function getProduct(): ?Product
    {
        return $this->product;
    }

    public function setProduct(?Product $product): self
    {
        $this->product = $product;

        return $this;
    }

    public function getShop(): ?Shop
    {
        return $this->shop;
    }

    public function setShop(?Shop $shop): self
    {
        $this->shop = $shop;

        return $this;
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

    public function getUnitValue(): float
    {
        return $this->unitValue;
    }

    public function setUnitValue(float $unitValue): self
    {
        $this->unitValue = $unitValue;

        return $this;
    }

    public function isChecked(): bool
    {
        return $this->checked;
    }

    public function unCheck(): self
    {
        $this->checked = false;

        return $this;
    }

    public function getValue(): PositionDto
    {
        return new PositionDto($this);
    }
}
