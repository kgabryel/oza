<template>
  <v-dialog
    v-model='showed'
    width='500'
  >
    <v-card elevation='2'>
      <v-toolbar
        color='primary'
        dark
        dense
      >
        <div>
          <p class='mb-0'>
            Sklepy
          </p>
        </div>
      </v-toolbar>
      <div class='pa-3'>
        <v-list
          subheader
          two-line
        >
          <v-list-item
            v-for='(price, index) in prices'
            :key='index'
            :class="{'selected-shop': selectedPosition.shopId && selectedPosition.shopId === price.shopId}"
          >
            <v-list-item-content>
              <v-list-item-title>
                {{ price.shopName }}
                <span v-if='price.min && price.min !== price.max'>
                  - od {{ parseFloat(price.min).toFixed(2) }} do {{ parseFloat(price.max).toFixed(2) }} zł
                </span>
                <span v-if='price.min && price.min === price.max'>
                  - {{ parseFloat(price.min).toFixed(2) }}  zł
                </span>
                <span v-if='price.min === undefined && price.price'>
                  {{ pricePerUnit(price) }}
                </span>
              </v-list-item-title>
              <v-list-item-subtitle>
                <span v-if='price.shortcut === undefined'>Brak danych</span>
                <span v-if='price.date'>Stara oferta - {{ price.date.date.slice(0, 10) }}</span>
                <span v-if='price.singleMinPrice && price.singleMinPrice !== price.singleMaxPrice'>
                  {{ priceInRange(price) }}
                </span>
                <span v-if='price.singleMinPrice && price.singleMinPrice === price.singleMaxPrice'>
                  {{ parseFloat(price.singleMinPrice).toFixed(2) }}  zł / {{ price.shortcut }}
                </span>
              </v-list-item-subtitle>
            </v-list-item-content>
            <v-list-item-action>
              <v-btn
                v-if='!(selectedPosition.shopId && selectedPosition.shopId === price.shopId)'
                class='pa-0 mr-3 select-shop-btn'
                color='success'
                elevation='2'
                tile
                type='button'
                @click='changeShop(selectedPosition.id, price.shopId)'
              >
                <v-icon dark>
                  mdi-check
                </v-icon>
              </v-btn>
            </v-list-item-action>
          </v-list-item>
        </v-list>
        <v-btn
          v-if='selectedPosition && selectedPosition.shopId'
          block
          class='mt-3'
          color='error'
          elevation='2'
          large
          type='button'
          @click='changeShop(selectedPosition.id, null)'
        >
          <v-icon
            dark
            left
          >
            mdi-close
          </v-icon>
          Usuń sklep
        </v-btn>
      </div>
    </v-card>
  </v-dialog>
</template>
<script>
import events from '../../config/events';

export default {
    name: 'PriceDialog',
    props: {
        visible: Boolean,
        prices: {
            type: Array,
            required: true
        },
        selectedPosition: {
            type: Object | null,
            default: null
        }
    },
    data: () => ({
        showed: false
    }),
    watch: {
        visible: function () {
            this.showed = this.visible;
        },
        showed: function () {
            if (!this.showed) {
                this.$emit(events.closeDialog);
            }
        }
    },
    methods: {
        changeShop: function (positionId, shopId) {
            this.$emit(events.selectShop, {positionId, shopId});
            this.showed = false;
        },
        pricePerUnit: function (price) {
            const parsedPrice = parseFloat(price.price).toFixed(2);
            const parsedSinglePrice = parseFloat(price.singlePrice).toFixed(2);
            return `${parsedPrice} zł (${parsedSinglePrice}) zł / ${price.shortcut}`;
        },
        priceInRange: function (price) {
            const parsedMinPrice = parseFloat(price.singleMinPrice).toFixed(2);
            const parsedMaxPrice = parseFloat(price.singleMaxPrice).toFixed(2);
            return `od ${parsedMinPrice} zł / ${price.shortcut} do ${parsedMaxPrice} zł / ${price.shortcut}`;
        }
    }
};
</script>
<style scoped>
.selected-shop {
    background: #c5e5fc;
}

.dark-background .selected-shop {
    background: #1565c0;
}

.select-shop-btn {
    min-width: 36px !important;
    width: 36px !important;
}
</style>