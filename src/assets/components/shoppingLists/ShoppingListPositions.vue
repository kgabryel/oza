<template>
  <div>
    <p class='text-center mb-0'>
      <span v-if="name !== '' ">
        {{ name }} -
      </span>
      {{ createdAt }}
    </p>
    <p
      v-if="!(note === '' || note === null)"
      class='note mb-0'
      v-html='note'
    />
    <v-progress-linear
      :color='color'
      :value='progress'
      class='mr-1 ml-1'
      height='25'
      striped
    >
      <strong v-if='checked < positions.length'>
        {{ checked }}/{{ positions.length }}
      </strong>
    </v-progress-linear>
    <grid-layout
      v-if='gridLayout'
      :hide-bought='hideBought'
      :positions='positions'
      :selected-shops='selectedShops'
      @delete='deletePosition($event)'
      @change-status='changeStatus($event)'
      @open-price-dialog='showPriceDialog($event)' />
    <list-layout
      v-else
      :hide-bought='hideBought'
      :positions='positions'
      :selected-shops='selectedShops'
      @delete='deletePosition($event)'
      @change-status='changeStatus($event)'
      @open-price-dialog='showPriceDialog($event)' />
  </div>
</template>
<script>
import events from '../../config/events';
import UnitInfoButton from '../units/UnitInfoButton';
import ProductInfoButton from '../products/ProductInfoButton';
import ProductsGroupInfoButton from '../productsGroups/ProductsGroupInfoButton';
import list from '../../mixins/list';
import GridLayout from './GridLayout.vue';
import ListLayout from './ListLayout.vue';

export default {
    name: 'ShoppingListPositions',
    components: {ListLayout, GridLayout, UnitInfoButton, ProductInfoButton, ProductsGroupInfoButton},
    mixins: [list],
    props: {
        name: {
            type: String,
            default: ''
        },
        createdAt: {
            type: String,
            default: ''
        },
        note: {
            type: String,
            default: ''
        },
        hideBought: Boolean,
        positions: {
            type: Array,
            required: true
        },
        selectedShops: {
            type: Array,
            required: true
        },
        gridLayout: Boolean
    },
    methods: {
        changeStatus: function (data) {
            this.$emit(events.changeStatus, {id: data.id, status: data.status});
        },
        deletePosition: function (id) {
            this.$emit(events.delete, id);
        },
        showPriceDialog: function (position) {
            this.$emit(events.openPriceDialog, position);
        }
    }
};
</script>
<style scoped>
.v-progress-linear {
    width: auto;
}

.note {
    white-space: pre-line;
}
</style>