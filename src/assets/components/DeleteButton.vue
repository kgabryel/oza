<template>
  <div>
    <v-btn
      class='mt-3'
      color='error'
      elevation='2'
      large
      type='submit'
      @click='showModal'
    >
      <v-icon
        dark
        left
      >
        mdi-delete
      </v-icon>
      Usuń
    </v-btn>
    <delete-dialog
      :visible='showed'
      @delete='remove'
      @close-dialog='showed = false'
    />
  </div>
</template>
<script>
import DeleteDialog from './DeleteDialog';

export default {
    name: 'DeleteButton',
    components: {DeleteDialog},
    data: () => ({
        form: null,
        showed: false
    }),
    methods: {
        showModal: function (event) {
            this.showed = false;
            this.form = this.$el.parentElement;
            this.showed = true;
            event.preventDefault();
        },
        remove: function () {
            if (this.form !== null) {
                this.form.submit();
                this.showed = false;
            }
        }
    }
};
</script>
<style scoped>
</style>