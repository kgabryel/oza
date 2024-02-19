<?php

namespace App\Services\Entity;

use App\Config\Message\ProductMessages;
use App\Controller\Web\BaseController;
use App\Entity\Product;
use App\Model\Form\EditProduct;
use App\Model\Form\Photo;
use App\Repository\ProductRepository;
use App\Utils\FormUtils;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Session\Flash\FlashBagInterface;
use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorageInterface;

class ProductService extends EntityService
{
    private Product $product;
    private ProductRepository $productRepository;

    public function __construct(
        FlashBagInterface $flashBag,
        EntityManagerInterface $entityManager,
        TokenStorageInterface $tokenStorage,
        ProductRepository $productRepository
    ) {
        parent::__construct($flashBag, $entityManager, $tokenStorage);
        $this->productRepository = $productRepository;
    }

    public function changeMainPhoto(FormInterface $form, Request $request): bool
    {
        $form->submit(FormUtils::getJsonContent($request));
        if (!$form->isSubmitted() || !$form->isValid()) {
            return false;
        }
        /** @var Photo $data */
        $data = $form->getData();
        $this->product->setMainPhoto($data->getPhoto());
        $this->saveEntity($this->product);
        return true;
    }

    public function find(int $id): bool
    {
        $product = $this->productRepository->findById($id, $this->user);

        if ($product === null) {
            return false;
        }
        $this->product = $product;

        return true;
    }

    public function findByBarcode(string $code): bool
    {
        $product = $this->productRepository->findOneBy([
            'barcode' => $code,
            'user' => $this->user
        ]);

        if ($product === null) {
            return false;
        }
        $this->product = $product;

        return true;
    }

    public function getProduct(): Product
    {
        return $this->product;
    }

    public function getUnits(): array
    {
        $result = [];
        $unit = $this->product->getUnit();
        $result['defaultAmount'] = $this->product->getDefaultAmount() ?? 1.0;
        $result['default'] = $unit->getId();
        $result['units'] = FormUtils::unitSelectOptions($unit);

        return $result;
    }

    public function remove(PhotoService $photoService): void
    {
        $this->flashBag->add(BaseController::SUCCESS_MESSAGE, ProductMessages::DELETE_CORRECT);
        foreach ($this->product->getPhotos() as $photo) {
            $photoService->set($photo)->remove();
        }
        $this->removeEntity($this->product);
    }

    public function update(FormInterface $form, Request $request): bool
    {
        $form->handleRequest($request);
        if (!$form->isSubmitted() || !$form->isValid()) {
            return false;
        }
        /** @var EditProduct $data */
        $data = $form->getData();
        $this->product->setName($data->getName());
        $this->product->setNote($data->getNote());
        $this->product->setUnit($data->getUnit());
        $this->product->setBrand($data->getBrand());
        $this->product->setBarcode($data->getBarcode());
        $this->product->clearGroups();
        foreach ($data->getProductsGroups() as $group) {
            $this->product->addGroup($group);
        }
        $this->saveEntity($this->product);
        $this->flashBag->add(BaseController::SUCCESS_MESSAGE, ProductMessages::UPDATE_CORRECT);

        return true;
    }
}
