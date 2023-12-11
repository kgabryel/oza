<template>
  <v-dialog
    v-model='showed'
    width='500'
  >
    <v-card>
      <v-card-title>
        <a
          :href='supplyHref(id)'
          class='text-decoration-none'
        >
          {{ name }}
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
        <p>
          {{ amount }} {{ unit }}
        </p>
        <a
          v-for='group in groups'
          :key='group.id'
          :href='supplyGroupHref(group.id)'
          class='text-decoration-none d-inline-block ma-1'
        >
          <v-chip
            class='c-pointer text--white'
            color='blue'
            label
          >
            {{ group.name }}
          </v-chip>
        </a>
        <p
          v-if="description !== '' "
          class='description'
        >
          {{ description }}
        </p>
      </v-card-text>
    </v-card>
  </v-dialog>
</template>
<script>
import {bus} from '../../app';
import events from '../../config/events';
import paths from '../../config/paths';

export default {
    name: 'SupplyInfo',
    data: () => ({
        name: '',
        groups: [],
        amount: 0,
        unit: '',
        id: 0,
        showed: false,
        description: ''
    }),
    mounted() {
        bus.$on(events.supplyInfoShow, data => {
            this.showed = true;
            this.fillData(data);
        });
    },
    methods: {
        fillData: function (data) {
            this.id = data.id;
            this.name = data.group.name;
            this.groups = data.groups;
            this.amount = data.amount;
            this.unit = data.unit.shortcut;
            this.description = data.description ?? '';
        },
        supplyHref: id => paths.supply(id),
        supplyGroupHref: id => paths.supplyGroup(id)
    }
};
</script>
<style scoped>
.description {
    white-space: pre-line;
}
</style>