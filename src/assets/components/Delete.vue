<template>
  <div>
    <v-tooltip bottom>
      <template #activator='{ on, attrs }'>
        <v-btn
          icon
          type='submit'
          v-bind='attrs'
          @click='showModal'
          v-on='on'
        >
          <v-icon>
            mdi-delete
          </v-icon>
        </v-btn>
      </template>
      <span>
        Usuń
      </span>
    </v-tooltip>
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
    name: 'Delete',
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