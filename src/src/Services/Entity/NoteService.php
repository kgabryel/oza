<?php

namespace App\Services\Entity;

use App\Config\Message\NoteMessages;
use App\Controller\Web\BaseController;
use App\Entity\Note;
use App\Repository\NoteRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Session\Flash\FlashBagInterface;
use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorageInterface;

class NoteService extends EntityService
{
    private Note $note;
    private NoteRepository $noteRepository;

    public function __construct(
        FlashBagInterface $flashBag,
        EntityManagerInterface $entityManager,
        TokenStorageInterface $tokenStorage,
        NoteRepository $noteRepository
    ) {
        parent::__construct($flashBag, $entityManager, $tokenStorage);
        $this->noteRepository = $noteRepository;
    }

    public function find(int $id): bool
    {
        $note = $this->noteRepository->findById($id, $this->user);
        if ($note === null) {
            return false;
        }
        $this->note = $note;

        return true;
    }

    public function remove(): void
    {
        $this->flashBag->add(BaseController::SUCCESS_MESSAGE, NoteMessages::DELETE_CORRECT);
        $this->removeEntity($this->note);
    }
}
