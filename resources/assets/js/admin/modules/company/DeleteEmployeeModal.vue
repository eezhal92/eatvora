<template>
  <base-modal :id="modalId">
    <span slot="modal-header">Delete Employee</span>
    <div slot="modal-body">Are you sure want to delete <b>{{ employeeName }}</b>?</div>
    <div slot="modal-footer">
      <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
      <button type="button" @click="deleteEmployee" class="btn btn-primary">Yes</button>
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
      employeeId: null,
    };
  },
  created() {
    bus.$on('employee-delete', (employee) => {
      $(`#${this.modalId}`).modal('show');
      this.employeeId = employee.id;
      this.employeeName = employee.name;
    });
  },
  methods: {
    hideModal() {
      $(`#${this.modalId}`).modal('hide');
    },
    deleteEmployee() {
      axios.delete(`/api/v1/employees/${this.employeeId}`)
        .then(() => {
          this.hideModal();

          bus.$emit('delete-employee-modal:deleted', this.employeeId);
        })
        .catch(({ response }) => {
          if (response.data && response.data.message) {
            this.hideModal();

            alert(response.data.message);
          }
        });
    },
  }
};
</script>

