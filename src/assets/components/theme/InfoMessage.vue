<template>
  <div />
</template>
<script>
import {bus} from '../../app';
import events from '../../config/events';
import Vue from 'vue';
import {TYPE} from 'vue-toastification';

export default {
    name: 'InfoMessage',
    props: {
        text: {
            type: String,
            default: ''
        }
    },
    data: () => ({
        timeout: 5000
    }),
    mounted() {
        bus.$on(events.showSuccess, data => data.forEach(message => Vue.$toast(message, {
            timeout: this.timeout,
            type: TYPE.SUCCESS
        })));
        if (this.text !== '') {
            let messages = JSON.parse(this.text);
            messages.forEach(message => Vue.$toast(message, {
                timeout: this.timeout,
                type: TYPE.SUCCESS
            }));
        }
    }
};
</script>
<style scoped>
</style>