<?php

namespace App\Services\Filters;

use App\Controller\Web\ProductsController;
use App\Form\Filters\ProductFindForm;
use App\Model\Filter\Product;
use App\Repository\ProductRepository;
use Symfony\Component\Form\FormFactoryInterface;
use Symfony\Component\HttpFoundation\RequestStack;
use Symfony\Component\Routing\RouterInterface;
use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorageInterface;

class ProductFilter extends Filter
{
    public function __construct(
        FormFactoryInterface $factory,
        RequestStack $stack,
        ProductRepository $repository,
        TokenStorageInterface $tokenStorage,
        RouterInterface $router
    ) {
        parent::__construct(
            $factory,
            $stack,
            $repository,
            $tokenStorage,
            $router,
            ProductFindForm::class,
            ProductsController::INDEX_URL
        );
        $data = $this->form->getData() ?? new Product();
        $this->results = $this->repository->filter($this->user, $data);
    }
}
