<?php

namespace App\Services\Factory;

use App\Config\Message\AlertMessages;
use App\Controller\Web\BaseController;
use App\Entity\Alert;
use App\Model\Form\Alert as AlertModel;
use App\Repository\AlertTypeRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Session\Flash\FlashBagInterface;
use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorageInterface;

class AlertFactory extends EntityFactory
{
    private AlertTypeRepository $alertTypeRepository;

    public function __construct(
        FlashBagInterface $flashBag,
        EntityManagerInterface $entityManager,
        TokenStorageInterface $tokenStorage,
        AlertTypeRepository $alertTypeRepository
    ) {
        parent::__construct($flashBag, $entityManager, $tokenStorage);
        $this->alertTypeRepository = $alertTypeRepository;
    }

    public function create(FormInterface $form, Request $request): bool
    {
        $form->handleRequest($request);
        if (!$form->isSubmitted() || !$form->isValid()) {
            return false;
        }
        /** @var AlertModel $data */
        $data = $form->getData();
        foreach ($data->getTypes() as $type) {
            $alert = new Alert();
            $alert->setUser($this->user);
            $data->isActive() ? $alert->activate() : $alert->deactivate();
            $alert->setDescription($data->getDescription());
            $alert->setType($this->alertTypeRepository->find($type));
            $this->saveEntity($alert);
        }
        $this->flashBag->add(BaseController::SUCCESS_MESSAGE, AlertMessages::CREATED_CORRECTLY);

        return true;
    }
}
