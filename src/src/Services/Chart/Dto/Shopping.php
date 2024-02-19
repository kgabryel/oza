<?php

namespace App\Services\Chart\Dto;

use App\Config\Chart;
use JsonSerializable;

class Shopping implements JsonSerializable
{
    private string $name;
    /** @var Position[] */
    private array $positions;

    public function __construct(string $name, array $positions)
    {
        $this->name = $name;
        $this->positions = $positions;
    }

    public function getName(): string
    {
        return $this->name;
    }

    public function getPositions(): array
    {
        return $this->positions;
    }

    public function setPositions(array $positions): void
    {
        $this->positions = $positions;
    }

    public function jsonSerialize(): array
    {
        return [
            Chart::NAME => $this->name,
            Chart::POSITIONS => $this->positions
        ];
    }
}
