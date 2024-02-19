<template>
  <div>
    <p class='text-center mb-0'>
      {{ createdAt }}
    </p>
    <p
      v-if="!(note === '' || note === null)"
      class='note mb-0'
      v-html='note'
    />
    <v-progress-linear
      :color='color'
      :value='progress'
      class='mr-1 ml-1'
      height='25'
      striped
    >
      <strong v-if='checked < positions.length'>
        {{ checked }}/{{ positions.length }}
      </strong>
    </v-progress-linear>
    <div>
      <div
        v-for='position in positions'
        :key='position.id'
      >
        <div
          v-if='!position.checked || !hideBought'
          class='pa-1 d-flex align-center'
        >
          <v-btn
            v-if='position.checked'
            class='pa-0'
            color='error'
            elevation='2'
            tile
            type='button'
            @click='changeStatus(position.id, position.checked)'
          >
            <v-icon dark>
              mdi-close
            </v-icon>
          </v-btn>
          <v-btn
            v-if='!position.checked'
            class='pa-0'
            color='success'
            elevation='2'
            tile
            type='button'
            @click='changeStatus(position.id, position.checked)'
          >
            <v-icon dark>
              mdi-check
            </v-icon>
          </v-btn>
          <p
            :class="{'text-decoration-line-through': position.checked}"
            class='px-1 mb-0 w-100'
          >
            {{ position.content }}
          </p>
          <v-btn
            class='pa-0'
            color='error'
            elevation='2'
            tile
            type='button'
            @click='deletePosition(position.id)'
          >
            <v-icon dark>
              mdi-delete
            </v-icon>
          </v-btn>
        </div>
      </div>
    </div>
  </div>
</template>
<script>
import events from '../../config/events';
import list from '../../mixins/list';

export default {
    name: 'QuickListPositions',
    mixins: [list],
    props: {
        name: {
            type: String,
            default: ''
        },
        createdAt: {
            type: String,
            default: ''
        },
        note: {
            type: String,
            default: ''
        },
        hideBought: Boolean,
        positions: {
            type: Array,
            required: true
        }
    },
    methods: {
        changeStatus: function (positionId, status) {
            this.$emit(events.changeStatus, {id: positionId, status});
        },
        deletePosition: function (id) {
            this.$emit(events.delete, id);
        }
    }
};
</script>
<style scoped>
button {
    min-width: 36px !important;
    width: 36px !important;
}

.note {
    white-space: pre-line;
}

.v-progress-linear {
    width: auto;
}
</style>