<?php

namespace App\ViewData\Units;

use App\Config\TableId;
use App\Config\TableName;
use App\Config\ViewParameters;
use App\Entity\Unit;
use App\Services\Filters\UnitFilter;
use App\Services\Transformer\UnitTransformer;
use App\ViewData\IndexViewData as BasicIndexViewData;
use Symfony\Component\HttpFoundation\Session\SessionInterface;

class IndexViewData extends BasicIndexViewData
{
    public function __construct(SessionInterface $session, UnitFilter $filter)
    {
        parent::__construct($session, $filter, TableName::UNITS_NAME, TableId::UNITS);
        $this->options[ViewParameters::ENTITIES] = array_map(
            static fn(Unit $unit): array => UnitTransformer::toArray($unit),
            $filter->getResults()
        );
    }
}
