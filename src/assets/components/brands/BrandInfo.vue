<template>
  <v-dialog
    v-model='showed'
    width='500'
  >
    <v-card>
      <v-card-title>
        <a
          :href='brandHref(id)'
          class='text-decoration-none'
        >
          {{ name }}
        </a>
        <v-spacer />
        <v-btn
          icon
          type='button'
          @click='showed = false'
        >
          <v-icon>
            mdi-close
          </v-icon>
        </v-btn>
      </v-card-title>
      <v-card-text>
        <a
          v-for='product in products'
          :key='product.id'
          :href='productHref(product.id)'
          class='text-decoration-none d-inline-block ma-1'
        >
          <v-chip
            class='c-pointer text--white'
            color='blue'
            label
          >
            {{ product.name }}
          </v-chip>
        </a>
      </v-card-text>
    </v-card>
  </v-dialog>
</template>
<script>
import {bus} from '../../app';
import events from '../../config/events';
import paths from '../../config/paths';

export default {
    name: 'BrandInfo',
    data: () => ({
        name: '',
        products: [],
        id: 0,
        showed: false
    }),
    mounted() {
        bus.$on(events.brandInfoShow, data => {
            this.showed = true;
            this.fillData(data);
        });
    },
    methods: {
        fillData: function (data) {
            this.id = data.id;
            this.name = data.name;
            this.products = data.products;
        },
        brandHref: id => paths.brand(id),
        productHref: id => paths.product(id)
    }
};
</script>
<style scoped>
</style>