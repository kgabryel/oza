<?php

namespace App\Controller\Web;

use App\Field\ShoppingListPosition;
use App\Form\ShoppingListForm;
use App\Model\Form\ShoppingList;
use App\Services\Entity\ShoppingListService;
use App\Services\Factory\ShoppingList\ListFactory;
use App\Services\Factory\ShoppingList\PositionFactory;
use App\Transformer\ShoppingListPositionTransformer;
use App\ViewData\ShoppingLists\FormViewData;
use App\ViewData\ShoppingLists\IndexViewData;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

final class ShoppingListsController extends BaseController
{
    public const ADD_TEMPLATE = 'shoppingLists/add';
    public const EDIT_TEMPLATE = 'shoppingLists/edit';
    public const INDEX_TEMPLATE = 'shoppingLists/index';
    public const INDEX_URL = 'shoppingLists.index';

    public function index(IndexViewData $indexViewData): Response
    {
        return $this->render(self::INDEX_TEMPLATE, $indexViewData->getOptions());
    }

    public function show(
        int $id,
        ShoppingListService $productsListService,
        FormViewData $formViewData,
        ShoppingListPositionTransformer $listPositionTransformer
    ): Response {
        if (!$productsListService->find($id)) {
            return new Response(null, Response::HTTP_FORBIDDEN);
        }
        $formViewData->addId($id)
            ->addForm(
                $this->createForm(
                    ShoppingListForm::class,
                    ShoppingList::fromEntity($productsListService->getList(), $listPositionTransformer),
                    ['method' => Request::METHOD_PUT]
                )
            );

        return $this->render(self::EDIT_TEMPLATE, $formViewData->getOptions());
    }

    public function showCreate(FormViewData $listsFormViewData, ShoppingListPosition $position): Response
    {
        $model = new ShoppingList();
        $model->setPositions([$position]);
        $form = $this->createForm(ShoppingListForm::class);
        $listsFormViewData->addForm($form)
            ->addEmptyPosition();

        return $this->render(self::ADD_TEMPLATE, $listsFormViewData->getOptions());
    }

    public function store(FormViewData $listsFormViewData, ListFactory $factory): Response
    {
        $form = $this->createForm(ShoppingListForm::class);
        if ($factory->create($form, $this->request)) {
            return $this->redirect($this->generateUrl(self::INDEX_URL));
        }
        $listsFormViewData->addForm($form);

        return $this->render(self::ADD_TEMPLATE, $listsFormViewData->getOptions());
    }

    public function update(
        int $id,
        ShoppingListService $productsListService,
        PositionFactory $shoppingListPositionFactory,
        FormViewData $listsFormViewData
    ): Response {
        if (!$productsListService->find($id)) {
            return new Response(null, Response::HTTP_FORBIDDEN);
        }
        $form = $this->createForm(
            ShoppingListForm::class,
            null,
            ['method' => Request::METHOD_PUT]
        );
        if ($productsListService->update($form, $this->request, $shoppingListPositionFactory)) {
            return $this->redirect($this->generateUrl(self::INDEX_URL));
        }
        $listsFormViewData->addForm($form)
            ->addId($id);

        return $this->render(self::EDIT_TEMPLATE, $listsFormViewData->getOptions());
    }

    protected function getActive(): int
    {
        return 7;
    }
}
