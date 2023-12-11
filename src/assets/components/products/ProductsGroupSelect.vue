<template>
  <div
    :class="{'invalid': errorMessages.length > 0 }"
    class='multi-select'
  >
    <v-autocomplete
      v-model='selected'
      :error='invalid'
      :error-messages='errorMessages'
      :items='availableOptions'
      :label='label'
      :search-input.sync='searchInput'
      chips
      clearable
      deletable-chips
      hide-details='auto'
      item-text='name'
      item-value='value'
      multiple
      small-chips
      @change='changed'
    />
    <select
      :name='name'
      class='hidden'
      multiple
    >
      <option
        v-for='item in selected'
        :key='item'
        :value='item'
        selected
      />
    </select>
  </div>
</template>
<script>
import events from '../../config/events';
import {bus} from '../../app';
import {stringsService} from '../../services/strings.service';

export default {
    name: 'ProductsGroupSelect',
    props: {
        label: {
            type: String,
            required: true
        },
        type: {
            type: String,
            default: 'text'
        },
        name: {
            type: String,
            required: true
        },
        value: {
            type: String,
            default: ''
        },
        items: {
            type: String,
            default: ''
        },
        invalid: {
            type: Boolean,
            default: false
        },
        errors: {
            type: String,
            default: ''
        },
        groups: {
            type: String,
            required: true
        }
    },
    data: () => ({
        selected: [],
        productsUnits: [],
        availableProducts: [],
        mainUnit: 0,
        searchInput: ''
    }),
    computed: {
        errorMessages: function () {
            return this.errors === '' ? [] : JSON.parse(this.errors);
        },
        selectOptions: function () {
            if (this.items === '') {
                return [];
            }
            const items = JSON.parse(this.items);
            let options = Object.keys(items).map(key => {
                return {
                    value: key,
                    name: items[key]
                };
            });
            return options.sort((a, b) => stringsService.compareStrings(a.name, b.name));
        },
        availableOptions: function () {
            if (this.availableProducts.length === 0) {
                return this.selectOptions;
            }
            return this.selectOptions.filter(product => this.availableProducts.includes(parseInt(product.value)));
        }
    },
    watch: {
        selected: function () {
            if (this.selected.length === 0) {
                this.availableProducts = [];
                this.mainUnit = 0;
            }
            if (this.selected.length === 1) {
                const data = this.productsUnits.find(element => element.products.includes(parseInt(this.selected[0])));
                this.availableProducts = data.products;
                this.mainUnit = data.unit;
            }
        },
        mainUnit: {
            immediate: true,
            handler() {
                bus.$emit(events.productsMainUnitChange, this.mainUnit);
            }
        }
    },
    mounted() {
        this.selected = JSON.parse(this.value);
        this.productsUnits = JSON.parse(this.groups);
    },
    methods: {
        changed: function () {
            this.searchInput = '';
        }
    }
};
</script>
<style scoped>
.multi-select {
    height: 44px !important;
}

.invalid {
    margin-bottom: 16px;
}
</style>