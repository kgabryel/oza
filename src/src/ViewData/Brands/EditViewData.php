<?php

namespace App\ViewData\Brands;

use App\Config\Settings;
use App\Config\ViewParameters;
use App\Entity\Brand;
use App\ViewData\ViewData;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\HttpFoundation\Session\SessionInterface;

class EditViewData extends ViewData
{
    public function __construct(SessionInterface $session)
    {
        parent::__construct($session);
        $this->options[ViewParameters::LIMIT] = $session->get(Settings::PAGINATION_COUNT);
    }

    public function addEntity(Brand $brand): self
    {
        $this->options[ViewParameters::ENTITY] = $brand;

        return $this;
    }

    public function addForm(FormInterface $form): self
    {
        $this->options[ViewParameters::CREATE_FORM] = $form->createView();

        return $this;
    }
}
