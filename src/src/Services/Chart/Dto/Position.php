<?php

namespace App\Services\Chart\Dto;

use App\Config\Chart;
use App\Entity\Shopping;
use DateTimeInterface;
use JsonSerializable;

class Position implements JsonSerializable
{
    private DateTimeInterface $date;
    private float $price;

    public function __construct(Shopping $shopping)
    {
        $this->price = round($shopping->getPrice(), 2);
        $this->date = $shopping->getDate();
    }

    public function getPrice(): float
    {
        return $this->price;
    }

    public function getDate(): DateTimeInterface
    {
        return $this->date;
    }

    public function jsonSerialize(): array
    {
        return $this->toArray();
    }

    public function toArray(): array
    {
        return [
            Chart::PRICE => $this->price,
            Chart::DATE => $this->date->getTimestamp()
        ];
    }
}
