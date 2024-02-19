<template>
  <v-dialog
    v-model='showed'
    width='500'
  >
    <v-card>
      <div class='d-flex flex-no-wrap'>
        <v-card
          v-if='photo !== null'
          class='mt-3 mx-2 c-pointer'
          @click='showFullPhoto'
        >
          <v-img
            :height='100'
            :src='photoUrl(photo.id)'
            :width='100'
            style='max-width: 100px'
          />
        </v-card>
        <div class='w-100'>
          <v-card-title>
            <a
              :href='productsGroupHref(id)'
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
        </div>
      </div>
    </v-card>
  </v-dialog>
</template>
<script>
import {bus} from '../../app';
import events from '../../config/events';
import paths from '../../config/paths';
import Overlay from '../photos/Overlay.vue';

export default {
    name: 'ProductsGroupInfo',
    components: {Overlay},
    data: () => ({
        name: '',
        products: [],
        id: 0,
        note: '',
        showed: false,
        photo: null
    }),
    mounted() {
        bus.$on(events.productsGroupInfoShow, data => {
            this.showed = true;
            this.fillData(data);
        });
    },
    methods: {
        fillData: function (data) {
            this.id = data.id;
            this.name = data.name;
            this.products = data.products;
            this.note = data.note;
            this.photo = data.photo;
        },
        productHref: id => paths.product(id),
        productsGroupHref: id => paths.productsGroup(id),
        photoUrl: id => paths.smallPhoto(id),
        showFullPhoto: function () {
            bus.$emit(events.originalPhotoShow, this.photo);
        }
    }
};
</script>
<style scoped>
</style>