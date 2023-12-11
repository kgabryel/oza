<?php

namespace App\Services\Entity;

use App\Config\Message\ProductsGroupMessages;
use App\Controller\Web\BaseController;
use App\Entity\ProductsGroup;
use App\Model\Form\EditProductsGroup;
use App\Model\Form\Photo;
use App\Repository\ProductsGroupRepository;
use App\Utils\FormUtils;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Session\Flash\FlashBagInterface;
use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorageInterface;

class ProductsGroupService extends EntityService
{
    private ProductsGroup $productsGroup;
    private ProductsGroupRepository $productsGroupRepository;

    public function __construct(
        FlashBagInterface $flashBag,
        EntityManagerInterface $entityManager,
        TokenStorageInterface $tokenStorage,
        ProductsGroupRepository $productsGroupRepository
    ) {
        parent::__construct($flashBag, $entityManager, $tokenStorage);
        $this->productsGroupRepository = $productsGroupRepository;
    }

    public function changeMainPhoto(FormInterface $form, Request $request): bool
    {
        $form->submit(FormUtils::getJsonContent($request));
        if (!$form->isSubmitted() || !$form->isValid()) {
            return false;
        }
        /** @var Photo $data */
        $data = $form->getData();
        $this->productsGroup->setMainPhoto($data->getPhoto());
        $this->saveEntity($this->productsGroup);
        return true;
    }

    public function find(int $id): bool
    {
        $productsGroup = $this->productsGroupRepository->findById($id, $this->user);

        if ($productsGroup === null) {
            return false;
        }
        $this->productsGroup = $productsGroup;

        return true;
    }

    public function getProductsGroup(): ProductsGroup
    {
        return $this->productsGroup;
    }

    public function getUnits(): array
    {
        $result = [];
        $unit = $this->productsGroup->getBaseUnit();
        $result['units'] = FormUtils::unitSelectOptions($unit);
        $result['default'] = $unit->getId();

        return $result;
    }

    public function remove(PhotoService $photoService): void
    {
        $this->flashBag->add(BaseController::SUCCESS_MESSAGE, ProductsGroupMessages::DELETE_CORRECT);
        foreach ($this->productsGroup->getPhotos() as $photo) {
            $photoService->set($photo)->remove();
        }
        $this->removeEntity($this->productsGroup);
    }

    public function update(FormInterface $form, Request $request): bool
    {
        $form->handleRequest($request);
        if (!$form->isSubmitted() || !$form->isValid()) {
            return false;
        }
        /** @var EditProductsGroup $data */
        $data = $form->getData();
        $this->productsGroup->setName($data->getName());
        $this->productsGroup->setNote($data->getNote());
        $this->productsGroup->setBaseUnit($data->getBaseUnit());
        $this->saveEntity($this->productsGroup);
        $this->flashBag->add(BaseController::SUCCESS_MESSAGE, ProductsGroupMessages::UPDATE_CORRECT);

        return true;
    }
}
