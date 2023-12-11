<template>
  <v-autocomplete
    :error='invalid'
    :error-messages='errorMessages'
    :items='selectOptions'
    :label='label'
    :name='name'
    :value='value'
    hide-details='auto'
    item-text='name'
    item-value='value'
    @change='changed'
  />
</template>
<script>
import events from '../../config/events';
import {bus} from '../../app';
import axios from 'axios';
import routing from '../../config/routing';
import {stringsService} from '../../services/strings.service';

export default {
    name: 'SupplyGroupSelect',
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
        items: {
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
    computed: {
        errorMessages: function () {
            return this.errors === '' ? [] : JSON.parse(this.errors);
        },
        selectOptions: function () {
            if (this.items === '') {
                return [];
            }
            const items = JSON.parse(this.items);

            let options = Object.keys(items).map(key => {
                return {
                    value: key,
                    name: items[key].label
                };
            });
            return options.sort((a, b) => stringsService.compareStrings(a.name, b.name));
        }
    },
    methods: {
        changed: function (event) {
            axios.get(routing.productsGroup(event)).then(response => bus.$emit(events.productsMainUnitChange, response.data.unit.id));
        }
    }
};
</script>
<style scoped>
</style>