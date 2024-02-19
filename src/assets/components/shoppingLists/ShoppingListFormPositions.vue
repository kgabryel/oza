<template>
  <div>
    <draggable
      v-model='order'
      handle='.c-move'
    >
      <span
        v-for='(item, index) in order'
        :key='item'
      >
        <position
          v-if="edit === 'false'"
          :dragable='positionInputs.length > 1'
          :index='index'
          :options='options'
          :value='positionInputs[item]'
        />
        <edit-position
          v-else
          :dragable='positionInputs.length > 1'
          :index='index'
          :options='options'
          :value='positionInputs[item]'
        />
      </span>
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
        options: [],
        order: []
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
        this.order = [...this.positionInputs.keys()];
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
            this.order.push([...this.positionInputs.keys()].pop());
        }
    }
};
</script>
<style scoped>
</style>