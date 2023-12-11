<template>
  <v-autocomplete
    v-model='unit'
    :disabled='options.length === 0'
    :error='invalid'
    :error-messages='errorMessages'
    :items='options'
    :label='label'
    :name='name'
    hide-details='auto'
    item-text='name'
    item-value='value'
  />
</template>
<script>
import events from '../../config/events';
import {bus} from '../../app';
import axios from 'axios';
import routing from '../../config/routing';

export default {
    name: 'ProductUnit',
    props: {
        label: {
            type: String,
            required: true
        },
        name: {
            type: String,
            required: true
        },
        value: {
            type: String,
            default: ''
        },
        invalid: {
            type: Boolean,
            default: false
        },
        errors: {
            type: String,
            default: ''
        }
    },
    data: () => ({
        options: [],
        unit: null
    }),
    computed: {
        errorMessages: function () {
            return this.errors === '' ? [] : JSON.parse(this.errors);
        }
    },
    mounted() {
        if (this.value === '') {
            this.unit = null;
        } else {
            this.unit = parseInt(this.value);
        }
        if (this.unit === 0) {
            this.unit = null;
        }
        bus.$on(events.productsMainUnitChange, unit => {
            this.options = [];
            if (unit !== null && unit !== 0) {
                axios.get(routing.unit(unit)).then(response => {
                    this.options.push({
                        value: response.data.id,
                        name: `${response.data.name} (${response.data.shortcut})`
                    });
                    response.data.subUnits.forEach(unit => this.options.push({
                        value: unit.id,
                        name: `${unit.name} (${unit.shortcut})`
                    }));
                });
            }
        });
    }
};
</script>
<style scoped>
</style>