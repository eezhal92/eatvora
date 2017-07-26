<template>
  <div class="vendor-row-action">
    <action-options-popover>
      <action-options-popover-menu
        :payload="vendor"
        event-name="employee-show"
        v-on:employee-show="goToShowPage"
      >
        Show
      </action-options-popover-menu>
      <action-options-popover-menu
        :payload="vendor"
        event-name="vendor-edit"
        v-on:vendor-edit="goToEditPage"
      >
        Edit
      </action-options-popover-menu>
      <hr style="margin: 0">
      <action-options-popover-menu
        :payload="vendor"
        event-name="vendor-delete"
        v-on:vendor-delete="showDeleteModal"
      >
        Delete
      </action-options-popover-menu>
    </action-options-popover>
  </div>
</template>

<script>
import bus from '../bus';

export default {
  props: ['vendor'],
  methods: {
    goToShowPage(payload) {
      window.location.href = '/ap/vendors/' + payload.id;
    },
    goToEditPage(payload) {
      window.location.href = '/ap/vendors/' + payload.id + '/edit';
    },
    showDeleteModal(payload) {
      bus.$emit('vendor-delete', payload);
    }
  }
}
</script>
