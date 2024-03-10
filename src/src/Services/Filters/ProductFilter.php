<?php

namespace App\Services\Filters;

use App\Controller\Web\ProductsController;
use App\Form\Filters\ProductFindForm;
use App\Model\Filter\Product;
use App\Repository\ProductRepository;
use App\Services\UserService;
use Symfony\Component\Form\FormFactoryInterface;
use Symfony\Component\HttpFoundation\RequestStack;
use Symfony\Component\Routing\RouterInterface;

class ProductFilter extends Filter
{
    public function __construct(
        FormFactoryInterface $factory,
        RequestStack $stack,
        ProductRepository $repository,
        UserService $userService,
        RouterInterface $router
    ) {
        parent::__construct(
            $factory,
            $stack,
            $userService,
            $router,
            ProductFindForm::class,
            ProductsController::INDEX_URL
        );
        $data = $this->form->getData() ?? new Product();
        $this->results = $repository->filter($this->user, $data);
    }
}
