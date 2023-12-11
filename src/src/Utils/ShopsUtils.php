<?php

namespace App\Utils;

class ShopsUtils
{
    public static function getPowerSet(array $shops, int $limit): array
    {
        $subset = [];
        $results = [[]];
        foreach ($shops as $element) {
            foreach ($results as $combination) {
                $result = array_merge([$element], $combination);
                $results[] = $result;
                if (count($result) === $limit) {
                    $subset[] = $result;
                }
            }
        }

        return $subset;
    }
}
