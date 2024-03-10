<?php

namespace App\ViewData\Home;

use App\Config\ViewParameters;
use App\Repository\AlertRepository;
use App\Repository\NoteRepository;
use App\Services\UserService;
use App\ViewData\ViewData;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\HttpFoundation\Session\SessionInterface;

class IndexViewData extends ViewData
{
    public function __construct(
        AlertRepository $alertRepository,
        NoteRepository $noteRepository,
        UserService $userService,
        SessionInterface $session
    ) {
        parent::__construct($session);
        $user = $userService->getUser();
        $this->options[ViewParameters::NOTES] = $noteRepository->findBy(['user' => $user], ['id' => 'DESC']);
        $this->options[ViewParameters::ALERTS] = $alertRepository->findBy(
            ['user' => $user, 'isActive' => true],
            ['id' => 'DESC']
        );
    }

    public function addForm(FormInterface $form): self
    {
        $this->options[ViewParameters::FORM] = $form->createView();

        return $this;
    }
}
