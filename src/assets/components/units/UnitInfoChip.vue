<template>
  <v-chip
    class='c-pointer text--white ma-1'
    color='blue'
    label
    @click='show'
  >
    {{ name }}
  </v-chip>
</template>
<script>
import {bus} from '../../app';
import axios from 'axios';
import routing from '../../config/routing';
import events from '../../config/events';

export default {
    name: 'UnitInfoChip',
    props: {
        id: {
            type: Number | String,
            required: true
        },
        name: {
            type: String,
            required: true
        }
    },
    methods: {
        show: function () {
            axios.get(routing.unit(this.id)).then(response => bus.$emit(events.unitInfoShow, response.data));
        }
    }
};
</script>
<style scoped>
</style>