<?php

namespace App\Controller\Web;

use App\Config\Message\NoteMessages;
use App\Form\NoteForm;
use App\Services\Entity\NoteService;
use App\Services\Factory\NoteFactory;
use App\ViewData\Home\IndexViewData;
use Symfony\Component\HttpFoundation\Response;

final class HomeController extends BaseController
{
    private const INDEX_TEMPLATE = 'home/index';
    public const INDEX_URL = 'home.index';

    public function destroy(int $id, NoteService $noteService): Response
    {
        if ($noteService->find($id)) {
            $noteService->remove();
        } else {
            $this->addErrorMessage(NoteMessages::DELETE_INCORRECT);
        }

        return $this->redirect($this->generateUrl(self::INDEX_URL));
    }

    public function index(IndexViewData $viewData): Response
    {
        $viewData->addForm($this->createForm(NoteForm::class));

        return $this->render(self::INDEX_TEMPLATE, $viewData->getOptions());
    }

    public function store(NoteFactory $noteFactory, IndexViewData $viewData): Response
    {
        $form = $this->createForm(NoteForm::class);
        if ($noteFactory->create($form, $this->request)) {
            return $this->redirectBack();
        }
        $viewData->addForm($form);

        return $this->render(self::INDEX_TEMPLATE, $viewData->getOptions());
    }

    protected function getActive(): int
    {
        return -1;
    }
}
