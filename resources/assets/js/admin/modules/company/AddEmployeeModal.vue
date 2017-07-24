<template>
  <base-modal :id="modalId">
    <span slot="modal-header">Add New Employee</span>
    <div slot="modal-body">
      <form @submit.prevent="storeEmployee">
        <div class="form-group" :class="{ 'has-error': errors.name }">
          <label for="add_employee_name">Name</label>
          <input id="add_employee_name" type="text" v-model="name" class="form-control" placeholder="">
          <span class="help-block" v-show="errors.name">
            {{ errors.name }}
          </span>
        </div>
        <div class="form-group" :class="{ 'has-error': errors.email }">
          <label for="add_employee_email">Email</label>
          <input id="add_employee_email" type="text" v-model="email" class="form-control" placeholder="">
          <span class="help-block" v-show="errors.email">
            {{ errors.email }}
          </span>
        </div>
        <button type="submit" style="display:none">Update</button>
      </form>
    </div>
    <div slot="modal-footer">
      <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
      <button @click="storeEmployee" type="button" class="btn btn-primary">Add</button>
    </div>
  </base-modal>
</template>

<script>
import bus from '../bus';

export default {
  data() {
    return {
      modalId: 'addEmployeeModal',
      name: '',
      email: '',
      officeId: null,
      errors: {},
    };
  },
  created() {
    bus.$on('employee-add', (officeId) => {
      $('#addEmployeeModal').modal('show');

      this.officeId = officeId;
    });
  },
  methods: {
    storeEmployee() {
      this.resetErrors();

      const payload = {
        name: this.name,
        email: this.email,
        office_id: this.officeId,
      };

      axios.post('/api/v1/employees', payload)
        .then(({ data }) => {
          bus.$emit('add-employee-modal:added', data);

          $('#addEmployeeModal').modal('hide');
        })
        .catch(({ response }) => {
          if (response.status === 422) {
            this.errors = this.formatErrors(response.data);
          }
        });
    },
    resetForm() {
      this.name = '';
      this.email = '';
    },
    resetErrors() {
      this.errors = {};
    },
    formatErrors(errors) {
      return Object.keys(errors).reduce((acc, errorKey) => {
        const message = errors[errorKey][0];

        return Object.assign(acc, { [errorKey]: message });
      }, {});
    }
  },
};
</script>

