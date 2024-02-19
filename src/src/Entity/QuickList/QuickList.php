<?php

namespace App\Entity\QuickList;

use App\Entity\User;
use App\Repository\QuickList\ListRepository;
use DateTime;
use DateTimeInterface;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=ListRepository::class)
 * @ORM\Table(name="quick_lists")
 */
class QuickList
{
    /**
     * @ORM\Column(type="datetime")
     */
    private DateTimeInterface $createdAt;
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private int $id;
    /**
     * @ORM\Column(type="string", length=255)
     */
    private ?string $name;
    /**
     * @ORM\Column(type="text", nullable=true)
     */
    private ?string $note;
    /**
     * @ORM\OneToMany(targetEntity=Position::class, mappedBy="list", cascade={"all"})
     * @ORM\OrderBy({"id" = "ASC"})
     */
    private Collection $positions;
    /**
     * @ORM\ManyToOne(targetEntity=User::class, inversedBy="quickLists")
     * @ORM\JoinColumn(nullable=false)
     */
    private User $user;

    public function __construct()
    {
        $this->createdAt = new DateTime();
        $this->positions = new ArrayCollection();
    }

    public function addPositions(array $positions): self
    {
        foreach ($positions as $position) {
            $positionEntity = new Position();
            $positionEntity->setContent($position->getPosition());
            $this->addPosition($positionEntity);
        }

        return $this;
    }

    public function addPosition(Position $position): self
    {
        if (!$this->positions->contains($position)) {
            $this->positions[] = $position;
            $position->setList($this);
        }

        return $this;
    }

    public function getCreatedAt(): DateTimeInterface
    {
        return $this->createdAt;
    }

    public function setCreatedAt(DateTimeInterface $createdAt): self
    {
        $this->createdAt = $createdAt;

        return $this;
    }

    public function getId(): int
    {
        return $this->id;
    }

    public function getName(): ?string
    {
        return $this->name;
    }

    public function setName(?string $name): self
    {
        if ($name === null) {
            $name = '';
        }
        $this->name = $name;

        return $this;
    }

    public function getNote(): ?string
    {
        return $this->note;
    }

    public function setNote(?string $note): self
    {
        $this->note = $note;

        return $this;
    }

    /**
     * @return Collection|Position[]
     */
    public function getPositions(): Collection
    {
        return $this->positions;
    }

    public function setPositions(ArrayCollection $positions): self
    {
        $this->positions = new ArrayCollection();
        foreach ($positions as $position) {
            $this->addPosition($position);
        }

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

    public function removePosition(Position $position): self
    {
        if ($this->positions->contains($position)) {
            $this->positions->removeElement($position);
        }

        return $this;
    }
}
