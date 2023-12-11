<template>
  <div>
    <v-card elevation='2'>
      <v-toolbar
        color='primary'
        dark
        dense
      >
        <shopping-list-header
          :id='id'
          :filtered-shops='selectedShops.length !== shops.length'
          :grid-layout='gridLayout'
          :hide-bought='hideBought'
          @delete='showDeleteDialog(null)'
          @switch-visibility='hideBought = !hideBought'
          @open-shop-dialog='shopDialog = true'
          @open-shop-prices-dialog='showShopsPrices'
          @switch-layout='gridLayout = !gridLayout'
        />
      </v-toolbar>
      <div class='pa-3 card'>
        <div class='positions'>
          <shopping-list-positions
            :created-at='createdAt'
            :grid-layout='gridLayout'
            :hide-bought='hideBought'
            :name='name'
            :note='note'
            :positions='positions'
            :selected-shops='selectedShops'
            @delete='showDeleteDialog($event)'
            @change-status='changeStatus($event)'
            @open-price-dialog='showPriceDialog($event)'
          />
        </div>
        <v-divider class='mx-4' />
        <v-btn
          block
          class='mt-3'
          color='secondary'
          elevation='2'
          large
          type='button'
          @click='show'
        >
          <v-icon
            dark
            left
          >
            mdi-magnify
          </v-icon>
          Pokaż
        </v-btn>
      </div>
    </v-card>
    <v-dialog
      v-model='showed'
      width='500'
    >
      <v-card elevation='2'>
        <v-toolbar
          color='primary'
          dark
          dense
        >
          <shopping-list-header
            :id='id'
            :filtered-shops='selectedShops.length !== shops.length'
            :grid-layout='gridLayout'
            :hide-bought='hideBought'
            @delete='showDeleteDialog(null)'
            @switch-visibility='hideBought = !hideBought'
            @open-shop-dialog='shopDialog = true'
            @open-shop-prices-dialog='showShopsPrices'
            @switch-layout='gridLayout = !gridLayout'
          />
        </v-toolbar>
        <div class='pa-3'>
          <div class='positions'>
            <shopping-list-positions
              :created-at='createdAt'
              :grid-layout='gridLayout'
              :hide-bought='hideBought'
              :name='name'
              :note='note'
              :positions='positions'
              :selected-shops='selectedShops'
              @delete='showDeleteDialog($event)'
              @change-status='changeStatus($event)'
              @open-price-dialog='showPriceDialog($event)'
            />
          </div>
        </div>
      </v-card>
    </v-dialog>
    <delete-dialog
      :visible='deleteDialog'
      @delete='confirmDelete'
      @close-dialog='deleteDialog = false'
    />
    <price-dialog
      :prices='prices'
      :selected-position='selectedPosition'
      :visible='priceDialog'
      @select-shop='changeShop($event)'
      @close-dialog='priceDialog = false'
    />
    <shop-filter-dialog
      :shops='shops'
      :visible='shopDialog'
      @change-shop-status='updateSelectedShops($event)'
      @close-dialog='shopDialog = false'
    />
    <shops-options-dialog
      :shops-prices='shopsPrices'
      :visible='shopsDialog'
      @select-shops='changeShops($event)'
      @close-dialog='shopsDialog = false'
    />
  </div>
</template>
<script>
import axios from 'axios';
import routing from '../../config/routing';
import {bus} from '../../app';
import events from '../../config/events';
import DeleteDialog from '../DeleteDialog';
import ShopFilterDialog from './ShopFilterDialog';
import ShopsOptionsDialog from './ShopsOptionsDialog';
import PriceDialog from './PriceDialog';
import ShoppingListHeader from './ShoppingListHeader';
import ShoppingListPositions from './ShoppingListPositions';

