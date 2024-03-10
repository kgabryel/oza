<?php

namespace App\Services\Filters;

use App\Controller\Web\AlertsController;
use App\Form\Filters\AlertFindForm;
use App\Model\Filter\Alert;
use App\Repository\AlertRepository;
use App\Services\UserService;
use Symfony\Component\Form\FormFactoryInterface;
use Symfony\Component\HttpFoundation\RequestStack;
use Symfony\Component\Routing\RouterInterface;

class AlertFilter extends Filter
{
    public function __construct(
        FormFactoryInterface $factory,
        RequestStack $stack,
        AlertRepository $repository,
        UserService $userService,
        RouterInterface $router
    ) {
        parent::__construct(
            $factory,
            $stack,
            $userService,
            $router,
            AlertFindForm::class,
            AlertsController::INDEX_URL
        );
        $data = $this->form->getData() ?? new Alert();
        $this->results = $repository->filter($this->user, $data);
    }
}
