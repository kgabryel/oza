<template>
  <div
    :class="{'invalid': errorMessages.length > 0 }"
    class='multi-select'
  >
    <v-autocomplete
      v-model='selected'
      :error='invalid'
      :error-messages='errorMessages'
      :items='selectOptions'
      :label='label'
      :search-input.sync='searchInput'
      chips
      clearable
      deletable-chips
      hide-details='auto'
      item-text='name'
      item-value='value'
      multiple
      small-chips
      @change='changed'
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
    <select
      :name='name'
      class='hidden'
      multiple
    >
      <option
        v-for='item in selected'
        :key='item'
        :value='item'
        selected
      />
    </select>
  </div>
</template>
<script>
import {stringsService} from '../../services/strings.service';
import {bus} from '../../app';
import events from '../../config/events';
import axios from 'axios';
import routing from '../../config/routing';
import {ImageBarcodeReader} from 'vue-barcode-reader';

export default {
    name: 'InputMultiEntityType',
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
        selected: [],
        searchInput: '',
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
                    value: key,
                    name: items[key]
                };
            });
            return options.sort((a, b) => stringsService.compareStrings(a.name, b.name));
        }
    },
    mounted() {
        this.selected = JSON.parse(this.value);
        this.scanEnabled = this.scannerEnabled === 'true';
    },
    methods: {
        changed: function () {
            this.searchInput = '';
        },
        onDecode: function (result) {
            axios.get(routing.findProductByBarCode(result)).then(response => {
                if (response === undefined) {
                    bus.$emit(events.showError, ['Nie znaleziono pasującego produktu.']);
                } else {
                    let id = response.data.id.toString();
                    if (!this.selected.includes(id)) {
                        this.selected.push(id);
                    }
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
.multi-select {
    height: 44px !important;
}

.invalid {
    margin-bottom: 16px;
}

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