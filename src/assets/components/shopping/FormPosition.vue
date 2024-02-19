<template>
  <div>
    <div class='d-flex align-center'>
      <v-autocomplete
        v-model='product'
        :error-messages='value.product.errors'
        :items='options'
        :label='label'
        hide-details='auto'
        item-text='name'
        item-value='value'
      >
        <template #prepend>
          <v-fade-transition leave-absolute>
            <v-btn
              class='scanner-button'
              icon
              type='button'
            >
              <v-icon>
                mdi-barcode-scan
              </v-icon>
              <ImageBarcodeReader
                @decode='onDecode'
                @error='onError'
                @result='onDecode'
              />
            </v-btn>
          </v-fade-transition>
        </template>
        <template #item='data'>
          <v-list>
            <v-list-item>
              <v-list-item-content>
                <v-list-item-title>
                  <span>
                    {{ data.item.name }}
                  </span>
                </v-list-item-title>
                <v-list-item-subtitle>
                  <span v-if="data.item.value.startsWith('product_')">
                    Produkt
                  </span>
                  <span v-else>
                    Grupa produktów
                  </span>
                </v-list-item-subtitle>
              </v-list-item-content>
            </v-list-item>
          </v-list>
        </template>
      </v-autocomplete>
      <v-btn
        class='pa-0 ml-1'
        color='error'
        elevation='2'
        tile
        type='button'
        @click='deletePosition'
      >
        <v-icon dark>
          mdi-delete
        </v-icon>
      </v-btn>
    </div>
    <div :class="{'d-none': unit.length === 0}">
      <v-row>
        <v-col
          cols='12'
          md='3'
        >
          <v-text-field
            v-model='amount'
            :error-messages='value.amount.errors'
            hide-details='auto'
            label='Ilość'
            step='any'
            type='number'
          />
        </v-col>
        <v-col
          cols='12'
          md='3'
        >
          <v-autocomplete
            v-model='unit'
            :error-messages='value.unit.errors'
            :items='units'
            hide-details='auto'
            item-text='name'
            item-value='value'
            label='Jednostka'
          />
        </v-col>
        <v-col
          cols='12'
          md='3'
        >
          <v-text-field
            v-model='price'
            :error-messages='value.price.errors'
            hide-details='auto'
            label='Cena'
            min='0.01'
            step='0.01'
            type='number'
          />
        </v-col>
        <v-col
          cols='12'
          md='3'
        >
          <v-text-field
            v-model='discount'
            :error-messages='value.discount.errors'
            hide-details='auto'
            label='Rabat'
            min='0.01'
            step='0.01'
            type='number'
          />
        </v-col>
      </v-row>
    </div>
    <div :class="{'d-none': !supplyAvailable}">
      <v-row v-if='supplyAvailable'>
        <v-col>
          <div>
            <v-switch
              v-model='createSupply'
              :error-messages='value.createSupply.errors'
              hide-details='auto'
              label='Utworzyć zapas'
            />
          </div>
        </v-col>
        <v-col v-if='supplies.length > 1'>
          <v-autocomplete
            v-model='supply'
            :error-messages='value.supply.errors'
            :items='supplies'
            hide-details='auto'
            item-text='name'
            item-value='value'
            label='Grupa zapasów'
          />
        </v-col>
      </v-row>
    </div>
    <input
      :name='`shopping_form[positions][${index}][position]`'
      :value='productId'
      type='hidden'
    >
    <input
      :name='`shopping_form[positions][${index}][amount]`'
      :value='amount'
      type='hidden'
    >
    <input
      :name='`shopping_form[positions][${index}][unit]`'
      :value='unit'
      type='hidden'
    >
    <input
      :name='`shopping_form[positions][${index}][type]`'
      :value='type'
      type='hidden'
    >
    <input
      :name='`shopping_form[positions][${index}][price]`'
      :value='price'
      type='hidden'
    >
    <input
      :name='`shopping_form[positions][${index}][discount]`'
      :value='discount'
      type='hidden'
    >
    <div class='checkbox-value'>
      <input
        :checked='createSupply'
        :name='`shopping_form[positions][${index}][createSupply]`'
        type='checkbox'
      >
    </div>
    <input
      :name='`shopping_form[positions][${index}][supply]`'
      :value='supply'
      type='hidden'
    >
  </div>
