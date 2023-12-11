<template>
  <v-menu
    v-if='items.length > 0'
    bottom
    transition='slide-y-transition'
  >
    <template #activator='{ on, attrs }'>
      <v-list-item
        class='test'
        v-bind='attrs'
        v-on='on'
      >
        <v-list-item-icon>
          <v-tooltip
            v-if='showed'
            right
          >
            <template #activator='{ iconOn }'>
              <v-icon
                color='white'
                v-on='iconOn'
              >
                mdi-anchor
              </v-icon>
            </template>
            <span>
              Odnośniki
            </span>
          </v-tooltip>
          <v-icon
            v-else
            color='white'
          >
            mdi-anchor
          </v-icon>
        </v-list-item-icon>
        <v-list-item-content>
          <v-list-item-title class='text--white'>
            Odnośniki
          </v-list-item-title>
        </v-list-item-content>
      </v-list-item>
    </template>
    <v-list>
      <a
        v-for='item in items'
        :key='item.id'
        :href='item.id'
        class='text-decoration-none text--white without-loading'
      >
        <v-list-item>
          <v-list-item-title>
            {{ item.name }}
          </v-list-item-title>
        </v-list-item>
      </a>
    </v-list>
  </v-menu>
</template>
<script>
export default {
    name: 'AnchorMenu',
    props: {
        showed: {
            type: Boolean,
            default: true
        }
    },
    data: () => ({
        items: []
    }),
    mounted() {
        let elements = [...document.getElementsByClassName('anchor')];
        this.items = elements.filter(el => el.dataset.name !== undefined).map(element => {
            return {
                id: `#${element.id}`,
                name: element.dataset.name
            };
        });
    }
};
</script>
<style scoped>
.test {
    background-color: #364a5f !important;
}

.test:before {
    background-color: #364a5f !important;
}
</style>