<template>
  <v-data-table
    :headers='headers'
    :items='supplies'
    :items-per-page='perPage'
    class='elevation-1'
    multi-sort
  >
    <template #item='{ item}'>
      <tr>
        <td>
          <span v-if='item.part > 1'>{{ item.part }} x</span> {{ item.amount.toFixed(item.amount % 1 === 0 ? 0 : 2) }}
          {{ item.unit.shortcut }}
        </td>
        <td>
          <unit-info-button
            :id='item.unit.id'
            :name='item.unit.name'
          />
        </td>
        <td>
          <product-info-button
            v-if='item.product.id !== null'
            :id='item.product.id'
            :name='item.product.name'
          />
        </td>
        <td>
          {{ item.updatedAt }}
        </td>
        <td>
          <span v-if='item.open'>
            Tak
          </span>
          <span v-else>
            Nie
          </span>
        </td>
        <td>
          {{ item.dateOfConsumption }}
        </td>
        <td>
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
        </td>
      </tr>
      <tr v-if="item.description !== ''">
        <td
          class='text-center'
          colspan='7'
        >
          {{ item.description }}
        </td>
      </tr>
    </template>
  </v-data-table>
</template>
<script>
import tableHeaders from '../../config/tableHeaders';
import Delete from '../Delete';
import paths from '../../config/paths';
import ProductsGroupInfoButton from '../productsGroups/ProductsGroupInfoButton';
import UnitInfoButton from '../units/UnitInfoButton';
import ProductInfoButton from '../products/ProductInfoButton';

export default {
    name: 'SupplyPartsTable',
    components: {Delete, ProductsGroupInfoButton, UnitInfoButton, ProductInfoButton},
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
        headers: tableHeaders.supplyParts,
        supplies: [],
        perPage: 5
    }),
    mounted() {
        this.supplies = JSON.parse(this.positions);
        this.perPage = parseInt(this.limit);
    },
    methods: {
        path: id => paths.supplyPart(id)
    }
};
</script>
<style scoped>
</style>