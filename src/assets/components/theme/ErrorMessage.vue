<template>
  <div />
</template>
<script>
import {bus} from '../../app';
import events from '../../config/events';
import Vue from 'vue';
import {TYPE} from 'vue-toastification';

export default {
    name: 'ErrorMessage',
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
        bus.$on(events.showError, data => data.forEach(message => Vue.$toast(message, {
            timeout: this.timeout,
            type: TYPE.ERROR
        })));
        if (this.text !== '') {
            let messages = JSON.parse(this.text);
            messages.forEach(message => Vue.$toast(message, {
                timeout: this.timeout,
                type: TYPE.ERROR
            }));
        }
    }
};
</script>
<style scoped>
</style>