<template>
  <v-data-table
    :headers='headers'
    :items='productsGroups'
    :items-per-page='perPage'
    :sort-by.sync='column'
    :sort-desc.sync='desc'
    class='elevation-1'
    multi-sort
  >
    <template #item.photo='{ item }'>
      <div
        :class="{'without-photo': item.photo === null }"
        class='d-flex justify-space-between image-wr my-3'
      >
        <v-img
          :class="{'empty-image': item.photo === null }"
          :height='100'
          :max-width='100'
          :src='smallUrl(item)'
          :width='100'
          class='c-pointer'
          @click='showFullPhoto(item)'
        />
        <div class='d-flex align-center photo-column-actions'>
          <div style='max-width: 40px'>
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
        </div>
      </div>
    </template>
    <template #item.name='{ item }'>
      <div class='d-flex align-center'>
        <a
          :href='path(item.id)'
          class='link'
        >
          {{ item.name }}
        </a>
        <v-spacer />
        <products-group-badge
          v-if='item.products.length > 0'
          :id='item.id'
          :key='item.id'
          :count='item.products.length'
        />
      </div>
    </template>
    <template #item.unit='{ item }'>
      <unit-info-button
        :id='item.unit.id'
        :name='item.unit.name'
      />
    </template>
    <template #item.baseUnit='{ item }'>
      <unit-info-button
        :id='item.baseUnit.id'
        :name='item.baseUnit.name'
      />
    </template>
    <template #item.note='{ item }'>
      <span v-html='item.note' />
    </template>
    <template #item.actions='{ item }'>
      <div
        :class="{'with-photo': item.photo !== null }"
        class='ml-auto actions-column'
      >
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
import UnitInfoButton from '../units/UnitInfoButton';
import ProductsGroupBadge from './ProductsGroupBadge';
import Delete from '../Delete';
import paths from '../../config/paths';
import {bus} from '../../app';
import events from '../../config/events';

export default {
    name: 'ProductsGroupsTable',
    components: {UnitInfoButton, ProductsGroupBadge, Delete},
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
        headers: tableHeaders.productsGroups,
        productsGroups: [],
        column: 'name',
        desc: false
    }),
    computed: {
        perPage: function () {
            return parseInt(this.limit);
        }
    },
    mounted() {
        this.productsGroups = JSON.parse(this.positions);
    },
    methods: {
        path: id => paths.productsGroup(id),
        smallUrl: function (position) {
            if (position.photo === null) {
                return '';
            }
            return paths.smallPhoto(position.photo.id);
        },
        showFullPhoto: function (position) {
            bus.$emit(events.originalPhotoShow, position.photo);
        }
    }
};
</script>
<style scoped>
*::v-deep div:has(> .image-wr) {
    width: 100%;
}

@media screen and (max-width: 599px) {
    *::v-deep .v-data-table__mobile-row:has(.actions-column.with-photo) {
        display: none;
    }

    *::v-deep .v-data-table__mobile-row:has(.without-photo) {
        display: none;
    }
}

@media screen and (min-width: 600px) {
    .photo-column-actions {
        display: none !important;
    }
}

.empty-image {
    cursor: default;
}
</style>