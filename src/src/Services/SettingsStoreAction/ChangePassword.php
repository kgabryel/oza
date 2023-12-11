<?php

namespace App\Services\SettingsStoreAction;

use App\Services\SettingsService;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;
use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorageInterface;

class ChangePassword extends Action
{
    private UrlGeneratorInterface $router;
    private SettingsService $settingsService;
    private TokenStorageInterface $tokenStorage;
    private UserPasswordHasherInterface $userPasswordHasher;

    public function __construct(
        Request $request,
        FormInterface $form,
        TokenStorageInterface $tokenStorage,
        SettingsService $settingsService,
        UserPasswordHasherInterface $userPasswordHasher,
        UrlGeneratorInterface $router
    ) {
        parent::__construct($request, $form);
        $this->tokenStorage = $tokenStorage;
        $this->settingsService = $settingsService;
        $this->userPasswordHasher = $userPasswordHasher;
        $this->router = $router;
    }

    public function execute(): bool
    {
        if ($this->tokenStorage->getToken()->getUser()->getFbId() !== null) {
            return false;
        }

        return $this->settingsService->changePassword($this->form, $this->request, $this->userPasswordHasher);
    }

    public function onSuccess(): Response
    {
        $this->request->getSession()->clear();
        $this->tokenStorage->setToken();

        return new RedirectResponse($this->router->generate('login.login'), 302);
    }
}
