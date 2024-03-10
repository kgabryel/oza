<?php

namespace App\Controller\Web;

use App\Config\Message\BrandsMessages;
use App\Form\BrandForm;
use App\Model\Form\Brand as BrandModel;
use App\Services\Entity\BrandService;
use App\Services\Factory\BrandFactory;
use App\ViewData\Brands\EditViewData;
use App\ViewData\Brands\IndexViewData;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

final class BrandsController extends BaseController
{
    public const EDIT_TEMPLATE = 'brands/edit';
    public const INDEX_TEMPLATE = 'brands/index';
    public const INDEX_URL = 'brands.index';

    public function destroy(int $id, BrandService $brandService): Response
    {
        if ($brandService->find($id)) {
            $brandService->remove();
        } else {
            $this->addErrorMessage(BrandsMessages::DELETE_INCORRECT);
        }

        return $this->redirect($this->generateUrl(self::INDEX_URL));
    }

    public function index(IndexViewData $indexViewData): Response
    {
        $indexViewData->addCreateForm($this->createForm(BrandForm::class));

        return $this->render(self::INDEX_TEMPLATE, $indexViewData->getOptions());
    }

    public function show(int $id, BrandService $brandService, EditViewData $editViewData): Response
    {
        if (!$brandService->find($id)) {
            return new Response(null, Response::HTTP_FORBIDDEN);
        }
        $brand = $brandService->getBrand();
        $form = $this->createForm(BrandForm::class, BrandModel::fromEntity($brand), [
            'method' => Request::METHOD_PUT,
            'expect' => $brand->getId()
        ]);
        $editViewData->addForm($form)
            ->addEntity($brand);

        return $this->render(self::EDIT_TEMPLATE, $editViewData->getOptions());
    }

    public function store(IndexViewData $indexViewData, BrandFactory $brandFactory): Response
    {
        $form = $this->createForm(BrandForm::class);
        if ($brandFactory->create($form, $this->request)) {
            return $this->redirectBack();
        }
        $indexViewData->addCreateForm($form);

        return $this->render(self::INDEX_TEMPLATE, $indexViewData->getOptions());
    }

    public function update(int $id, BrandService $brandService, EditViewData $editViewData): Response
    {
        if (!$brandService->find($id)) {
            return new Response(null, Response::HTTP_FORBIDDEN);
        }
        $brand = $brandService->getBrand();
        $form = $this->createForm(BrandForm::class, null, [
            'method' => Request::METHOD_PUT,
            'expect' => $brand->getId()
        ]);
        if ($brandService->update($form, $this->request)) {
            return $this->redirectBack();
        }
        $editViewData->addEntity($brand)
            ->addForm($form);

        return $this->render(self::EDIT_TEMPLATE, $editViewData->getOptions());
    }

    protected function getActive(): int
    {
        return 3;
    }
}
