<?php

namespace App\Controller\Web;

use App\Config\Message\SupplyMessages;
use App\Entity\Supply;
use App\Form\EditSupplyForm;
use App\Form\SupplyAlertForm;
use App\Form\SupplyPartForm;
use App\Model\Form\SupplyPart;
use App\Repository\AlertRepository;
use App\Services\Entity\SupplyAlertService;
use App\Services\Entity\SupplyPartService;
use App\Services\Entity\SupplyService;
use App\Services\ExternalSuppliesService;
use App\Services\Factory\SupplyPartFactory;
use App\Services\SupplyAlerts\SupplyAlertsService;
use App\ViewData\Supplies\EditViewData;
use App\ViewData\Supplies\SupplyPartViewData;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\RequestStack;
use Symfony\Component\HttpFoundation\Response;

final class SupplyPartsController extends BaseController
{
    public const EDIT_TEMPLATE = 'supplyParts/edit';

    private SupplyPartService $supplyPartService;

    public function __construct(RequestStack $requestStack, SupplyPartService $supplyPartService)
    {
        parent::__construct($requestStack);
        $this->supplyPartService = $supplyPartService;
    }

    public function destroy(int $id): Response
    {
        if ($this->supplyPartService->find($id)) {
            $this->supplyPartService->remove();
        } else {
            $this->addErrorMessage(SupplyMessages::DELETE_INCORRECT);
        }

        return $this->redirectToSupply();
    }

    private function redirectToSupply(): Response
    {
        return $this->redirect(
            $this->generateUrl(
                SuppliesController::SHOW_URL,
                [
                    'id' => $this->supplyPartService->getSupplyId()
                ]
            )
        );
    }

    public function show(int $id, SupplyPartViewData $viewData): Response
    {
        if (!$this->supplyPartService->find($id)) {
            return new Response(null, Response::HTTP_FORBIDDEN);
        }
        $supplyPart = $this->supplyPartService->getSupplyPart();
        $supplyPartForm = $this->createForm(SupplyPartForm::class, SupplyPart::fromEntity($supplyPart), [
            'supply' => $supplyPart->getSupply(),
            'method' => Request::METHOD_PUT
        ]);
        $viewData->addEntity($supplyPart)
            ->addSupplyPartForm($supplyPartForm);

        return $this->render(self::EDIT_TEMPLATE, $viewData->getOptions());
    }

    public function store(
        int $id,
        SupplyService $supplyService,
        EditViewData $editViewData,
        AlertRepository $alertRepository,
        SupplyAlertService $supplyAlertService,
        SupplyAlertsService $alertsService,
        ExternalSuppliesService $externalSuppliesService,
        SupplyPartFactory $supplyPartFactory
    ): Response {
        if (!$supplyService->find($id)) {
            return new Response(null, Response::HTTP_FORBIDDEN);
        }
        $supply = $supplyService->getSupply();
        $supplyPartForm = $this->createForm(SupplyPartForm::class, null, [
            'supply' => $supply
        ]);
        if ($supplyPartFactory->create($supplyPartForm, $this->request, $supplyService->getSupply())) {
            $externalSuppliesService->update($supply);
            $supplyAlertService->reactivate($supply, $alertsService);

            return $this->redirectBack();
        }
        $alertForm = $this->getSupplyAlertForm($supply, $alertRepository);
        $editForm = $this->createForm(EditSupplyForm::class, null, [
            'method' => Request::METHOD_PUT
        ]);
        $editViewData->addEntity($supply)
            ->addEditForm($editForm)
            ->addAlertForm($alertForm)
            ->addSupplyPartForm($supplyPartForm);

        return $this->render(SuppliesController::EDIT_TEMPLATE, $editViewData->getOptions());
    }

    public function update(
        int $id,
        SupplyPartViewData $viewData,
        ExternalSuppliesService $externalSuppliesService
    ): Response {
        if (!$this->supplyPartService->find($id)) {
            return new Response(null, Response::HTTP_FORBIDDEN);
        }
        $supplyPart = $this->supplyPartService->getSupplyPart();
        $supplyPartForm = $this->createForm(SupplyPartForm::class, null, [
            'supply' => $supplyPart->getSupply(),
            'method' => Request::METHOD_PUT
        ]);
        if ($this->supplyPartService->update($supplyPartForm, $this->request, $externalSuppliesService)) {
            return $this->redirectToSupply();
        }
        $viewData->addEntity($supplyPart)
            ->addSupplyPartForm($supplyPartForm);

        return $this->render(self::EDIT_TEMPLATE, $viewData->getOptions());
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

    protected function getActive(): int
    {
        return 9;
    }
}
