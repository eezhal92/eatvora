<template>
  <button @click="showNoteModal" class="btn btn-sm btn-default">Show</button>
</template>

<script>
import bus from '../bus';

export default {
  props: ['payment'],
  methods: {
    showNoteModal() {
      bus.$emit('payment-note-modal:show', this.payment);
    },
  },
};
</script>
