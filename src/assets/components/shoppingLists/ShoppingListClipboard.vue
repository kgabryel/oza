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
          <template v-for='(position, key) in positions'>
            <v-divider
              v-if='key !== 0'
              :key='key'
              class='mt-1'
            />
            <div
              :key='key'
              class='d-flex align-center justify-space-between'
            >
              <div>
                <p class='mb-0'>
                  <products-group-info-button
                    v-if='position.isGroup === 1'
                    :id='position.productId'
                    :name='position.productName'
                  />
                  <product-info-button
                    v-else
                    :id='position.productId'
                    :name='position.productName'
                  />
                </p>
                <p class='mb-0'>
                  {{ position.amount.toFixed(position.amount % 1 === 0 ? 0 : 2) }}
                  <unit-info-button
                    :id='position.unitId'
                    :name='position.unitShortcut'
                  />
                </p>
                <p v-if="position.description !== null && position.description !== ''">
                  {{ position.description }}
                </p>
              </div>
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
            </div>
          </template>
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
import ProductsGroupInfoButton from '../productsGroups/ProductsGroupInfoButton';
import ProductInfoButton from '../products/ProductInfoButton';
import UnitInfoButton from '../units/UnitInfoButton';
import DeleteDialog from '../DeleteDialog';

export default {
    name: 'ShoppingListClipboard',
    components: {DeleteDialog, ProductsGroupInfoButton, ProductInfoButton, UnitInfoButton},
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
        bus.$on(events.productsListDeleted, () => axios.get(routing.shoppingListClipboard).then(response => {
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
            this.deleteDialog = false;
            this.deleteDialog = true;
        },
        deletePosition: function (id) {
            axios.delete(routing.shoppingListClipboardPosition(id)).then(() => {
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

.positions > div > div:first-of-type {
    width: calc(100% - 40px);
}
</style>