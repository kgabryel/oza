<?php

namespace App\Config\Form;

abstract class SettingsConfig
{
    public const AUTOCOMPLETE = 'Automatyczne uzupełnianie włączone';
    public const CREATE_SUPPLY_LABEL = 'Domyślnie tworzyć zapas podczas dodawania grupy produktów';
    public const DELETE_LISTS = 'Usuwać listy zakupów';
    public const DELETE_LIST_DAYS = 'Ilość dni po których usuwać listy zakupów';
    public const DELETE_QUICK_LISTS = 'Usuwać szybkie listy';
    public const DELETE_QUICK_LIST_DAYS = 'Ilość dni po których usuwać szybkie listy';
    public const DELETE_UNCHECKED_POSITIONS_LABEL = 'Usuwać niekupione pozycje - listy zakupów';
    public const DELETE_UNCHECKED_POSITIONS_QUICK_LABEL = 'Usuwać niekupione pozycje - szybkie listy';
    public const HIDE_BOUGHT_LABEL = 'Domyślnie ukrywanie kupione produkty';
    public const MAX_SHOPS_GROUP_COUNT_LABEL = 'Maksymalna ilość sklepów w grupie';
    public const NEW_SHOPPING_DAYS_LABEL = 'Ilość dni jako nowy zakup';
    public const PAGINATION_COUNT_LABEL = 'Domyślna ilość wyświetlanych pozycji w tabelach';
    public const SHOOPING_LIST_LAYOUT_TYPE_LABEL = 'Domyślny typ listy zakupów';
}
