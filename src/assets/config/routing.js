export default {
    alerts: `/api/alerts`,
    productsGroupUnits: id => `/api/products-groups/${id}/units`,
    productUnits: id => `/api/products/${id}/units`,
    unit: id => `/api/units/${id}`,
    brand: id => `/api/brands/${id}`,
    productsGroup: id => `/api/products-groups/${id}`,
    product: id => `/api/products/${id}`,
    productsGroupChart: id => `/api/products-groups/${id}/chart`,
    productChart: id => `/api/products/${id}/chart`,
    quickList: id => `/api/quick-lists/${id}`,
    shoppingList: id => `/api/shopping-lists/${id}`,
    quickListClipboard: `/api/quick-list-clipboard`,
    shoppingListClipboard: `/api/shopping-list-clipboard`,
    shoppingListClipboardPosition: id => `/api/shopping-list-clipboard/${id}`,
    quickListPosition: id => `/api/quick-list-positions/${id}`,
    quickListClipboardPosition: id => `/api/quick-list-clipboard/${id}`,
    quickListPositionCheck: (id, checked) => {
        if (checked) {
            return `/api/quick-list-positions/${id}/uncheck`;
        }
        return `/api/quick-list-positions/${id}/check`;
    },
    shoppingListPosition: id => `/api/shopping-list-positions/${id}`,
    shoppingListPositionCheck: (id, checked) => {
        if (checked) {
            return `/api/shopping-list-positions/${id}/uncheck`;
        }
        return `/api/shopping-list-positions/${id}/check`;
    },
    shoppingListPositionChangeShop: (id) => `/api/shopping-list-positions/${id}/change-shop`,
    alertStatusChange: (id, checked) => {
        if (checked) {
            return `api/alerts/${id}/deactivate`;
        }
        return `api/alerts/${id}/activate`;
    },
    shoppingListPositionPrices: id => `/api/shopping-list-positions/${id}/prices`,
    shoppingListPrices: id => `/api/shopping-lists/${id}/prices`,
    supplyGroup: id => `/api/supply-groups/${id}`,
    supply: id => `/api/supplies/${id}`,
    apiKey: id => `/api/settings/api-key/${id}`,
    findProductByBarCode: code => `/api/products/find-by-barcode/${code}`,
    productsGroupSupplyInfo: id => `/api/products-groups/${id}/supply-info`,
    productSupplyInfo: id => `/api/products/${id}/supply-info`,
    deletePhoto: id => `/api/photos/${id}`
};