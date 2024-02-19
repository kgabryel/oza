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
    <template #item.shop='{ item }'>
      <a
        :href='item.shop.url'
        class='link'
      >
        {{ item.shop.name }}
      </a>
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
    <template #item.product='{ item }'>
      <product-info-button
        v-if='withProduct'
        :id='item.product.id'
        :name='item.product.name'
      />
    </template>
  </v-data-table>
</template>
<script>
import tableHeaders from '../config/tableHeaders';
import PriceInfoButton from './PriceInfoButton.vue';
import ProductInfoButton from './products/ProductInfoButton.vue';
import ProductsGroupInfoButton from './productsGroups/ProductsGroupInfoButton.vue';

export default {
    name: 'ChartPositionsTable',
    components: {ProductsGroupInfoButton, ProductInfoButton, PriceInfoButton},
    props: {
        product: {
            type: String,
            required: true
        },
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
        headers: [],
        shopping: [],
        perPage: 5,
        withProduct: false,
        column: 'date',
        desc: true
    }),
    mounted() {
        this.withProduct = this.product === 'true';
        if (this.withProduct) {
            this.headers = tableHeaders.chartProductsGroupPositions;
        } else {
            this.headers = tableHeaders.chartProductPositions;
        }
        this.shopping = JSON.parse(this.positions);
        this.perPage = parseInt(this.limit);
    }
};
</script>
<style scoped>
</style>