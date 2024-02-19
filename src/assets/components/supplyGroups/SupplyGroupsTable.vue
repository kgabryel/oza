<template>
  <v-data-table
    :headers='headers'
    :items='supplyGroups'
    :items-per-page='perPage'
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
      </div>
    </template>
    <template #item.supplies='{ item }'>
      <supply-info-chip
        v-for='supply in item.supplies'
        :id='supply.id'
        :key='supply.id'
        :name='supply.name'
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
import SupplyInfoChip from '../supplies/SupplyInfoChip';
import paths from '../../config/paths';

export default {
    name: 'SupplyGroupsTable',
    components: {Delete, SupplyInfoChip},
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
        headers: tableHeaders.supplyGroups,
        supplyGroups: [],
        perPage: 5
    }),
    mounted() {
        this.supplyGroups = JSON.parse(this.positions);
        this.perPage = parseInt(this.limit);
    },
    methods: {
        path: id => paths.supplyGroup(id)

    }
};
</script>
<style scoped>
</style>