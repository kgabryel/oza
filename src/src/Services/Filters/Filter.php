<?php

namespace App\Services\Filters;

use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\ORM\QueryBuilder;
use Symfony\Component\Form\FormFactoryInterface;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\RequestStack;
use Symfony\Component\Routing\RouterInterface;
use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorageInterface;
use Symfony\Component\Security\Core\User\UserInterface;

abstract class Filter
{
    protected QueryBuilder $builder;
    protected FormInterface $form;
    protected int $page;
    protected string $pathName;
    protected ServiceEntityRepository $repository;
    protected Request $request;
    protected array $results;
    protected RouterInterface $router;
    protected UserInterface $user;

    public function __construct(
        FormFactoryInterface $factory,
        RequestStack $stack,
        ServiceEntityRepository $repository,
        TokenStorageInterface $tokenStorage,
        RouterInterface $router,
        string $formName,
        string $pathName
    ) {
        $this->pathName = $pathName;
        $this->form = $factory->create($formName);
        $this->router = $router;
        $this->repository = $repository;
        $this->request = $stack->getCurrentRequest();
        $this->form->handleRequest($this->request);
        $this->user = $tokenStorage->getToken()->getUser();
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
