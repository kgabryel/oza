<template>
  <v-data-table
    :headers='headers'
    :items='units'
    :items-per-page='perPage'
    :sort-by.sync='column'
    :sort-desc.sync='desc'
    class='elevation-1'
    multi-sort
  >
    <template #item.name='{ item }'>
      <div class='d-flex align-center'>
        <a
          :href='path(item.id)'
          class='link'
        >
          {{ item.name }}
        </a>
        <v-spacer />
        <unit-badge
          v-if='item.isMain && item.subUnits && item.subUnits.length > 0'
          :id='item.id'
          :count='item.subUnits.length'
        />
      </div>
    </template>
    <template #item.mainUnit='{ item }'>
      <unit-info-button
        v-if='!item.isMain'
        :id='item.main.id'
        :name='item.main.name'
      />
    </template>
    <template #item.converter='{ item }'>
      <span v-if='!item.isMain'>
        1 {{ item.main.shortcut }} = {{ item.converter }} {{ item.shortcut }}
      </span>
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
import UnitInfoButton from './UnitInfoButton';
import UnitBadge from './UnitBadge';
import Delete from '../Delete';
import paths from '../../config/paths';

export default {
    name: 'UnitsTable',
    components: {UnitInfoButton, UnitBadge, Delete},
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
        headers: tableHeaders.units,
        units: [],
        perPage: 5,
        column: 'name',
        desc: false
    }),
    mounted() {
        this.units = JSON.parse(this.positions);
        this.perPage = parseInt(this.limit);
    },
    methods: {
        path: id => paths.unit(id)
    }
};
</script>
<style scoped>
</style>