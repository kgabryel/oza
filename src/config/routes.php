<?php

use Symfony\Component\Routing\Loader\Configurator\RoutingConfigurator;

return static function (RoutingConfigurator $routes) {
    $routes->import('routing/api.php');
    $routes->import('routing/web.php');
    return $routes;
};
