<?php

namespace App\Entity;

use App\Model\PositionDto;
use App\Repository\ShoppingRepository;
use DateTimeInterface;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=ShoppingRepository::class)
 * @ORM\Table(name="shopping")
 */
class Shopping
{
    /**
     * @ORM\Column(type="float")
     */
    private float $amount;
    /**
     * @ORM\Column(type="date")
     */
    private DateTimeInterface $date;
    /**
     * @ORM\ManyToOne(targetEntity=ProductsGroup::class, inversedBy="shopping")
     */
    private ?ProductsGroup $group;
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private int $id;
    /**
     * @ORM\Column(type="float")
     */
    private float $originalPrice;
    /**
     * @ORM\Column(type="float")
     */
    private float $price;
    /**
     * @ORM\ManyToOne(targetEntity=Product::class, inversedBy="shopping")
     */
    private ?Product $product;
    /**
     * @ORM\ManyToOne(targetEntity=Shop::class, inversedBy="shopping")
     * @ORM\JoinColumn(nullable=false)
     */
    private Shop $shop;
    /**
     * @ORM\ManyToOne(targetEntity=Unit::class, inversedBy="shopping")
     * @ORM\JoinColumn(nullable=false)
     */
    private Unit $unit;
    /**
     * @ORM\ManyToOne(targetEntity=User::class, inversedBy="shopping")
     * @ORM\JoinColumn(nullable=false)
     */
    private User $user;

    public function getAmount(): float
    {
        return $this->amount;
    }

    public function setAmount(float $amount): self
    {
        $this->amount = $amount;

        return $this;
    }

    public function getDate(): DateTimeInterface
    {
        return $this->date;
    }

    public function setDate(DateTimeInterface $date): self
    {
        $this->date = $date;

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

    public function getOriginalPrice(): float
    {
        return $this->originalPrice;
    }

    public function setOriginalPrice(float $originalPrice): self
    {
        $this->originalPrice = $originalPrice;

        return $this;
    }

    public function getPosition(): PositionDto
    {
        return new PositionDto($this);
    }

    public function getPrice(): float
    {
        return $this->price;
    }

    public function setPrice(float $price): self
    {
        $this->price = $price;

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

    public function getShop(): Shop
    {
        return $this->shop;
    }

    public function setShop(Shop $shop): self
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

    public function getUser(): User
    {
        return $this->user;
    }

    public function setUser(User $user): self
    {
        $this->user = $user;

        return $this;
    }
}
