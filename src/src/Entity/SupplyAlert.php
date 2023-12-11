<?php

namespace App\Entity;

use App\Repository\SupplyAlertRepository;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=SupplyAlertRepository::class)
 * @ORM\Table(name="supply_alerts")
 */
class SupplyAlert
{
    /**
     * @ORM\ManyToOne(targetEntity=Alert::class, inversedBy="supplyAlerts")
     * @ORM\JoinColumn(nullable=false)
     */
    private Alert $alert;
    /**
     * @ORM\Column(type="float")
     */
    private float $amount;
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private int $id;
    /**
     * @ORM\ManyToOne(targetEntity=Supply::class, inversedBy="alerts")
     * @ORM\JoinColumn(nullable=false)
     */
    private Supply $supply;
    /**
     * @ORM\ManyToOne(targetEntity=Unit::class, inversedBy="supplyAlerts")
     * @ORM\JoinColumn(nullable=false)
     */
    private Unit $unit;

    public function getAlert(): Alert
    {
        return $this->alert;
    }

    public function setAlert(Alert $alert): self
    {
        $this->alert = $alert;

        return $this;
    }

    public function getAmount(): float
    {
        return $this->amount;
    }

    public function setAmount(float $amount): self
    {
        $this->amount = $amount;

        return $this;
    }

    public function getId(): int
    {
        return $this->id;
    }

    public function getSupply(): Supply
    {
        return $this->supply;
    }

    public function setSupply(Supply $supply): self
    {
        $this->supply = $supply;

        return $this;
    }

    public function getUnit(): Unit
    {
        return $this->unit;
    }

    public function setUnit(Unit $unit): self
    {
        $this->unit = $unit;

        return $this;
    }
}
