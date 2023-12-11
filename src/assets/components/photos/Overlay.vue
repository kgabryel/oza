<template>
  <div
    v-if='photo.id'
    class='overlay pa-2 d-flex justify-center align-center'
    @click.stop='photo = {}'
  >
    <v-img
      :height="'100%'"
      :max-height='photo.height'
      :max-width='photo.width'
      :src='url'
      :width="'100%'"
      contain
      dark
    />
  </div>
</template>
<script>
import paths from '../../config/paths';
import {bus} from '../../app';
import events from '../../config/events';

export default {
    name: 'Overlay',
    data: () => ({
        photo: {}
    }),
    computed: {
        url: function () {
            if (!this.photo.id) {
                return '';
            }
            return paths.originalPhoto(this.photo.id);
        }
    },
    mounted() {
        bus.$on(events.originalPhotoShow, photo => this.photo = photo);
    }
};
</script>
<style scoped>
.overlay {
    align-items: center;
    background-color: rgba(0, 0, 0, 0.8);
    bottom: 0;
    cursor: pointer;
    display: flex;
    justify-content: center;
    left: 0;
    max-height: 100%;
    max-width: 100%;
    position: fixed;
    right: 0;
    top: 0;
    z-index: 1000;
}
</style>