<?php

namespace App\Entity;

use App\Repository\ApplicationRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Stringable;

/**
 * @ORM\Entity(repositoryClass=ApplicationRepository::class)
 */
class Application implements Stringable
{
    /**
     * @ORM\OneToMany(targetEntity=ApiKey::class, mappedBy="application")
     * @ORM\OrderBy({"id" = "DESC"})
     */
    private Collection $apiKeys;
    /**
     * @ORM\Column(type="string", length=255)
     */
    private string $href;
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private int $id;
    /**
     * @ORM\Column(type="string", length=255)
     */
    private string $name;

    public function __construct()
    {
        $this->apiKeys = new ArrayCollection();
    }

    public function __toString()
    {
        return $this->name;
    }

    public function addApiKey(ApiKey $apiKey): self
    {
        if (!$this->apiKeys->contains($apiKey)) {
            $this->apiKeys[] = $apiKey;
            $apiKey->setApplication($this);
        }

        return $this;
    }

    /**
     * @return Collection|ApiKey[]
     */
    public function getApiKeys(): Collection
    {
        return $this->apiKeys;
    }

    public function getHref(): string
    {
        return $this->href;
    }

    public function setHref(string $href): self
    {
        $this->href = $href;

        return $this;
    }

    public function getId(): int
    {
        return $this->id;
    }

    public function getName(): string
    {
        return $this->name;
    }

    public function setName(string $name): self
    {
        $this->name = $name;

        return $this;
    }

    public function removeApiKey(ApiKey $apiKey): self
    {
        if ($this->apiKeys->removeElement($apiKey)) {
            $apiKey->setApplication(null);
        }

        return $this;
    }
}
