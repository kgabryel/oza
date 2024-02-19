<?php

namespace App\Services\Filters;

use App\Controller\Web\ShopsController;
use App\Form\Filters\ShopFindForm;
use App\Model\Filter\Shop;
use App\Repository\ShopRepository;
use Symfony\Component\Form\FormFactoryInterface;
use Symfony\Component\HttpFoundation\RequestStack;
use Symfony\Component\Routing\RouterInterface;
use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorageInterface;

class ShopFilter extends Filter
{
    public function __construct(
        FormFactoryInterface $factory,
        RequestStack $stack,
        ShopRepository $repository,
        TokenStorageInterface $tokenStorage,
        RouterInterface $router
    ) {
        parent::__construct(
            $factory,
            $stack,
            $repository,
            $tokenStorage,
            $router,
            ShopFindForm::class,
            ShopsController::INDEX_URL
        );
        $data = $this->form->getData() ?? new Shop();
        $this->results = $this->repository->filter($this->user, $data);
    }
}
