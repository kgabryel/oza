<template>
  <div class='mt-4'>
    <vue-editor
      v-model='content'
      :class="{'invalid': errorMessages.length > 0 }"
      :placeholder='label'
    />
    <div
      v-if=' errorMessages.length > 0'
      class='v-messages theme--light error--text mt-2 mb-1'
      role='alert'
    >
      <div class='v-messages__wrapper'>
        <div
          v-for='message in errorMessages'
          :key='message'
          class='v-messages__message'
        >
          {{ message }}
        </div>
      </div>
    </div>
    <div class='hidden'>
      <v-textarea
        :name='name'
        :value='content'
      />
    </div>
  </div>
</template>
<script>
import {VueEditor} from 'vue2-editor';

export default {
    name: 'Wysiwyg',
    components: {VueEditor},
    props: {
        label: {
            type: String,
            required: true
        },
        name: {
            type: String,
            required: true
        },
        value: {
            type: String,
            default: ''
        },
        errors: {
            type: String,
            default: ''
        }
    },
    data: () => ({
        content: ''
    }),
    computed: {
        errorMessages: function () {
            return this.errors === '' ? [] : JSON.parse(this.errors);
        }
    },
    mounted() {
        this.content = this.value;
    }
};
</script>
<style scoped>
.invalid, .invalid:focus {
    border: 1px solid #d50000;
}

.theme--dark *::v-deep .ql-editor.ql-blank::before {
    color: white;
}

.theme--dark *::v-deep .ql-snow .ql-fill, .ql-snow .ql-stroke.ql-fill {
    fill: white;
}

.theme--dark *::v-deep .quillWrapper .ql-snow .ql-stroke {
    stroke: white;
}

.theme--dark *::v-deep .ql-snow .ql-picker {
    color: white;
}

.theme--dark *::v-deep .ql-snow .ql-picker.ql-expanded .ql-picker-label {
    color: white;
}

.theme--dark *::v-deep .ql-picker-options {
    background-color: #1e1e1e;
}
</style>