<?php

namespace App\Services\SupplyAlerts;

use App\Entity\Supply;
use App\Entity\SupplyAlert;
use App\Services\SupplyAlerts\Dto\AlertAmount;
use App\Utils\UnitUtils;

class SupplyAlertsService
{
    /** @var SupplyAlert[] */
    private array $alerts;

    public function __construct()
    {
        $this->alerts = [];
    }

    public function getAlertToActivate(Supply $supply): ?SupplyAlert
    {
        foreach ($this->alerts as $alert) {
            $parsedUnit = UnitUtils::parseAmount(
                $alert->getAmount(),
                $alert->getUnit(),
                $supply->getGroup()->getBaseUnit()
            );
            if ($parsedUnit > $supply->getAmount()) {
                return $alert;
            }
        }

        return null;
    }

    public function getAlerts(): array
    {
        return $this->alerts;
    }

    /**
     * @param  SupplyAlert[]  $alerts
     *
     * @return $this
     */
    public function setAlerts(array $alerts): self
    {
        $this->alerts = $alerts;

        return $this;
    }

    public function sort(): self
    {
        if ($this->alerts === []) {
            return $this;
        }
        $baseUnit = $this->alerts[0]->getUnit()->getMain() ?? $this->alerts[0]->getUnit();
        $values = [];
        foreach ($this->alerts as $key => $alert) {
            $values[] = new AlertAmount($key, $alert, $baseUnit);
        }
        usort($values, static fn(AlertAmount $a, AlertAmount $b): int => $a->getAmount() <=> $b->getAmount());
        $tmp = $this->alerts;
        $this->alerts = array_map(static fn(AlertAmount $amount) => $tmp[$amount->getIndex()], $values);

        return $this;
    }
}
