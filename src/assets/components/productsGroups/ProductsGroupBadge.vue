<template>
  <v-chip
    class='ml-1'
    color='error'
    label
    small
    text-color='white'
    @click='show'
  >
    {{ count }}
  </v-chip>
</template>
<script>
import {bus} from '../../app';
import axios from 'axios';
import routing from '../../config/routing';
import events from '../../config/events';

export default {
    name: 'ProductsGroupBadge',
    props: {
        id: {
            type: Number | String,
            required: true
        },
        count: {
            type: Number,
            required: true
        }
    },
    methods: {
        show: function () {
            axios.get(routing.productsGroup(this.id)).then(response => bus.$emit(events.productsGroupInfoShow, response.data));
        }
    }
};
</script>
<style scoped>
</style>