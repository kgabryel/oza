<template>
  <div>
    <draggable
      v-if="edit === 'false'"
      v-model='positionInputs'
      handle='.c-move'
    >
      <position
        v-for='(item, index) in positionInputs'
        :key='index'
        :dragable='positionInputs.length > 1'
        :index='index'
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
        positionInputs: []
    }),
    mounted: function () {
        this.positionInputs = JSON.parse(this.positions);
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
        }
    }
};
</script>
<style scoped>
</style>