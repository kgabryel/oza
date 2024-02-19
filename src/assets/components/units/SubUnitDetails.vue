<template>
  <v-row :class="{'d-none': mainUnit}">
    <slot />
  </v-row>
</template>
<script>
import {bus} from '../../app';
import events from '../../config/events';

export default {
    name: 'SubUnitDetails',
    data: () => ({
        mainUnit: false
    }),
    mounted() {
        bus.$on(events.unitFormIsMainValueChanged, data => this.mainUnit = data);
    }
};
</script>
<style scoped>
</style>