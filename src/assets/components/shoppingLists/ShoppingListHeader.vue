<template>
  <div class='d-flex align-center w-100 justify-space-between'>
    <v-tooltip bottom>
      <template #activator='{ on, attrs }'>
        <a
          :href='editPath'
          class='text-decoration-none'
          v-bind='attrs'
          v-on='on'
        >
          <v-btn
            icon
            type='button'
          >
            <v-icon>
              mdi-pencil
            </v-icon>
          </v-btn>
        </a>
      </template>
      <span>
        Edytuj
      </span>
    </v-tooltip>
    <v-tooltip
      v-if='hideBought'
      bottom
    >
      <template #activator='{ on, attrs }'>
        <v-btn
          icon
          type='button'
          v-bind='attrs'
          @click='switchVisibility'
          v-on='on'
        >
          <v-icon>
            mdi-eye
          </v-icon>
        </v-btn>
      </template>
      <span>
        Pokaż kupione
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
          @click='switchVisibility'
          v-on='on'
        >
          <v-icon>
            mdi-eye-off
          </v-icon>
        </v-btn>
      </template>
      <span>
        Ukryj kupione
      </span>
    </v-tooltip>
    <v-tooltip bottom>
      <template #activator='{ on, attrs }'>
        <v-btn
          icon
          type='button'
          v-bind='attrs'
          @click='openShopDialog'
          v-on='on'
        >
          <v-icon v-if='filteredShops'>
            mdi-home-edit
          </v-icon>
          <v-icon v-else>
            mdi-home
          </v-icon>
        </v-btn>
      </template>
      <span>
        Sklepy - filtrowanie
      </span>
    </v-tooltip>
    <v-tooltip bottom>
      <template #activator='{ on, attrs }'>
        <v-btn
          icon
          type='button'
          v-bind='attrs'
          @click='openShopPricesDialog'
          v-on='on'
        >
          <v-icon>
            mdi-currency-usd
          </v-icon>
        </v-btn>
      </template>
      <span>
        Sklepy - ceny
      </span>
    </v-tooltip>
    <v-tooltip bottom>
      <template #activator='{ on, attrs }'>
        <v-btn
          icon
          type='button'
          v-bind='attrs'
          @click='switchLayout'
          v-on='on'
        >
          <v-icon v-if='gridLayout'>
            mdi-view-grid
          </v-icon>
          <v-icon v-else>
            mdi-view-list
          </v-icon>
        </v-btn>
      </template>
      <span v-if='gridLayout'>
        Kafelki
      </span>
      <span v-else>
        Lista
      </span>
    </v-tooltip>
    <v-tooltip bottom>
      <template #activator='{ on, attrs }'>
        <v-btn
          class='text-right'
          icon
          type='button'
          v-bind='attrs'
          @click='deleteList'
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
</template>
<script>
import paths from '../../config/paths';
import events from '../../config/events';

export default {
    name: 'ShoppingListHeader',
    props: {
        hideBought: Boolean,
        id: {
            type: Number | String,
            required: true
        },
        filteredShops: Boolean,
        gridLayout: Boolean
    },
    computed: {
        editPath: function () {
            return paths.shoppingList(this.id);
        }
    },
    methods: {
        deleteList: function () {
            this.$emit(events.delete);
        },
        switchVisibility: function () {
            this.$emit(events.switchVisibility);
        },
        openShopDialog: function () {
            this.$emit(events.openShopDialog);
        },
        openShopPricesDialog: function () {
            this.$emit(events.openShopPricesDialog);
        },
        switchLayout: function () {
            this.$emit(events.switchLayout);
        }
    }
};
</script>
<style scoped>
@media only screen and (max-width: 500px) {
    button {
        width: 24px !important;
    }
}
</style>