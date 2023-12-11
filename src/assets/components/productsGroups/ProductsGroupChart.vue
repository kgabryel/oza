<template>
  <chart
    v-if='!empty'
    :data='data'
  />
</template>
<script>
import 'chartjs-adapter-moment';
import axios from 'axios';
import routing from '../../config/routing';
import Chart from '../Chart';

export default {
    name: 'ProductsGroupChart',
    components: {Chart},
    props: {
        id: {
            type: Number | String,
            required: true
        }
    },
    data: () => ({
        data: {},
        empty: true
    }),
    mounted() {
        axios.get(routing.productsGroupChart(this.id)).then(response => {
            this.data = response;
            this.empty = response.data.shopping.length === 0;
        });
    }
};
</script>
<style scoped>
</style>