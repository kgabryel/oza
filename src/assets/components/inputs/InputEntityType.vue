<template>
  <v-autocomplete
    :error='invalid'
    :error-messages='errorMessages'
    :items='selectOptions'
    :label='label'
    :name='name'
    :value='inputValue'
    clearable
    hide-details='auto'
    item-text='name'
    item-value='value'
  >
    <template
      v-if='scanEnabled'
      #prepend
    >
      <v-fade-transition leave-absolute>
        <v-btn
          class='scanner-button'
          icon
          type='button'
        >
          <v-icon>
            mdi-barcode-scan
          </v-icon>
          <ImageBarcodeReader
            @decode='onDecode'
            @error='onError'
            @result='onDecode'
          />
        </v-btn>
      </v-fade-transition>
    </template>
  </v-autocomplete>
</template>
<script>
import {stringsService} from '../../services/strings.service';
import axios from 'axios';
import routing from '../../config/routing';
import {bus} from '../../app';
import events from '../../config/events';
import {ImageBarcodeReader} from 'vue-barcode-reader';

export default {
    name: 'InputEntityType',
    components: {ImageBarcodeReader},
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
        },
        scannerEnabled: {
            type: String,
            default: 'false'
        }
    },
    data: () => ({
        inputValue: null,
        scanEnabled: false
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
            if (Object.keys(items).length === 0) {
                return [];
            }
            let options = Object.keys(items).map(key => {
                return {
                    value: parseInt(items[key]['value']),
                    name: items[key]['label']
                };
            });
            return options.sort((a, b) => stringsService.compareStrings(a.name, b.name));
        }
    },
    mounted() {
        this.scanEnabled = this.scannerEnabled === 'true';
        this.inputValue = parseInt(this.value.replace('"', ''));
        if (isNaN(this.inputValue)) {
            this.inputValue = null;
        }
    },
    methods: {
        onDecode: function (result) {
            axios.get(routing.findProductByBarCode(result)).then(response => {
                if (response === undefined) {
                    bus.$emit(events.showError, ['Nie znaleziono pasującego produktu.']);
                } else {
                    this.inputValue = response.data.id;
                }
            });
        },
        onError: function () {
            bus.$emit(events.showError, ['Nie udało się odczytać kodu.']);
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
    font-size: 100px;
    min-height: 100%;
    min-width: 100%;
    opacity: 0;
    outline: none;
    position: absolute;
    right: 0;
    text-align: right;
    top: 0;
}
</style>