<template>
  <div>
    <v-progress-linear
      v-if='loading'
      class='progress-bar'
      color='cyan darken-2'
      indeterminate
    />
    <v-app-bar
      :class="{'loading': loading}"
      app
    >
      <v-app-bar-nav-icon
        color='white'
        @click='switchMenu'
      >
        <v-icon v-if='showed'>
          mdi-close
        </v-icon>
        <v-icon v-else>
          mdi-menu
        </v-icon>
      </v-app-bar-nav-icon>
      <a
        class='ml-3'
        href='/'
      >
        <img
          alt='logo'
          src='/img/logo.png'
        >
      </a>
      <v-spacer />
      <alerts />
      <ModeSwitch :user-id='userId' />
      <a
        class='text-decoration-none ml-3 d-flex d-sm-none'
        href='/logout'
      >
        <v-btn
          class='logout-icon-btn'
          color='error'
          type='button'
        >
          <v-icon dark>mdi-logout-variant</v-icon>
        </v-btn>
      </a>
      <a
        class='text-decoration-none ml-3 d-none d-sm-flex'
        href='/logout'
      >
        <v-btn
          color='error'
          type='button'
        >
          Wyloguj
        </v-btn>
      </a>
    </v-app-bar>
  </div>
</template>
<script>
import Alerts from '../alerts/Alerts';
import events from '../../config/events';
import ModeSwitch from './ModeSwitch';
import {bus} from '../../app';

export default {
    name: 'NavHeader',
    components: {ModeSwitch, Alerts},
    props: {
        userId: {
            type: Number | String,
            required: true
        },
        showed: Boolean
    },
    data: () => ({
        loading: false
    }),
    mounted() {
        this.small = window.innerWidth < 600;
        window.onresize = () => this.small = window.innerWidth < 600;
        bus.$on(events.loading, () => this.loading = true);
    },
    methods: {
        switchMenu: function () {
            this.$emit(events.switchMenu);
        }
    }
};
</script>
<style scoped>
header {
    background-color: #243a51 !important;
    z-index: 10 !important;
}

img {
    height: 45px;
    width: auto;
}

.logout-icon-btn {
    min-width: 32px !important;
    padding: 0 !important;
    padding-left: 6px !important;
    padding-right: 6px !important;
}

.progress-bar {
    background: #243a51;
    z-index: 11;
}

.loading {
    margin-top: 4px !important;
}
</style>