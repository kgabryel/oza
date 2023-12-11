<template>
  <div>
    <form-position
      v-for='(item, index) in positionInputs'
      :key='index'
      :index='index'
      :options='options'
      :value='item'
    />
    <v-btn
      block
      class='mb-3 mt-3'
      color='secondary'
      elevation='2'
      large
      type='button'
      @click='addPosition'
    >
      <v-icon
        dark
        left
      >
        mdi-plus
      </v-icon>
      Dodaj
    </v-btn>
  </div>
</template>
<script>
import FormPosition from './FormPosition';

export default {
    name: 'ShoppingPositions',
    components: {FormPosition},
    props: {
        products: {
            type: String,
            default: ''
        },
        groups: {
            type: String,
            default: ''
        },
        positions: {
            type: String,
            default: ''
        }
    },
    data: () => ({
        positionInputs: [],
        options: []
    }),
    mounted() {
        const products = JSON.parse(this.products);
        if (Object.keys(products).length > 0) {
            this.options.push({header: 'Produkty'});
            Object.keys(products).forEach(key => this.options.push({
                name: key,
                value: `product_${products[key]}`
            }));
        }
        const productsGroups = JSON.parse(this.groups);
        if (Object.keys(productsGroups).length > 0) {
            this.options.push({header: 'Grupy produktów'});
            Object.keys(productsGroups).forEach(key => this.options.push({
                name: key,
                value: `productsGroup_${productsGroups[key]}`
            }));
        }
        this.positionInputs = JSON.parse(this.positions);
    },
    methods: {
        addPosition: function () {
            this.positionInputs.push({
                checked: {
                    value: '0',
                    errors: []
                },
                unit: {
                    value: '',
                    errors: []
                },
                product: {
                    value: '',
                    errors: []
                },
                amount: {
                    value: 1,
                    errors: []
                },
                price: {
                    value: null,
                    errors: []
                },
                discount: {
                    value: null,
                    errors: []
                },
                supply: {
                    value: null,
                    errors: []
                },
                createSupply: {
                    value: false,
                    errors: []
                }
            });
        }
    }
};
</script>
<style scoped>
</style>