<?php

namespace App\Services\Entity;

use App\Config\Message\AlertMessages;
use App\Controller\Web\BaseController;
use App\Entity\Alert;
use App\Model\Form\EditAlert;
use App\Repository\AlertRepository;
use App\Services\UserService;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Session\Flash\FlashBagInterface;

class AlertService extends EntityService
{
    private Alert $alert;
    private AlertRepository $alertRepository;

    public function __construct(
        FlashBagInterface $flashBag,
        EntityManagerInterface $entityManager,
        UserService $userService,
        AlertRepository $alertRepository
    ) {
        parent::__construct($flashBag, $entityManager, $userService);
        $this->alertRepository = $alertRepository;
    }

    public function changeStatus(bool $status): void
    {
        $status ? $this->alert->activate() : $this->alert->deactivate();
        $this->saveEntity($this->alert);
    }

    public function find(int $id): bool
    {
        $alert = $this->alertRepository->findById($id, $this->user);
        if ($alert === null) {
            return false;
        }
        $this->alert = $alert;

        return true;
    }

    public function getAlert(): Alert
    {
        return $this->alert;
    }

    public function remove(): void
    {
        $this->flashBag->add(BaseController::SUCCESS_MESSAGE, AlertMessages::DELETE_CORRECT);
        $this->removeEntity($this->alert);
    }

    public function update(FormInterface $form, Request $request): bool
    {
        $form->handleRequest($request);
        if (!$form->isSubmitted() || !$form->isValid()) {
            return false;
        }
        /** @var EditAlert $data */
        $data = $form->getData();
        $this->alert->setDescription($data->getDescription())
            ->setType($data->getType());
        $data->isActive() ? $this->alert->activate() : $this->alert->deactivate();
        $this->saveEntity($this->alert);
        $this->flashBag->add(BaseController::SUCCESS_MESSAGE, AlertMessages::UPDATE_CORRECT);

        return true;
    }
}
