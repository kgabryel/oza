<template>
  <v-select
    :error='invalid'
    :error-messages='errorMessages'
    :items='selectOptions'
    :label='label'
    :name='name'
    :value='inputValue'
    hide-details='auto'
    item-text='name'
    item-value='value'
  />
</template>
<script>
import {stringsService} from '../../services/strings.service';

export default {
    name: 'InputSelect',
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
    data: () => ({
        inputValue: null
    }),
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
                    value: items[key].value,
                    name: items[key].label
                };
            });
            return options.sort((a, b) => stringsService.compareStrings(a.name, b.name));
        }
    },
    mounted() {
        this.inputValue = this.value;
    }
};
</script>
<style scoped>
</style>