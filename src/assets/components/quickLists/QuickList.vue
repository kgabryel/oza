<template>
  <div>
    <v-card elevation='2'>
      <quick-list-header
        :id='id'
        :hide-bought='hideBought'
        :name='name'
        @delete='showDeleteDialog(null)'
        @switch-visibility='hideBought = !hideBought'
      />
      <div class='pa-3 card'>
        <div class='positions'>
          <quick-list-positions
            :created-at='createdAt'
            :hide-bought='hideBought'
            :name='name'
            :note='note'
            :positions='positions'
            @delete='showDeleteDialog($event)'
            @change-status='changeStatus($event)'
          />
        </div>
        <v-divider class='mx-4' />
        <v-btn
          block
          class='mt-3'
          color='secondary'
          elevation='2'
          large
          type='button'
          @click='show'
        >
          <v-icon
            dark
            left
          >
            mdi-magnify
          </v-icon>
          Pokaż
        </v-btn>
      </div>
    </v-card>
    <v-dialog
      v-model='showed'
      width='500'
    >
      <v-card elevation='2'>
        <quick-list-header
          :id='id'
          :hide-bought='hideBought'
          :name='name'
          @delete='showDeleteDialog(null)'
          @switch-visibility='hideBought = !hideBought'
        />
        <div class='pa-3'>
          <div class='positions'>
            <quick-list-positions
              :created-at='createdAt'
              :hide-bought='hideBought'
              :name='name'
              :note='note'
              :positions='positions'
              @delete='showDeleteDialog($event)'
              @change-status='changeStatus($event)'
            />
          </div>
        </div>
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
import axios from 'axios';
import routing from '../../config/routing';
import {bus} from '../../app';
import events from '../../config/events';
import DeleteDialog from '../DeleteDialog';
import QuickListHeader from './QuickListHeader';
import QuickListPositions from './QuickListPositions';

export default {
    name: 'QuickList',
    components: {DeleteDialog, QuickListHeader, QuickListPositions},
    props: {
        list: {
            type: String,
            default: ''
        },
        hide: {
            type: Boolean,
            default: false
        }
    },
    data: () => ({
        name: '',
        createdAt: '',
        id: 0,
        positions: [],
        hideBought: false,
        showed: false,
        deleteCallback: null,
        deleteDialog: false,
        note: ''
    }),
    mounted() {
        this.hideBought = this.hide;
        const list = JSON.parse(this.list);
        this.id = list.id;
        this.name = list.name;
        this.createdAt = list.createdAt;
        this.positions = list.positions;
        this.note = list.note;
    },
    methods: {
        show: function () {
            this.showed = true;
        },
        changeStatus: function (data) {
            const id = data.id;
            const checked = data.status;
            axios.post(routing.quickListPositionCheck(id, checked)).then(() => {
                let position = this.positions.find(position => position.id === id);
                position.checked = !position.checked;
            });
        },
        deletePosition: function (id) {
            axios.delete(routing.quickListPosition(id)).then(() => {
                this.positions = this.positions.filter(position => position.id !== id);
                bus.$emit(events.showSuccess, ['Pozycja została usunięta.']);
            });
        },
        delete: function () {
            axios.delete(routing.quickList(this.id)).then(() => {
                bus.$emit(events.quickListDeleted);
                bus.$emit(events.showSuccess, ['Lista została usunięta.']);
                this.$el.parentElement.remove();
            });
        },
        showDeleteDialog(id) {
            id === null ? this.deleteCallback = this.delete : this.deleteCallback = () => this.deletePosition(id);
            this.deleteDialog = true;
        },
        confirmDelete: function () {
            this.deleteDialog = false;
            this.deleteCallback();
        }
    }
};
</script>
<style scoped>
.positions {
    min-height: 320px;
}

.card .positions {
    max-height: 320px;
    overflow-y: auto;
}
</style>