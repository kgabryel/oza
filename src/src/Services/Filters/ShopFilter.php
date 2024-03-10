<?php

namespace App\Services\Filters;

use App\Controller\Web\ShopsController;
use App\Form\Filters\ShopFindForm;
use App\Model\Filter\Shop;
use App\Repository\ShopRepository;
use App\Services\UserService;
use Symfony\Component\Form\FormFactoryInterface;
use Symfony\Component\HttpFoundation\RequestStack;
use Symfony\Component\Routing\RouterInterface;

class ShopFilter extends Filter
{
    public function __construct(
        FormFactoryInterface $factory,
        RequestStack $stack,
        ShopRepository $repository,
        UserService $userService,
        RouterInterface $router
    ) {
        parent::__construct(
            $factory,
            $stack,
            $userService,
            $router,
            ShopFindForm::class,
            ShopsController::INDEX_URL
        );
        $data = $this->form->getData() ?? new Shop();
        $this->results = $repository->filter($this->user, $data);
    }
}
