<?php

namespace App\Services\Routing;

use Kgabryel\Routing\Group;
use Symfony\Component\Routing\Loader\Configurator\RoutingConfigurator;

class ApiGroup extends Group
{
    public function __construct(RoutingConfigurator $configurator, string $namePrefix = '', string $pathPrefix = '')
    {
        parent::__construct(
            $configurator,
            sprintf('api.%s', $namePrefix),
            sprintf('api/%s', trim($pathPrefix, self::URL_SEPARATOR))
        );
    }
}
