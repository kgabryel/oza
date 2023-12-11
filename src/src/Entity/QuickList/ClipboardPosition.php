<?php

namespace App\Entity\QuickList;

use App\Entity\User;
use App\Repository\QuickList\ClipboardPositionRepository;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=ClipboardPositionRepository::class)
 * @ORM\Table(name="quick_lists_clipboard_positions")
 */
class ClipboardPosition
{
    /**
     * @ORM\Column(type="string", length=255)
     */
    private string $content;
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private int $id;
    /**
     * @ORM\ManyToOne(targetEntity=User::class, inversedBy="clipboardPositions")
     * @ORM\JoinColumn(nullable=false)
     */
    private User $user;

    public function getContent(): ?string
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

    public function getUser(): User
    {
        return $this->user;
    }

    public function setUser(User $user): self
    {
        $this->user = $user;

        return $this;
    }
}
