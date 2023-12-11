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
    />
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

export default {
    name: 'InputMultiSelect',
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
        }
    },
    data: () => ({
        selected: [],
        searchInput: ''
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
    },
    methods: {
        changed: function () {
            this.searchInput = '';
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
</style>