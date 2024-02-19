<?php

namespace App\Services\Filters;

use App\Controller\Web\ShoppingController;
use App\Form\Filters\ShoppingFindForm;
use App\Model\Filter\Shopping;
use App\Repository\ShoppingRepository;
use Symfony\Component\Form\FormFactoryInterface;
use Symfony\Component\HttpFoundation\RequestStack;
use Symfony\Component\Routing\RouterInterface;
use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorageInterface;

class ShoppingFilter extends Filter
{
    public function __construct(
        FormFactoryInterface $factory,
        RequestStack $stack,
        ShoppingRepository $repository,
        TokenStorageInterface $tokenStorage,
        RouterInterface $router
    ) {
        parent::__construct(
            $factory,
            $stack,
            $repository,
            $tokenStorage,
            $router,
            ShoppingFindForm::class,
            ShoppingController::INDEX_URL
        );
        $data = $this->form->getData() ?? new Shopping();
        $this->results = $this->repository->filter($this->user, $data);
    }
}
