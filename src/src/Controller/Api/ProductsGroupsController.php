<?php

namespace App\Controller\Api;

use App\Form\MainPhotoForm;
use App\Form\PhotoForm;
use App\Services\Chart\ProductsGroupChartService;
use App\Services\Condition\Condition;
use App\Services\Entity\ProductsGroupService;
use App\Services\Factory\PhotoFactory;
use App\Services\Transformer\PhotoTransformer;
use App\Services\Transformer\ProductsGroupTransformer;
use Closure;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\RequestStack;
use Symfony\Component\HttpFoundation\Response;

final class ProductsGroupsController extends BaseController
{
    private ProductsGroupService $productsGroupService;

    public function __construct(RequestStack $requestStack, ProductsGroupService $productsGroupService)
    {
        parent::__construct($requestStack);
        $this->productsGroupService = $productsGroupService;
    }

    public function addPhoto(int $id, PhotoFactory $photoFactory): Response
    {
        $condition = $this->getCondition(
            $id,
            function() use ($photoFactory) {
                $form = $this->createForm(PhotoForm::class, null, [
                    'method' => Request::METHOD_POST
                ]);
                $photo = $photoFactory->create(
                    $form,
                    $this->request,
                    null,
                    $this->productsGroupService->getProductsGroup()
                );
                if ($photo === false) {
                    return new Response(null, Response::HTTP_BAD_REQUEST);
                }

                return PhotoTransformer::toArray($photo, false, true);
            }
        );

        return $condition();
    }

    private function getCondition(int $id, Closure $successAction): Condition
    {
        return $this->getBaseCondition($successAction, fn(): bool => $this->productsGroupService->find($id));
    }

    public function changeMainPhoto(int $id): Response
    {
        $condition = $this->getCondition(
            $id,
            function() {
                $productsGroup = $this->productsGroupService->getProductsGroup();
                $photos = $this->productsGroupService->getProductsGroup()->getPhotos()->toArray();
                foreach ($productsGroup->getProducts() as $product) {
                    foreach ($product->getPhotos() as $photo) {
                        $photos[] = $photo;
                    }
                }
                $form = $this->createForm(MainPhotoForm::class, null, [
                    'method' => Request::METHOD_POST,
                    'photos' => $photos
                ]);

                if (!$this->productsGroupService->changeMainPhoto($form, $this->request)) {
                    return new Response(null, Response::HTTP_BAD_REQUEST);
                }

                return new Response(null, Response::HTTP_NO_CONTENT);
            }
        );

        return $condition();
    }

    public function getAvailableUnits(int $id): Response
    {
        return ($this->getCondition($id, fn(): array => $this->productsGroupService->getUnits()))();
    }

    public function getChartData(int $id, ProductsGroupChartService $chartService): Response
    {
        $condition = $this->getCondition(
            $id,
            function() use ($chartService) {
                return $chartService->setProductsGroup($this->productsGroupService->getProductsGroup())
                    ->fillData()
                    ->getData();
            }
        );

        return $condition();
    }

    public function getSupplyInfo(int $id): Response
    {
        return $this->getCondition(
            $id,
            fn(): array => ProductsGroupTransformer::toSupplyInfo($this->productsGroupService->getProductsGroup())
        )();
    }

    public function show(int $id): Response
    {
        return $this->getCondition(
            $id,
            fn(): array => ProductsGroupTransformer::toArray($this->productsGroupService->getProductsGroup())
        )();
    }
}
