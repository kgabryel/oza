<?php

namespace App\Services\Factory;

use App\Config\Photo as PhotoConfig;
use App\Config\PhotoType;
use App\Entity\Photo;
use App\Entity\Product;
use App\Entity\ProductsGroup;
use App\Model\Form\Photo as PhotoModel;
use App\Services\UserService;
use App\Utils\FormUtils;
use App\Utils\PhotoUtils;
use Doctrine\ORM\EntityManagerInterface;
use Exception;
use Imagick;
use Ramsey\Uuid\Uuid;
use Symfony\Component\Filesystem\Filesystem;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Session\Flash\FlashBagInterface;
use Symfony\Component\HttpKernel\KernelInterface;

class PhotoFactory extends EntityFactory
{
    private string $fileName;
    private Filesystem $filesystem;
    private Imagick $image;
    private KernelInterface $kernel;
    private Imagick $originalImage;

    public function __construct(
        FlashBagInterface $flashBag,
        EntityManagerInterface $entityManager,
        UserService $userService,
        KernelInterface $kernel
    ) {
        parent::__construct($flashBag, $entityManager, $userService);
        $this->filesystem = new Filesystem();
        $this->fileName = Uuid::uuid4()->toString();
        $this->image = new Imagick();
        $this->originalImage = new Imagick();
        $this->kernel = $kernel;
    }

    public function create(
        FormInterface $form,
        Request $request,
        ?Product $product,
        ?ProductsGroup $productsGroup
    ): false|Photo {
        $form->submit(FormUtils::getJsonContent($request));
        if (!$form->isSubmitted() || !$form->isValid()) {
            return false;
        }
        /** @var PhotoModel $data */
        $data = $form->getData();
        $base64 = $data->getPhoto() ?? '';
        $position = strpos($base64, 'base64,');
        if ($position === false) {
            return false;
        }
        $base64 = substr($base64, $position + strlen('base64,'));
        try {
            $this->originalImage->readImageBlob(base64_decode($base64));
            if (!$this->validatePhoto()) {
                return false;
            }
            $this->image = $this->originalImage->clone();
            $this->saveFile(PhotoType::ORIGINAL);
            $this->image->scaleImage(
                PhotoConfig::getWidth(PhotoType::MEDIUM),
                PhotoConfig::getHeight(PhotoType::MEDIUM)
            );
            $this->saveFile(PhotoType::MEDIUM);
            $this->image = $this->originalImage->clone();
            $this->image->scaleImage(PhotoConfig::getWidth(PhotoType::SMALL), PhotoConfig::getHeight(PhotoType::SMALL));
            $this->saveFile(PhotoType::SMALL);
        } catch (Exception) {
            return false;
        }
        $photo = new Photo();
        $photo->setUser($this->user)
            ->setFileName($this->fileName)
            ->setHeight($this->originalImage->getImageHeight())
            ->setWidth($this->originalImage->getImageWidth())
            ->setType($this->originalImage->getImageMimeType())
            ->setProduct($product)
            ->setProductsGroup($productsGroup);
        $this->saveEntity($photo);

        return $photo;
    }

    private function validatePhoto(): bool
    {
        if ($this->originalImage->getImageWidth() !== $this->originalImage->getImageHeight()) {
            return false;
        }
        if ($this->originalImage->getImageHeight() < PhotoConfig::MIN_HEIGHT) {
            return false;
        }

        return $this->originalImage->getImageWidth() < PhotoConfig::MIN_WIDTH;
    }

    private function saveFile(PhotoType $type): void
    {
        if (!$this->filesystem->exists(PhotoUtils::getFilesDirectory($this->kernel->getProjectDir()))) {
            $this->filesystem->mkdir(PhotoUtils::getFilesDirectory($this->kernel->getProjectDir()));
        }
        if (!$this->filesystem->exists(PhotoUtils::getTypeDirectory($this->kernel->getProjectDir(), $type->value))) {
            $this->filesystem->mkdir(PhotoUtils::getTypeDirectory($this->kernel->getProjectDir(), $type->value));
        }
        $this->filesystem->touch(PhotoUtils::getPath($this->kernel->getProjectDir(), $type->value, $this->fileName));
        $this->filesystem->appendToFile(
            PhotoUtils::getPath($this->kernel->getProjectDir(), $type->value, $this->fileName),
            $this->image->getImageBlob()
        );
    }
}
