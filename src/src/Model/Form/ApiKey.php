<?php

namespace App\Model\Form;

use App\Entity\Application;

class ApiKey
{
    private ?Application $application;
    private ?string $key;

    public function __construct()
    {
        $this->application = null;
        $this->key = null;
    }

    public function getApplication(): ?Application
    {
        return $this->application;
    }

    public function setApplication(?Application $application): void
    {
        $this->application = $application;
    }

    public function getKey(): ?string
    {
        return $this->key;
    }

    public function setKey(?string $key): void
    {
        $this->key = $key;
    }
}
