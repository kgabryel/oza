<?php

namespace App\Services\Filters;

use App\Controller\Web\SupplyGroupsController;
use App\Form\Filters\SupplyGroupFindForm;
use App\Model\Filter\SupplyGroup;
use App\Repository\SupplyGroupRepository;
use App\Services\UserService;
use Symfony\Component\Form\FormFactoryInterface;
use Symfony\Component\HttpFoundation\RequestStack;
use Symfony\Component\Routing\RouterInterface;

class SupplyGroupFilter extends Filter
{
    public function __construct(
        FormFactoryInterface $factory,
        RequestStack $stack,
        SupplyGroupRepository $repository,
        UserService $userService,
        RouterInterface $router
    ) {
        parent::__construct(
            $factory,
            $stack,
            $userService,
            $router,
            SupplyGroupFindForm::class,
            SupplyGroupsController::INDEX_URL
        );
        $data = $this->form->getData() ?? new SupplyGroup();
        $this->results = $repository->filter($this->user, $data);
    }
}
