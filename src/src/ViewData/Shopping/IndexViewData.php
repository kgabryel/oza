<?php

namespace App\ViewData\Shopping;

use App\Config\TableId;
use App\Config\TableName;
use App\Config\ViewParameters;
use App\Entity\Shopping;
use App\Repository\ProductRepository;
use App\Repository\ProductsGroupRepository;
use App\Services\Filters\ShoppingFilter;
use App\Services\Transformer\ShoppingTransformer;
use App\Services\UserService;
use App\Utils\FormUtils;
use App\ViewData\IndexViewData as BasicIndexViewData;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use Symfony\Component\Routing\RouterInterface;

class IndexViewData extends BasicIndexViewData
{
    public function __construct(
        SessionInterface $session,
        ShoppingFilter $filter,
        ProductsGroupRepository $productsGroupRepository,
        ProductRepository $productRepository,
        UserService $userService,
        RouterInterface $router
    ) {
        parent::__construct($session, $filter, TableName::SHOPPING_NAME, TableId::SHOPPING);
        $user = $userService->getUser();
        $this->options[ViewParameters::PRODUCTS_GROUPS] = FormUtils::productsGroupSelectOptions(
            $productsGroupRepository->findBy(['user' => $user], ['id' => 'DESC'])
        );
        $this->options[ViewParameters::PRODUCTS] = FormUtils::productSelectOptions(
            $productRepository->findBy(['user' => $user], ['id' => 'DESC'])
        );
        $this->options[ViewParameters::ENTITIES] = array_map(
            static fn(Shopping $shopping): array => ShoppingTransformer::toFullArray(
                $shopping,
                $router
            ),
            $filter->getResults()
        );
    }
}
