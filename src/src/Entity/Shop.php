<?php

namespace App\Entity;

use App\Entity\ShoppingList\Position;
use App\Repository\ShopRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Stringable;

/**
 * @ORM\Entity(repositoryClass=ShopRepository::class)
 * @ORM\Table(name="shops")
 */
class Shop implements Stringable
{
    /**
     * @ORM\Column(type="text", nullable=true)
     */
    private ?string $description;
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private int $id;
    /**
     * @ORM\Column(type="string", length=40)
     */
    private string $name;
    /**
     * @ORM\OneToMany(targetEntity=Position::class, mappedBy="shop", cascade={"all"})
     * @ORM\OrderBy({"id" = "DESC"})
     */
    private Collection $positions;
    /**
     * @ORM\OneToMany(targetEntity=Shopping::class, mappedBy="shop", cascade={"all"})
     * @ORM\OrderBy({"date" = "DESC"})
     */
    private Collection $shopping;
    /**
     * @ORM\ManyToOne(targetEntity=User::class, inversedBy="shops")
     * @ORM\JoinColumn(nullable=false)
     */
    private User $user;

    public function __construct()
    {
        $this->shopping = new ArrayCollection();
        $this->positions = new ArrayCollection();
    }

    public function __toString(): string
    {
        return $this->name;
    }

    public function addListPosition(Position $position): self
    {
        if (!$this->positions->contains($position)) {
            $this->positions[] = $position;
            $position->setShop($this);
        }

        return $this;
    }

    public function addShopping(Shopping $shopping): self
    {
        if (!$this->shopping->contains($shopping)) {
            $this->shopping[] = $shopping;
            $shopping->setShop($this);
        }

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

    public function getId(): int
    {
        return $this->id;
    }

    /**
     * @return Collection|Position[]
     */
    public function getListPositions(): Collection
    {
        return $this->positions;
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
     * @return Collection|Shopping[]
     */
    public function getShopping(): Collection
    {
        return $this->shopping;
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

    public function removeListPosition(Position $position): self
    {
        if ($this->positions->removeElement($position)) {
            $position->setShop(null);
        }

        return $this;
    }

    public function removeShopping(Shopping $shopping): self
    {
        $this->shopping->removeElement($shopping);

        return $this;
    }
}
