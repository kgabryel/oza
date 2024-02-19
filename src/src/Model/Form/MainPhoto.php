<?php

namespace App\Model\Form;

use App\Entity\Photo;

class MainPhoto
{
    private ?Photo $photo;

    public function __construct()
    {
        $this->photo = null;
    }

    public function getPhoto(): ?Photo
    {
        return $this->photo;
    }

    public function setPhoto(?Photo $photo): void
    {
        $this->photo = $photo;
    }
}
