<template>
  <v-card class='ma-2'>
    <div class='d-flex justify-space-between'>
      <v-tooltip
        v-if='selected'
        bottom
      >
        <template #activator='{ on, attrs }'>
          <v-btn
            icon
            type='button'
            v-bind='attrs'
            @click='changeMain(false)'
            v-on='on'
          >
            <v-icon color='amber darken-2'>
              mdi-star
            </v-icon>
          </v-btn>
        </template>
        <span>
          Odznacz
        </span>
      </v-tooltip>
      <v-tooltip
        v-else
        bottom
      >
        <template #activator='{ on, attrs }'>
          <v-btn
            icon
            type='button'
            v-bind='attrs'
            @click='changeMain(true)'
            v-on='on'
          >
            <v-icon>
              mdi-star-outline
            </v-icon>
          </v-btn>
        </template>
        <span>
          Oznacz jako główne
        </span>
      </v-tooltip>
      <v-tooltip bottom>
        <template #activator='{ on, attrs }'>
          <v-btn
            :disabled='!deletable'
            icon
            type='button'
            v-bind='attrs'
            @click='deleteDialog = true'
            v-on='on'
          >
            <v-icon>
              mdi-delete
            </v-icon>
          </v-btn>
        </template>
        <span>
          Usuń
        </span>
      </v-tooltip>
    </div>
    <v-img
      :height='100'
      :src='smallUrl'
      :width='100'
      class='ma-3 c-pointer d-md-none'
      @click='showFullPhoto'
    />
    <v-img
      :height='200'
      :src='mediumUrl'
      :width='200'
      class='ma-3 c-pointer d-none d-md-flex'
      @click='showFullPhoto'
    />
    <delete-dialog
      :visible='deleteDialog'
      @delete='confirmDelete'
      @close-dialog='deleteDialog = false'
    />
  </v-card>
</template>
<script>
import paths from '../../config/paths';
import {bus} from '../../app';
import events from '../../config/events';
import DeleteDialog from '../DeleteDialog.vue';
import axios from 'axios';
import routing from '../../config/routing';

export default {
    name: 'Photo',
    components: {DeleteDialog},
    props: {
        photo: {
            type: Object,
            required: true
        },
        selected: Boolean,
        deletable: Boolean,
        url: {
            type: String,
            required: true
        }
    },
    data: () => ({
        deleteDialog: false
    }),
    computed: {
        mediumUrl: function () {
            return paths.mediumPhoto(this.photo.id);
        },
        smallUrl: function () {
            return paths.smallPhoto(this.photo.id);
        }
    },
    methods: {
        showFullPhoto: function () {
            bus.$emit(events.originalPhotoShow, this.photo);
        },
        confirmDelete: function () {
            axios.delete(routing.deletePhoto(this.photo.id)).then((response) => {
                let status = 0;
                if (response !== undefined) {
                    status = response.status ?? 0;
                }
                this.deleteDialog = false;
                if (status === 204) {
                    this.$emit(events.delete);
                    bus.$emit(events.showSuccess, ['Zdjęcie zostało usunięte.']);
                } else {
                    bus.$emit(events.showError, ['Wystąpił błąd podczas usuwania zdjęcia.']);
                }
            });
        },
        changeMain: function (selected) {
            let photo = selected ? this.photo.id : null;
            axios.post(this.url, {photo: photo}).then((response) => {
                let status = 0;
                if (response !== undefined) {
                    status = response.status ?? 0;
                }
                if (status === 204) {
                    this.$emit(events.changed, {
                        selected: selected,
                        id: this.photo.id
                    });
                    bus.$emit(events.showSuccess, ['Zmodyfikowano pomyślnie.']);
                } else {
                    bus.$emit(events.showError, ['Wystąpił błąd edycji.']);
                }
            });
        }
    }
};
</script>
<style scoped>
</style>