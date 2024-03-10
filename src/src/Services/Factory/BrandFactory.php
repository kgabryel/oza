<?php

namespace App\Services\Factory;

use App\Config\Message\BrandsMessages;
use App\Controller\Web\BaseController;
use App\Entity\Brand;
use App\Model\Form\Brand as BrandModel;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\HttpFoundation\Request;

class BrandFactory extends EntityFactory
{
    public function create(FormInterface $form, Request $request): bool
    {
        $form->handleRequest($request);
        if (!$form->isSubmitted() || !$form->isValid()) {
            return false;
        }
        /** @var BrandModel $data */
        $data = $form->getData();
        $brand = new Brand();
        $brand->setUser($this->user)
            ->setName($data->getName())
            ->setDescription($data->getDescription());
        $this->saveEntity($brand);
        $this->flashBag->add(BaseController::SUCCESS_MESSAGE, BrandsMessages::CREATED_CORRECTLY);

        return true;
    }
}
