<?php

namespace App\Controller\Web;

use App\Config\Message\SettingsMessages;
use App\Entity\ApiKey;
use App\Form\ApiKeyForm;
use App\Form\ChangePasswordForm;
use App\Form\EditApiKeyForm;
use App\Form\SettingsForm;
use App\Model\Form\ApiKeyDescription;
use App\Model\Form\Settings;
use App\Repository\SupplyRepository;
use App\Services\Entity\ApiKeyService;
use App\Services\Factory\ApiKeyFactory;
use App\Services\SettingsStoreAction\SettingsStoreActionFactory;
use App\Services\SupplyReport\SupplyReportService;
use App\ViewData\Settings\IndexViewData;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Session\SessionInterface;

final class SettingsController extends BaseController
{
    public const INDEX_TEMPLATE = 'settings/index';
    public const INDEX_URL = 'settings.index';

    public function destroyKey(int $id, ApiKeyService $apiKeyService): Response
    {
        if ($apiKeyService->find($id)) {
            $apiKeyService->remove();
        } else {
            $this->addErrorMessage(SettingsMessages::DELETE_INCORRECT);
        }

        return $this->redirect($this->generateUrl(self::INDEX_URL));
    }

    public function downloadReport(
        SupplyReportService $supplyReportService,
        SupplyRepository $supplyRepository
    ): Response {
        return $supplyReportService->download(
            $supplyReportService->create($supplyRepository->findForUser($this->getUser()))
        );
    }

    public function generateKey(ApiKeyFactory $apiKeyFactory): Response
    {
        $apiKeyFactory->generate();

        return $this->redirectBack();
    }

    public function index(SessionInterface $session, IndexViewData $viewData): Response
    {
        $keys = $this->getUser()->getApiKeys();
        $viewData->addKeys($keys);
        $keys = $keys->toArray();
        array_walk($keys, fn(ApiKey $apiKey) => $viewData->addDescriptionForm(
            $this->createForm(EditApiKeyForm::class, ApiKeyDescription::fromEntity($apiKey)),
            $apiKey->getId()
        ));
        $viewData->addSettingsForm($this->createForm(SettingsForm::class, Settings::fromSession($session)));
        $viewData->addKeyForm($this->createForm(ApiKeyForm::class));
        $viewData->addChangePasswordForm($this->createForm(ChangePasswordForm::class));

        return $this->render(self::INDEX_TEMPLATE, $viewData->getOptions());
    }

    public function store(IndexViewData $viewData, SettingsStoreActionFactory $actionFactory): Response
    {
        $settingsForm = $this->createForm(SettingsForm::class);
        $keyForm = $this->createForm(ApiKeyForm::class);
        $changePasswordForm = $this->createForm(ChangePasswordForm::class);
        $action = $actionFactory->get($this->request, $settingsForm, $changePasswordForm, $keyForm);
        if ($action->execute()) {
            return $action->onSuccess();
        }
        $keys = $this->getUser()->getApiKeys();
        $viewData->addKeys($keys);
        $keys = $keys->toArray();
        array_walk($keys, fn(ApiKey $apiKey) => $viewData->addDescriptionForm(
            $this->createForm(EditApiKeyForm::class, ApiKeyDescription::fromEntity($apiKey)),
            $apiKey->getId()
        ));
        $viewData->addSettingsForm($settingsForm);
        $viewData->addKeyForm($keyForm);
        $viewData->addChangePasswordForm($changePasswordForm);

        return $this->render(self::INDEX_TEMPLATE, $viewData->getOptions());
    }

    public function updateKeyDescription(int $id, ApiKeyService $apiKeyService): Response
    {
        $form = $this->createForm(EditApiKeyForm::class, null, [
            'method' => Request::METHOD_PATCH
        ]);
        if (!$apiKeyService->find($id)) {
            $this->addErrorMessage(SettingsMessages::UPDATE_INCORRECT);
        } elseif ($apiKeyService->updateDescription($form, $this->request)) {
            $this->addSuccessMessage(SettingsMessages::UPDATE_CORRECT);
        } else {
            $this->addErrorMessage(SettingsMessages::UPDATE_INCORRECT);
        }

        return $this->redirect($this->generateUrl(self::INDEX_URL));
    }

    protected function getActive(): int
    {
        return 0;
    }
}