</template>
<script>
import axios from 'axios';
import routing from '../../config/routing';
import {ImageBarcodeReader} from 'vue-barcode-reader';
import {bus} from '../../app';
import events from '../../config/events';

export default {
    name: 'FormPosition',
    components: {ImageBarcodeReader},
    props: {
        index: {
            type: Number,
            default: 0
        },
        options: {
            type: Array,
            required: true
        },
        value: {
            type: Object,
            required: true
        }
    },
    data: () => ({
        product: '',
        units: [],
        type: '',
        amount: 1,
        unit: '',
        productId: 0,
        price: null,
        discount: null,
        createSupply: false,
        supply: null,
        supplies: [],
        supplyAvailable: false
    }),
    computed: {
        label: function () {
            switch (this.type) {
                case 'product':
                    return 'Produkt';
                case 'productsGroup':
                    return 'Grupa produktów';
                default:
                    return 'Produkt / Grupa produktów';
            }
        }
    },
    watch: {
        product: function () {
            this.type = this.product.slice(0, this.product.indexOf('_'));
            this.productId = parseInt(this.product.slice(this.product.indexOf('_') + 1));
            this.type === 'product' ? this.findUnitsForProduct() : this.findUnitsForProductsGroup();
            this.type === 'product' ? this.getSupplyInfoForProduct() : this.getSupplyInfoForProductsGroup();
        }
    },
    mounted: function () {
        this.product = this.value.product.value;
        this.amount = this.value.amount.value;
        this.unit = this.value.unit.value;
        this.price = this.value.price.value;
        this.discount = this.value.discount.value;
        this.createSupply = this.value.createSupply.value;
        this.supply = this.value.supply.value;
    },
    methods: {
        findUnitsForProductsGroup: function () {
            axios.get(routing.productsGroupUnits(this.productId)).then(response => this.findUnits(response));
        },
        findUnitsForProduct: function () {
            axios.get(routing.productUnits(this.productId)).then(response => this.findUnits(response));
        },
        getSupplyInfoForProductsGroup: function () {
            axios.get(routing.productsGroupSupplyInfo(this.productId)).then(response => this.setSupplyInfo(response));
        },
        getSupplyInfoForProduct: function () {
            axios.get(routing.productSupplyInfo(this.productId)).then(response => this.setSupplyInfo(response));
        },
        setSupplyInfo: function (response) {
            if (response === undefined) {
                this.supplies = [];
                this.supplyAvailable = false;
                return;
            }
            let supplies = response.data.supplies;
            this.supplyAvailable = response.data.available;
            this.supplies = [];
            Object.keys(supplies).forEach(key => {
                this.supplies.push({
                    name: supplies[key],
                    value: key
                });
            });
            if (this.supplies.length === 1) {
                this.supply = this.supplies[0].value;
            }
        },
        deletePosition: function () {
            this.$el.parentNode.removeChild(this.$el);
        },
        findUnits: function (response) {
            let units = [];
            this.amount = 1;
            if (response !== undefined) {
                units = response.data.units;
                this.amount = response.data.defaultAmount ?? 1;
            }
            this.units = [];
            let exists = false;
            Object.keys(units).forEach(key => {
                if (key === this.unit) {
                    exists = true;
                }
                this.units.push({
                    name: units[key],
                    value: key
                });
            });
            if (this.unit === '' || !exists) {
                this.unit = response.data.default.toString();
            }
        },
        onDecode: function (result) {
            axios.get(routing.findProductByBarCode(result)).then(response => {
                if (response === undefined) {
                    bus.$emit(events.showError, ['Nie znaleziono pasującego produktu.']);
                } else {
                    this.product = `product_${response.data.id}`;
                }
            });
        },
        onError: function () {
            bus.$emit(events.showError, ['Nie udało się odczytać kodu.']);
        }
    }
};
</script>
<style scoped>
button {
    min-width: 36px !important;
    width: 36px !important;
}

.v-list, .v-list-item__content {
    padding-bottom: 0;
    padding-top: 0;
}

.v-list-item {
    padding-left: 0;
}

.scanner-button input[type=file] {
    background: white;
    cursor: inherit;
    display: block;
    filter: alpha(opacity=0);
    font-size: 100px;
    min-height: 100%;
    min-width: 100%;
    opacity: 0;
    outline: none;
    position: absolute;
    right: 0;
    text-align: right;
    top: 0;
}
</style>