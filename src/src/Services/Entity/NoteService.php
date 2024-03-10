<?php

namespace App\Services\Entity;

use App\Config\Message\NoteMessages;
use App\Controller\Web\BaseController;
use App\Entity\Note;
use App\Repository\NoteRepository;
use App\Services\UserService;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Session\Flash\FlashBagInterface;

class NoteService extends EntityService
{
    private Note $note;
    private NoteRepository $noteRepository;

    public function __construct(
        FlashBagInterface $flashBag,
        EntityManagerInterface $entityManager,
        UserService $userService,
        NoteRepository $noteRepository
    ) {
        parent::__construct($flashBag, $entityManager, $userService);
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
