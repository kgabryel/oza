<template>
  <div>
    <v-switch
      v-model='value'
      :error='invalid'
      :error-messages='errorMessages'
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
import {bus} from '../../app';
import events from '../../config/events';

export default {
    name: 'MainUnitSwitch',
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
    watch: {
        value: function () {
            bus.$emit(events.unitFormIsMainValueChanged, this.value);
        }
    },
    mounted() {
        this.value = this.checked === '1';
        bus.$emit(events.unitFormIsMainValueChanged, this.value);
    }
};
</script>
<style scoped>
</style>