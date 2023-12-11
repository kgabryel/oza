<?php

namespace App\Services\Filters;

use App\Controller\Web\AlertsController;
use App\Form\Filters\AlertFindForm;
use App\Model\Filter\Alert;
use App\Repository\AlertRepository;
use Symfony\Component\Form\FormFactoryInterface;
use Symfony\Component\HttpFoundation\RequestStack;
use Symfony\Component\Routing\RouterInterface;
use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorageInterface;

class AlertFilter extends Filter
{
    public function __construct(
        FormFactoryInterface $factory,
        RequestStack $stack,
        AlertRepository $repository,
        TokenStorageInterface $tokenStorage,
        RouterInterface $router
    ) {
        parent::__construct(
            $factory,
            $stack,
            $repository,
            $tokenStorage,
            $router,
            AlertFindForm::class,
            AlertsController::INDEX_URL
        );
        $data = $this->form->getData() ?? new Alert();
        $this->results = $this->repository->filter($this->user, $data);
    }
}
