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
            Sklepy - opcje
          </p>
        </div>
      </v-toolbar>
      <div class='pa-3'>
        <v-list subheader>
          <v-list-item-group multiple>
            <v-list-item
              v-for='(option, index) in shopsPrices'
              :key='index'
              @click='select(option.shops)'
            >
              <span>
                <v-list-item-content>
                  <v-list-item-title>
                    <span
                      v-for='(shop, shopIndex) in option.shops'
                      :key='shopIndex'
                    >
                      {{ shop.shopName }}: {{ option.totalPrice.toFixed(2) }} zł
                    </span>
                  </v-list-item-title>
                  <v-list-item-subtitle>
                    Razem: {{ option.totalPrice.toFixed(2) }} zł
                  </v-list-item-subtitle>
                </v-list-item-content>
              </span>
            </v-list-item>
          </v-list-item-group>
        </v-list>
        <div
          v-if='shopsPrices.length === 0'
          class='text-center'
        >
          Brak opcji
        </div>
      </div>
    </v-card>
  </v-dialog>
</template>
<script>
import events from '../../config/events';

export default {
    name: 'ShopsOptionsDialog',
    props: {
        visible: Boolean,
        shopsPrices: {
            type: Array,
            default: () => []
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
        select: function (shopping) {
            this.$emit(events.selectShops, shopping);
        },
        getTotalPriceInShop(shopping, shopId) {
            let total = 0;
            shopping.forEach(function (position) {
                if (position.shopId === shopId) {
                    total += parseFloat(position.price);
                }
            });
            return total.toFixed(2);
        }
    }
};
</script>
<style scoped>
</style>