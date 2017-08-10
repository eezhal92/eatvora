<template>
  <div class="employee-import-form">
    <h5>Bulk Import Employee</h5>
    <form class="form-inline" @submit.prevent="importEmployee">
      <div class="form-group">
        <input type="file" ref="file">
        <span class="help-block">File extension should be .csv</span>
      </div>
      <button type="submit" class="pull-right btn btn-sm btn-default">Import</button>
    </form>
  </div>
</template>

<script>
export default {
  props: ['officeId'],

  methods: {
    getFile() {
      return this.$refs.file.files[0];
    },
    isFileChosen() {
      return !!this.getFile();
    },
    makePayload() {
      const payload = new FormData();
      const file = this.$refs.file;

      payload.append('office_id', this.officeId);
      payload.append('file', this.getFile());

      return payload;
    },
    importEmployee() {
      if (!this.isFileChosen()) {
        return;
      }

      const options = { headers: { 'Content-Type': 'multipart/form-data' } };

      axios.post(`/api/v1/employees/bulk`, this.makePayload(), options)
        .then(() => {
          alert('Success');
        })
        .catch(() => {
          alert('Failed');
        });
    }
  }
};
</script>

<style lang="scss">
  .employee-import-form {
    margin-top: 20px;
  }
</style>
