<?php

namespace App\ViewData\Shops;

use App\Config\TableId;
use App\Config\TableName;
use App\Config\ViewParameters;
use App\Entity\Shop;
use App\Services\Filters\ShopFilter;
use App\Services\Transformer\ShopTransformer;
use App\ViewData\IndexViewData as BasicIndexViewData;
use Symfony\Component\HttpFoundation\Session\SessionInterface;

class IndexViewData extends BasicIndexViewData
{
    public function __construct(SessionInterface $session, ShopFilter $filter)
    {
        parent::__construct($session, $filter, TableName::SHOPS_NAME, TableId::SHOPS);
        $this->options[ViewParameters::ENTITIES] = array_map(
            static fn(Shop $shop): array => ShopTransformer::toArray($shop),
            $filter->getResults()
        );
    }
}
