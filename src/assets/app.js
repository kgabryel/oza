/*
 * Welcome to your app's main JavaScript file!
 *
 * We recommend including the built version of this JavaScript file
 * (and its CSS file) in your base layout (base.html.twig).
 */

// any CSS you import will output into a single css file (app.css in this case)
import './styles/app.css';
import 'vue-toastification/dist/index.css';

// start the Stimulus application
import Vue from 'vue';
import vuetify from './plugins/vuetify';
import Toast from 'vue-toastification';

import UnitInfoButton from './components/units/UnitInfoButton';
import UnitInfo from './components/units/UnitInfo';
import ProductsGroupInfoButton from './components/productsGroups/ProductsGroupInfoButton';
import ProductsGroupInfo from './components/productsGroups/ProductsGroupInfo';
import UnitBadge from './components/units/UnitBadge';
import ProductsGroupBadge from './components/productsGroups/ProductsGroupBadge';
import Delete from './components/Delete';
import PriceInfo from './components/PriceInfo';
import PriceInfoButton from './components/PriceInfoButton';
import AlertStatus from './components/alerts/AlertStatus';
import ProductChart from './components/products/ProductChart';
import ProductsGroupChart from './components/productsGroups/ProductsGroupChart';
import Alerts from './components/alerts/Alerts';
import InputTextField from './components/inputs/InputTextField';
import NavHeader from './components/theme/NavHeader';
import App from './components/theme/App';
import InputTextarea from './components/inputs/InputTextarea';
import InputSwitch from './components/inputs/InputSwitch';
import InputSelect from './components/inputs/InputSelect';
import InputMultiSelect from './components/inputs/InputMultiSelect';
import InputDate from './components/inputs/InputDate';
import ShoppingPositions from './components/shopping/ShoppingPositions';
import QuickList from './components/quickLists/QuickList';
import QuickListFormPositions from './components/quickLists/QuickListFormPositions';
import ShoppingList from './components/shoppingLists/ShoppingList';
import ShoppingListFormPositions from './components/shoppingLists/ShoppingListFormPositions';
import InfoMessage from './components/theme/InfoMessage';
import ErrorMessage from './components/theme/ErrorMessage';
import ProductsGroupSelect from './components/products/ProductsGroupSelect';
import ProductUnit from './components/products/ProductUnit';
import ProductInfo from './components/products/ProductInfo';
import QuickListClipboard from './components/quickLists/QuickListClipboard';
import ShoppingListClipboard from './components/shoppingLists/ShoppingListClipboard';
import ProductsGroupsTable from './components/productsGroups/ProductsGroupsTable';
import ShopsTable from './components/shops/ShopsTable';
import UnitsTable from './components/units/UnitsTable';
import SuppliesTable from './components/supplies/SuppliesTable';
import ShoppingTable from './components/shopping/ShoppingTable';
import ProductsTable from './components/products/ProductsTable';
import AlertsTable from './components/alerts/AlertsTable';
import ShopsShoppingTable from './components/shops/ShopsShoppingTable';
import MainUnitSwitch from './components/units/MainUnitSwitch';
import SubUnitDetails from './components/units/SubUnitDetails';
import InputEntityType from './components/inputs/InputEntityType';
import InputMultiEntityType from './components/inputs/InputMultiEntityType';
import UnitInfoChip from './components/units/UnitInfoChip';
import ProductsGroupInfoChip from './components/productsGroups/ProductsGroupInfoChip';
import axios from 'axios';
import events from './config/events';
import ProductInfoChip from './components/products/ProductInfoChip';
import SupplyGroupSelect from './components/supplies/GroupSelect';
import SupplyGroupInfoChip from './components/supplyGroups/SupplyGroupInfoChip';
import SupplyGroupInfo from './components/supplyGroups/SupplyGroupInfo';
import SupplyGroupsTable from './components/supplyGroups/SupplyGroupsTable';
import SupplyInfoChip from './components/supplies/SupplyInfoChip';
import SupplyInfo from './components/supplies/SupplyInfo';
import Key from './components/settings/Key';
import VueClipboard from 'vue-clipboard2';
import DeleteButton from './components/DeleteButton';
import SupplyPartsTable from './components/supplies/SupplyPartsTable';
import ProductInfoButton from './components/products/ProductInfoButton';
import Alert from './components/alerts/Alert';
import Wysiwyg from './components/inputs/Wysiwyg';
import BrandsTable from './components/brands/BrandsTable';
import BrandInfo from './components/brands/BrandInfo.vue';
import BrandInfoButton from './components/brands/BrandInfoButton.vue';
import InputBarcodeField from './components/inputs/InputBarcodeField.vue';
import VueBarcode from '@chenfengyuan/vue-barcode';
import Scanner from './components/products/Scanner.vue';
import ChartPositionsTable from './components/ChartPositionsTable.vue';
import UploadButton from './components/photos/UploadButton.vue';
import UploadModal from './components/photos/UploadModal.vue';
import Gallery from './components/photos/Gallery.vue';
import Overlay from './components/photos/Overlay.vue';

