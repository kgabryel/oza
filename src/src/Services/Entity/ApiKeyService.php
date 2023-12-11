<?php

namespace App\Services\Entity;

use App\Config\Message\SettingsMessages;
use App\Controller\Web\BaseController;
use App\Entity\ApiKey;
use App\Model\Form\ApiKeyDescription;
use App\Repository\ApiKeyRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Session\Flash\FlashBagInterface;
use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorageInterface;

class ApiKeyService extends EntityService
{
    private ApiKey $apiKey;
    private ApiKeyRepository $apiKeyRepository;

    public function __construct(
        FlashBagInterface $flashBag,
        EntityManagerInterface $entityManager,
        TokenStorageInterface $tokenStorage,
        ApiKeyRepository $apiKeyRepository
    ) {
        parent::__construct($flashBag, $entityManager, $tokenStorage);
        $this->apiKeyRepository = $apiKeyRepository;
    }

    public function find(int $id): bool
    {
        $apiKey = $this->apiKeyRepository->findById($id, $this->user);
        if ($apiKey === null) {
            return false;
        }
        $this->apiKey = $apiKey;

        return true;
    }

    public function remove(): void
    {
        $this->flashBag->add(BaseController::SUCCESS_MESSAGE, SettingsMessages::DELETE_CORRECT);
        $this->removeEntity($this->apiKey);
    }

    public function switch(): void
    {
        $this->apiKey->isActive() ? $this->apiKey->deactivate() : $this->apiKey->activate();
        $this->saveEntity($this->apiKey);
    }

    public function updateDescription(FormInterface $form, Request $request): bool
    {
        $form->handleRequest($request);
        if (!$form->isSubmitted() || !$form->isValid()) {
            return false;
        }
        /** @var ApiKeyDescription $data */
        $data = $form->getData();
        $this->apiKey->setDescription($data->getDescription());
        $this->saveEntity($this->apiKey);

        return true;
    }
}
