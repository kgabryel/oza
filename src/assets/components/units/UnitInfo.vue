<template>
  <v-dialog
    v-model='showed'
    width='500'
  >
    <v-card>
      <v-card-title>
        <a
          :href='path(id)'
          class='text-decoration-none'
        >
          {{ name }} - {{ shortcut }}
        </a>
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
      <v-card-text>
        <v-simple-table>
          <template #default>
            <thead>
            <tr>
              <th>
                Nazwa
              </th>
              <th>
                Skrót
              </th>
              <th>
                Przelicznik
              </th>
            </tr>
            </thead>
            <tbody>
            <tr
              v-for='unit in subUnits'
              :key='unit.id'
            >
              <td>
                <a
                  :href='path(unit.id)'
                  class='text-decoration-none'
                >
                  {{ unit.name }}
                </a>
              </td>
              <td>
                {{ unit.shortcut }}
              </td>
              <td>
                1 {{ shortcut }} - {{ unit.converter }} {{ unit.shortcut }}
              </td>
            </tr>
            <tr v-if='subUnits.length === 0'>
              <td colspan='3'>
                Brak jednostek podrzędnych
              </td>
            </tr>
            </tbody>
          </template>
        </v-simple-table>
      </v-card-text>
    </v-card>
  </v-dialog>
</template>
<script>
import {bus} from '../../app';
import events from '../../config/events';
import paths from '../../config/paths';

export default {
    name: 'UnitInfo',
    data: () => ({
        name: '',
        shortcut: '',
        subUnits: [],
        id: 0,
        showed: false
    }),
    mounted() {
        bus.$on(events.unitInfoShow, data => {
            this.showed = true;
            this.fillData(data);
        });
    },
    methods: {
        fillData: function (data) {
            this.id = data.id;
            this.name = data.name;
            this.shortcut = data.shortcut;
            this.subUnits = data.subUnits;
        },
        path: id => paths.unit(id)
    }
};
</script>
<style scoped>
</style>