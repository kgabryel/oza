<?php

namespace App\Controller\Web;

use App\Config\Message\SupplyGroupsMessages;
use App\Form\SupplyGroupForm;
use App\Model\Form\SupplyGroup as SupplyGroupModel;
use App\Services\Entity\SupplyGroupService;
use App\Services\Factory\SupplyGroupFactory;
use App\ViewData\SupplyGroups\EditViewData;
use App\ViewData\SupplyGroups\IndexViewData;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

final class SupplyGroupsController extends BaseController
{
    public const EDIT_TEMPLATE = 'supplyGroup/edit';
    public const INDEX_TEMPLATE = 'supplyGroup/index';
    public const INDEX_URL = 'supplyGroups.index';

    public function destroy(int $id, SupplyGroupService $supplyGroupService): Response
    {
        if ($supplyGroupService->find($id)) {
            $supplyGroupService->remove();
        } else {
            $this->addErrorMessage(SupplyGroupsMessages::DELETE_INCORRECT);
        }

        return $this->redirect($this->generateUrl(self::INDEX_URL));
    }

    public function index(IndexViewData $indexViewData): Response
    {
        $indexViewData->addCreateForm($this->createForm(SupplyGroupForm::class));

        return $this->render(self::INDEX_TEMPLATE, $indexViewData->getOptions());
    }

    public function show(int $id, SupplyGroupService $supplyGroupService, EditViewData $editViewData): Response
    {
        if (!$supplyGroupService->find($id)) {
            return new Response(null, Response::HTTP_FORBIDDEN);
        }
        $supplyGroup = $supplyGroupService->getSupplyGroup();
        $editViewData->addEntity($supplyGroup);
        $form = $this->createForm(SupplyGroupForm::class, SupplyGroupModel::fromEntity($supplyGroup), [
            'method' => Request::METHOD_PUT,
            'expect' => $supplyGroup->getId()
        ]);
        $editViewData->addForm($form);

        return $this->render(self::EDIT_TEMPLATE, $editViewData->getOptions());
    }

    public function store(IndexViewData $indexViewData, SupplyGroupFactory $supplyGroupFactory): Response
    {
        $form = $this->createForm(SupplyGroupForm::class);
        if ($supplyGroupFactory->create($form, $this->request)) {
            return $this->redirectBack();
        }
        $indexViewData->addCreateForm($form);

        return $this->render(self::INDEX_TEMPLATE, $indexViewData->getOptions());
    }

    public function update(int $id, SupplyGroupService $supplyGroupService, EditViewData $editViewData): Response
    {
        if (!$supplyGroupService->find($id)) {
            return new Response(null, Response::HTTP_FORBIDDEN);
        }
        $supplyGroup = $supplyGroupService->getSupplyGroup();
        $form = $this->createForm(SupplyGroupForm::class, null, [
            'method' => Request::METHOD_PUT,
            'expect' => $supplyGroup->getId()
        ]);
        if ($supplyGroupService->update($form, $this->request)) {
            return $this->redirectBack();
        }
        $editViewData->addEntity($supplyGroup);
        $editViewData->addForm($form);

        return $this->render(self::EDIT_TEMPLATE, $editViewData->getOptions());
    }

    protected function getActive(): int
    {
        return 11;
    }
}
