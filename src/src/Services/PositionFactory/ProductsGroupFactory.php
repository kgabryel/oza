<?php

namespace App\Services\PositionFactory;

use App\Entity\Product;
use App\Entity\ProductsGroup;
use App\Entity\User;
use App\Repository\ProductsGroupRepository;

class ProductsGroupFactory implements Factory
{
    private int $id;
    private ProductsGroupRepository $repository;
    private User $user;

    public function __construct(ProductsGroupRepository $repository, User $user, int $id)
    {
        $this->repository = $repository;
        $this->user = $user;
        $this->id = $id;
    }

    public function getProduct(): ?Product
    {
        return null;
    }

    public function getProductsGroup(): ?ProductsGroup
    {
        return $this->repository->findById($this->id, $this->user);
    }
}
