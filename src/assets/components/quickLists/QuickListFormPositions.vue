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
          :value='positionInputs[item]'
        />
        <edit-position
          v-else
          :dragable='positionInputs.length > 1'
          :index='index'
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
      Dodaj
    </v-btn>
  </div>
</template>
<script>
import Position from './FormPosition';
import EditPosition from './EditFormPosition';
import draggable from 'vuedraggable';

export default {
    name: 'QuickListFormPositions',
    components: {Position, EditPosition, draggable},
    props: {
        positions: {
            type: String,
            default: ''
        },
        edit: {
            type: String,
            default: 'false'
        }
    },
    data: () => ({
        positionInputs: [],
        order: []
    }),
    mounted: function () {
        this.positionInputs = JSON.parse(this.positions);
        this.order = [...this.positionInputs.keys()];
    },
    methods: {
        addPosition: function () {
            this.positionInputs.push({
                checked: {
                    value: false,
                    errors: []
                },
                position: {
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