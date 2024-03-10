<?php

namespace App\Controller\Web;

use App\Config\Message\AlertMessages;
use App\Form\AlertForm;
use App\Form\EditAlertForm;
use App\Model\Form\EditAlert;
use App\Services\Entity\AlertService;
use App\Services\Factory\AlertFactory;
use App\ViewData\Alerts\EditViewData;
use App\ViewData\Alerts\IndexViewData;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

final class AlertsController extends BaseController
{
    public const EDIT_TEMPLATE = 'alerts/edit';
    public const INDEX_TEMPLATE = 'alerts/index';
    public const INDEX_URL = 'alerts.index';

    public function destroy(int $id, AlertService $alertService): Response
    {
        if ($alertService->find($id)) {
            $alertService->remove();
        } else {
            $this->addErrorMessage(AlertMessages::DELETE_INCORRECT);
        }

        return $this->redirect($this->generateUrl(self::INDEX_URL));
    }

    public function index(IndexViewData $indexViewData): Response
    {
        $indexViewData->addCreateForm($this->createForm(AlertForm::class));

        return $this->render(self::INDEX_TEMPLATE, $indexViewData->getOptions());
    }

    public function show(int $id, AlertService $alertService, EditViewData $editViewData): Response
    {
        if (!$alertService->find($id)) {
            return new Response(null, Response::HTTP_FORBIDDEN);
        }
        $alert = $alertService->getAlert();
        $form = $this->createForm(EditAlertForm::class, EditAlert::fromEntity($alert), [
            'method' => Request::METHOD_PUT
        ]);
        $editViewData->addEditForm($form)
            ->addEntity($alert);

        return $this->render(self::EDIT_TEMPLATE, $editViewData->getOptions());
    }

    public function store(IndexViewData $indexViewData, AlertFactory $alertsFactory): Response
    {
        $form = $this->createForm(AlertForm::class);
        if ($alertsFactory->create($form, $this->request)) {
            return $this->redirectBack();
        }
        $indexViewData->addCreateForm($form);

        return $this->render(self::INDEX_TEMPLATE, $indexViewData->getOptions());
    }

    public function update(int $id, AlertService $alertService, EditViewData $editViewData): Response
    {
        if (!$alertService->find($id)) {
            return new Response(null, Response::HTTP_FORBIDDEN);
        }
        $alert = $alertService->getAlert();
        $form = $this->createForm(EditAlertForm::class, EditAlert::fromEntity($alert), [
            'method' => Request::METHOD_PUT
        ]);
        if ($alertService->update($form, $this->request)) {
            return $this->redirectBack();
        }
        $editViewData->addEntity($alert)
            ->addEditForm($form);

        return $this->render(self::EDIT_TEMPLATE, $editViewData->getOptions());
    }

    protected function getActive(): int
    {
        return 9;
    }
}
