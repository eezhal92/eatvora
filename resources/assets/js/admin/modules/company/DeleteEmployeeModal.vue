<template>
  <base-modal :id="modalId">
    <span slot="modal-header">Delete Employee</span>
    <div slot="modal-body">Are you sure want to delete <b>{{ employeeName }}</b>?</div>
    <div slot="modal-footer">
      <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
      <button type="button" class="btn btn-primary">Yes</button>
    </div>
  </base-modal>
</template>

<script>
import bus from '../bus';

export default {
  data() {
    return {
      modalId: 'deleteEmployeeModal',
      employeeName: '',
    };
  },
  created() {
    bus.$on('employee-delete', (employee) => {
      $(`#${this.modalId}`).modal('show');
      this.employeeName = employee.name;
    });
  }
};
</script>

