<?php

namespace App\Controller\Web;

use App\Entity\Photo;
use App\Entity\User;
use App\Repository\PhotoRepository;
use App\Utils\PhotoUtils;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\KernelInterface;

/** @method User getUser() */
final class PhotosController extends AbstractController
{
    private KernelInterface $kernel;
    private PhotoRepository $photoRepository;

    public function __construct(PhotoRepository $photoRepository, KernelInterface $kernel)
    {
        $this->photoRepository = $photoRepository;
        $this->kernel = $kernel;
    }

    public function show(string $type, int $id): Response
    {
        /** @var Photo|null $photo */
        $photo = $this->photoRepository->findById($id, $this->getUser());
        if ($photo === null) {
            return new Response(null, Response::HTTP_FORBIDDEN);
        }
        $response = new Response();
        $response->headers->set('Content-Type', $photo->getType());
        $fileName = $photo->getFileName() ?? '';
        $response->setContent(
            (string)file_get_contents(PhotoUtils::getPath($this->kernel->getProjectDir(), $type, $fileName))
        );

        return $response;
    }
}
