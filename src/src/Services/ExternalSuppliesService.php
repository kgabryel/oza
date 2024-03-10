<?php

namespace App\Services;

use App\Config\Message\SupplyMessages;
use App\Controller\Web\BaseController;
use App\Entity\ApiKey;
use App\Entity\Application;
use App\Entity\Supply;
use Doctrine\Common\Collections\ReadableCollection;
use Exception;
use Symfony\Component\HttpClient\Exception\ClientException;
use Symfony\Component\HttpClient\Exception\TransportException;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Session\Flash\FlashBagInterface;
use Symfony\Contracts\HttpClient\HttpClientInterface;

class ExternalSuppliesService
{
    private const URL = 'api/oza/supplies/';
    private HttpClientInterface $client;
    private FlashBagInterface $flashBag;
    private ReadableCollection $keys;

    public function __construct(UserService $userService, HttpClientInterface $client, FlashBagInterface $flashBag)
    {
        $this->keys = $userService
            ->getUser()
            ->getApiKeys()
            ->filter(static fn(ApiKey $apiKey): bool => $apiKey->getApplication() !== null);
        $this->client = $client;
        $this->flashBag = $flashBag;
    }

    public function disconnect(Supply $supply): void
    {
        if ($this->keys->isEmpty()) {
            return;
        }
        /** @var ApiKey $key */
        foreach ($this->keys as $key) {
            try {
                $this->sendDeleteRequest($key, $supply);
            } catch (ClientException|TransportException $exception) {
                $this->handleException($exception);
            }
        }
    }

    private function sendDeleteRequest(ApiKey $key, Supply $supply): void
    {
        /** @var Application $application */
        $application = $key->getApplication();
        $this->client->request(
            Request::METHOD_DELETE,
            sprintf('%s%s%s', $application->getHref(), self::URL, $supply->getId()),
            [
                'headers' => [
                    'X-AUTH-TOKEN' => $key->getKey()
                ]
            ]
        );
    }

    private function handleException(Exception $exception): void
    {
        if ($exception->getCode() !== 404) {
            $this->flashBag->add(BaseController::ERROR_MESSAGE, SupplyMessages::SUPPLY_UPDATE_ERROR);
        }
    }

    public function update(Supply $supply): void
    {
        if ($this->keys->isEmpty()) {
            return;
        }
        /** @var ApiKey $key */
        foreach ($this->keys as $key) {
            try {
                $this->sendUpdateRequest($key, $supply);
            } catch (ClientException|TransportException $exception) {
                $this->handleException($exception);
            }
        }
    }

    private function sendUpdateRequest(ApiKey $key, Supply $supply): void
    {
        /** @var Application $application */
        $application = $key->getApplication();
        $this->client->request(
            Request::METHOD_PATCH,
            sprintf('%s%s%s', $application->getHref(), self::URL, $supply->getId()),
            [
                'headers' => [
                    'X-AUTH-TOKEN' => $key->getKey()
                ],
                'body' => [
                    'available' => $supply->getAmount() > 0
                ]
            ]
        );
    }
}
