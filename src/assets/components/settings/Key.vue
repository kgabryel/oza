<template>
  <div>
    <p class='mb-0'>
      {{ apiKey }}
    </p>
    <div class='d-flex justify-space-between'>
      <v-btn
        class='mt-3'
        elevation='2'
        large
        type='button'
        @click='copy'
      >
        <v-icon
          dark
          left
        >
          mdi-clipboard-multiple-outline
        </v-icon>
        Kopiuj
      </v-btn>
      <div>
        <v-switch
          v-model='isActive'
          class='big-switch'
          hide-details='auto'
          label='Aktywne'
          @change='update'
        />
      </div>
      <form
        :action='path(id)'
        class='d-inline-block'
        method='post'
      >
        <input
          name='_method'
          type='hidden'
          value='delete'
        >
        <delete-button />
      </form>
    </div>
    <div class='d-flex justify-center mt-1'>
      <v-switch
        v-model='isActive'
        class='small-switch'
        hide-details='auto'
        label='Aktywne'
        @change='update'
      />
    </div>
    <slot />
  </div>
</template>
<script>
import {bus} from '../../app';
import events from '../../config/events';
import paths from '../../config/paths';
import DeleteButton from '../DeleteButton';
import axios from 'axios';
import routing from '../../config/routing';

export default {
    name: 'Key',
    components: {DeleteButton},
    props: {
        apiKey: {
            type: String,
            required: true
        },
        active: {
            type: String,
            required: true
        },
        id: {
            type: String,
            required: true
        }
    },
    data: () => ({
        isActive: false
    }),
    mounted() {
        this.isActive = this.active === 'true';
    },
    methods: {
        copy: function () {
            let container = this.$refs.container;
            this.$copyText(this.apiKey, container);
            bus.$emit(events.showSuccess, ['Klucz został skopiowany.']);
        },
        update: function () {
            axios.patch(routing.apiKey(this.id)).then(() => bus.$emit(events.showSuccess, ['Klucz został zaktualizowany.']));
        },
        path: id => paths.apiKey(id)
    }
};
</script>
<style scoped>
.small-switch {
    display: none;
}

@media screen and (max-width: 560px) {
    .big-switch {
        display: none;
    }

    .small-switch {
        display: block;
    }
}
</style>