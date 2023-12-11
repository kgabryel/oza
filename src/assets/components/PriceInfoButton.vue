<template>
  <span
    data-toggle='modal'
    @click='show'
  >
    {{ roundedPrice }} zł / {{ shortcut }}
  </span>
</template>
<script>
import axios from 'axios';
import {bus} from '../app';
import routing from '../config/routing';
import events from '../config/events';

export default {
    name: 'PriceInfoButton',
    props: {
        price: {
            type: Number | String,
            required: true
        },
        id: {
            type: Number | String,
            required: true
        },
        shortcut: {
            type: String,
            required: true
        }
    },
    computed: {
        roundedPrice: function () {
            return parseFloat(this.price).toFixed(2);
        }
    },
    methods: {
        show: function () {
            axios.get(routing.unit(this.id)).then(response =>
                bus.$emit(events.priceInfoShow, {
                    id: this.id,
                    basePrice: this.price,
                    unit: response.data
                })
            );
        }
    }
};
</script>
<style scoped>
span {
    color: #303f9f;
    cursor: pointer;
    font-weight: bold;
}

.dark-background span {
    color: #2196f3;
}
</style>