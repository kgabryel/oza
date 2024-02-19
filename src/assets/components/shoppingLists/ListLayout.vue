<template>
  <div>
    <div
      v-for='(position, index) in positions'
      :key='index'
      class='w-100'
    >
      <div
        v-if='(!position.checked || !hideBought) && shopAvailable(position)'
        class='pa-1 big'
      >
        <div class='d-flex align-center'>
          <v-btn
            v-if='position.checked'
            class='pa-0'
            color='error'
            elevation='2'
            tile
            type='button'
            @click='changeStatus(position.id, position.checked)'
          >
            <v-icon dark>
              mdi-close
            </v-icon>
          </v-btn>
          <v-btn
            v-if='!position.checked'
            class='pa-0'
            color='success'
            elevation='2'
            tile
            type='button'
            @click='changeStatus(position.id, position.checked)'
          >
            <v-icon dark>
              mdi-check
            </v-icon>
          </v-btn>
          <div class='px-1 mb-0 w-100'>
            <p
              :class="{'text-decoration-line-through': position.checked}"
              class='mb-0'
            >
              <products-group-info-button
                v-if='position.isGroup === 1'
                :id='position.productId'
                :name='position.name'
              />
              <product-info-button
                v-else
                :id='position.productId'
                :name='position.name'
              />
            </p>
            <p
              :class="{'text-decoration-line-through': position.checked}"
              class='mb-0'
            >
              {{ position.amount.toFixed(position.amount % 1 === 0 ? 0 : 2) }}
              <unit-info-button
                :id='position.unitId'
                :name='position.unitShortcut'
              />
              <span v-if='position.shopName'>
                  ({{ position.shopName }})
                </span>
            </p>
            <p v-if="position.description !== null && position.description !== ''">
              {{ position.description }}
            </p>
          </div>
          <v-btn
            class='pa-0 mr-3'
            color='info'
            elevation='2'
            tile
            type='button'
            @click='showPriceDialog(position)'
          >
            <v-icon dark>
              mdi-home
            </v-icon>
          </v-btn>
          <v-btn
            class='pa-0'
            color='error'
            elevation='2'
            tile
            type='button'
            @click='deletePosition(position.id)'
          >
            <v-icon dark>
              mdi-delete
            </v-icon>
          </v-btn>
        </div>
      </div>
      <div
        v-if='(!position.checked || !hideBought) && shopAvailable(position)'
        class='pa-1 small'
      >
        <div>
          <p
            :class="{'text-decoration-line-through': position.checked}"
            class='mb-0'
          >
            <products-group-info-button
              v-if='position.isGroup === 1'
              :id='position.productId'
              :name='position.name'
            />
            <product-info-button
              v-else
              :id='position.productId'
              :name='position.name'
            />
          </p>
          <p
            :class="{'text-decoration-line-through': position.checked}"
            class='mb-0'
          >
            {{ position.amount.toFixed(position.amount % 1 === 0 ? 0 : 2) }}
            <unit-info-button
              :id='position.unitId'
              :name='position.unitShortcut'
            />
            <span v-if='position.shopName'>
                ({{ position.shopName }})
              </span>
          </p>
          <p v-if="position.description !== null && position.description !== ''">
            {{ position.description }}
          </p>
        </div>
        <div class='buttons'>
          <v-btn
            v-if='position.checked'
            class='pa-0'
            color='error'
            elevation='2'
            tile
            type='button'
            @click='changeStatus(position.id, position.checked)'
          >
            <v-icon dark>
              mdi-close
            </v-icon>
          </v-btn>
          <v-btn
            v-else
            class='pa-0'
            color='success'
            elevation='2'
            tile
            type='button'
            @click='changeStatus(position.id, position.checked)'
          >
            <v-icon dark>
              mdi-check
            </v-icon>
          </v-btn>
          <v-btn
            class='pa-0'
            color='info'
            elevation='2'
            tile
            type='button'
            @click='showPriceDialog(position)'
          >
            <v-icon dark>
              mdi-home
            </v-icon>
          </v-btn>
          <v-btn
            class='pa-0'
            color='error'
            elevation='2'
            tile
            type='button'
            @click='deletePosition(position.id)'
          >
            <v-icon dark>
              mdi-delete
            </v-icon>
          </v-btn>
        </div>
      </div>
    </div>
  </div>
</template>
<script>
import ProductsGroupInfoButton from '../productsGroups/ProductsGroupInfoButton.vue';
import ProductInfoButton from '../products/ProductInfoButton.vue';
import UnitInfoButton from '../units/UnitInfoButton.vue';
import events from '../../config/events';
import paths from '../../config/paths';
import {bus} from '../../app';

export default {
    name: 'ListLayout',
    components: {UnitInfoButton, ProductInfoButton, ProductsGroupInfoButton},
    props: {
        hideBought: Boolean,
        positions: {
            type: Array,
            required: true
        },
        selectedShops: {
            type: Array,
            required: true
        }
    },
    methods: {
        shopAvailable: function (position) {
            let id = position.shopId ?? null;
            return this.selectedShops.includes(id);
        },
        changeStatus: function (positionId, status) {
            this.$emit(events.changeStatus, {id: positionId, status});
        },
        deletePosition: function (id) {
            this.$emit(events.delete, id);
        },
        showPriceDialog: function (position) {
            this.$emit(events.openPriceDialog, position);
        },
        mediumUrl: function (position) {
            if (position.photo === null) {
                return '';
            }
            return paths.mediumPhoto(position.photo.id);
        },
        showFullPhoto: function (position) {
            bus.$emit(events.originalPhotoShow, position.photo);
        }
    }
};
</script>
<style scoped>
button {
    min-width: 36px !important;
    width: 36px !important;
}

.small {
    display: none;
}

@media only screen and (max-width: 500px) {
    .small {
        display: block
    }

    .big {
        display: none;
    }
}

.small .buttons {
    display: flex;
    justify-content: space-between;
}
</style>