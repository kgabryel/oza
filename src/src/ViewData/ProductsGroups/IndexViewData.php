<?php

namespace App\ViewData\ProductsGroups;

use App\Config\TableId;
use App\Config\TableName;
use App\Config\ViewParameters;
use App\Entity\ProductsGroup;
use App\Services\Filters\ProductsGroupFilter;
use App\Services\Transformer\ProductsGroupTransformer;
use App\ViewData\IndexViewData as BasicIndexViewData;
use Symfony\Component\HttpFoundation\Session\SessionInterface;

class IndexViewData extends BasicIndexViewData
{
    public function __construct(SessionInterface $session, ProductsGroupFilter $filter)
    {
        parent::__construct($session, $filter, TableName::PRODUCTS_GROUPS_NAME, TableId::PRODUCTS_GROUPS);
        $this->options[ViewParameters::ENTITIES] = array_map(
            static fn(ProductsGroup $productsGroup): array => ProductsGroupTransformer::toArray($productsGroup),
            $filter->getResults()
        );
    }
}
