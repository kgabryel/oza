<?php

namespace App\ViewData\QuickLists;

use App\Config\ViewParameters;
use App\Entity\QuickList\ClipboardPosition;
use App\Repository\QuickList\ClipboardPositionRepository;
use App\Services\Transformer\QuickListClipboardPositionTransformer;
use App\ViewData\ViewData;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorageInterface;

class FormViewData extends ViewData
{
    public function __construct(
        ClipboardPositionRepository $clipboardPositionRepository,
        TokenStorageInterface $tokenStorage,
        SessionInterface $session
    ) {
        parent::__construct($session);
        $user = $tokenStorage->getToken()->getUser();
        $this->options[ViewParameters::CLIPBOARD_POSITIONS] = array_map(
            static fn(ClipboardPosition $position): array => QuickListClipboardPositionTransformer::toArray($position),
            $clipboardPositionRepository->findBy(['user' => $user], ['id' => 'DESC'])
        );
        $this->options[ViewParameters::ADD_EMPTY_POSITION] = false;
    }

    public function addEmptyPosition(): self
    {
        $this->options[ViewParameters::ADD_EMPTY_POSITION] = true;

        return $this;
    }

    public function addForm(FormInterface $form): self
    {
        $this->options[ViewParameters::FORM] = $form->createView();

        return $this;
    }

    public function addId(int $id): self
    {
        $this->options[ViewParameters::ID] = $id;

        return $this;
    }
}
