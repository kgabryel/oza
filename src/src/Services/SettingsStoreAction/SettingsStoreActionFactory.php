<?php

namespace App\Services\SettingsStoreAction;

use App\Services\Factory\ApiKeyFactory;
use App\Services\SettingsService;
use App\Services\UserService;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;
use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorageInterface;

class SettingsStoreActionFactory
{
    private ApiKeyFactory $apiKeyFactory;
    private UrlGeneratorInterface $router;
    private SettingsService $settingsService;
    private TokenStorageInterface $tokenStorage;
    private UserPasswordHasherInterface $userPasswordHasher;
    private UserService $userService;

    public function __construct(
        SettingsService $settingsService,
        TokenStorageInterface $tokenStorage,
        UserPasswordHasherInterface $userPasswordHasher,
        UrlGeneratorInterface $router,
        ApiKeyFactory $apiKeyFactory,
        UserService $userService
    ) {
        $this->settingsService = $settingsService;
        $this->tokenStorage = $tokenStorage;
        $this->userPasswordHasher = $userPasswordHasher;
        $this->router = $router;
        $this->apiKeyFactory = $apiKeyFactory;
        $this->userService = $userService;
    }

    public function get(
        Request $request,
        FormInterface $settingsForm,
        FormInterface $changePasswordForm,
        FormInterface $keyForm
    ): Action {
        if ($request->request->has('settings_form')) {
            return new UpdateSettings($request, $settingsForm, $this->settingsService);
        }

        if ($request->request->has('change_password_form')) {
            return new ChangePassword(
                $request,
                $changePasswordForm,
                $this->tokenStorage,
                $this->settingsService,
                $this->userPasswordHasher,
                $this->router,
                $this->userService
            );
        }

        return new CreateKey($request, $keyForm, $this->apiKeyFactory);
    }
}
