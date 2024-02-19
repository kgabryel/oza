<template>
  <v-textarea
    :error-messages='errorMessages'
    :label='label'
    :name='name'
    :rows='rows'
    :value='value'
    hide-details='auto'
  />
</template>
<script>
export default {
    name: 'InputTextarea',
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
        errors: {
            type: String,
            default: ''
        },
        rows: {
            type: Number,
            default: 5
        }
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