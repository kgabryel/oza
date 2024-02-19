<template>
  <v-menu
    v-model='menu'
    :close-on-content-click='false'
    :nudge-right='40'
    min-width='auto'
    offset-y
    transition='scale-transition'
  >
    <template #activator='{ on, attrs }'>
      <v-text-field
        v-model='date'
        :error='invalid'
        :error-messages='errorMessages'
        :label='label'
        :name='name'
        clearable
        hide-details='auto'
        readonly
        v-bind='attrs'
        v-on='on'
      />
    </template>
    <v-date-picker
      v-model='date'
      :first-day-of-week='1'
      locale='pl-pl'
      @input='menu = false'
    />
  </v-menu>
</template>
<script>
export default {
    name: 'InputDate',
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
        date: null,
        menu: false
    }),
    computed: {
        errorMessages: function () {
            return this.errors === '' ? [] : JSON.parse(this.errors);
        }
    },
    mounted() {
        this.date = this.value;
    }
};
</script>
<style scoped>
</style>