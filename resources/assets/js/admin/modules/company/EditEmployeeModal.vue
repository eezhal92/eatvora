<template>
  <base-modal :id="modalId">
    <span slot="modal-header">Edit Employee {{ employeeName }}</span>
    <div slot="modal-body" v-if="employee.id">
      <form @submit.prevent="updateEmployee">
        <div class="form-group" :class="{ 'has-error': errors.name }">
          <label for="edit_employee_name">Name</label>
          <input id="edit_employee_name" type="text" v-model="editedEmployee.name" class="form-control" placeholder="">
          <span class="help-block" v-show="errors.name">
            {{ errors.name }}
          </span>
        </div>
        <div class="form-group" :class="{ 'has-error': errors.email }">
          <label for="edit_employee_email">Email</label>
          <input id="edit_employee_email" type="text" v-model="editedEmployee.email" class="form-control" placeholder="">
          <span class="help-block" v-show="errors.email">
            {{ errors.email }}
          </span>
        </div>
        <button type="submit" style="display:none">Update</button>
      </form>
    </div>
    <div slot="modal-footer">
      <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
      <button @click="updateEmployee" type="button" class="btn btn-primary">Update</button>
    </div>
  </base-modal>
</template>

<script>
import bus from '../bus';

export default {
  data() {
    return {
      modalId: 'editEmployeeModal',
      employeeName: '',
      employee: {},
      errors: {},
    };
  },
  computed: {
    editedEmployee() {
      return Object.assign({}, this.employee);
    }
  },
  created() {
    bus.$on('employee-edit', (employee) => {
      $('#editEmployeeModal').modal('show');

      this.employee = employee;
      this.employeeName = employee.name;
    });
  },
  methods: {
    updateEmployee() {
      this.resetErrors();

      const employeeId = this.employee.id;
      const { name, email } = this.editedEmployee;

      const payload = { _method: 'PATCH', name, email };

      axios.post(`/api/v1/employees/${employeeId}`, payload)
        .then(({ data }) => {
          bus.$emit('edit-employee-modal:updated', data);

          $('#editEmployeeModal').modal('hide');
        })
        .catch(({ response }) => {
          if (response.status === 422) {
            this.errors = this.formatErrors(response.data);
          }
        });
    },
    resetErrors() {
      this.errors = {};
    },
    formatErrors(errors) {
      return Object.keys(errors).reduce((acc, errorKey) => {
        const message = errors[errorKey][0];

        return Object.assign(acc, { [errorKey]: message });
      }, {});
    },
  },
};
</script>

