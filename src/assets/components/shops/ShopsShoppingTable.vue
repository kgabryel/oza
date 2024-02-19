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
    <template #item.product='{ item }'>
      <products-group-info-button
        v-if='item.isGroup'
        :id='item.product.id'
        :name='item.product.name'
      />
      <product-info-button
        v-else
        :id='item.product.id'
        :name='item.product.name'
      />
    </template>
    <template #item.amount='{ item }'>
      {{ item.amount.toFixed(item.amount % 1 === 0 ? 0 : 2) }} {{ item.unit.shortcut }}
    </template>
    <template #item.price='{ item }'>
      <price-info-button
        :id='item.unit.id'
        :price='item.price'
        :shortcut='item.unit.shortcut'
      />
    </template>
  </v-data-table>
</template>
<script>
import tableHeaders from '../../config/tableHeaders';
import Delete from '../Delete';
import ProductsGroupInfoButton from '../productsGroups/ProductsGroupInfoButton';
import ProductInfoButton from '../products/ProductInfoButton';
import PriceInfoButton from '../PriceInfoButton';

export default {
    name: 'ShopsShoppingTable',
    components: {Delete, ProductsGroupInfoButton, ProductInfoButton, PriceInfoButton},
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
        headers: tableHeaders.shopsShopping,
        shopping: [],
        perPage: 5,
        column: 'date',
        desc: true
    }),
    mounted() {
        this.shopping = JSON.parse(this.positions);
        this.perPage = parseInt(this.limit);
    }
};
</script>
<style scoped>
</style>