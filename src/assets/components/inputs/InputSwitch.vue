<template>
  <div>
    <v-switch
      v-model='value'
      :error='invalid'
      :label='label'
      hide-details='auto'
    />
    <div class='checkbox-value'>
      <input
        :checked='value'
        :name='name'
        type='checkbox'
      >
    </div>
  </div>
</template>
<script>
export default {
    name: 'InputSwitch',
    props: {
        label: {
            type: String,
            default: ''
        },
        name: {
            type: String,
            default: ''
        },
        checked: {
            type: String,
            required: true
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
        value: false
    }),
    computed: {
        errorMessages: function () {
            return this.errors === '' ? [] : JSON.parse(this.errors);
        }
    },
    mounted() {
        this.value = this.checked === '1';
    }
};
</script>
<style scoped>
</style>