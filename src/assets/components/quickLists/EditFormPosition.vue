<template>
  <div class='d-flex align-stretch'>
    <div :class="{'d-none' : !dragable}" class='py-1'>
      <v-btn
        class='pa-0 mx-1 c-move h-100'
        elevation='2'
        tile
        type='button'
      >
        <v-icon dark>
          mdi-drag
        </v-icon>
      </v-btn>
    </div>
    <div class='w-100'>
      <div class='d-flex align-center'>
        <v-switch
          v-model='checked'
          class='big-actions'
          hide-details='auto'
        />
        <div class='checkbox-value'>
          <input
            :checked='checked'
            :name='`quick_list_form[positions][${index}][checked]`'
            type='checkbox'
          >
        </div>
        <v-text-field
          v-model='inputValue'
          :counter='maxLength'
          :counter-value='v => v.length'
          :error-messages='value.position.errors'
          :maxlength='maxLength'
          :name='`quick_list_form[positions][${index}][position]`'
          :value='value.position.value'
          hide-details='auto'
          label='Pozycja'
          type='text'
        />
        <v-btn
          class='pa-0 ml-1 big-actions'
          color='error'
          elevation='2'
          tile
          type='button'
          @click='deletePosition'
        >
          <v-icon dark>
            mdi-delete
          </v-icon>
        </v-btn>
      </div>
      <div class='small-actions pt-1 justify-space-between align-center'>
        <v-switch
          v-model='checked'
          hide-details='auto'
        />
        <v-btn
          class='pa-0 ml-1'
          color='error'
          elevation='2'
          tile
          type='button'
          @click='deletePosition'
        >
          <v-icon dark>
            mdi-delete
          </v-icon>
        </v-btn>
      </div>
    </div>
  </div>
</template>
<script>
export default {
    name: 'EditFormPosition',
    props: {
        dragable: {
            type: Boolean,
            required: true
        },
        index: {
            type: Number,
            default: 0
        },
        value: {
            type: Object,
            required: true
        }
    },
    data: () => ({
        checked: false,
        inputValue: '',
        maxLength: 255
    }),
    watch: {
        value: function () {
            this.checked = this.value.checked.value;
            this.inputValue = this.value.position.value;
        }
    },
    mounted: function () {
        this.checked = this.value.checked.value;
        this.inputValue = this.value.position.value;
    },
    methods: {
        deletePosition: function () {
            this.$el.parentNode.removeChild(this.$el);
        }
    }
};
</script>
<style scoped>
button {
    min-width: 36px !important;
    width: 36px !important;
}

*::v-deep .v-input--switch {
    margin-top: 0;
    padding-top: 0;
}

.small-actions {
    display: none;
}

@media screen and (max-width: 649px) {
    .big-actions {
        display: none;
    }

    .small-actions {
        display: flex;
    }
}
</style>