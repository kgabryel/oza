<template>
  <div>
    <v-btn
      v-if='positions.length > 0'
      :class="{ top: top === 'true' }"
      class='dialog-btn ma-3'
      color='secondary'
      dark
      fab
      type='button'
      @click='showedDialog = true'
    >
      <v-icon dark>
        mdi-clipboard-alert
      </v-icon>
    </v-btn>
    <v-dialog
      v-model='showedDialog'
      width='500'
    >
      <v-card>
        <v-card-title>
          <p>
            Oczekujące produkty
          </p>
          <v-spacer />
          <v-btn
            icon
            type='button'
            @click='showedDialog = false'
          >
            <v-icon>
              mdi-close
            </v-icon>
          </v-btn>
        </v-card-title>
        <v-card-text class='positions'>
          <div
            v-for='position in positions'
            :key='position.id'
          >
            <p>
              <span>
                {{ position.name }}
              </span>
              <v-btn
                class='pa-0'
                color='error'
                elevation='2'
                tile
                type='button'
                @click='showDeleteDialog(position.id)'
              >
                <v-icon dark>
                  mdi-delete
                </v-icon>
              </v-btn>
            </p>
          </div>
        </v-card-text>
      </v-card>
    </v-dialog>
    <delete-dialog
      :visible='deleteDialog'
      @delete='confirmDelete'
      @close-dialog='deleteDialog = false'
    />
  </div>
</template>
<script>
import {bus} from '../../app';
import events from '../../config/events';
import axios from 'axios';
import routing from '../../config/routing';
import DeleteDialog from '../DeleteDialog';

export default {
    name: 'QuickListClipboard',
    components: {DeleteDialog},
    props: {
        value: {
            type: String,
            default: ''
        },
        top: {
            type: String,
            default: 'false'
        }
    },
    data: () => ({
        showedButton: false,
        showedDialog: false,
        positions: [],
        deleteCallback: null,
        deleteDialog: false
    }),
    mounted() {
        bus.$on(events.quickListDeleted, () => axios.get(routing.quickListClipboard).then(response => {
            if (Object.keys(response.data).length > 0) {
                this.positions = response.data;
                this.showedButton = true;
            }
        }));
        const positions = JSON.parse(this.value);
        if (Object.keys(positions).length > 0) {
            this.positions = positions;
            this.showedButton = true;
        }
    },
    methods: {
        showDeleteDialog(id) {
            this.deleteCallback = () => this.deletePosition(id);
            this.deleteDialog = true;
        },
        deletePosition: function (id) {
            axios.delete(routing.quickListClipboardPosition(id)).then(() => {
                this.positions = this.positions.filter(position => position.id !== id);
                bus.$emit(events.showSuccess, ['Pozycja została usunięta.']);
                if (this.positions.length === 0) {
                    this.showedDialog = false;
                }
            });
        },
        confirmDelete: function () {
            this.deleteDialog = false;
            this.deleteCallback();
        }
    }
};
</script>
<style scoped>
.dialog-btn {
    bottom: 0;
    position: fixed;
    right: 0;
}

.dialog-btn.top {
    bottom: 70px;
}

.positions button {
    min-width: 36px !important;
    width: 36px !important;
}

.positions > div:last-child > p {
    margin-bottom: 0;
}

.positions span {
    display: inline-block;
    width: calc(100% - 40px);
}
</style>