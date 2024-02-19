<template>
  <v-dialog
    v-model='showed'
    width='500'
  >
    <v-card>
      <v-card-title>
        <v-text-field
          v-model='count'
          hide-details='auto'
          label='Ilość'
          type='number'
        />
        <v-btn
          icon
          type='button'
          @click='showed = false'
        >
          <v-icon>
            mdi-close
          </v-icon>
        </v-btn>
      </v-card-title>
      <v-card-text>
        <v-simple-table>
          <template #default>
            <thead>
            <tr>
              <th>
                Nazwa
              </th>
              <th>
                Skrót
              </th>
              <th>
                Przelicznik
              </th>
              <th>
                Kwota
              </th>
            </tr>
            </thead>
            <tbody>
            <tr
              v-for='unit in units'
              :key='unit.id'
            >
              <td>
                {{ unit.name }}
              </td>
              <td>
                {{ unit.shortcut }}
              </td>
              <td>
                  <span v-if='unit.id !== id'>
                    1 {{ mainShortcut }} - {{ unit.converter }} {{ unit.shortcut }}
                  </span>
              </td>
              <td>
                {{ unit.price }} zł
              </td>
            </tr>
            </tbody>
          </template>
        </v-simple-table>
      </v-card-text>
    </v-card>
  </v-dialog>
</template>
<script>
import {bus} from '../app';
import events from '../config/events';

export default {
    name: 'PriceInfo',
    data: () => ({
        count: 1,
        id: 0,
        basePrice: 0,
        mainShortcut: '',
        units: [],
        showed: false
    }),
    watch: {
        count: function (value) {
            return this.units.forEach((element, key) => this.units[key].price = (this.basePrice / element.converter * value).toFixed(2));
        }
    },
    created: function () {
        bus.$on(events.priceInfoShow, data => {
            this.showed = true;
            this.fillData(data);
        });
    },
    methods: {
        fillData: function (data) {
            this.count = 1;
            this.id = data.id;
            if (data.id === data.unit.id) {
                this.basePrice = parseFloat(data.basePrice).toFixed(2);
            } else {
                const unit = data.unit.subUnits.subUnits.find(value => value.id === data.id);
                this.basePrice = data.basePrice * unit.converter;
            }
            this.units = [];
            this.units.push({
                id: data.id,
                name: data.unit.name,
                shortcut: data.unit.shortcut,
                converter: 1,
                price: parseFloat(this.basePrice).toFixed(2)
            });
            this.mainShortcut = data.unit.shortcut;
            data.unit.subUnits.forEach(unit => this.units.push({
                id: unit.id,
                name: unit.name,
                shortcut: unit.shortcut,
                converter: unit.converter,
                price: (this.basePrice / unit.converter).toFixed(2)
            }));
        }
    }
};
</script>
<style scoped>
</style>