<?php

namespace App\ViewData;

use App\Config\Settings;
use App\Config\ViewParameters;
use App\Services\Filters\Filter;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\HttpFoundation\Session\SessionInterface;

abstract class IndexViewData extends ViewData
{
    public function __construct(SessionInterface $session, Filter $filter, string $tableName, string $tableId)
    {
        parent::__construct($session);
        $this->options[ViewParameters::TABLE_NAME] = $tableName;
        $this->options[ViewParameters::LIMIT] = $session->get(Settings::PAGINATION_COUNT);
        $this->options[ViewParameters::FIND_FORM] = $filter->getForm()->createView();
        $this->options[ViewParameters::TABLE_ID] = $tableId;
    }

    public function addCreateForm(FormInterface $form): self
    {
        $this->options[ViewParameters::CREATE_FORM] = $form->createView();

        return $this;
    }
}
