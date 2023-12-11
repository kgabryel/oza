<template>
  <div
    class='c-pointer'
    @click='toggleDarkTheme'
  >
    <v-tooltip
      v-if='darkMode'
      bottom
    >
      <template #activator='{ on, attrs }'>
        <v-icon
          class='ml-2'
          large
          v-bind='attrs'
          v-on='on'
        >
          mdi-brightness-2
        </v-icon>
      </template>
      <span>
        Wyłącz ciemny tryb
      </span>
    </v-tooltip>
    <v-tooltip
      v-else
      bottom
    >
      <template #activator='{ on, attrs }'>
        <v-icon
          class='ml-2'
          color='white'
          large
          v-bind='attrs'
          v-on='on'
        >
          mdi-brightness-5
        </v-icon>
      </template>
      <span>
        Włącz ciemny tryb
      </span>
    </v-tooltip>
  </div>
</template>
<script>
export default {
    name: 'ModeSwitch',
    props: {
        userId: {
            type: Number | String,
            required: true
        }
    },
    data: () => ({
        darkMode: false
    }),
    mounted() {
        if (localStorage.getItem('mode-' + this.userId)) {
            this.darkMode = localStorage.getItem('mode-' + this.userId) === 'true';
            this.$vuetify.theme.dark = this.darkMode;
            if (this.darkMode) {
                document.getElementById('body').classList.add('dark-background');
            }
        }
    },
    methods: {
        toggleDarkTheme: function () {
            this.$vuetify.theme.dark = !this.$vuetify.theme.dark;
            this.darkMode = this.$vuetify.theme.dark;
            if (this.darkMode) {
                document.getElementById('body').classList.add('dark-background');
            } else {
                document.getElementById('body').classList.remove('dark-background');
            }
            localStorage.setItem('mode-' + this.userId, this.darkMode);
        }
    }
};
</script>
<style scoped>
</style>