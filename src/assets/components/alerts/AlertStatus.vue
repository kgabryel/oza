<template>
  <v-switch
    v-model='value'
    class='mt-0'
    hide-details='auto'
    @change='changeStatus'
  />
</template>
<script>
import axios from 'axios';
import {bus} from '../../app';
import routing from '../../config/routing';
import InputSwitch from '../inputs/InputSwitch';
import events from '../../config/events';

export default {
    name: 'AlertStatus',
    components: {InputSwitch},
    props: {
        checked: {
            type: Boolean,
            required: true
        },
        id: {
            type: Number | String,
            required: true
        }
    },
    data: () => ({
        value: false
    }),
    mounted() {
        this.value = this.checked;
    },
    methods: {
        changeStatus: function () {
            axios.post(routing.alertStatusChange(this.id, !this.value)).then(() => bus.$emit(events.alertsChanged));
        }
    }
};
</script>
<style scoped>
</style>