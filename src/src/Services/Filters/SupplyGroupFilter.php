<?php

namespace App\Services\Filters;

use App\Controller\Web\SupplyGroupsController;
use App\Form\Filters\SupplyGroupFindForm;
use App\Model\Filter\SupplyGroup;
use App\Repository\SupplyGroupRepository;
use Symfony\Component\Form\FormFactoryInterface;
use Symfony\Component\HttpFoundation\RequestStack;
use Symfony\Component\Routing\RouterInterface;
use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorageInterface;

class SupplyGroupFilter extends Filter
{
    public function __construct(
        FormFactoryInterface $factory,
        RequestStack $stack,
        SupplyGroupRepository $repository,
        TokenStorageInterface $tokenStorage,
        RouterInterface $router
    ) {
        parent::__construct(
            $factory,
            $stack,
            $repository,
            $tokenStorage,
            $router,
            SupplyGroupFindForm::class,
            SupplyGroupsController::INDEX_URL
        );
        $data = $this->form->getData() ?? new SupplyGroup();
        $this->results = $this->repository->filter($this->user, $data);
    }
}
