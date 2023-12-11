<?php

use App\Controller\Api\AlertsController;
use App\Controller\Api\BrandsController;
use App\Controller\Api\PhotosController;
use App\Controller\Api\ProductsController;
use App\Controller\Api\ProductsGroupsController;
use App\Controller\Api\QuickListClipboardPositionsController;
use App\Controller\Api\QuickListsController;
use App\Controller\Api\QuickListsPositionsController;
use App\Controller\Api\SettingsController;
use App\Controller\Api\ShoppingListClipboardPositionsController;
use App\Controller\Api\ShoppingListsController;
use App\Controller\Api\ShoppingListsPositionsController;
use App\Controller\Api\SuppliesController;
use App\Controller\Api\SupplyGroupsController;
use App\Controller\Api\UnitsController;
use App\Services\Routing\ApiGroup;
use Kgabryel\Routing\Info;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Loader\Configurator\RoutingConfigurator;
use Symfony\Component\Routing\Route;

return static function (RoutingConfigurator $routes) {
    $productsGroupsGroup = new ApiGroup($routes, 'productsGroups', '/products-groups');
    $productsGroupsGroup->setController(ProductsGroupsController::class)
        ->addShow()
        ->add(
            'addPhoto',
            new Route('/{id}/photos'),
            new Info([Request::METHOD_POST], 'addPhoto'),
            ['id' => '\d+']
        )
        ->add(
            'units',
            new Route('/{id}/units'),
            new Info([Request::METHOD_GET], 'getAvailableUnits'),
            ['id' => '\d+']
        )
        ->add(
            'supplyInfo',
            new Route('/{id}/supply-info'),
            new Info([Request::METHOD_GET], 'getSupplyInfo'),
            ['id' => '\d+']
        )
        ->add(
            'chart',
            new Route('/{id}/chart'),
            new Info([Request::METHOD_GET], 'getChartData'),
            ['id' => '\d+']
        )
        ->add(
            'changeMainPhoto',
            new Route('/{id}/change-main-photo'),
            new Info([Request::METHOD_POST], 'changeMainPhoto'),
            ['id' => '\d+']
        );
    $productsGroup = new ApiGroup($routes, 'products', '/products');
    $productsGroup->setController(ProductsController::class)
        ->add(
            'findByBarcode',
            new Route('/find-by-barcode/{code}'),
            new Info([Request::METHOD_GET], 'findByBarcode'),
            ['code' => '\d+']
        )
        ->addShow()
        ->add(
            'addPhoto',
            new Route('/{id}/photos'),
            new Info([Request::METHOD_POST], 'addPhoto'),
            ['id' => '\d+']
        )
        ->add(
            'units',
            new Route('/{id}/units'),
            new Info([Request::METHOD_GET], 'getAvailableUnits'),
            ['id' => '\d+']
        )
        ->add(
            'supplyInfo',
            new Route('/{id}/supply-info'),
            new Info([Request::METHOD_GET], 'getSupplyInfo'),
            ['id' => '\d+']
        )
        ->add(
            'chart',
            new Route('/{id}/chart'),
            new Info([Request::METHOD_GET], 'getChartData'),
            ['id' => '\d+']
        )
        ->add(
            'changeMainPhoto',
            new Route('/{id}/change-main-photo'),
            new Info([Request::METHOD_POST], 'changeMainPhoto'),
            ['id' => '\d+']
        );
    $alertsGroup = new ApiGroup($routes, 'alerts', '/alerts');
    $alertsGroup->setController(AlertsController::class)
        ->add(
            'activeAlerts',
            new Route('/'),
            new Info([Request::METHOD_GET], 'getAlerts')
        )
        ->add(
            'activate',
            new Route('/{id}/activate'),
            new Info([Request::METHOD_POST], 'activate'),
            ['id' => '\d+']
        )
        ->add(
            'deactivate',
            new Route('/{id}/deactivate'),
            new Info([Request::METHOD_POST], 'deactivate'),
            ['id' => '\d+']
        );
    $shoppingListsGroup = new ApiGroup($routes, 'shoppingLists', '/shopping-lists');
    $shoppingListsGroup->setController(ShoppingListsController::class)
        ->addDestroy()
        ->add(
            'getPrices',
            new Route('/{id}/prices'),
            new Info([Request::METHOD_GET], 'getPrices'),
            ['id' => '\d+']
        );
    $quickListsGroup = new ApiGroup($routes, 'quick-lists', '/quick-lists');
    $quickListsGroup->setController(QuickListsController::class)
        ->addDestroy();
    $quickListPositionsGroup = new ApiGroup(
        $routes,
        'quick-list-positions.',
        '/quick-list-positions'
    );
    $quickListPositionsGroup->setController(QuickListsPositionsController::class)
        ->addDestroy()
        ->add(
            'check',
            new Route('/{id}/check'),
            new Info([Request::METHOD_POST], 'check'),
            ['id' => '\d+']
        )
        ->add(
            'uncheck',
            new Route('/{id}/uncheck'),
            new Info([Request::METHOD_POST], 'unCheck'),
            ['id' => '\d+']
        );
    $shoppingListPositionsGroup = new ApiGroup(
        $routes,
        'shopping-list-positions',
        '/shopping-list-positions'
    );
    $shoppingListPositionsGroup->setController(ShoppingListsPositionsController::class)
        ->addDestroy()
        ->add(
            'check',
            new Route('/{id}/check'),
            new Info([Request::METHOD_POST], 'check'),
            ['id' => '\d+']
        )
        ->add(
            'uncheck',
            new Route('/{id}/uncheck'),
            new Info([Request::METHOD_POST], 'unCheck'),
            ['id' => '\d+']
        )
        ->add(
            'changeShop',
            new Route('/{id}/change-shop'),
            new Info([Request::METHOD_PATCH], 'changeShop'),
            ['id' => '\d+']
        )
        ->add(
            'getPrices',
            new Route('/{id}/prices'),
            new Info([Request::METHOD_GET], 'getPrices'),
            ['id' => '\d+']
        );
    $unitsGroup = new ApiGroup($routes, 'units', '/units');
    $unitsGroup->setController(UnitsController::class)
        ->addShow();
    $quickListClipboardsGroup = new ApiGroup(
        $routes,
        'quick-list-clipboard',
        '/quick-list-clipboard'
    );
    $quickListClipboardsGroup->setController(QuickListClipboardPositionsController::class)
        ->addIndex()
        ->addDestroy();
    $productsListClipboardsGroup = new ApiGroup(
        $routes,
        'shopping-list-clipboard.',
        '/shopping-list-clipboard'
    );
    $productsListClipboardsGroup->setController(ShoppingListClipboardPositionsController::class)
        ->addIndex()
        ->addDestroy();
    $supplyGroupsGroup = new ApiGroup($routes, 'supplyGroups', '/supply-groups');
    $supplyGroupsGroup->setController(SupplyGroupsController::class)
        ->addShow();
    $suppliesGroup = new ApiGroup($routes, 'supplies', '/supplies');
    $suppliesGroup->setController(SuppliesController::class)
        ->addIndex()
        ->addShow();
    $settingsGroup = new ApiGroup($routes, 'settings', '/settings');
    $settingsGroup->setController(SettingsController::class)
        ->add(
            'update',
            new Route('/api-key/{id}'),
            new Info([Request::METHOD_PATCH], 'switch'),
            ['id' => '\d+']
        );
    $brandsGroup = new ApiGroup($routes, 'brands', '/brands');
    $brandsGroup->setController(BrandsController::class)
        ->addShow();
    $photosGroup = new ApiGroup($routes, 'photos', '/photos');
    $photosGroup->setController(PhotosController::class)
        ->addDestroy();
    return $routes;
};
