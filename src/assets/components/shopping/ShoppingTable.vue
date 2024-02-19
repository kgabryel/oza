<template>
  <v-data-table
    :headers='headers'
    :items='shopping'
    :items-per-page='perPage'
    :sort-by.sync='column'
    :sort-desc.sync='desc'
    class='elevation-1'
    multi-sort
  >
    <template #item.date='{ item }'>
      <p class='mb-0 text-no-wrap'>
        {{ item.date }}
      </p>
    </template>
    <template #item.product='{ item }'>
      <span v-if='item.isGroup'>
        <products-group-info-button
          :id='item.product.id'
          :name='item.product.name'
        />
        (Grupa produktów)
      </span>
      <span v-else>
        <product-info-button
          :id='item.product.id'
          :name='item.product.name'
        />
        (Produkt)
      </span>
    </template>
    <template #item.unit='{ item }'>
      <unit-info-button
        :id='item.unit.id'
        :name='item.unit.name'
      />
    </template>
    <template #item.amount='{ item }'>
      <p class='mb-0 text-no-wrap'>
        {{ item.amount.toFixed(item.amount % 1 === 0 ? 0 : 2) }} {{ item.unit.shortcut }}
      </p>
    </template>
    <template #item.price='{ item }'>
      <p class='mb-0 text-no-wrap'>
        <price-info-button
          :id='item.unit.mainId'
          :price='item.price'
          :shortcut='item.unit.mainShortcut'
        />
        ({{ item.originalPrice }} zł)
      </p>
    </template>
    <template #item.shop='{ item }'>
      <a
        :href='item.shop.url'
        class='link'
      >
        {{ item.shop.name }}
      </a>
    </template>
    <template #item.type='{item}'>
      <span
        v-if='item.isGroup'
        class='text-no-wrap'
      >
        Grupa produktów
      </span>
      <span
        v-else
        class='text-no-wrap'
      >
        Produkt
      </span>
    </template>
    <template #item.actions='{ item }'>
      <div class='ml-auto text-end'>
        <v-tooltip bottom>
          <template #activator='{ on, attrs }'>
            <form
              :action='path(item.id)'
              class='d-inline-block'
              method='post'
              v-bind='attrs'
              v-on='on'
            >
              <input
                name='_method'
                type='hidden'
                value='delete'
              >
              <delete />
            </form>
          </template>
          <span>
            Usuń
          </span>
        </v-tooltip>
      </div>
    </template>
  </v-data-table>
</template>
<script>
import tableHeaders from '../../config/tableHeaders';
import Delete from '../Delete';
import paths from '../../config/paths';
import ProductsGroupInfoButton from '../productsGroups/ProductsGroupInfoButton';
import UnitInfoButton from '../units/UnitInfoButton';
import ProductInfoButton from '../products/ProductInfoButton';
import PriceInfoButton from '../PriceInfoButton';

export default {
    name: 'ShoppingTable',
    components: {Delete, ProductsGroupInfoButton, UnitInfoButton, ProductInfoButton, PriceInfoButton},
    props: {
        limit: {
            type: String,
            default: '5'
        },
        positions: {
            type: String,
            default: ''
        }
    },
    data: () => ({
        headers: tableHeaders.shopping,
        shopping: [],
        perPage: 5,
        column: 'date',
        desc: false
    }),
    mounted() {
        this.shopping = JSON.parse(this.positions);
        this.perPage = parseInt(this.limit);
    },
    methods: {
        path: id => paths.shopping(id)
    }
};
</script>
<style scoped>
</style>