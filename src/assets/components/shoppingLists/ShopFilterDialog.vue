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
            Sklepy - filtrowanie
          </p>
        </div>
      </v-toolbar>
      <div class='pa-3'>
        <v-list subheader>
          <v-list-item-group multiple>
            <v-list-item
              v-for='(shop, index) in shops'
              :key='index'
              @click='changeShopStatus(index)'
            >
              <span>
                <v-list-item-action>
                  <v-checkbox
                    v-model='shop.checked'
                    readonly
                  />
                </v-list-item-action>
                <v-list-item-content>
                  <v-list-item-title>
                    {{ shop.name }}
                  </v-list-item-title>
                </v-list-item-content>
              </span>
            </v-list-item>
          </v-list-item-group>
        </v-list>
      </div>
    </v-card>
  </v-dialog>
</template>
<script>
import events from '../../config/events';

export default {
    name: 'ShopFilterDialog',
    props: {
        visible: Boolean,
        shops: {
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
        changeShopStatus: function (id) {
            this.$emit(events.changeShopStatus, id);
        }
    }
};
</script>
<style scoped>
</style>