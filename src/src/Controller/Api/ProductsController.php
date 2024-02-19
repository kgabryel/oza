<?php

namespace App\Controller\Api;

use App\Form\MainPhotoForm;
use App\Form\PhotoForm;
use App\Services\Chart\ProductChartService;
use App\Services\Condition\Condition;
use App\Services\Entity\ProductService;
use App\Services\Factory\PhotoFactory;
use App\Services\Transformer\PhotoTransformer;
use App\Services\Transformer\ProductTransformer;
use Closure;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\RequestStack;
use Symfony\Component\HttpFoundation\Response;

final class ProductsController extends BaseController
{
    private ProductService $productService;

    public function __construct(RequestStack $requestStack, ProductService $productService)
    {
        parent::__construct($requestStack);
        $this->productService = $productService;
    }

    public function addPhoto(int $id, PhotoFactory $photoFactory): Response
    {
        $condition = $this->getCondition(
            $id,
            function() use ($photoFactory) {
                $form = $this->createForm(PhotoForm::class, null, [
                    'method' => Request::METHOD_POST
                ]);
                $photo = $photoFactory->create($form, $this->request, $this->productService->getProduct(), null);
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
        return $this->getBaseCondition($successAction, fn(): bool => $this->productService->find($id));
    }

    public function changeMainPhoto(int $id): Response
    {
        $condition = $this->getCondition(
            $id,
            function() {
                $product = $this->productService->getProduct();
                $photos = $this->productService->getProduct()->getPhotos()->toArray();
                foreach ($product->getGroups() as $group) {
                    foreach ($group->getPhotos() as $photo) {
                        $photos[] = $photo;
                    }
                }
                $form = $this->createForm(MainPhotoForm::class, null, [
                    'method' => Request::METHOD_POST,
                    'photos' => $photos
                ]);

                if (!$this->productService->changeMainPhoto($form, $this->request)) {
                    return new Response(null, Response::HTTP_BAD_REQUEST);
                }

                return new Response(null, Response::HTTP_NO_CONTENT);
            }
        );

        return $condition();
    }

    public function findByBarcode(string $code): Response
    {
        $condition = $this->getBaseCondition(
            fn(): array => ProductTransformer::toArray($this->productService->getProduct()),
            fn(): bool => $this->productService->findByBarcode($code)
        );
        $condition->setFailAction(fn(): Response => new Response(null, Response::HTTP_NOT_FOUND));

        return $condition();
    }

    public function getAvailableUnits(int $id): Response
    {
        return ($this->getCondition($id, fn(): array => $this->productService->getUnits()))();
    }

    public function getChartData(int $id, ProductChartService $chartService): Response
    {
        return $this->getCondition(
            $id,
            function() use ($chartService) {
                return $chartService->setProduct($this->productService->getProduct())->fillData()->getData();
            }
        )();
    }

    public function getSupplyInfo(int $id): Response
    {
        return $this->getCondition(
            $id,
            fn(): array => ProductTransformer::toSupplyInfo($this->productService->getProduct())
        )();
    }

    public function show(int $id): Response
    {
        return ($this->getCondition(
            $id,
            fn(): array => ProductTransformer::toArray($this->productService->getProduct())
        ))();
    }
}
