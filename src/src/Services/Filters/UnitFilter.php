<?php

namespace App\Services\Filters;

use App\Controller\Web\UnitsController;
use App\Form\Filters\UnitFindForm;
use App\Model\Filter\Unit;
use App\Repository\UnitRepository;
use App\Services\UserService;
use Symfony\Component\Form\FormFactoryInterface;
use Symfony\Component\HttpFoundation\RequestStack;
use Symfony\Component\Routing\RouterInterface;

class UnitFilter extends Filter
{
    public function __construct(
        FormFactoryInterface $factory,
        RequestStack $stack,
        UnitRepository $repository,
        UserService $userService,
        RouterInterface $router
    ) {
        parent::__construct(
            $factory,
            $stack,
            $userService,
            $router,
            UnitFindForm::class,
            UnitsController::INDEX_URL
        );
        $data = $this->form->getData() ?? new Unit();
        $this->results = $repository->filter($this->user, $data);
    }
}
