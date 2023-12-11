<?php

namespace App\ViewData;

use App\Config\Settings;
use App\Config\ViewParameters;
use Symfony\Component\HttpFoundation\Session\SessionInterface;

abstract class ViewData
{
    protected array $options;

    public function __construct(SessionInterface $session)
    {
        $this->options = [ViewParameters::AUTOCOMPLETE => $session->get(Settings::AUTOCOMPLETE)];
    }

    public function addOption(string $key, mixed $value): self
    {
        $this->options[$key] = $value;

        return $this;
    }

    public function getOptions(): array
    {
        return $this->options;
    }
}
