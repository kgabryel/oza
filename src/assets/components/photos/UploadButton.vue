<template>
  <v-card
    class='c-pointer ma-2'
    @click='open()'
  >
    <div class='my-3 card d-flex justify-center align-center'>
      <v-icon size='100'>
        mdi-plus
      </v-icon>
    </div>
  </v-card>
</template>
<script>
import {bus} from '../../app';
import events from '../../config/events';

export default {
    name: 'UploadButton',
    methods: {
        open: function () {
            bus.$emit(events.uploadPhotoModalOpen);
        }
    }
};
</script>
<style scoped>
.card {
    height: 236px;
    width: 200px;
}

@media screen and (max-width: 959px) {
    .card {
        height: 136px;
        width: 100px;
    }
}
</style>