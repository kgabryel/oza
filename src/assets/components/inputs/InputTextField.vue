<template>
  <v-text-field
    v-if="type === 'number'"
    v-model='inputValue'
    :error='invalid'
    :error-messages='errorMessages'
    :hint='bottom'
    :label='label'
    :name='name'
    :step='step'
    :type='type'
    :value='value'
    hide-details='auto'
  />
  <v-text-field
    v-else-if="maxlength === '-1'"
    v-model='inputValue'
    :error='invalid'
    :error-messages='errorMessages'
    :hint='bottom'
    :label='label'
    :name='name'
    :type='type'
    :value='value'
    hide-details='auto'
  />
  <v-text-field
    v-else
    v-model='inputValue'
    :counter='maxlength'
    :counter-value='v => v.length'
    :error='invalid'
    :error-messages='errorMessages'
    :hint='bottom'
    :label='label'
    :maxlength='maxlength'
    :name='name'
    :type='type'
    :value='value'
    hide-details='auto'
  />
</template>
<script>
export default {
    name: 'InputTextField',
    props: {
        label: {
            type: String,
            required: true
        },
        type: {
            type: String,
            default: 'text'
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
        },
        bottom: {
            type: String,
            default: ''
        },
        maxlength: {
            type: String,
            default: '-1'
        },
        step: {
            type: String,
            default: 'any'
        }
    },
    data() {
        return {
            inputValue: this.value
        };
    },
    computed: {
        errorMessages: function () {
            return this.errors === '' ? [] : JSON.parse(this.errors);
        }
    }
};
</script>
<style scoped>
</style>