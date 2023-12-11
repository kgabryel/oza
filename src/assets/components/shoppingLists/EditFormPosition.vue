<template>
  <div class='d-flex align-stretch'>
    <div :class="{'d-none' : !dragable}" class='py-1'>
      <v-btn
        class='pa-0 mx-1 c-move h-100'
        elevation='2'
        tile
        type='button'
      >
        <v-icon dark>
          mdi-drag
        </v-icon>
      </v-btn>
    </div>
    <div class='w-100'>
      <div class='d-flex align-center'>
        <v-switch
          v-model='checked'
          class='big-actions mt-2'
          hide-details='auto'
        />
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
          class='pa-0 ml-1 big-actions'
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
      <div :class="{'d-none': units.length === 0}">
        <v-row>
          <v-col cols='6'>
            <v-text-field
              v-model='amount'
              :error-messages='value.amount.errors'
              hide-details='auto'
              label='Ilość'
              min='0'
              step='any'
              type='number'
            />
          </v-col>
          <v-col cols='6'>
            <v-autocomplete
              v-model='unit'
              :items='units'
              hide-details='auto'
              item-text='name'
              item-value='value'
              label='Jednostka'
            />
          </v-col>
        </v-row>
      </div>
      <div>
        <v-row>
          <v-col cols='12'>
            <v-textarea
              v-model='description'
              :error='value.description.errors.length > 0'
              :error-messages='value.description.errors'
              hide-details='auto'
              label='Notatka'
              rows='2'
            />
          </v-col>
        </v-row>
      </div>
      <div class='small-actions pt-1 justify-space-between align-center'>
        <v-switch
          v-model='checked'
          class='mt-3'
          hide-details='auto'
        />
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
    </div>
    <input
      :name='`shopping_list_form[positions][${index}][position]`'
      :value='productId'
      type='hidden'
    >
    <input
      :name='`shopping_list_form[positions][${index}][amount]`'
      :value='amount'
      type='hidden'
    >
    <input
      :name='`shopping_list_form[positions][${index}][unit]`'
      :value='unit'
      type='hidden'
    >
    <input
      :name='`shopping_list_form[positions][${index}][type]`'
      :value='type'
      type='hidden'
    >
    <input
      :name='`shopping_list_form[positions][${index}][shop]`'
      :value='shop'
      type='hidden'
    >
    <input
      :checked='checked'
      :name='`shopping_list_form[positions][${index}][checked]`'
      type='checkbox'
    >
    <input
      :name='`shopping_list_form[positions][${index}][description]`'
      :value='description'
      type='hidden'
    >
  </div>
</template>
<script>
import axios from 'axios';
import routing from '../../config/routing';
import InputTextarea from '../inputs/InputTextarea';
import {bus} from '../../app';
import events from '../../config/events';
import {ImageBarcodeReader} from 'vue-barcode-reader';

export default {
    name: 'EditPosition',
    components: {ImageBarcodeReader, InputTextarea},
    props: {
        dragable: {
            type: Boolean,
            required: true
        },
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
        checked: false,
        shop: null,
        init: false,
        description: ''
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
        },
        value: function () {
            this.setValues();
        }
    },
    mounted: function () {
        this.setValues();
    },
    methods: {
        setValues: function () {
            this.product = this.value.product.value;
            this.amount = this.value.amount.value;
            this.unit = this.value.unit.value;
            this.checked = this.value.checked.value;
            this.description = this.value.description.value;
            this.shop = null;
            if (typeof this.value.shop.value === 'object' && this.value.shop.value !== null) {
                this.shop = this.value.shop.value.id;
            }
        },
        findUnitsForProductsGroup: function () {
            if (this.productId === null || isNaN(this.productId) || this.productId === 0) {
                return;
            }
            axios.get(routing.productsGroupUnits(this.productId)).then(response => this.findUnits(response));
        },
        findUnitsForProduct: function () {
            if (this.productId === null || isNaN(this.productId) || this.productId === 0) {
                return;
            }
            axios.get(routing.productUnits(this.productId)).then(response => this.findUnits(response));
        },
        deletePosition: function () {
            this.$el.parentNode.removeChild(this.$el);
        },
        findUnits: function (response) {
            let units = {};
            this.units = [];
            this.amount = 1;
            let defaultUnit = null;
            if (response !== undefined) {
                units = response.data.units;
                this.amount = response.data.defaultAmount ?? 1;
                defaultUnit = response.data.default.toString();
            }
            Object.keys(units).forEach(key => this.units.push({
                name: units[key],
                value: key
            }));
            let unitsIds = this.units.map(unit => unit.value);
            if (this.unit === '' || !unitsIds.includes(defaultUnit)) {
                this.unit = defaultUnit;
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

*::v-deep .v-input--switch {
    margin-top: 0;
    padding-top: 0;
}

.v-list, .v-list-item__content {
    padding-bottom: 0;
    padding-top: 0;
}

.v-list-item {
    padding-left: 0;
}

.small-actions {
    display: none;
}

@media screen and (max-width: 649px) {
    .big-actions {
        display: none;
    }

    .small-actions {
        display: flex;
    }
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