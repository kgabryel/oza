<template>
  <div>
    <nav-header
      :showed='showed'
      :user-id='userId'
      @switch-menu='switchMenu'
    />
    <menu-nav-small
      v-if='small'
      :active-page='activePage'
      :menu='menu'
      :showed='showed'
      :username='username'
    />
    <menu-nav-big
      v-else
      :active-page='activePage'
      :menu='menu'
      :showed='showed'
      :username='username'
    />
    <v-main class='page-content'>
      <slot />
    </v-main>
  </div>
</template>
<script>
import NavHeader from './NavHeader';
import MenuNavSmall from './MenuNavSmall';
import MenuNavBig from './MenuNavBig';

export default {
    name: 'App',
    components: {NavHeader, MenuNavSmall, MenuNavBig},
    props: {
        username: {
            type: String,
            required: true
        },
        menu: {
            type: String,
            required: true
        },
        userId: {
            type: Number | String,
            required: true
        },
        page: {
            type: String,
            default: '-1'
        }
    },
    data: () => ({
        showed: false,
        small: false
    }),
    computed: {
        activePage: function () {
            return parseInt(this.page);
        }
    },
    mounted() {
        this.small = window.innerWidth < 600;
        window.onresize = () => this.small = window.innerWidth < 600;
    },
    methods: {
        switchMenu: function () {
            this.showed = !this.showed;
        }
    }
};
</script>
<style scoped>
@media screen and (min-width: 600px) {
    .page-content {
        padding-left: 60px !important;
    }
}
</style>