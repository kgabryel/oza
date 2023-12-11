<?php

namespace App\Services\Factory;

use App\Config\Message\NoteMessages;
use App\Controller\Web\BaseController;
use App\Entity\Note;
use App\Model\Form\Note as NoteModel;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\HttpFoundation\Request;

class NoteFactory extends EntityFactory
{
    public function create(FormInterface $form, Request $request): bool
    {
        $form->handleRequest($request);
        if (!$form->isSubmitted() || !$form->isValid()) {
            return false;
        }
        /** @var NoteModel $data */
        $data = $form->getData();
        $note = new Note();
        $note->setUser($this->user);
        $note->setContent($data->getContent());
        $this->saveEntity($note);
        $this->flashBag->add(BaseController::SUCCESS_MESSAGE, NoteMessages::CREATED_CORRECTLY);

        return true;
    }
}
