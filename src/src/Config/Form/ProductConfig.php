<?php

namespace App\Config\Form;

abstract class ProductConfig
{
    public const BARCODE_LABEL = 'Kod kreskowy';
    public const BRAND_LABEL = 'Marka';
    public const DEFAULT_AMOUNT_LABEL = 'Domyślna ilość';
    public const DESCRIPTION_LABEL = 'Notatka';
    public const NAME_BARCODE_LENGTH = 13;
    public const NAME_LABEL = 'Nazwa';
    public const NAME_MAX_LENGTH = 255;
    public const PRODUCTS_GROUPS_LABEL = 'Grupy produktów';
    public const PRODUCTS_GROUPS_UNITS_LABEL = 'Jednostka bazowa grupy produktów';
    public const UNIT_LABEL = 'Jednostka bazowa';
}
