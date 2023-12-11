<template>
  <v-data-table
    :headers='headers'
    :items='supplies'
    :items-per-page='perPage'
    class='elevation-1'
    multi-sort
  >
    <template #item.group='{ item }'>
      <products-group-info-button
        :id='item.group.id'
        :name='item.group.name'
      />
    </template>
    <template #item.amount='{ item }'>
      {{ item.amount.toFixed(item.amount % 1 === 0 ? 0 : 2) }} {{ item.unit.shortcut }}
    </template>
    <template #item.unit='{ item }'>
      <unit-info-button
        :id='item.unit.id'
        :name='item.unit.name'
      />
    </template>
    <template #item.description='{ item }'>
      <span v-html='item.description' />
    </template>
    <template #item.groups='{ item }'>
      <supply-group-info-chip
        v-for='group in item.groups'
        :id='group.id'
        :key='group.id'
        :name='group.name'
      />
    </template>
    <template #item.actions='{ item }'>
      <div class='ml-auto actions-column'>
        <v-tooltip bottom>
          <template #activator='{ on, attrs }'>
            <a
              :href='path(item.id)'
              class='text-decoration-none'
              v-bind='attrs'
              v-on='on'
            >
              <v-btn
                icon
                type='button'
              >
                <v-icon>
                  mdi-pencil
                </v-icon>
              </v-btn>
            </a>
          </template>
          <span>
            Edytuj
          </span>
        </v-tooltip>
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
import SupplyGroupInfoChip from '../supplyGroups/SupplyGroupInfoChip';

export default {
    name: 'SuppliesTable',
    components: {Delete, ProductsGroupInfoButton, UnitInfoButton, SupplyGroupInfoChip},
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
        headers: tableHeaders.supplies,
        supplies: [],
        perPage: 5
    }),
    mounted() {
        this.supplies = JSON.parse(this.positions);
        this.perPage = parseInt(this.limit);
    },
    methods: {
        path: id => paths.supply(id)
    }
};
</script>
<style scoped>
</style>