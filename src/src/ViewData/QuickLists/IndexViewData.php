<?php

namespace App\ViewData\QuickLists;

use App\Config\Settings;
use App\Config\ViewParameters;
use App\Entity\QuickList\ClipboardPosition;
use App\Entity\QuickList\QuickList;
use App\Repository\QuickList\ClipboardPositionRepository;
use App\Repository\QuickList\ListRepository;
use App\Services\Transformer\QuickListClipboardPositionTransformer;
use App\Services\Transformer\QuickListTransformer;
use App\Services\UserService;
use App\ViewData\ViewData;
use Symfony\Component\HttpFoundation\Session\SessionInterface;

class IndexViewData extends ViewData
{
    protected array $options;

    public function __construct(
        ListRepository $listRepository,
        UserService $userService,
        ClipboardPositionRepository $clipboardPositionRepository,
        SessionInterface $session
    ) {
        parent::__construct($session);
        $user = $userService->getUser();
        $this->options[ViewParameters::HIDE] = $session->get(Settings::HIDE_BOUGHT);
        $this->options[ViewParameters::LISTS] = array_map(
            static fn(QuickList $quickList): array => QuickListTransformer::toArray($quickList),
            $listRepository->findBy(['user' => $user], ['createdAt' => 'DESC'])
        );
        $this->options[ViewParameters::CLIPBOARD_POSITIONS] = array_map(
            static fn(ClipboardPosition $position): array => QuickListClipboardPositionTransformer::toArray($position),
            $clipboardPositionRepository->findBy(['user' => $user], ['id' => 'DESC'])
        );
    }
}
