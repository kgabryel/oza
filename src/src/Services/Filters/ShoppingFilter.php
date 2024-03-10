<?php

namespace App\Services\Filters;

use App\Controller\Web\ShoppingController;
use App\Form\Filters\ShoppingFindForm;
use App\Model\Filter\Shopping;
use App\Repository\ShoppingRepository;
use App\Services\UserService;
use Symfony\Component\Form\FormFactoryInterface;
use Symfony\Component\HttpFoundation\RequestStack;
use Symfony\Component\Routing\RouterInterface;

class ShoppingFilter extends Filter
{
    public function __construct(
        FormFactoryInterface $factory,
        RequestStack $stack,
        ShoppingRepository $repository,
        UserService $userService,
        RouterInterface $router
    ) {
        parent::__construct(
            $factory,
            $stack,
            $userService,
            $router,
            ShoppingFindForm::class,
            ShoppingController::INDEX_URL
        );
        $data = $this->form->getData() ?? new Shopping();
        $this->results = $repository->filter($this->user, $data);
    }
}
