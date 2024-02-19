<?php

namespace App\ViewData\Supplies;

use App\Config\TableId;
use App\Config\TableName;
use App\Config\ViewParameters;
use App\Entity\Supply;
use App\Services\Filters\SupplyFilter;
use App\Services\Transformer\SupplyTransformer;
use App\ViewData\IndexViewData as BasicIndexViewData;
use Symfony\Component\HttpFoundation\Session\SessionInterface;

class IndexViewData extends BasicIndexViewData
{
    public function __construct(
        SessionInterface $session,
        SupplyFilter $filter
    ) {
        parent::__construct($session, $filter, TableName::SUPPLIES_NAME, TableId::SUPPLIES);

        $this->options[ViewParameters::ENTITIES] = array_map(
            static fn(Supply $supply) => SupplyTransformer::toArray($supply),
            $filter->getResults()
        );
    }
}
