<?php

namespace App\Entity;

use App\Repository\SupplyPartRepository;
use DateTimeInterface;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=SupplyPartRepository::class)
 */
class SupplyPart
{
    /**
     * @ORM\Column(type="float")
     */
    private float $amount;
    /**
     * @ORM\Column(type="date", nullable=true)
     */
    private ?DateTimeInterface $dateOfConsumption;
    /**
     * @ORM\Column(type="text")
     */
    private string $description;
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private int $id;
    /**
     * @ORM\Column(type="boolean")
     */
    private bool $open;
    /**
     * @ORM\Column(type="integer")
     */
    private int $part;
    /**
     * @ORM\ManyToOne(targetEntity=Product::class, inversedBy="supplyParts")
     */
    private ?Product $product;
    /**
     * @ORM\ManyToOne(targetEntity=Supply::class, inversedBy="supplyParts")
     * @ORM\JoinColumn(nullable=false)
     */
    private Supply $supply;
    /**
     * @ORM\ManyToOne(targetEntity=Unit::class, inversedBy="supplyParts")
     * @ORM\JoinColumn(nullable=false)
     */
    private Unit $unit;
    /**
     * @ORM\Column(type="datetime", nullable=true)
     */
    private ?DateTimeInterface $updatedAt;

    public function close(): self
    {
        $this->open = false;

        return $this;
    }

    public function getAmount(): float
    {
        return $this->amount;
    }

    public function setAmount(float $amount): self
    {
        $this->amount = $amount;

        return $this;
    }

    public function getDateOfConsumption(): ?DateTimeInterface
    {
        return $this->dateOfConsumption;
    }

    public function setDateOfConsumption(?DateTimeInterface $dateOfConsumption): self
    {
        $this->dateOfConsumption = $dateOfConsumption;

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

    public function getPart(): int
    {
        return $this->part;
    }

    public function setPart(int $part): self
    {
        $this->part = $part;

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

    public function getSupply(): Supply
    {
        return $this->supply;
    }

    public function setSupply(Supply $supply): self
    {
        $this->supply = $supply;

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

    public function getUpdatedAt(): ?DateTimeInterface
    {
        return $this->updatedAt;
    }

    public function setUpdatedAt(?DateTimeInterface $updatedAt): self
    {
        $this->updatedAt = $updatedAt;

        return $this;
    }

    public function isOpen(): bool
    {
        return $this->open;
    }

    public function open(): self
    {
        $this->open = true;

        return $this;
    }
}
