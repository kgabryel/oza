<?php

namespace App\Security;

use App\Config\Message\Error\LoginErrors;
use App\Controller\Web\HomeController;
use App\Controller\Web\SecurityController;
use App\Entity\User;
use App\Services\LoginValidator;
use App\Services\SettingsService;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
use Symfony\Component\Security\Core\Exception\AuthenticationException;
use Symfony\Component\Security\Core\Security;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\Security\Core\User\UserProviderInterface;
use Symfony\Component\Security\Csrf\CsrfToken;
use Symfony\Component\Security\Csrf\CsrfTokenManagerInterface;
use Symfony\Component\Security\Guard\Authenticator\AbstractFormLoginAuthenticator;
use Symfony\Component\Security\Guard\PasswordAuthenticatedInterface;
use Symfony\Component\Security\Http\Util\TargetPathTrait;

class FormAuthenticator extends AbstractFormLoginAuthenticator implements PasswordAuthenticatedInterface
{
    use TargetPathTrait;

    private CsrfTokenManagerInterface $csrfTokenManager;
    private EntityManagerInterface $entityManager;
    private SessionInterface $session;
    private UrlGeneratorInterface $urlGenerator;
    private UserPasswordHasherInterface $userPasswordHasher;
    private LoginValidator $validator;

    public function __construct(
        EntityManagerInterface $entityManager,
        UrlGeneratorInterface $urlGenerator,
        CsrfTokenManagerInterface $csrfTokenManager,
        UserPasswordHasherInterface $userPasswordHasher,
        LoginValidator $validator,
        SessionInterface $session
    ) {
        $this->entityManager = $entityManager;
        $this->urlGenerator = $urlGenerator;
        $this->csrfTokenManager = $csrfTokenManager;
        $this->userPasswordHasher = $userPasswordHasher;
        $this->validator = $validator;
        $this->session = $session;
    }

    public function getCredentials(Request $request): array
    {
        $credentials = [
            'email' => $request->request->get('email'),
            'password' => $request->request->get('password'),
            'csrf_token' => $request->request->get('_csrf_token')
        ];
        $request->getSession()->set(Security::LAST_USERNAME, $credentials['email']);

        return $credentials;
    }

    /**
     * Used to upgrade (rehash) the user's password automatically over time.
     *
     * @param $credentials
     *
     * @return string|null
     */
    public function getPassword($credentials): ?string
    {
        return $credentials['password'];
    }

    /**
     * @param mixed $credentials
     * @param UserProviderInterface $userProvider
     *
     * @return UserInterface|void
     */
    public function getUser($credentials, UserProviderInterface $userProvider)
    {
        if (!$this->validator->checkCredentials($credentials)) {
            return;
        }
        $token = new CsrfToken('authenticate', $credentials['csrf_token']);
        if (!$this->csrfTokenManager->isTokenValid($token)) {
            $this->validator->setLoginError(LoginErrors::INVALID_CSRF);

            return;
        }
        /** @var User $user */
        $user = $this->entityManager->getRepository(User::class)
            ->findOneBy([
                'email' => $credentials['email'],
                'userType' => 1
            ]);
        if (!$user) {
            $this->validator->setLoginError(LoginErrors::INVALID_DATA);

            return;
        }
        $settings = $user->getSettings();
        SettingsService::updateSession($this->session, $settings);

        return $user;
    }

    /**
     * @param mixed $credentials
     * @param UserInterface $user
     *
     * @return bool
     */
    public function checkCredentials($credentials, UserInterface $user): bool
    {
        if ($this->userPasswordHasher->isPasswordValid($user, $credentials['password'])) {
            return true;
        }
        $this->validator->setPasswordError(LoginErrors::INVALID_DATA);

        return false;
    }

    /**
     * Override to change what happens after a bad username/password is submitted.
     *
     * @param Request $request
     * @param AuthenticationException $exception
     *
     * @return RedirectResponse
     */
    public function onAuthenticationFailure(Request $request, AuthenticationException $exception): Response
    {
        $this->validator->setErrors($request);

        return new RedirectResponse($this->urlGenerator->generate('login.show'));
    }

    public function onAuthenticationSuccess(
        Request $request,
        TokenInterface $token,
        string $providerKey
    ): RedirectResponse {
        if ($this->getTargetPath($request->getSession(), $providerKey)) {
            return new RedirectResponse($this->getTargetPath($request->getSession(), $providerKey));
        }

        return new RedirectResponse($this->urlGenerator->generate(HomeController::INDEX_URL));
    }

    /**
     * Does the authenticator support the given Request?
     * If this returns false, the authenticator will be skipped.
     *
     * @param Request $request
     *
     * @return bool
     */
    public function supports(Request $request): bool
    {
        return SecurityController::LOGIN_LOGIN_URL === $request->attributes->get('_route')
            && $request->isMethod('POST');
    }

    /**
     * @return string
     */
    protected function getLoginUrl(): string
    {
        return $this->urlGenerator->generate('login.show');
    }
}
