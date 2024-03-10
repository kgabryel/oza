<?php

namespace App\Services\Entity;

use App\Config\Message\BrandsMessages;
use App\Controller\Web\BaseController;
use App\Entity\Brand;
use App\Model\Form\Brand as BrandModel;
use App\Repository\BrandRepository;
use App\Services\UserService;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Session\Flash\FlashBagInterface;

class BrandService extends EntityService
{
    private Brand $brand;
    private BrandRepository $brandRepository;

    public function __construct(
        FlashBagInterface $flashBag,
        EntityManagerInterface $entityManager,
        UserService $userService,
        BrandRepository $brandRepository
    ) {
        parent::__construct($flashBag, $entityManager, $userService);
        $this->brandRepository = $brandRepository;
    }

    public function find(int $id): bool
    {
        $brand = $this->brandRepository->findById($id, $this->user);

        if ($brand === null) {
            return false;
        }
        $this->brand = $brand;

        return true;
    }

    public function getBrand(): Brand
    {
        return $this->brand;
    }

    public function remove(): void
    {
        foreach ($this->brand->getProducts() as $product) {
            $product->setBrand(null);
            $this->saveEntity($product);
        }
        $this->flashBag->add(BaseController::SUCCESS_MESSAGE, BrandsMessages::DELETE_CORRECT);
        $this->removeEntity($this->brand);
    }

    public function update(FormInterface $form, Request $request): bool
    {
        $form->handleRequest($request);
        if (!$form->isSubmitted() || !$form->isValid()) {
            return false;
        }
        /** @var BrandModel $data */
        $data = $form->getData();
        $this->brand->setName($data->getName())
            ->setDescription($data->getDescription());
        $this->saveEntity($this->brand);
        $this->flashBag->add(BaseController::SUCCESS_MESSAGE, BrandsMessages::UPDATE_CORRECT);

        return true;
    }
}
