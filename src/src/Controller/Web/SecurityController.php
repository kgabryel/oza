<?php

namespace App\Controller\Web;

use App\Form\RegisterForm;
use App\Services\RegistrationService;
use App\Services\SettingsService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Session\SessionInterface;

final class SecurityController extends AbstractController
{
    public const LOGIN_LOGIN_URL = 'login.login';
    public const LOGIN_SHOW_URL = 'login.show';
    public const LOGIN_TEMPLATE = 'security/login.html.twig';
    public const REGISTER_TEMPLATE = 'security/register.html.twig';

    public function logout(): void
    {
    }

    public function register(Request $request, RegistrationService $registration, SessionInterface $session): Response
    {
        $form = $this->createForm(
            RegisterForm::class,
            null,
            [
                'csrf_protection' => false
            ]
        );
        $form->handleRequest($request);
        if ($form->isValid()) {
            $user = $registration->register($form->getData());
            $settings = $user->getSettings();
            SettingsService::updateSession($session, $settings);

            return $registration->authenticate($user);
        }

        return $this->render(self::REGISTER_TEMPLATE, [
            'form' => $form->createView()
        ]);
    }

    public function showLogin(): Response
    {
        return $this->render(self::LOGIN_TEMPLATE);
    }

    public function showRegister(): Response
    {
        $form = $this->createForm(RegisterForm::class);

        return $this->render(self::REGISTER_TEMPLATE, [
            'form' => $form->createView()
        ]);
    }
}
