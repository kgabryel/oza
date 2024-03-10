<?php

namespace App\Services\Filters;

use App\Entity\User;
use App\Services\UserService;
use Doctrine\ORM\QueryBuilder;
use Symfony\Component\Form\FormFactoryInterface;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\RequestStack;
use Symfony\Component\Routing\RouterInterface;

abstract class Filter
{
    protected QueryBuilder $builder;
    protected FormInterface $form;
    protected int $page;
    protected string $pathName;
    protected Request $request;
    protected array $results;
    protected RouterInterface $router;
    protected User $user;

    public function __construct(
        FormFactoryInterface $factory,
        RequestStack $stack,
        UserService $userService,
        RouterInterface $router,
        string $formName,
        string $pathName
    ) {
        $this->pathName = $pathName;
        $this->form = $factory->create($formName);
        $this->router = $router;
        $this->request = $stack->getCurrentRequest();
        $this->form->handleRequest($this->request);
        $this->user = $userService->getUser();
    }

    public function getForm(): FormInterface
    {
        return $this->form;
    }

    public function getResults(): array
    {
        return $this->results;
    }
}
