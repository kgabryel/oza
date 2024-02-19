<template>
  <div>
    <v-alert
      v-if="type !== ''"
      :type='type'
      border='left'
      class='my-1'
    >
      <span v-html='description' /> [{{ name }}]
    </v-alert>
    <v-alert
      v-else
      border='left'
      class='my-1'
    >
      <span v-html='description' /> [{{ name }}]
    </v-alert>
  </div>
</template>
<script>
export default {
    name: 'Alert',
    props: {
        type: {
            type: String,
            required: true
        },
        description: {
            type: String,
            required: true
        },
        name: {
            type: String,
            required: true
        }
    }
};
</script>
<style scoped>
</style>