<?php

namespace App\Services\Filters;

use App\Controller\Web\BrandsController;
use App\Form\Filters\BrandFindForm;
use App\Model\Filter\Brand;
use App\Repository\BrandRepository;
use App\Services\UserService;
use Symfony\Component\Form\FormFactoryInterface;
use Symfony\Component\HttpFoundation\RequestStack;
use Symfony\Component\Routing\RouterInterface;

class BrandFilter extends Filter
{
    public function __construct(
        FormFactoryInterface $factory,
        RequestStack $stack,
        BrandRepository $repository,
        UserService $userService,
        RouterInterface $router
    ) {
        parent::__construct(
            $factory,
            $stack,
            $userService,
            $router,
            BrandFindForm::class,
            BrandsController::INDEX_URL
        );
        $data = $this->form->getData() ?? new Brand();
        $this->results = $repository->filter($this->user, $data);
    }
}
