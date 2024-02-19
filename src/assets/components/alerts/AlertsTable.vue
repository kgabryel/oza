<template>
  <v-data-table
    :headers='headers'
    :items='alerts'
    :items-per-page='perPage'
    :sort-by.sync='column'
    :sort-desc.sync='desc'
    class='elevation-1'
    multi-sort
  >
    <template #item.content='{ item }'>
      <div class='pa-1'>
        <alert
          :description='item.description'
          :name='item.typeName'
          :type='item.type'
        />
      </div>
    </template>
    <template #item.active='{ item }'>
      <alert-status
        :id='item.id'
        :checked='item.isActive'
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
import AlertStatus from './AlertStatus';
import Delete from '../Delete';
import paths from '../../config/paths';
import Alert from './Alert';

export default {
    name: 'AlertsTable',
    components: {AlertStatus, Delete, Alert},
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
        headers: tableHeaders.alerts,
        alerts: [],
        perPage: 5,
        column: 'content',
        desc: false
    }),
    mounted() {
        this.alerts = JSON.parse(this.positions);
        this.perPage = parseInt(this.limit);
    },
    methods: {
        path: id => paths.alert(id)
    }
};
</script>
<style scoped>
*::v-deep th:nth-child(1) {
    width: 100%;
}

*::v-deep .v-data-table__mobile-table-row > .v-data-table__mobile-row:first-child,
*::v-deep .v-data-table__mobile-table-row > .v-data-table__mobile-row:nth-child(3) {
    display: block !important;
}

*::v-deep .v-data-table__mobile-table-row > .v-data-table__mobile-row:first-child > .v-data-table__mobile-row__header {
    padding-top: 6px;
    text-align: center;
}

@media screen  and (max-width: 600px) {
    .actions-column {
        display: flex;
        justify-content: space-between;
        margin-left: 0;
        width: 100%;
    }
}
</style>