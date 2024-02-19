<?php

namespace App\ViewData\SupplyGroups;

use App\Config\TableId;
use App\Config\TableName;
use App\Config\ViewParameters;
use App\Entity\SupplyGroup;
use App\Services\Filters\SupplyGroupFilter;
use App\Services\Transformer\SupplyGroupTransformer;
use App\ViewData\IndexViewData as BasicIndexViewData;
use Symfony\Component\HttpFoundation\Session\SessionInterface;

class IndexViewData extends BasicIndexViewData
{
    public function __construct(SessionInterface $session, SupplyGroupFilter $filter)
    {
        parent::__construct($session, $filter, TableName::SUPPLY_GROUPS, TableId::SUPPLY_GROUPS);
        $this->options[ViewParameters::ENTITIES] = array_map(
            static fn(SupplyGroup $supplyGroup): array => SupplyGroupTransformer::toArray($supplyGroup),
            $filter->getResults()
        );
    }
}
