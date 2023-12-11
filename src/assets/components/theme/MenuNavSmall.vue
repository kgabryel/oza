<template>
  <v-navigation-drawer
    v-model='drawer'
    class='pt-16'
    clipped
    fixed
    temporary
  >
    <v-list-item>
      <v-list-item-content>
        <v-list-item-title class='text-h6 text--white'>
          {{ username }}
        </v-list-item-title>
      </v-list-item-content>
    </v-list-item>
    <v-divider />
    <v-list
      dense
      shaped
    >
      <v-list-item-group>
        <AnchorMenu :showed='showed' />
      </v-list-item-group>
    </v-list>
    <v-list
      dense
      shaped
    >
      <v-list-item-group v-model='active'>
        <a
          v-for='item in menuItems'
          :key='item.name'
          :href='item.href'
          class='text-decoration-none'
        >
          <v-list-item link>
            <v-list-item-icon>
              <v-tooltip
                v-if='showed'
                right
              >
                <template #activator='{ on, attrs }'>
                  <v-icon
                    color='white'
                    v-bind='attrs'
                    v-on='on'
                  >
                    {{ item.icon }}
                  </v-icon>
                </template>
                <span>
                  {{ item.name }}
                </span>
              </v-tooltip>
              <v-icon
                v-else
                color='white'
              >
                {{ item.icon }}
              </v-icon>
            </v-list-item-icon>
            <v-list-item-content>
              <v-list-item-title class='text--white'>
                {{ item.name }}
              </v-list-item-title>
            </v-list-item-content>
          </v-list-item>
        </a>
      </v-list-item-group>
    </v-list>
  </v-navigation-drawer>
</template>
<script>
import AnchorMenu from './AnchorMenu.vue';

export default {
    name: 'MenuNavSmall',
    components: {AnchorMenu},
    props: {
        showed: {
            type: Boolean,
            default: true
        },
        username: {
            type: String,
            required: true
        },
        menu: {
            type: String,
            required: true
        },
        activePage: {
            type: Number,
            default: -1
        }
    },
    data: () => ({
        active: 0,
        menuItems: [],
        drawer: false
    }),
    watch: {
        showed: {
            immediate: true,
            handler(val) {
                this.drawer = val;
            }
        }
    },
    mounted() {
        this.menuItems = JSON.parse(this.menu);
        this.active = this.activePage;
    }
};
</script>
<style scoped>
.v-navigation-drawer {
    background-color: #364a5f !important;
}

.v-item--active {
    background-color: #64b5f6 !important;
}
</style>