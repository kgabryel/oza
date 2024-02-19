<?php

namespace App\Controller\Web;

use App\Config\Message\ProductMessages;
use App\Form\EditProductForm;
use App\Form\ProductForm;
use App\Model\Form\EditProduct;
use App\Services\Entity\PhotoService;
use App\Services\Entity\ProductService;
use App\Services\Factory\ProductFactory;
use App\ViewData\Products\EditViewData;
use App\ViewData\Products\IndexViewData;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

final class ProductsController extends BaseController
{
    public const EDIT_TEMPLATE = 'products/edit';
    public const INDEX_TEMPLATE = 'products/index';
    public const INDEX_URL = 'products.index';

    public function destroy(int $id, ProductService $productService, PhotoService $photoService): Response
    {
        if ($productService->find($id)) {
            $productService->remove($photoService);
        } else {
            $this->addErrorMessage(ProductMessages::DELETE_INCORRECT);
        }

        return $this->redirect($this->generateUrl(self::INDEX_URL));
    }

    public function index(IndexViewData $indexViewData): Response
    {
        $indexViewData->addCreateForm($this->createForm(ProductForm::class));

        return $this->render(self::INDEX_TEMPLATE, $indexViewData->getOptions());
    }

    public function show(int $id, ProductService $productService, EditViewData $editViewData): Response
    {
        if (!$productService->find($id)) {
            return new Response(null, Response::HTTP_FORBIDDEN);
        }
        $product = $productService->getProduct();
        $editViewData->addEntity($product);
        $form = $this->createForm(EditProductForm::class, EditProduct::fromEntity($product), [
            'method' => Request::METHOD_PUT,
            'id' => $product->getId()
        ]);
        $editViewData->addForm($form);

        return $this->render(self::EDIT_TEMPLATE, $editViewData->getOptions());
    }

    public function store(IndexViewData $indexViewData, ProductFactory $productFactory): Response
    {
        $form = $this->createForm(ProductForm::class);
        if ($productFactory->create($form, $this->request)) {
            return $this->redirectBack();
        }
        $indexViewData->addCreateForm($form);

        return $this->render(self::INDEX_TEMPLATE, $indexViewData->getOptions());
    }

    public function update(int $id, ProductService $productService, EditViewData $editViewData): Response
    {
        if (!$productService->find($id)) {
            return new Response(null, Response::HTTP_FORBIDDEN);
        }
        $product = $productService->getProduct();
        $form = $this->createForm(EditProductForm::class, EditProduct::fromEntity($product), [
            'method' => Request::METHOD_PUT,
            'id' => $product->getId()
        ]);
        if ($productService->update($form, $this->request)) {
            return $this->redirectBack();
        }
        $editViewData->addForm($form);
        $editViewData->addEntity($product);

        return $this->render(self::EDIT_TEMPLATE, $editViewData->getOptions());
    }

    protected function getActive(): int
    {
        return 6;
    }
}
