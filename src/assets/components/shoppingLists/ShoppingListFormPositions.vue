<template>
  <div>
    <draggable
      v-if="edit === 'false'"
      v-model='positionInputs'
      handle='.c-move'
    >
      <position
        v-for='(item, index) in positionInputs'
        v-if='item !== undefined'
        :key='index'
        :dragable='positionInputs.length > 1'
        :index='index'
        :options='options'
        :value='item'
      />
    </draggable>
    <draggable
      v-else
      v-model='positionInputs'
      handle='.c-move'
    >
      <edit-position
        v-for='(item, index) in positionInputs'
        :key='index'
        :dragable='positionInputs.length > 1'
        :index='index'
        :options='options'
        :value='item'
      />
    </draggable>
    <v-btn
      block
      class='mt-3'
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
      Dodaj pozycję
    </v-btn>
  </div>
</template>
<script>
import Position from './FormPosition';
import EditPosition from './EditFormPosition';
import draggable from 'vuedraggable';

export default {
    name: 'ShoppingListFormPositions',
    components: {Position, EditPosition, draggable},
    props: {
        positions: {
            type: String,
            default: ''
        },
        edit: {
            type: String,
            default: 'false'
        },
        groups: {
            type: String,
            default: ''
        },
        products: {
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
            Object.keys(productsGroups).forEach(
                key => this.options.push({
                    name: key,
                    value: `productsGroup_${productsGroups[key]}`
                }));
        }
        this.positionInputs = JSON.parse(this.positions);
    },
    methods: {
        addPosition: function () {
            this.positionInputs.push({
                shop: {
                    value: null,
                    errors: []
                },
                checked: {
                    value: false,
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
                description: {
                    value: '',
                    errors: []
                }
            });
        }
    }
};
</script>
<style scoped>
</style>