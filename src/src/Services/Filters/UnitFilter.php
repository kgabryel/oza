<?php

namespace App\Services\Filters;

use App\Controller\Web\UnitsController;
use App\Form\Filters\UnitFindForm;
use App\Model\Filter\Unit;
use App\Repository\UnitRepository;
use Symfony\Component\Form\FormFactoryInterface;
use Symfony\Component\HttpFoundation\RequestStack;
use Symfony\Component\Routing\RouterInterface;
use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorageInterface;

class UnitFilter extends Filter
{
    public function __construct(
        FormFactoryInterface $factory,
        RequestStack $stack,
        UnitRepository $repository,
        TokenStorageInterface $tokenStorage,
        RouterInterface $router
    ) {
        parent::__construct(
            $factory,
            $stack,
            $repository,
            $tokenStorage,
            $router,
            UnitFindForm::class,
            UnitsController::INDEX_URL
        );
        $data = $this->form->getData() ?? new Unit();
        $this->results = $this->repository->filter($this->user, $data);
    }
}
