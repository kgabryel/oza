<?php

namespace App\Services\Filters;

use App\Controller\Web\ProductsGroupsController;
use App\Form\Filters\ProductsGroupFindForm;
use App\Model\Filter\ProductsGroup;
use App\Repository\ProductsGroupRepository;
use Symfony\Component\Form\FormFactoryInterface;
use Symfony\Component\HttpFoundation\RequestStack;
use Symfony\Component\Routing\RouterInterface;
use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorageInterface;

class ProductsGroupFilter extends Filter
{
    public function __construct(
        FormFactoryInterface $factory,
        RequestStack $stack,
        ProductsGroupRepository $repository,
        TokenStorageInterface $tokenStorage,
        RouterInterface $router
    ) {
        parent::__construct(
            $factory,
            $stack,
            $repository,
            $tokenStorage,
            $router,
            ProductsGroupFindForm::class,
            ProductsGroupsController::INDEX_URL
        );
        $data = $this->form->getData() ?? new ProductsGroup();
        $this->results = $this->repository->filter($this->user, $data);
    }
}
