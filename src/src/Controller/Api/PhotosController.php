<?php

namespace App\Controller\Api;

use App\Services\Entity\PhotoService;
use Symfony\Component\HttpFoundation\RequestStack;
use Symfony\Component\HttpFoundation\Response;

class PhotosController extends BaseController
{
    private PhotoService $photoService;

    public function __construct(RequestStack $requestStack, PhotoService $photoService)
    {
        parent::__construct($requestStack);
        $this->photoService = $photoService;
    }

    public function destroy(int $id): Response
    {
        $condition = $this->getBaseCondition(
            function() {
                $this->photoService->remove();

                return new Response(null, Response::HTTP_NO_CONTENT);
            },
            fn(): bool => $this->photoService->find($id)
        );
        $condition->setFailAction(fn(): Response => new Response(null, Response::HTTP_NOT_FOUND));

        return $condition();
    }
}
