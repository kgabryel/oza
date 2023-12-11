<?php

namespace App\Services\Filters;

use App\Controller\Web\SuppliesController;
use App\Form\Filters\SupplyFindForm;
use App\Model\Filter\Supply;
use App\Repository\SupplyRepository;
use Symfony\Component\Form\FormFactoryInterface;
use Symfony\Component\HttpFoundation\RequestStack;
use Symfony\Component\Routing\RouterInterface;
use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorageInterface;

class SupplyFilter extends Filter
{
    public function __construct(
        FormFactoryInterface $factory,
        RequestStack $stack,
        SupplyRepository $repository,
        TokenStorageInterface $tokenStorage,
        RouterInterface $router
    ) {
        parent::__construct(
            $factory,
            $stack,
            $repository,
            $tokenStorage,
            $router,
            SupplyFindForm::class,
            SuppliesController::INDEX_URL
        );
        $data = $this->form->getData() ?? new Supply();
        $this->results = $this->repository->filter($this->user, $data);
    }
}
