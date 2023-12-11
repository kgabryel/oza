<?php

namespace App\Controller\Web;

use App\Form\QuickListForm;
use App\Model\Form\QuickList;
use App\Model\Form\QuickListPosition;
use App\Services\Entity\QuickListService;
use App\Services\Factory\QuickList\ListFactory;
use App\Services\Factory\QuickList\PositionFactory;
use App\ViewData\QuickLists\FormViewData;
use App\ViewData\QuickLists\IndexViewData;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

final class QuickListsController extends BaseController
{
    public const EDIT_TEMPLATE = 'quickLists/edit';
    public const INDEX_TEMPLATE = 'quickLists/index';
    public const INDEX_URL = 'quickLists.index';
    public const SHOW_TEMPLATE = 'quickLists/add';

    public function index(IndexViewData $indexViewData): Response
    {
        return $this->render(self::INDEX_TEMPLATE, $indexViewData->getOptions());
    }

    public function show(int $id, QuickListService $quickListService, FormViewData $formViewData): Response
    {
        if (!$quickListService->find($id)) {
            return new Response(null, Response::HTTP_FORBIDDEN);
        }
        $form = $this->createForm(
            QuickListForm::class,
            QuickList::fromEntity($quickListService->getList()),
            ['method' => Request::METHOD_PUT]
        );
        $formViewData->addForm($form);
        $formViewData->addId($id);

        return $this->render(self::EDIT_TEMPLATE, $formViewData->getOptions());
    }

    public function showCreate(FormViewData $formViewData): Response
    {
        $formViewData->addEmptyPosition();
        $model = new QuickList();
        $model->setPositions([new QuickListPosition()]);
        $form = $this->createForm(QuickListForm::class, $model);
        $formViewData->addForm($form);

        return $this->render(self::SHOW_TEMPLATE, $formViewData->getOptions());
    }

    public function store(ListFactory $quickListFactory, FormViewData $formViewData): Response
    {
        $form = $this->createForm(QuickListForm::class);
        if ($quickListFactory->create($form, $this->request)) {
            return $this->redirect($this->generateUrl(self::INDEX_URL));
        }
        $formViewData->addForm($form);

        return $this->render(self::SHOW_TEMPLATE, $formViewData->getOptions());
    }

    public function update(
        int $id,
        QuickListService $quickListService,
        FormViewData $formViewData,
        PositionFactory $positionFactory
    ): Response {
        if (!$quickListService->find($id)) {
            return new Response(null, Response::HTTP_FORBIDDEN);
        }
        $list = $quickListService->getList();
        $form = $this->createForm(
            QuickListForm::class,
            QuickList::fromEntity($list),
            ['method' => Request::METHOD_PUT]
        );
        $positionFactory->setList($list);
        if ($quickListService->update($form, $this->request, $positionFactory)) {
            return $this->redirect($this->generateUrl(self::INDEX_URL));
        }
        $formViewData->addForm($form);
        $formViewData->addId($id);

        return $this->render(self::EDIT_TEMPLATE, $formViewData->getOptions());
    }

    protected function getActive(): int
    {
        return 8;
    }
}
