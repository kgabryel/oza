<?php

namespace App\Entity\ShoppingList;

use App\Entity\Product;
use App\Entity\ProductsGroup;
use App\Entity\Unit;
use App\Entity\User;
use App\Repository\ShoppingList\ClipboardPositionRepository;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=ClipboardPositionRepository::class)
 * @ORM\Table(name="shopping_lists_clipboard_positions")
 */
class ClipboardPosition
{
    /**
     * @ORM\Column(type="float")
     */
    private float $amount;
    /**
     * @ORM\Column(type="text", nullable=true)
     */
    private ?string $description;
    /**
     * @ORM\ManyToOne(targetEntity=ProductsGroup::class, inversedBy="clipboardPositions")
     */
    private ?ProductsGroup $group;
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private int $id;
    /**
     * @ORM\ManyToOne(targetEntity=Product::class, inversedBy="clipboardPositions")
     */
    private ?Product $product;
    /**
     * @ORM\ManyToOne(targetEntity=Unit::class, inversedBy="clipboardPositions")
     * @ORM\JoinColumn(nullable=false)
     */
    private Unit $unit;
    /**
     * @ORM\ManyToOne(targetEntity=User::class, inversedBy="listsClipboardPositions")
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

    public function getProduct(): ?Product
    {
        return $this->product;
    }

    public function setProduct(?Product $product): self
    {
        $this->product = $product;

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
