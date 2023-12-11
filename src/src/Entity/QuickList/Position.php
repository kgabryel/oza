<?php

namespace App\Entity\QuickList;

use App\Repository\QuickList\PositionRepository;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=PositionRepository::class)
 * @ORM\Table(name="quick_lists_positions")
 */
class Position
{
    /**
     * @ORM\Column(type="boolean")
     */
    private bool $checked;
    /**
     * @ORM\Column(type="string", length=255)
     */
    private string $content;
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private int $id;
    /**
     * @ORM\ManyToOne(targetEntity=QuickList::class, inversedBy="positions")
     * @ORM\JoinColumn(nullable=false)
     */
    private QuickList $list;

    public function __construct()
    {
        $this->checked = false;
    }

    public function check(): self
    {
        $this->checked = true;

        return $this;
    }

    public function getContent(): string
    {
        return $this->content;
    }

    public function setContent(string $content): self
    {
        $this->content = $content;

        return $this;
    }

    public function getId(): int
    {
        return $this->id;
    }

    public function getList(): QuickList
    {
        return $this->list;
    }

    public function setList(QuickList $list): self
    {
        $this->list = $list;

        return $this;
    }

    public function isChecked(): bool
    {
        return $this->checked;
    }

    public function unCheck(): self
    {
        $this->checked = false;

        return $this;
    }
}
