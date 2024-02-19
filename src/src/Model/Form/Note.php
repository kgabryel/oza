<?php

namespace App\Model\Form;

class Note
{
    private ?string $content;

    public function __construct()
    {
        $this->content = null;
    }

    public function getContent(): ?string
    {
        return $this->content;
    }

    public function setContent(?string $content): void
    {
        $this->content = $content;
    }
}
