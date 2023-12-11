<template>
  <span @click='show'>
    {{ name }}
  </span>
</template>
<script>
import {bus} from '../../app';
import axios from 'axios';
import routing from '../../config/routing';
import events from '../../config/events';

export default {
    name: 'ProductsGroupInfoButton',
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
            axios.get(routing.productsGroup(this.id)).then(response => bus.$emit(events.productsGroupInfoShow, response.data));
        }
    }
};
</script>
<style scoped>
span {
    color: #303f9f;
    cursor: pointer;
    font-weight: bold;
}

.dark-background span {
    color: #2196f3;
}
</style>