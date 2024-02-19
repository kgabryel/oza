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
              :href='productHref(id)'
              class='text-decoration-none'
            >
              <span>
                {{ name }}
              </span>
              <span v-if="brand !== ''">
                [{{ brand }}]
              </span>
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
              v-for='group in productsGroups'
              :key='group.id'
              :href='productsGroupHref(group.id)'
              class='text-decoration-none d-inline-block ma-1'
            >
              <v-chip
                class='c-pointer text--white'
                color='blue'
                label
              >
                {{ group.name }}
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

export default {
    name: 'ProductInfo',
    data: () => ({
        name: '',
        productsGroups: [],
        id: 0,
        note: '',
        showed: false,
        brand: '',
        photo: null
    }),
    created: function () {
        bus.$on(events.productInfoShow, data => {
            this.showed = true;
            this.fillData(data);
        });
    },
    methods: {
        fillData: function (data) {
            this.id = data.id;
            this.name = data.name;
            this.productsGroups = data.productsGroups;
            this.note = data.note;
            this.brand = data.brand?.name ?? '';
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