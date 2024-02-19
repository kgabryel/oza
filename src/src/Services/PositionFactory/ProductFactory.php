<?php

namespace App\Services\PositionFactory;

use App\Entity\Product;
use App\Entity\ProductsGroup;
use App\Entity\User;
use App\Repository\ProductRepository;

class ProductFactory implements Factory
{
    private int $id;
    private ProductRepository $repository;
    private User $user;

    public function __construct(ProductRepository $repository, User $user, int $id)
    {
        $this->repository = $repository;
        $this->user = $user;
        $this->id = $id;
    }

    public function getProduct(): ?Product
    {
        return $this->repository->findById($this->id, $this->user);
    }

    public function getProductsGroup(): ?ProductsGroup
    {
        return null;
    }
}
