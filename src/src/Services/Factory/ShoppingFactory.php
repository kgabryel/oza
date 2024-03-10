<?php

namespace App\Services\Factory;

use App\Config\Message\ShoppingMessages;
use App\Config\ProductPosition;
use App\Controller\Web\BaseController;
use App\Entity\Shopping;
use App\Model\Form\Shopping as ShoppingModel;
use App\Model\Form\ShoppingPosition;
use App\Services\Entity\SupplyAlertService;
use App\Services\ExternalSuppliesService;
use App\Services\SupplyAlerts\SupplyAlertsService;
use App\Services\UserService;
use App\Utils\ShoppingPositionUtils;
use DateTime;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Session\Flash\FlashBagInterface;

class ShoppingFactory extends EntityFactory
{
    private SupplyAlertsService $alertsService;
    private ExternalSuppliesService $externalSuppliesService;
    private SupplyAlertService $supplyAlertService;
    private SupplyPartFactory $supplyPartFactory;

    public function __construct(
        FlashBagInterface $flashBag,
        EntityManagerInterface $entityManager,
        UserService $userService,
        SupplyPartFactory $supplyPartFactory,
        SupplyAlertService $supplyAlertService,
        SupplyAlertsService $alertsService,
        ExternalSuppliesService $externalSuppliesService
    ) {
        parent::__construct($flashBag, $entityManager, $userService);
        $this->supplyPartFactory = $supplyPartFactory;
        $this->supplyAlertService = $supplyAlertService;
        $this->alertsService = $alertsService;
        $this->externalSuppliesService = $externalSuppliesService;
    }

    public function create(FormInterface $form, Request $request): bool
    {
        $form->handleRequest($request);
        if (!$form->isSubmitted() || !$form->isValid()) {
            return false;
        }
        /** @var ShoppingModel $data */
        $data = $form->getData();
        $shop = $data->getShop();
        $date = $data->getDate();
        foreach ($data->getPositions() as $position) {
            $entity = new Shopping();
            $entity->setUser($this->user)
                ->setShop($shop)
                ->setDate(DateTime::createFromFormat('Y-m-d', $date))
                ->setShop($shop)
                ->setUnit($position->getUnit())
                ->setAmount($position->getAmount());
            $price = $position->getPrice();
            $discount = $position->getDiscount();
            if ($position->getType() === ProductPosition::PRODUCTS_GROUP) {
                $entity->setGroup($position->getProductsGroup());
            } else {
                $entity->setProduct($position->getProduct());
            }
            if ($discount !== null) {
                $price -= $discount;
            }
            $entity->setOriginalPrice($price);
            $entity->setPrice(ShoppingPositionUtils::getParsedPrice($price, $position));
            $this->entityManager->persist($entity);
            if ($position->createSupply()) {
                $this->addSupply($position);
            }
        }
        $this->entityManager->flush();
        $this->flashBag->add(BaseController::SUCCESS_MESSAGE, ShoppingMessages::CREATED_CORRECTLY);

        return true;
    }

    private function addSupply(ShoppingPosition $shoppingPosition): void
    {
        $this->supplyPartFactory->createFromShoppingPosition($shoppingPosition);
        $this->externalSuppliesService->update($shoppingPosition->getSupply());
        $this->supplyAlertService->reactivate($shoppingPosition->getSupply(), $this->alertsService);
    }
}
