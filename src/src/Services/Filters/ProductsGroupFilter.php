<?php

namespace App\Services\Filters;

use App\Controller\Web\ProductsGroupsController;
use App\Form\Filters\ProductsGroupFindForm;
use App\Model\Filter\ProductsGroup;
use App\Repository\ProductsGroupRepository;
use App\Services\UserService;
use Symfony\Component\Form\FormFactoryInterface;
use Symfony\Component\HttpFoundation\RequestStack;
use Symfony\Component\Routing\RouterInterface;

class ProductsGroupFilter extends Filter
{
    public function __construct(
        FormFactoryInterface $factory,
        RequestStack $stack,
        ProductsGroupRepository $repository,
        UserService $userService,
        RouterInterface $router
    ) {
        parent::__construct(
            $factory,
            $stack,
            $userService,
            $router,
            ProductsGroupFindForm::class,
            ProductsGroupsController::INDEX_URL
        );
        $data = $this->form->getData() ?? new ProductsGroup();
        $this->results = $repository->filter($this->user, $data);
    }
}
