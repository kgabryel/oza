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
</style>