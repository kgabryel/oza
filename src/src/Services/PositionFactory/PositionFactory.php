<?php

namespace App\Services\PositionFactory;

use App\Config\ProductPosition;
use App\Entity\User;
use App\Repository\ProductRepository;
use App\Repository\ProductsGroupRepository;
use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorageInterface;

class PositionFactory
{
    private ProductRepository $productRepository;
    private ProductsGroupRepository $productsGroupRepository;
    private User $user;

    public function __construct(
        ProductRepository $productRepository,
        ProductsGroupRepository $productsGroupRepository,
        TokenStorageInterface $tokenStorage
    ) {
        $this->productsGroupRepository = $productsGroupRepository;
        $this->productRepository = $productRepository;
        $this->user = $tokenStorage->getToken()->getUser();
    }

    public function get(string $type, int $id): Factory
    {
        if ($type === ProductPosition::PRODUCTS_GROUP) {
            return new ProductsGroupFactory($this->productsGroupRepository, $this->user, $id);
        }

        return new ProductFactory($this->productRepository, $this->user, $id);
    }
}
