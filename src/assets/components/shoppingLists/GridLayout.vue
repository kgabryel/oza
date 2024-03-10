<template>
  <div class='wrapper'>
    <template v-for='(position, index) in positions'>
      <div v-if='(!position.checked || !hideBought) && shopAvailable(position)' :key='index' class='tile pa-2'>
        <p
          :class="{'text-decoration-line-through': position.checked}"
          class='mb-0 text-center'
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
        <div class='img w-100 d-flex justify-center'>
          <v-img
            v-if='position.photo !== null'
            :height='200'
            :max-width='200'
            :src='mediumUrl(position)'
            :width='200'
            class='c-pointer'
            @click='showFullPhoto(position)'
          />
          <v-icon v-else class='dummy-image' dark>
            mdi-image-off-outline
          </v-icon>
        </div>
        <p
          :class="{'text-decoration-line-through': position.checked}"
          class='mb-0 text-center'
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
        <p v-if="position.description !== null && position.description !== ''">
          {{ position.description }}
        </p>
      </div>
    </template>
  </div>
</template>
<script>
import paths from '../../config/paths';
import {bus} from '../../app';
import events from '../../config/events';
import ProductsGroupInfoButton from '../productsGroups/ProductsGroupInfoButton.vue';
import ProductInfoButton from '../products/ProductInfoButton.vue';
import UnitInfoButton from '../units/UnitInfoButton.vue';

export default {
    name: 'GridLayout',
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
.tile {
    min-height: 300px;
}

.buttons {
    display: flex;
    justify-content: space-between;
}

.img {
    height: 200px;
}

.wrapper {
    display: grid;
    grid-template-columns: repeat(auto-fill, minmax(200px, 1fr));
    grid-gap: 1em;
}

.dummy-image {
    font-size: 100px;
}
</style>