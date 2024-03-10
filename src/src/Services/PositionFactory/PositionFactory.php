<?php

namespace App\Services\PositionFactory;

use App\Config\ProductPosition;
use App\Entity\User;
use App\Repository\ProductRepository;
use App\Repository\ProductsGroupRepository;
use App\Services\UserService;

class PositionFactory
{
    private ProductRepository $productRepository;
    private ProductsGroupRepository $productsGroupRepository;
    private User $user;

    public function __construct(
        ProductRepository $productRepository,
        ProductsGroupRepository $productsGroupRepository,
        UserService $userService
    ) {
        $this->productsGroupRepository = $productsGroupRepository;
        $this->productRepository = $productRepository;
        $this->user = $userService->getUser();
    }

    public function get(string $type, int $id): Factory
    {
        if ($type === ProductPosition::PRODUCTS_GROUP) {
            return new ProductsGroupFactory($this->productsGroupRepository, $this->user, $id);
        }

        return new ProductFactory($this->productRepository, $this->user, $id);
    }
}
