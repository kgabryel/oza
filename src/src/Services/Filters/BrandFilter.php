<?php

namespace App\Services\Filters;

use App\Controller\Web\BrandsController;
use App\Form\Filters\BrandFindForm;
use App\Model\Filter\Brand;
use App\Repository\BrandRepository;
use Symfony\Component\Form\FormFactoryInterface;
use Symfony\Component\HttpFoundation\RequestStack;
use Symfony\Component\Routing\RouterInterface;
use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorageInterface;

class BrandFilter extends Filter
{
    public function __construct(
        FormFactoryInterface $factory,
        RequestStack $stack,
        BrandRepository $repository,
        TokenStorageInterface $tokenStorage,
        RouterInterface $router
    ) {
        parent::__construct(
            $factory,
            $stack,
            $repository,
            $tokenStorage,
            $router,
            BrandFindForm::class,
            BrandsController::INDEX_URL
        );
        $data = $this->form->getData() ?? new Brand();
        $this->results = $this->repository->filter($this->user, $data);
    }
}
