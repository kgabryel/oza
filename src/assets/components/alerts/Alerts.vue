<template>
  <div>
    <div @click='showed = true'>
      <notification-bell
        v-if='alerts.length > 0'
        :animated='true'
        :count='alerts.length'
        icon-color='white'
      />
    </div>
    <v-dialog
      v-model='showed'
      width='500'
    >
      <v-card>
        <v-card-title>
          <p class='mb-0'>
            Aktywne powiadomienia
          </p>
          <v-spacer />
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
              <tbody>
              <tr
                v-for='alert in alerts'
                :key='alert.id'
              >
                <td>
                  <alert
                    :description='alert.description'
                    :name='alert.name'
                    :type='alert.type'
                  />
                </td>
              </tr>
              </tbody>
            </template>
          </v-simple-table>
        </v-card-text>
      </v-card>
    </v-dialog>
  </div>
</template>
<script>
import axios from 'axios';
import {bus} from '../../app';
import routing from '../../config/routing';
import events from '../../config/events';
import NotificationBell from 'vue-notification-bell';
import Alert from './Alert';

export default {
    name: 'Alerts',
    components: {NotificationBell, Alert},
    data: () => ({
        alerts: [],
        showed: false
    }),
    mounted() {
        bus.$on(events.alertsChanged, () => this.loadAlert());
        this.loadAlert();
    },
    methods: {
        loadAlert: function () {
            axios.get(routing.alerts).then(response => this.alerts = response.data);
        }
    }
};
</script>
<style scoped>
.notificationBell {
    cursor: pointer;
}
</style>