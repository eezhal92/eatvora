<template>
  <a href="#" @click="emitOfficeId">Show Employee</a>
</template>

<script>
import bus from '../bus';

export default {
  props: ['officeId', 'officeName'],
  methods: {
    emitOfficeId() {
      const payload = {
        officeId: this.officeId,
        officeName: this.officeName,
      };
      bus.$emit('employee-list-button:clicked', payload);
    },
  }
};
</script>
