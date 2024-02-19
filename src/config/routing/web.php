<?php

use App\Config\PhotoType;
use App\Controller\Web\AlertsController;
use App\Controller\Web\BrandsController;
use App\Controller\Web\FBController;
use App\Controller\Web\HomeController;
use App\Controller\Web\PhotosController;
use App\Controller\Web\ProductsController;
use App\Controller\Web\ProductsGroupsController;
use App\Controller\Web\QuickListsController;
use App\Controller\Web\ResetPasswordController;
use App\Controller\Web\SecurityController;
use App\Controller\Web\SettingsController;
use App\Controller\Web\ShoppingController;
use App\Controller\Web\ShoppingListsController;
use App\Controller\Web\ShopsController;
use App\Controller\Web\SuppliesController;
use App\Controller\Web\SupplyGroupsController;
use App\Controller\Web\SupplyPartsController;
use App\Controller\Web\UnitsController;
use Kgabryel\Routing\Group;
use Kgabryel\Routing\Info;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Loader\Configurator\RoutingConfigurator;
use Symfony\Component\Routing\Route;

return static function (RoutingConfigurator $routes) {
    $loginGroup = new Group($routes, 'login', '/login');
    $loginGroup->setController(SecurityController::class)
        ->add('show', new Route('/'), new Info(['GET'], 'showLogin'))
        ->add('login', new Route('/'), new Info(['POST'], 'login'));
    $registerGroup = new Group($routes, 'register', '/register');
    $registerGroup->setController(SecurityController::class)
        ->add('show', new Route('/'), new Info(['GET'], 'showRegister'))
        ->add('register', new Route('/'), new Info(['POST'], 'register'));
    $fbGroup = new Group($routes, 'fb', '/fb');
    $fbGroup->setController(FBController::class)
        ->add('auth', new Route('/auth'), new Info(['GET'], 'auth'))
        ->add('login', new Route('/login'), new Info(['GET'], 'login'));
    $homeGroup = new Group($routes, 'home', '/');
    $homeGroup->setController(HomeController::class)
        ->addStore()
        ->addDestroy()
        ->add('index', new Route('/'), new Info(['GET'], 'index'));
    $logoutGroup = new Group($routes, 'logout', '/logout');
    $logoutGroup->setController(SecurityController::class)
        ->add('logout', new Route('/'), new Info(['GET'], 'logout'));
    $unitsGroup = new Group($routes, 'units', '/units');
    $unitsGroup->setController(UnitsController::class)
        ->addIndex()
        ->addStore()
        ->addShow()
        ->addUpdate()
        ->addDestroy();
    $quickListsGroup = new Group($routes, 'quickLists', '/quick-lists');
    $quickListsGroup->setController(QuickListsController::class)
        ->addIndex()
        ->addStore()
        ->addUpdate()
        ->add(
            'add',
            new Route('/add'),
            new Info([Request::METHOD_GET], 'showCreate')
        )
        ->addShow();
    $shopsGroup = new Group($routes, 'shops', '/shops');
    $shopsGroup->setController(ShopsController::class)
        ->addIndex()
        ->addStore()
        ->addShow()
        ->addUpdate()
        ->addDestroy();
    $productsGroupsGroup = new Group($routes, 'productsGroups', '/products-groups');
    $productsGroupsGroup->setController(ProductsGroupsController::class)
        ->addIndex()
        ->addStore()
        ->addShow()
        ->addUpdate()
        ->addDestroy();
    $productsGroup = new Group($routes, 'products', '/products');
    $productsGroup->setController(ProductsController::class)
        ->addIndex()
        ->addStore()
        ->addShow()
        ->addUpdate()
        ->addDestroy();
    $ShoppingGroup = new Group($routes, 'shopping', '/shopping');
    $ShoppingGroup->setController(ShoppingController::class)
        ->addIndex()
        ->addStore()
        ->addDestroy();
    $alertsGroup = new Group($routes, 'alerts', '/alerts');
    $alertsGroup->setController(AlertsController::class)
        ->addIndex()
        ->addShow()
        ->addStore()
        ->addDestroy()
        ->addUpdate()
        ->add(
            'activate,',
            new Route('/{id}/activate'),
            new Info([Request::METHOD_POST], 'activate'),
            ['id' => '\d+']
        )
        ->add(
            'deactivate,',
            new Route('/{id}/deactivate'),
            new Info([Request::METHOD_POST], 'deactivate'),
            ['id' => '\d+']
        );
    $shoppingListsGroup = new Group($routes, 'shoppingLists', '/shopping-lists');
    $shoppingListsGroup->setController(ShoppingListsController::class)
        ->addIndex()
        ->addStore()
        ->addEdit()
        ->addUpdate()
        ->add(
            'add',
            new Route('/add'),
            new Info([Request::METHOD_GET], 'showCreate')
        )
        ->addShow();
    $suppliesGroup = new Group($routes, 'supplies', '/supplies');
    $suppliesGroup->setController(SuppliesController::class)
        ->addIndex()
        ->addStore()
        ->addShow()
        ->addUpdate()
        ->addDestroy()
        ->add(
            'alerts.destroy',
            new Route('/alerts/{id}'),
            new Info([Request::METHOD_DELETE], 'deleteAlert'),
            ['id' => '\d+']
        )
        ->add(
            'alerts.create',
            new Route('/alerts/{id}'),
            new Info([Request::METHOD_POST], 'createAlert'),
            ['id' => '\d+']
        );
    $settingsGroup = new Group($routes, 'settings', '/settings');
    $settingsGroup->setController(SettingsController::class)
        ->addIndex()
        ->addStore()
        ->add(
            'generateKey',
            new Route('/api-key/generate'),
            new Info([Request::METHOD_POST], 'generateKey'),
        )
        ->add(
            'deleteKey',
            new Route('/api-key/{id}'),
            new Info([Request::METHOD_DELETE], 'destroyKey'),
            ['id' => '\d+']
        )
        ->add(
            'updateKeyDescription',
            new Route('/api-key/{id}'),
            new Info([Request::METHOD_PATCH], 'updateKeyDescription'),
            ['id' => '\d+']
        )
        ->add(
            'downloadReport',
            new Route('/download-report'),
            new Info([Request::METHOD_GET], 'downloadReport'),
        );
    $supplyGroupsGroup = new Group($routes, 'supplyGroups', 'supply-groups');
    $supplyGroupsGroup->setController(SupplyGroupsController::class)
        ->addIndex()
        ->addStore()
        ->addShow()
        ->addUpdate()
        ->addDestroy();
    $supplyPartsGroup = new Group($routes, 'supplyParts', 'supply-parts');
    $supplyPartsGroup->setController(SupplyPartsController::class)
        ->addDestroy()
        ->addShow()
        ->addUpdate();
    $supplyPartsGroup = new Group($routes, 'supplyParts', 'supplies');
    $supplyPartsGroup->setController(SupplyPartsController::class)
        ->add(
            'store',
            new Route('/{id}'),
            new Info([Request::METHOD_POST], 'store'),
            ['id' => '\d+']
        );
    $resetPasswordGroup = new Group($routes, 'resetPassword', '');
    $resetPasswordGroup->setController(ResetPasswordController::class)
        ->add(
            'showSendEmail',
            new Route('/reset-password'),
            new Info([Request::METHOD_GET], 'showSendEmail')
        )
        ->add(
            'sendEmail',
            new Route('/reset-password'),
            new Info([Request::METHOD_POST], 'sendEmail')
        )
        ->add(
            'showChangePassword',
            new Route('/change-password/{token}'),
            new Info([Request::METHOD_GET], 'showChangePassword')
        )
        ->add(
            'changePassword',
            new Route('/change-password/{token}'),
            new Info([Request::METHOD_POST], 'changePassword')
        );
    $brandsGroup = new Group($routes, 'brands', '/brands');
    $brandsGroup->setController(BrandsController::class)
        ->addIndex()
        ->addStore()
        ->addShow()
        ->addUpdate()
        ->addDestroy();
    $imagesGroup = new Group($routes, 'photos', '/photos');
    $imagesGroup->setController(PhotosController::class)
        ->add(
            'show',
            new Route('/{type}/{id}'),
            new Info([Request::METHOD_GET], 'show'),
            [
                'type' => sprintf('(%s)', implode('|', array_column(PhotoType::cases(), 'value'))),
                'id' => '\d+'
            ]
        );
    return $routes;
};
