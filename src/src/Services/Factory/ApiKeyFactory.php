<?php

namespace App\Services\Factory;

use App\Config\Message\SettingsMessages;
use App\Controller\Web\BaseController;
use App\Entity\ApiKey;
use App\Model\Form\ApiKey as ApiKeyModel;
use Doctrine\DBAL\Exception\UniqueConstraintViolationException;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\String\ByteString;

class ApiKeyFactory extends EntityFactory
{
    public function create(FormInterface $form, Request $request): bool
    {
        $form->handleRequest($request);
        if (!$form->isSubmitted() || !$form->isValid()) {
            return false;
        }
        /** @var ApiKeyModel $data */
        $data = $form->getData();
        $apiKey = new ApiKey();
        $apiKey->setUser($this->user);
        $apiKey->setKey($data->getKey());
        $apiKey->activate();
        $apiKey->setApplication($data->getApplication());
        $this->saveEntity($apiKey);
        $this->flashBag->add(BaseController::SUCCESS_MESSAGE, SettingsMessages::KEY_CREATED_CORRECTLY);

        return true;
    }

    public function generate(): void
    {
        $apiKey = new ApiKey();
        $apiKey->deactivate();
        $apiKey->setUser($this->user);
        $apiKey->setDescription(null);
        $saved = false;
        $failCount = 0;
        while (!$saved && $failCount < 10) {
            $apiKey->setKey(ByteString::fromRandom(128)->toString());
            try {
                $this->saveEntity($apiKey);
                $saved = true;
            } catch (UniqueConstraintViolationException) {
                $failCount++;
            }
        }
        if ($saved) {
            $this->flashBag->add(BaseController::SUCCESS_MESSAGE, SettingsMessages::KEY_GENERATED);
        } else {
            $this->flashBag->add(BaseController::ERROR_MESSAGE, SettingsMessages::KEY_NOT_GENERATED);
        }
    }
}
