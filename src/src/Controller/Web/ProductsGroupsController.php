<?php

namespace App\Controller\Web;

use App\Config\Message\ProductsGroupMessages;
use App\Config\Settings as SettingsConfig;
use App\Form\EditProductsGroupForm;
use App\Form\ProductsGroupForm;
use App\Model\Form\EditProductsGroup;
use App\Model\Form\ProductsGroup;
use App\Services\Entity\PhotoService;
use App\Services\Entity\ProductsGroupService;
use App\Services\Factory\ProductsGroupFactory;
use App\ViewData\ProductsGroups\EditViewData;
use App\ViewData\ProductsGroups\IndexViewData;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Session\SessionInterface;

final class ProductsGroupsController extends BaseController
{
    public const EDIT_TEMPLATE = 'productsGroups/edit';
    public const INDEX_TEMPLATE = 'productsGroups/index';
    public const INDEX_URL = 'productsGroups.index';

    public function destroy(int $id, ProductsGroupService $productsGroupService, PhotoService $photoService): Response
    {
        if ($productsGroupService->find($id)) {
            $productsGroupService->remove($photoService);
        } else {
            $this->addErrorMessage(ProductsGroupMessages::DELETE_INCORRECT);
        }

        return $this->redirect($this->generateUrl(self::INDEX_URL));
    }

    public function index(IndexViewData $indexViewData, SessionInterface $session): Response
    {
        $formData = new ProductsGroup();
        $formData->setCreateSupply($session->get(SettingsConfig::CREATE_SUPPLY));
        $indexViewData->addCreateForm($this->createForm(ProductsGroupForm::class, $formData));

        return $this->render(self::INDEX_TEMPLATE, $indexViewData->getOptions());
    }

    public function show(int $id, ProductsGroupService $productsGroupService, EditViewData $editViewData): Response
    {
        if (!$productsGroupService->find($id)) {
            return new Response(null, Response::HTTP_FORBIDDEN);
        }
        $productsGroup = $productsGroupService->getProductsGroup();
        $form = $this->createForm(
            EditProductsGroupForm::class,
            EditProductsGroup::fromEntity($productsGroup),
            [
                'method' => Request::METHOD_PUT,
                'expect' => $productsGroup->getId()
            ]
        );
        $editViewData->addForm($form)
            ->addEntity($productsGroup);

        return $this->render(self::EDIT_TEMPLATE, $editViewData->getOptions());
    }

    public function store(IndexViewData $indexViewData, ProductsGroupFactory $productsGroupFactory): Response
    {
        $form = $this->createForm(ProductsGroupForm::class);
        if ($productsGroupFactory->create($form, $this->request)) {
            return $this->redirectBack();
        }
        $indexViewData->addCreateForm($form);

        return $this->render(self::INDEX_TEMPLATE, $indexViewData->getOptions());
    }

    public function update(int $id, ProductsGroupService $productsGroupService, EditViewData $editViewData): Response
    {
        if (!$productsGroupService->find($id)) {
            return new Response(null, Response::HTTP_FORBIDDEN);
        }
        $productsGroup = $productsGroupService->getProductsGroup();
        $form = $this->createForm(EditProductsGroupForm::class, null, [
            'method' => Request::METHOD_PUT,
            'expect' => $productsGroup->getId()
        ]);

        if ($productsGroupService->update($form, $this->request)) {
            return $this->redirectBack();
        }
        $editViewData->addForm($form)
            ->addEntity($productsGroup);

        return $this->render(self::EDIT_TEMPLATE, $editViewData->getOptions());
    }

    protected function getActive(): int
    {
        return 5;
    }
}
