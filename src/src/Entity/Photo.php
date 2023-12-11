<?php

namespace App\Entity;

use App\Repository\PhotoRepository;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=PhotoRepository::class)
 */
class Photo
{
    /**
     * @ORM\Column(type="string", length=36)
     */
    private string $fileName;
    /**
     * @ORM\Column(type="integer")
     */
    private int $height;
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private int $id;
    /**
     * @ORM\ManyToOne(targetEntity=Product::class, inversedBy="photos")
     */
    private ?Product $product;
    /**
     * @ORM\ManyToOne(targetEntity=ProductsGroup::class, inversedBy="photos")
     */
    private ?ProductsGroup $productsGroup;
    /**
     * @ORM\Column(type="string", length=100)
     */
    private string $type;
    /**
     * @ORM\ManyToOne(targetEntity=User::class, inversedBy="photos")
     * @ORM\JoinColumn(nullable=false)
     */
    private User $user;
    /**
     * @ORM\Column(type="integer")
     */
    private int $width;

    public function __construct()
    {
        $this->product = null;
        $this->productsGroup = null;
    }

    public function getFileName(): ?string
    {
        return $this->fileName;
    }

    public function setFileName(string $fileName): self
    {
        $this->fileName = $fileName;

        return $this;
    }

    public function getHeight(): ?int
    {
        return $this->height;
    }

    public function setHeight(int $height): self
    {
        $this->height = $height;

        return $this;
    }

    public function getId(): ?int
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

    public function getProductsGroup(): ?ProductsGroup
    {
        return $this->productsGroup;
    }

    public function setProductsGroup(?ProductsGroup $productsGroup): self
    {
        $this->productsGroup = $productsGroup;

        return $this;
    }

    public function getType(): ?string
    {
        return $this->type;
    }

    public function setType(string $type): self
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

    public function getWidth(): ?int
    {
        return $this->width;
    }

    public function setWidth(int $width): self
    {
        $this->width = $width;

        return $this;
    }
}
