<?php

namespace App\Services\Entity;

use App\Config\PhotoType;
use App\Entity\Photo;
use App\Repository\PhotoRepository;
use App\Utils\PhotoUtils;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Filesystem\Filesystem;
use Symfony\Component\HttpFoundation\Session\Flash\FlashBagInterface;
use Symfony\Component\HttpKernel\KernelInterface;
use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorageInterface;

class PhotoService extends EntityService
{
    private Filesystem $filesystem;
    private KernelInterface $kernel;
    private Photo $photo;
    private PhotoRepository $photoRepository;

    public function __construct(
        FlashBagInterface $flashBag,
        EntityManagerInterface $entityManager,
        TokenStorageInterface $tokenStorage,
        PhotoRepository $photoRepository,
        Filesystem $filesystem,
        KernelInterface $kernel
    ) {
        parent::__construct($flashBag, $entityManager, $tokenStorage);
        $this->photoRepository = $photoRepository;
        $this->filesystem = $filesystem;
        $this->kernel = $kernel;
    }

    public function find(int $id): bool
    {
        $photo = $this->photoRepository->findById($id, $this->user);

        if ($photo === null) {
            return false;
        }
        $this->photo = $photo;

        return true;
    }

    public function getPhoto(): Photo
    {
        return $this->photo;
    }

    public function remove(): void
    {
        $fileName = $this->photo->getFileName();
        $this->removeEntity($this->photo);
        foreach (array_column(PhotoType::cases(), 'value') as $type) {
            $this->filesystem->remove(PhotoUtils::getPath($this->kernel->getProjectDir(), $type, $fileName));
        }
    }

    public function set(Photo $photo): self
    {
        $this->photo = $photo;
        return $this;
    }
}
