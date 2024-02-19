<?php

namespace App\Controller\Web;

use App\Config\Message\Error\LoginErrors;
use App\Repository\UserRepository;
use App\Services\FBAuthenticator;
use App\Services\FBFactory;
use App\Services\SettingsService;
use Doctrine\ORM\EntityManagerInterface;
use Exception;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Session\Session;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;
use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorageInterface;

final class FBController extends AbstractController
{
    private const FB_URL = 'fb.login';
    private const HOME_URL = 'home.index';

    public function __construct(SessionInterface $session)
    {
        $session->start();
    }

    public function auth(): RedirectResponse
    {
        $authenticator = new FBAuthenticator(
            FBFactory::getInstance(
                $this->generateUrl(self::FB_URL, [], UrlGeneratorInterface::ABSOLUTE_URL)
            )
        );

        return $authenticator->redirect();
    }

    public function login(
        EntityManagerInterface $manager,
        TokenStorageInterface $tokenStorage,
        Session $session,
        UserRepository $repository
    ): RedirectResponse {
        $authenticator = new FBAuthenticator(
            FBFactory::getInstance(
                $this->generateUrl(self::FB_URL, [], UrlGeneratorInterface::ABSOLUTE_URL)
            )
        );
        try {
            $authenticator->getUserInfo();
        } catch (Exception) {
            $this->addFlash(BaseController::ERROR_MESSAGE, LoginErrors::FB_ERROR);

            return new RedirectResponse($this->generateUrl(SecurityController::LOGIN_SHOW_URL));
        }
        if (!$authenticator->userExists($repository)) {
            $authenticator->createUser($manager);
        } else {
            $authenticator->setUser($repository);
        }
        $user = $authenticator->getUser();
        $settings = $user->getSettings();
        SettingsService::updateSession($session, $settings);
        $authenticator->authenticate($tokenStorage, $session);

        return new RedirectResponse($this->generateUrl(self::HOME_URL));
    }
}
