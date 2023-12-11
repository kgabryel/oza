<template>
  <v-toolbar
    color='primary'
    dark
    dense
  >
    <div class='d-flex align-center w-100'>
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
      <p class='mb-0 w-100'>
        {{ name }}
      </p>
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
  </v-toolbar>
</template>
<script>
import events from '../../config/events';
import paths from '../../config/paths';

export default {
    name: 'QuickListHeader',
    props: {
        hideBought: Boolean,
        id: {
            type: Number | String,
            required: true
        },
        name: {
            type: String,
            required: true
        }
    },
    computed: {
        editPath: function () {
            return paths.quickList(this.id);
        }
    },
    methods: {
        deleteList: function () {
            this.$emit(events.delete);
        },
        switchVisibility: function () {
            this.$emit(events.switchVisibility);
        }
    }
};
</script>
<style scoped>
</style>