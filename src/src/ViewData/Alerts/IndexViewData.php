<?php

namespace App\ViewData\Alerts;

use App\Config\TableId;
use App\Config\TableName;
use App\Config\ViewParameters;
use App\Entity\Alert;
use App\Services\Filters\AlertFilter;
use App\Services\Transformer\AlertTransformer;
use App\ViewData\IndexViewData as BasicIndexViewData;
use Symfony\Component\HttpFoundation\Session\SessionInterface;

class IndexViewData extends BasicIndexViewData
{
    public function __construct(SessionInterface $session, AlertFilter $filter)
    {
        parent::__construct($session, $filter, TableName::ALERTS_NAME, TableId::ALERTS);
        $this->options[ViewParameters::ENTITIES] = array_map(
            static fn(Alert $alert): array => AlertTransformer::toArray($alert),
            $filter->getResults()
        );
    }
}
