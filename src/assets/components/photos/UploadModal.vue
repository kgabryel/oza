<template>
  <v-dialog
    v-model='showed'
    :max-width='image.src ? 1000 : 500'
  >
    <v-card
      :disabled='loading'
      :loading='loading'
    >
      <v-card-title>
        <p class='mb-0'>
          Dodawanie nowego zdjęcia
        </p>
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
      <div class='pa-3 overflow-hidden'>
        <div>
          <v-row>
            <v-col cols='6'>
              <v-btn
                block
                class='mt-3 upload-button'
                color='secondary'
                elevation='2'
                large
                type='button'
              >
                <input
                  ref='file'
                  accept='image/*'
                  type='file'
                  @change='uploadImage($event)'
                >
                <v-icon
                  dark
                  left
                >
                  mdi-upload
                </v-icon>
                Wybierz zdjęcie
              </v-btn>
            </v-col>
            <v-col cols='6'>
              <v-btn
                :disabled='!cropAvailable'
                block
                class='mt-3'
                color='primary'
                elevation='2'
                large
                type='button'
                @click='crop'
              >
                <v-icon
                  dark
                  left
                >
                  mdi-content-save
                </v-icon>
                Dodaj
              </v-btn>
            </v-col>
          </v-row>
        </div>
        <cropper
          v-if='image.src'
          ref='cropper'
          :src='image.src'
          :stencil-props='{aspectRatio: 1}'
          class='cropper my-3'
          @change='change'
        />
      </div>
    </v-card>
  </v-dialog>
</template>
<script>
import {bus} from '../../app';
import events from '../../config/events';
import {Cropper} from 'vue-advanced-cropper';
import 'vue-advanced-cropper/dist/style.css';
import axios from 'axios';

export default {
    name: 'UploadModal',
    components: {Cropper},
    props: {
        path: {
            type: String,
            required: true
        }
    },
    data: () => ({
        showed: false,
        image: {},
        cropAvailable: false,
        img: null,
        callback: null,
        photoData: '',
        loading: false
    }),
    created: function () {
        bus.$on(events.uploadPhotoModalOpen, () => {
            this.showed = true;
            if (this.$refs.file) {
                this.$refs.file.value = null;
            }
            this.reset();
        });
        this.reset();
        this.img = new Image();
        this.img.onload = () => this.check();
    },
    unmounted: function () {
        if (this.image.src) {
            URL.revokeObjectURL(this.image.src);
        }
    },
    methods: {
        uploadImage: function (event) {
            const {files} = event.target;
            if (files && files[0]) {

                const blob = URL.createObjectURL(files[0]);
                this.img.src = blob;

                this.callback = () => this.set(blob, files);
            }
        },
        crop: function () {
            this.loading = true;
            axios.post(this.path, {photo: this.photoData}).then((response) => {
                this.loading = false;
                let status = 0;
                if (response !== undefined) {
                    status = response.status ?? 0;

                } else {
                    bus.$emit(events.showError, ['Wystąpił błąd podczas dodawania zdjęcia.']);
                }
                if (status === 200) {
                    this.showed = false;
                    bus.$emit(events.showSuccess, ['Zdjęcie zostało dodane.']);
                    bus.$emit(events.photoAdded, response.data);
                }
            });
        },
        reset: function () {
            if (this.image.src) {
                URL.revokeObjectURL(this.image.src);
            }
            this.image = {};
            this.cropAvailable = false;
        },
        change: function (data) {
            this.photoData = data.canvas.toDataURL();
            this.cropAvailable = data.coordinates.width >= 200 && data.coordinates.height >= 200;
        },
        set: function (blob, files) {
            this.reset();
            this.image.src = blob;
            this.image.type = files[0].type;
            this.cropAvailable = true;
        },
        check: function () {
            if (this.img.width < 200 || this.img.height < 200) {
                bus.$emit(events.showError, ['Zdjęcia ma za małe wymiary.']);
            } else {
                this.callback();
            }
        }
    }
};
</script>
<style scoped>
.upload-button input[type=file] {
    background: white;
    cursor: inherit;
    display: block;
    filter: alpha(opacity=0);
    font-size: 0;
    height: 44px;
    opacity: 0;
    outline: none;
    position: absolute;
    right: -20px;
    text-align: right;
    top: -12px;
    width: calc(100% + 40px);
}
</style>