<?php

namespace App\Controller\Web;

use App\Config\Message\ShopsMessages;
use App\Form\ShopForm;
use App\Model\Form\Shop as ShopModel;
use App\Services\Entity\ShopService;
use App\Services\Factory\ShopFactory;
use App\ViewData\Shops\EditViewData;
use App\ViewData\Shops\IndexViewData;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

final class ShopsController extends BaseController
{
    public const EDIT_TEMPLATE = 'shops/edit';
    public const INDEX_TEMPLATE = 'shops/index';
    public const INDEX_URL = 'shops.index';

    public function destroy(int $id, ShopService $shopService): Response
    {
        if ($shopService->find($id)) {
            $shopService->remove();
        } else {
            $this->addErrorMessage(ShopsMessages::DELETE_INCORRECT);
        }

        return $this->redirect($this->generateUrl(self::INDEX_URL));
    }

    public function index(IndexViewData $indexViewData): Response
    {
        $indexViewData->addCreateForm($this->createForm(ShopForm::class));

        return $this->render(self::INDEX_TEMPLATE, $indexViewData->getOptions());
    }

    public function show(int $id, ShopService $shopService, EditViewData $editViewData): Response
    {
        if (!$shopService->find($id)) {
            return new Response(null, Response::HTTP_FORBIDDEN);
        }
        $shop = $shopService->getShop();
        $editViewData->addEntity($shop);
        $form = $this->createForm(ShopForm::class, ShopModel::fromEntity($shop), [
            'method' => Request::METHOD_PUT,
            'expect' => $shop->getId()
        ]);
        $editViewData->addForm($form);

        return $this->render(self::EDIT_TEMPLATE, $editViewData->getOptions());
    }

    public function store(IndexViewData $indexViewData, ShopFactory $shopFactory): Response
    {
        $form = $this->createForm(ShopForm::class);
        if ($shopFactory->create($form, $this->request)) {
            return $this->redirectBack();
        }
        $indexViewData->addCreateForm($form);

        return $this->render(self::INDEX_TEMPLATE, $indexViewData->getOptions());
    }

    public function update(int $id, ShopService $shopService, EditViewData $editViewData): Response
    {
        if (!$shopService->find($id)) {
            return new Response(null, Response::HTTP_FORBIDDEN);
        }
        $shop = $shopService->getShop();
        $form = $this->createForm(ShopForm::class, null, [
            'method' => Request::METHOD_PUT,
            'expect' => $shop->getId()
        ]);
        if ($shopService->update($form, $this->request)) {
            return $this->redirectBack();
        }
        $editViewData->addEntity($shop);
        $editViewData->addForm($form);

        return $this->render(self::EDIT_TEMPLATE, $editViewData->getOptions());
    }

    protected function getActive(): int
    {
        return 2;
    }
}
