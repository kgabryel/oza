<template>
  <div>
    <v-dialog
      v-model='showed'
      persistent
      width='500'
    >
      <v-card>
        <v-toolbar
          color='error'
          dark
          dense
        >
          Jesteś pewien?
        </v-toolbar>
        <v-card-text class='text-center pb-0'>
          <v-icon color='error'>
            mdi-close
          </v-icon>
        </v-card-text>
        <v-divider />
        <v-card-actions>
          <v-spacer />
          <v-btn
            color='error'
            outlined
            type='button'
            @click='remove'
          >
            Tak
          </v-btn>
          <v-btn
            color='error'
            type='button'
            @click='showed = false'
          >
            Nie
          </v-btn>
        </v-card-actions>
      </v-card>
    </v-dialog>
  </div>
</template>
<script>
import events from '../config/events';

export default {
    name: 'DeleteDialog',
    props: {
        visible: Boolean
    },
    data: () => ({
        showed: false
    }),
    watch: {
        visible: function () {
            this.showed = this.visible;
        },
        showed: function () {
            if (!this.showed) {
                this.$emit(events.closeDialog);
            }
        }
    },
    methods: {
        remove: function () {
            this.$emit(events.delete);
        }
    }
};
</script>
<style scoped>
.v-dialog .v-icon {
    animation-duration: 3s;
    animation-name: spin;
    font-size: 100px;
}

@keyframes spin {
    from {
        transform: rotate(0deg);
    }
    to {
        transform: rotate(360deg);
    }
}
</style>