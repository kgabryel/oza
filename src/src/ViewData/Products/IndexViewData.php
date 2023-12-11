<?php

namespace App\ViewData\Products;

use App\Config\TableId;
use App\Config\TableName;
use App\Config\ViewParameters;
use App\Entity\Product;
use App\Repository\ProductsGroupRepository;
use App\Services\Filters\ProductFilter;
use App\Services\Transformer\ProductTransformer;
use App\Utils\FormUtils;
use App\ViewData\IndexViewData as BasicIndexViewData;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorageInterface;

class IndexViewData extends BasicIndexViewData
{
    public function __construct(
        SessionInterface $session,
        ProductFilter $filter,
        ProductsGroupRepository $productsGroupRepository,
        TokenStorageInterface $tokenStorage
    ) {
        parent::__construct($session, $filter, TableName::PRODUCTS_NAME, TableId::PRODUCTS);
        $user = $tokenStorage->getToken()->getUser();
        $this->options[ViewParameters::PRODUCTS_UNITS] = FormUtils::getProductsUnits($productsGroupRepository, $user);
        $this->options[ViewParameters::ENTITIES] = array_map(
            static fn(Product $product): array => ProductTransformer::toArray($product),
            $filter->getResults()
        );
    }
}
