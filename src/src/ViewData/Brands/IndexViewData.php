<?php

namespace App\ViewData\Brands;

use App\Config\TableId;
use App\Config\TableName;
use App\Config\ViewParameters;
use App\Entity\Brand;
use App\Services\Filters\BrandFilter;
use App\Services\Transformer\BrandTransformer;
use App\ViewData\IndexViewData as BasicIndexViewData;
use Symfony\Component\HttpFoundation\Session\SessionInterface;

class IndexViewData extends BasicIndexViewData
{
    public function __construct(SessionInterface $session, BrandFilter $filter)
    {
        parent::__construct($session, $filter, TableName::BRANDS_NAME, TableId::BRANDS);
        $this->options[ViewParameters::ENTITIES] = array_map(
            static fn(Brand $brand): array => BrandTransformer::toArray($brand),
            $filter->getResults()
        );
    }
}
