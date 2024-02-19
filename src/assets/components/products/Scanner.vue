<template>
  <v-btn
    block
    class='mt-3 scanner-button'
    color='secondary'
    elevation='2'
    large
    type='button'
  >
    <v-icon
      dark
      left
    >
      mdi-barcode-scan
    </v-icon>
    Szukaj produktu
    <ImageBarcodeReader
      @decode='onDecode'
      @error='onError'
      @result='onDecode'
    />
  </v-btn>
</template>
<script>
import {bus} from '../../app';
import axios from 'axios';
import routing from '../../config/routing';
import events from '../../config/events';
import {ImageBarcodeReader} from 'vue-barcode-reader';

export default {
    name: 'Scanner',
    components: {ImageBarcodeReader},
    methods: {
        onDecode: function (result) {
            axios.get(routing.findProductByBarCode(result)).then(response => {
                if (response === undefined) {
                    bus.$emit(events.showError, ['Nie znaleziono pasującego produktu.']);
                } else {
                    this.show(response.data.id);
                }
            });
        },
        onError: function () {
            bus.$emit(events.showError, ['Nie udało się odczytać kodu.']);
        },
        show: function (id) {
            axios.get(routing.product(id)).then(response => bus.$emit(events.productInfoShow, response.data));
        }
    }
};
</script>
<style scoped>
.scanner-button input[type=file] {
    background: white;
    cursor: inherit;
    display: block;
    filter: alpha(opacity=0);
    font-size: 0;
    height: 44px;
    opacity: 0;
    outline: none;
    position: absolute;
    right: -20px;
    text-align: right;
    top: -12px;
    width: calc(100% + 40px);
}
</style>