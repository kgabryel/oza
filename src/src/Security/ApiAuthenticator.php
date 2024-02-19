<?php

namespace App\Security;

use App\Entity\User;
use App\Repository\ApiKeyRepository;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
use Symfony\Component\Security\Core\Exception\AuthenticationException;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\Security\Core\User\UserProviderInterface;
use Symfony\Component\Security\Guard\AbstractGuardAuthenticator;

class ApiAuthenticator extends AbstractGuardAuthenticator
{
    private const AUTH_HEADER = 'X-AUTH-TOKEN';
    private const AVAILABLE_PATH = '/api/supplies';
    private ApiKeyRepository $apiKeyRepository;

    public function __construct(ApiKeyRepository $apiKeyRepository)
    {
        $this->apiKeyRepository = $apiKeyRepository;
    }

    public function checkCredentials($credentials, UserInterface $user): bool
    {
        return true;
    }

    public function getCredentials(Request $request)
    {
        return $request->headers->get(self::AUTH_HEADER);
    }

    public function getUser($credentials, UserProviderInterface $userProvider): ?User
    {
        $key = $this->apiKeyRepository->findOneBy([
            'key' => $credentials,
            'active' => true
        ]);

        return $key?->getUser();
    }

    public function onAuthenticationFailure(Request $request, AuthenticationException $exception): Response
    {
        return new Response(null, Response::HTTP_UNAUTHORIZED);
    }

    public function onAuthenticationSuccess(Request $request, TokenInterface $token, string $providerKey): void
    {
        return;
    }

    public function start(Request $request, AuthenticationException $authException = null)
    {
        return null;
    }

    public function supports(Request $request): bool
    {
        return $request->headers->has(self::AUTH_HEADER) && $request->isMethod(Request::METHOD_GET)
            && str_starts_with($request->getRequestUri(), self::AVAILABLE_PATH);
    }

    public function supportsRememberMe(): bool
    {
        return false;
    }
}
