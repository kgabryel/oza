<template>
  <v-text-field
    v-if="maxlength === '-1'"
    v-model='inputValue'
    :error='invalid'
    :error-messages='errorMessages'
    :hint='bottom'
    :label='label'
    :name='name'
    :type='type'
    :value='value'
    hide-details='auto'
  >
    <template #append>
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
  </v-text-field>
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
  >
    <template #append>
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
  </v-text-field>
</template>
<script>
import {ImageBarcodeReader} from 'vue-barcode-reader';
import events from '../../config/events';
import {bus} from '../../app';

export default {
    name: 'InputBarcodeField',
    components: {ImageBarcodeReader},
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
    },
    methods: {
        onDecode: function (result) {
            this.inputValue = result;
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