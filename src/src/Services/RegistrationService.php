<?php

namespace App\Services;

use App\Entity\User;
use App\Model\Form\UserModel;
use App\Security\FormAuthenticator;
use App\Services\Factory\SettingsFactory;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\RequestStack;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\Security\Guard\GuardAuthenticatorHandler;

class RegistrationService
{
    private const PROVIDER = 'main';
    private FormAuthenticator $authenticator;
    private GuardAuthenticatorHandler $guardHandler;
    private EntityManagerInterface $manager;
    private Request $request;
    private UserPasswordHasherInterface $userPasswordHasher;

    public function __construct(
        RequestStack $requestStack,
        GuardAuthenticatorHandler $guardHandler,
        FormAuthenticator $authenticator,
        EntityManagerInterface $manager,
        UserPasswordHasherInterface $userPasswordHasher
    ) {
        $this->request = $requestStack->getCurrentRequest();
        $this->guardHandler = $guardHandler;
        $this->authenticator = $authenticator;
        $this->manager = $manager;
        $this->userPasswordHasher = $userPasswordHasher;
    }

    public function authenticate(UserInterface $user): Response
    {
        return $this->guardHandler->authenticateUserAndHandleSuccess(
            $user,
            $this->request,
            $this->authenticator,
            self::PROVIDER
        );
    }

    public function register(UserModel $model): User
    {
        $user = $model->getUser($this->userPasswordHasher);
        $settings = SettingsFactory::get($user);
        $user->setSettings($settings);
        $this->manager->persist($settings);
        $this->manager->persist($user);
        $this->manager->flush();

        return $user;
    }
}