export default {
    name: 'ShoppingList',
    components: {
        ShopFilterDialog,
        DeleteDialog,
        ShopsOptionsDialog,
        PriceDialog,
        ShoppingListHeader,
        ShoppingListPositions
    },
    props: {
        list: {
            type: String,
            default: ''
        },
        hide: {
            type: Boolean,
            default: false
        },
        layout: {
            type: String,
            default: ''
        }
    },
    data: () => ({
        name: '',
        createdAt: '',
        id: 0,
        positions: [],
        hideBought: false,
        showed: false,
        deleteCallback: null,
        deleteDialog: false,
        priceDialog: false,
        note: '',
        prices: [],
        shops: [],
        selectedShops: [],
        shopDialog: false,
        selectedPosition: null,
        shopsDialog: false,
        shopsPrices: [],
        gridLayout: false
    }),
    mounted() {
        this.hideBought = this.hide;
        const list = JSON.parse(this.list);
        this.id = list.id;
        this.name = list.name;
        this.createdAt = list.createdAt;
        this.positions = list.positions;
        let usedValues = [];
        this.positions.forEach(position => {
            if (position.shopId) {
                if (!usedValues.includes(position.shopId)) {
                    usedValues.push(position.shopId);
                    this.shops.push({
                        id: position.shopId,
                        name: position.shopName,
                        checked: true
                    });
                    this.selectedShops.push(position.shopId);
                }
            } else {
                if (!usedValues.includes(null)) {
                    usedValues.push(null);
                    this.shops.push({
                        id: null,
                        name: 'Brak przypisania',
                        checked: true
                    });
                    this.selectedShops.push(null);
                }
            }
        });
        this.shops.sort((a, b) => this.sortShops(a, b));
        this.note = list.note;
        this.gridLayout = this.layout === 'grid';
    },
    methods: {
        shopAvailable: function (position) {
            let id = position.shopId ?? null;
            return this.selectedShops.includes(id);
        },
        changeShops: function (shopping) {
            shopping.forEach(positions => positions.positions.forEach(position => {
                this.changeShop({positionId: position, shopId: positions.shopId});
            }));
            this.shopsDialog = false;
        },
        changeShop: function (event) {
            let positionId = event.positionId;
            let shopId = event.shopId;
            axios.patch(routing.shoppingListPositionChangeShop(positionId), {shop: shopId})
                .then(response => {
                    this.selectedPosition = response.data;
                    let positionIndex = this.positions.indexOf(this.positions.find(position => position.id === this.selectedPosition.id));
                    this.positions[positionIndex] = response.data;
                    this.selectedShops = [];
                    this.shops = [];
                    let usedValues = [];
                    this.positions.forEach(position => {
                        if (position.shopId) {
                            if (!usedValues.includes(position.shopId)) {
                                usedValues.push(position.shopId);
                                this.shops.push({
                                    id: position.shopId,
                                    name: position.shopName,
                                    checked: true
                                });
                                this.selectedShops.push(position.shopId);
                            }
                        } else {
                            if (!usedValues.includes(null)) {
                                usedValues.push(null);
                                this.shops.push({
                                    id: null,
                                    name: 'Brak przypisania',
                                    checked: true
                                });
                                this.selectedShops.push(null);
                            }
                        }
                    });
                    this.shops.sort((a, b) => this.sortShops(a, b));
                });
        },
        updateSelectedShops: function (index) {
            let shop = this.shops[index];
            let i = this.selectedShops.indexOf(shop.id);
            this.shops[index].checked = !shop.checked;
            this.selectedShops.includes(shop.id) ? this.selectedShops.splice(i, 1) : this.selectedShops.push(shop.id);
        },
        show: function () {
            this.showed = true;
        },
        changeStatus: function (data) {
            const id = data.id;
            const checked = data.status;
            axios.post(routing.shoppingListPositionCheck(id, checked)).then(() => {
                let position = this.positions.find(position => position.id === id);
                position.checked = !position.checked;
            });
        },
        deletePosition: function (id) {
            axios.delete(routing.shoppingListPosition(id)).then(() => {
                this.positions = this.positions.filter(position => position.id !== id);
                bus.$emit(events.showSuccess, ['Pozycja została usunięta.']);
            });
        },

        delete: function () {
            axios.delete(routing.shoppingList(this.id)).then(() => {
                bus.$emit(events.productsListDeleted);
                bus.$emit(events.showSuccess, ['Lista została usunięta.']);
                this.$el.parentElement.remove();
            });
        },
        showDeleteDialog(id) {
            id === null ? this.deleteCallback = this.delete : this.deleteCallback = () => this.deletePosition(id);
            this.deleteDialog = true;
        },
        showPriceDialog(position) {
            this.selectedPosition = position;
            axios.get(routing.shoppingListPositionPrices(position.id)).then(response => this.prices = response.data);
            this.priceDialog = false;
            this.priceDialog = true;
        },
        confirmDelete: function () {
            this.deleteDialog = false;
            this.deleteCallback();
        },
        showShopsPrices: function () {
            this.shopsDialog = true;
            axios.get(routing.shoppingListPrices(this.id)).then(response => this.shopsPrices = response.data);
        },
        sortShops: (a, b) => {
            if (b.id === null || a.name < b.name) {
                return -1;
            }
            if (a.id === null || a.name > b.name) {
                return 1;
            }
            return 0;
        }
    }
};
</script>
<style scoped>
.positions {
    min-height: 350px;
}

.card .positions {
    max-height: 350px;
    overflow-y: auto;
}
</style>