<template>
  <v-slide-group
    center-active
    class='pa-4'
    show-arrows
  >
    <v-slide-item>
      <upload-button />
    </v-slide-item>
    <v-slide-item
      v-for='position in positions'
      :key='position.id'
    >
      <photo
        :deletable='position.deletable'
        :photo='position'
        :selected='position.selected'
        :url='url'
        @changed='changed'
        @delete='deleted(position.id)'
      />
    </v-slide-item>
  </v-slide-group>
</template>
<script>
import Photo from './Photo.vue';
import UploadButton from './UploadButton.vue';
import Overlay from './Overlay.vue';
import {bus} from '../../app';
import events from '../../config/events';

export default {
    name: 'Gallery',
    components: {Overlay, Photo, UploadButton},
    props: {
        photos: {
            type: String,
            required: true
        },
        url: {
            type: String,
            required: true
        }
    },
    data: () => ({
        positions: []
    }),
    mounted() {
        this.positions = JSON.parse(this.photos);
        bus.$on(events.photoAdded, photo => this.positions.unshift(photo));
    },
    methods: {
        deleted: function (id) {
            this.positions = this.positions.filter(position => position.id !== id);
        },
        changed: function (data) {
            let key = this.positions.findIndex(photo => photo.id === data.id);
            if (key === -1) {
                return;
            }
            let keys = this.positions.keys();
            for (const key of keys) {
                this.positions[key].selected = false;
            }
            this.positions[key].selected = data.selected;
        }
    }
};
</script>
<style scoped>
</style>