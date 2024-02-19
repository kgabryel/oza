<?php

namespace App\Services\Transformer;

use App\Entity\Unit;

class UnitTransformer
{
    public static function toArray(Unit $unit): array
    {
        $main = $unit->getMain();
        $result = [
            'id' => $unit->getId(),
            'name' => $unit->getName(),
            'shortcut' => $unit->getShortcut()
        ];
        if ($main === null) {
            $subUnits = [];
            foreach ($unit->getUnits() as $subUnit) {
                $subUnits[] = [
                    'id' => $subUnit->getId(),
                    'name' => $subUnit->getName(),
                    'shortcut' => $subUnit->getShortcut(),
                    'converter' => $subUnit->getConverter()
                ];
            }
            $result['isMain'] = true;
            $result['subUnits'] = $subUnits;
        } else {
            $result['converter'] = $unit->getConverter();
            $result['main'] = [
                'id' => $main->getId(),
                'shortcut' => $main->getShortcut(),
                'name' => $main->getName()
            ];
        }

        return $result;
    }
}
