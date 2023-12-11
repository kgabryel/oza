<?php

namespace App\Services;

use App\Entity\User;
use App\Repository\UserRepository;
use App\Services\Factory\SettingsFactory;
use Doctrine\ORM\EntityManagerInterface;
use League\OAuth2\Client\Provider\Facebook;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Session\Session;
use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorageInterface;
use Symfony\Component\Security\Core\Authentication\Token\UsernamePasswordToken;
use Symfony\Component\String\ByteString;

class FBAuthenticator
{
    public const FB_ACCOUNT_TYPE = 2;
    private Facebook $facebook;
    private User $user;
    private array $userInfo;

    public function __construct(Facebook $facebook)
    {
        $this->userInfo = [];
        $this->facebook = $facebook;
    }

    public function authenticate(
        TokenStorageInterface $tokenStorage,
        Session $session
    ): void {
        $token = new UsernamePasswordToken($this->user, null, 'main', $this->user->getRoles());
        $tokenStorage->setToken($token);
        $session->set('_security_main', serialize($token));
    }

    public function createUser(EntityManagerInterface $manager): self
    {
        $this->user = new User();
        $this->user->setUserType(self::FB_ACCOUNT_TYPE);
        $this->user->setEmail($this->userInfo['email'] ?? '');
        $this->user->setName($this->userInfo['name'] ?? '');
        $this->user->setFbId($this->userInfo['id']);
        $this->user->setPassword(ByteString::fromRandom(30)->toString());
        $settings = SettingsFactory::get($this->user);
        $this->user->setSettings($settings);
        $manager->persist($this->user);
        $manager->persist($settings);
        $manager->flush();

        return $this;
    }

    public function getUser(): User
    {
        return $this->user;
    }

    public function setUser(UserRepository $repository): self
    {
        $this->user = $repository->findOneBy([
            'fbId' => $this->userInfo['id'],
            'userType' => self::FB_ACCOUNT_TYPE
        ]);

        return $this;
    }

    public function getUserInfo(): self
    {
        $accessToken = $this->facebook->getAccessToken('authorization_code', [
            'code' => $_GET['code']
        ]);
        $this->userInfo = $this->facebook->getResourceOwner($accessToken)->toArray();

        return $this;
    }

    public function redirect(): RedirectResponse
    {
        return new RedirectResponse($this->facebook->getAuthorizationUrl());
    }

    public function userExists(UserRepository $repository): bool
    {
        return $repository->findOneBy([
                'fbId' => $this->userInfo['id'],
                'userType' => self::FB_ACCOUNT_TYPE
            ]) !== null;
    }
}
