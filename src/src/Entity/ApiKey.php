<?php

namespace App\Entity;

use App\Repository\ApiKeyRepository;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Table(name="api_key",
 *    uniqueConstraints={
 *        @ORM\UniqueConstraint(name="application_key",
 *            columns={"key", "application_id"})
 *    }
 * )
 * @ORM\Entity(repositoryClass=ApiKeyRepository::class)
 */
class ApiKey
{
    /**
     * @ORM\Column(type="boolean")
     */
    private bool $active;
    /**
     * @ORM\ManyToOne(targetEntity=Application::class, inversedBy="apiKeys", fetch="EAGER")
     */
    private ?Application $application;
    /**
     * @ORM\Column(type="text", nullable=true)
     */
    private ?string $description;
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private int $id;
    /**
     * @ORM\Column(type="string", length=128)
     */
    private string $key;
    /**
     * @ORM\ManyToOne(targetEntity=User::class, inversedBy="apiKeys")
     * @ORM\JoinColumn(nullable=false)
     */
    private User $user;

    public function activate(): self
    {
        $this->active = true;

        return $this;
    }

    public function deactivate(): self
    {
        $this->active = false;

        return $this;
    }

    public function getApplication(): ?Application
    {
        return $this->application;
    }

    public function setApplication(?Application $application): self
    {
        $this->application = $application;

        return $this;
    }

    public function getDescription(): ?string
    {
        return $this->description;
    }

    public function setDescription(?string $description): self
    {
        $this->description = $description;

        return $this;
    }

    public function getId(): int
    {
        return $this->id;
    }

    public function getKey(): string
    {
        return $this->key;
    }

    public function setKey(string $key): self
    {
        $this->key = $key;

        return $this;
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

    public function isActive(): bool
    {
        return $this->active;
    }
}
