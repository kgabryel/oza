<?php

namespace App\Services\Filters;

use App\Controller\Web\SuppliesController;
use App\Form\Filters\SupplyFindForm;
use App\Model\Filter\Supply;
use App\Repository\SupplyRepository;
use App\Services\UserService;
use Symfony\Component\Form\FormFactoryInterface;
use Symfony\Component\HttpFoundation\RequestStack;
use Symfony\Component\Routing\RouterInterface;

class SupplyFilter extends Filter
{
    public function __construct(
        FormFactoryInterface $factory,
        RequestStack $stack,
        SupplyRepository $repository,
        UserService $userService,
        RouterInterface $router
    ) {
        parent::__construct(
            $factory,
            $stack,
            $userService,
            $router,
            SupplyFindForm::class,
            SuppliesController::INDEX_URL
        );
        $data = $this->form->getData() ?? new Supply();
        $this->results = $repository->filter($this->user, $data);
    }
}