export const bus = new Vue();
Vue.use(VueClipboard);
Vue.use(Toast, {});
Vue.component(VueBarcode.name, VueBarcode);
if (window.location.hash == '#_=_') {
    window.history.replaceState('', document.title, window.location.pathname);
}

axios.interceptors.response.use(function (response) {
    return response;
}, function (error) {
    if (error.response.status === 403) {
        bus.$emit(events.showError, ['Przesłano błędne dane']);
    }
    if (error.response.status === 401) {
        window.location.href = '/login';
    }
});

axios.interceptors.request.use(request => {
    request.headers['X-Requested-With'] = 'XMLHttpRequest';
    return request;
});
new Vue({
    el: '#app', vuetify, components: {
        UnitInfoButton,
        UnitInfo,
        ProductsGroupInfoButton,
        ProductsGroupInfo,
        UnitBadge,
        ProductsGroupBadge,
        Delete,
        PriceInfo,
        PriceInfoButton,
        AlertStatus,
        ProductChart,
        ProductsGroupChart,
        Alerts,
        InputTextField,
        NavHeader,
        App,
        InputTextarea,
        InputSwitch,
        InputSelect,
        InputMultiSelect,
        InputDate,
        ShoppingPositions,
        QuickList,
        QuickListFormPositions,
        ShoppingList,
        ShoppingListFormPositions,
        InfoMessage,
        ErrorMessage,
        ProductsGroupSelect,
        ProductUnit,
        ProductInfo,
        QuickListClipboard,
        ShoppingListClipboard,
        ProductsGroupsTable,
        ShopsTable,
        UnitsTable,
        SuppliesTable,
        ShoppingTable,
        ProductsTable,
        AlertsTable,
        ShopsShoppingTable,
        MainUnitSwitch,
        SubUnitDetails,
        InputEntityType,
        InputMultiEntityType,
        UnitInfoChip,
        ProductsGroupInfoChip,
        ProductInfoChip,
        SupplyGroupSelect,
        SupplyGroupInfoChip,
        SupplyGroupInfo,
        SupplyGroupsTable,
        SupplyInfoChip,
        SupplyInfo,
        Key,
        DeleteButton,
        SupplyPartsTable,
        ProductInfoButton,
        Alert,
        Wysiwyg,
        BrandsTable,
        BrandInfo,
        BrandInfoButton,
        InputBarcodeField,
        Scanner,
        ChartPositionsTable,
        UploadButton,
        UploadModal,
        Gallery,
        Overlay
    }
});

document.addEventListener('click', e => {
    if (e.target.closest('a:not(.without-loading)') || e.target.closest('button[type="submit"]')) {
        setTimeout(() => bus.$emit(events.loading), 500);
    }
});