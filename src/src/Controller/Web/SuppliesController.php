<?php

namespace App\Controller\Web;

use App\Config\Message\AlertMessages;
use App\Config\Message\SupplyMessages;
use App\Entity\Supply;
use App\Form\EditSupplyForm;
use App\Form\SupplyAlertForm;
use App\Form\SupplyForm;
use App\Form\SupplyPartForm;
use App\Model\Form\EditSupply;
use App\Repository\AlertRepository;
use App\Services\Entity\SupplyAlertService;
use App\Services\Entity\SupplyService;
use App\Services\ExternalSuppliesService;
use App\Services\Factory\SupplyAlertFactory;
use App\Services\Factory\SupplyFactory;
use App\Services\SupplyAlerts\SupplyAlertsService;
use App\ViewData\Supplies\EditViewData;
use App\ViewData\Supplies\IndexViewData;
use Kgabryel\ErrorConverter\FormErrorConverter;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\RequestStack;
use Symfony\Component\HttpFoundation\Response;

final class SuppliesController extends BaseController
{
    public const EDIT_TEMPLATE = 'supplies/edit';
    public const INDEX_TEMPLATE = 'supplies/index';
    public const INDEX_URL = 'supplies.index';
    public const SHOW_URL = 'supplies.show';

    private SupplyService $supplyService;

    public function __construct(RequestStack $requestStack, SupplyService $supplyService)
    {
        parent::__construct($requestStack);
        $this->supplyService = $supplyService;
    }

    public function createAlert(
        int $id,
        SupplyAlertFactory $alertFactory,
        SupplyAlertService $supplyAlertService,
        AlertRepository $alertRepository,
        SupplyAlertsService $alertsService
    ): Response {
        if (!$this->supplyService->find($id)) {
            return new Response(null, Response::HTTP_FORBIDDEN);
        }
        $supply = $this->supplyService->getSupply();
        $form = $this->getSupplyAlertForm($supply, $alertRepository);
        if ($alertFactory->create($form, $this->request, $supply)) {
            $supplyAlertService->reactivate($supply, $alertsService);
            $this->addSuccessMessage(SupplyMessages::ALERT_ADDED);
        } else {
            $parser = new FormErrorConverter($form);
            $parser->parse();
            foreach ($parser->getErrors() as $error) {
                foreach ($error as $val) {
                    $this->addErrorMessage($val);
                }
            }
        }

        return $this->redirect($this->generateUrl(self::SHOW_URL, ['id' => $supply->getId()]));
    }

    private function getSupplyAlertForm(Supply $supply, AlertRepository $alertRepository): FormInterface
    {
        return $this->createForm(SupplyAlertForm::class, null, [
            'id' => $supply->getId(),
            'alerts' => $alertRepository->findWithoutSupply(
                $this->getUser(),
                $supply
            )
        ]);
    }

    public function deleteAlert(
        int $id,
        SupplyAlertService $alertService,
        SupplyAlertsService $supplyAlertsService
    ): Response {
        if ($alertService->find($id)) {
            $alertService->remove($supplyAlertsService);
        } else {
            $this->addErrorMessage(AlertMessages::DELETE_INCORRECT);
        }

        return $this->redirectBack();
    }

    public function destroy(int $id, ExternalSuppliesService $externalSuppliesService): Response
    {
        if ($this->supplyService->find($id)) {
            $this->supplyService->remove($externalSuppliesService);
        } else {
            $this->addErrorMessage(SupplyMessages::DELETE_INCORRECT);
        }

        return $this->redirect($this->generateUrl(self::INDEX_URL));
    }

    public function index(IndexViewData $indexViewData): Response
    {
        $indexViewData->addCreateForm($this->createForm(SupplyForm::class));

        return $this->render(self::INDEX_TEMPLATE, $indexViewData->getOptions());
    }

    public function show(int $id, EditViewData $editViewData, AlertRepository $alertRepository): Response
    {
        if (!$this->supplyService->find($id)) {
            return new Response(null, Response::HTTP_FORBIDDEN);
        }
        $supply = $this->supplyService->getSupply();
        $editForm = $this->createForm(EditSupplyForm::class, EditSupply::fromEntity($supply), [
            'method' => Request::METHOD_PUT
        ]);
        $supplyPartForm = $this->createForm(SupplyPartForm::class, null, [
            'supply' => $supply
        ]);
        $alertForm = $this->getSupplyAlertForm($supply, $alertRepository);
        $editViewData->addEditForm($editForm)
            ->addAlertForm($alertForm)
            ->addSupplyPartForm($supplyPartForm)
            ->addEntity($supply);

        return $this->render(self::EDIT_TEMPLATE, $editViewData->getOptions());
    }

    public function store(IndexViewData $indexViewData, SupplyFactory $supplyFactory): Response
    {
        $form = $this->createForm(SupplyForm::class);
        if ($supplyFactory->create($form, $this->request)) {
            return $this->redirectBack();
        }
        $indexViewData->addCreateForm($form);

        return $this->render(self::INDEX_TEMPLATE, $indexViewData->getOptions());
    }

    public function update(
        int $id,
        EditViewData $editViewData,
        AlertRepository $alertRepository,
        SupplyAlertService $supplyAlertService,
        SupplyAlertsService $alertsService,
        ExternalSuppliesService $externalSuppliesService
    ): Response {
        if (!$this->supplyService->find($id)) {
            return new Response(null, Response::HTTP_FORBIDDEN);
        }
        $supply = $this->supplyService->getSupply();
        $editForm = $this->createForm(EditSupplyForm::class, null, [
            'method' => Request::METHOD_PUT
        ]);
        if ($this->supplyService->update($editForm, $this->request, $externalSuppliesService)) {
            $supplyAlertService->reactivate($supply, $alertsService);

            return $this->redirectBack();
        }
        $alertForm = $this->getSupplyAlertForm($supply, $alertRepository);
        $supplyPartForm = $this->createForm(SupplyPartForm::class, null, [
            'supply' => $supply
        ]);
        $editViewData->addEntity($supply)
            ->addEditForm($editForm)
            ->addAlertForm($alertForm)
            ->addSupplyPartForm($supplyPartForm);

        return $this->render(self::EDIT_TEMPLATE, $editViewData->getOptions());
    }

    protected function getActive(): int
    {
        return 10;
    }
}
