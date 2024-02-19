<?php

namespace App\Controller\Web;

use App\Config\Message\UnitMessages;
use App\Form\EditUnitForm;
use App\Form\UnitForm;
use App\Model\Form\EditUnit;
use App\Services\Entity\UnitService;
use App\Services\Factory\UnitFactory;
use App\ViewData\Units\EditViewData;
use App\ViewData\Units\IndexViewData;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

final class UnitsController extends BaseController
{
    public const EDIT_TEMPLATE = 'units/edit';
    public const INDEX_TEMPLATE = 'units/index';
    public const INDEX_URL = 'units.index';

    public function destroy(int $id, UnitService $unitService): Response
    {
        if ($unitService->find($id)) {
            $unitService->remove();
        } else {
            $this->addErrorMessage(UnitMessages::DELETE_INCORRECT);
        }

        return $this->redirect($this->generateUrl(self::INDEX_URL));
    }

    public function index(IndexViewData $indexViewData): Response
    {
        $indexViewData->addCreateForm($this->createForm(UnitForm::class));

        return $this->render(self::INDEX_TEMPLATE, $indexViewData->getOptions());
    }

    public function show(int $id, UnitService $unitService, EditViewData $editViewData): Response
    {
        if (!$unitService->find($id)) {
            return new Response(null, Response::HTTP_FORBIDDEN);
        }
        $unit = $unitService->getUnit();
        $editViewData->addEntity($unit);
        $form = $this->createForm(EditUnitForm::class, EditUnit::fromEntity($unit), [
            'method' => Request::METHOD_PUT,
            'expect' => $unit->getId()
        ]);
        $editViewData->addForm($form);

        return $this->render(self::EDIT_TEMPLATE, $editViewData->getOptions());
    }

    public function store(UnitFactory $unitFactory, IndexViewData $indexViewData): Response
    {
        $form = $this->createForm(UnitForm::class);
        if ($unitFactory->createUnit($form, $this->request)) {
            return $this->redirectBack();
        }
        $indexViewData->addCreateForm($form);

        return $this->render(self::INDEX_TEMPLATE, $indexViewData->getOptions());
    }

    public function update(int $id, UnitService $unitService, EditViewData $editViewData): Response
    {
        if (!$unitService->find($id)) {
            return new Response(null, Response::HTTP_FORBIDDEN);
        }
        $unit = $unitService->getUnit();
        $form = $this->createForm(EditUnitForm::class, null, [
            'method' => Request::METHOD_PUT,
            'expect' => $unit->getId()
        ]);
        if ($unitService->update($form, $this->request)) {
            return $this->redirectBack();
        }
        $editViewData->addEntity($unit);
        $editViewData->addForm($form);

        return $this->render(self::EDIT_TEMPLATE, $editViewData->getOptions());
    }

    protected function getActive(): int
    {
        return 1;
    }
}
