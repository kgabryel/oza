export default {
    productsGroups: [
        {
            text: '',
            sortable: false,
            value: 'photo'
        },
        {
            text: 'Nazwa',
            sortable: true,
            value: 'name'
        },
        {
            text: 'Bazowa jednostka',
            sortable: false,
            value: 'unit'
        },
        {
            text: 'Podstawowa jednostka',
            sortable: false,
            value: 'baseUnit'
        },
        {
            text: 'Notatka',
            sortable: true,
            value: 'note'
        },
        {
            text: '',
            sortable: false,
            value: 'actions'
        }
    ],
    units: [
        {
            text: 'Nazwa',
            sortable: true,
            value: 'name'
        },
        {
            text: 'Skrót',
            sortable: true,
            value: 'shortcut'
        },
        {
            text: 'Jednostka nadrzędna',
            sortable: false,
            value: 'mainUnit'
        },
        {
            text: 'Przelicznik',
            sortable: true,
            value: 'converter'
        },
        {
            text: '',
            sortable: false,
            value: 'actions'
        }
    ],
    alerts: [
        {
            text: 'Treść',
            sortable: true,
            value: 'content'
        },
        {
            text: 'Czy aktywne',
            sortable: true,
            value: 'active'
        },
        {
            text: '',
            sortable: false,
            value: 'actions'
        }
    ],
    shopping: [
        {
            text: 'Data',
            sortable: true,
            value: 'date'
        },
        {
            text: 'Sklep',
            sortable: false,
            value: 'shop'
        },
        {
            text: 'Ilość',
            sortable: true,
            value: 'amount'
        },
        {
            text: 'Kwota',
            sortable: true,
            value: 'price'
        },
        {
            text: 'Jednostka',
            sortable: false,
            value: 'unit'
        },
        {
            text: 'Produkt / Grupa produktów',
            sortable: false,
            value: 'product'
        },
        {
            text: '',
            sortable: false,
            value: 'actions'
        }
    ],
    shopsShopping: [
        {
            text: 'Data',
            sortable: true,
            value: 'date'
        },
        {
            text: 'Produkt',
            sortable: true,
            value: 'product'
        },
        {
            text: 'Kwota',
            sortable: true,
            value: 'price'
        }
    ],
    products: [
        {
            text: '',
            sortable: false,
            value: 'photo'
        },
        {
            text: 'Nazwa',
            sortable: true,
            value: 'name'
        },
        {
            text: 'Marka',
            sortable: false,
            value: 'brand'
        },
        {
            text: 'Grupy Produktów',
            sortable: false,
            value: 'productsGroups'
        },
        {
            text: 'Domyślna ilość',
            sortable: true,
            value: 'defaultAmount'
        },
        {
            text: 'Jednostka bazowa',
            sortable: false,
            value: 'unit'
        },
        {
            text: 'Notatka',
            sortable: true,
            value: 'note'
        },
        {
            text: '',
            sortable: false,
            value: 'actions'
        }
    ],
    supplies: [
        {
            text: 'Grupa produktów',
            sortable: false,
            value: 'group'
        },
        {
            text: 'Stan',
            sortable: true,
            value: 'amount'
        },
        {
            text: 'Jednostka',
            sortable: false,
            value: 'unit'
        },
        {
            text: 'Powiadomienia',
            sortable: true,
            value: 'alertsLength'
        },
        {
            text: 'Ostatnia aktualizacja',
            sortable: true,
            value: 'updatedAt'
        },
        {
            text: 'Grupy zapasów',
            sortable: false,
            value: 'groups'
        },
        {
            text: 'Notatka',
            sortable: true,
            value: 'description'
        },
        {
            text: '',
            sortable: false,
            value: 'actions'
        }
    ],
    supplyParts: [
        {
            text: 'Stan',
            sortable: true,
            value: 'amount'
        },
        {
            text: 'Jednostka',
            sortable: false,
            value: 'unit'
        },
        {
            text: 'Produkt',
            sortable: false,
            value: 'product'
        },
        {
            text: 'Ostatnia aktualizacja',
            sortable: true,
            value: 'updatedAt'
        },
        {
            text: 'Otwarty',
            sortable: true,
            value: 'open'
        },
        {
            text: 'Termin ważności',
            sortable: true,
            value: 'dateOfConsumption'
        },
        {
            text: '',
            sortable: false,
            value: 'actions'
        }
    ],
    shops: [
        {
            text: 'Nazwa',
            sortable: true,
            value: 'name'
        },
        {
            text: 'Opis',
            sortable: true,
            value: 'description'
        },
        {
            text: '',
            sortable: false,
            value: 'actions'
        }
    ],
    supplyGroups: [
        {
            text: 'Nazwa',
            sortable: true,
            value: 'name'
        },
        {
            text: 'Zapasy',
            sortable: false,
            value: 'supplies'
        },
        {
            text: '',
            sortable: false,
            value: 'actions'
        }
    ],
    brands: [
        {
            text: 'Nazwa',
            sortable: true,
            value: 'name'
        },
        {
            text: 'Opis',
            sortable: true,
            value: 'description'
        },
        {
            text: '',
            sortable: false,
            value: 'actions'
        }
    ],
    chartProductsGroupPositions: [
        {
            text: 'Data',
            sortable: true,
            value: 'date'
        },
        {
            text: 'Sklep',
            sortable: false,
            value: 'shop'
        },
        {
            text: 'Cena',
            sortable: true,
            value: 'price'
        },
        {
            text: 'Produkt',
            sortable: false,
            value: 'product'
        }
    ],
    chartProductPositions: [
        {
            text: 'Data',
            sortable: true,
            value: 'date'
        },
        {
            text: 'Sklep',
            sortable: false,
            value: 'shop'
        },
        {
            text: 'Cena',
            sortable: true,
            value: 'price'
        }
    ]
};