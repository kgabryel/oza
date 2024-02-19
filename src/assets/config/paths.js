export default {
    productsGroup: id => `/products-groups/${id}`,
    product: id => `/products/${id}`,
    unit: id => `/units/${id}`,
    shop: id => `/shops/${id}`,
    shopping: id => `/shopping/${id}`,
    alert: id => `/alerts/${id}`,
    supply: id => `/supplies/${id}`,
    quickList: id => `/quick-lists/${id}`,
    shoppingList: id => `/shopping-lists/${id}`,
    shoppingListShopping: id => `/shopping/shopping-list/${id}`,
    supplyGroup: id => `/supply-groups/${id}`,
    apiKey: id => `/settings/api-key/${id}`,
    supplyPart: id => `/supply-parts/${id}`,
    brand: id => `/brands/${id}`,
    smallPhoto: id => `/photos/small/${id}`,
    mediumPhoto: id => `/photos/medium/${id}`,
    originalPhoto: id => `/photos/original/${id}`
};