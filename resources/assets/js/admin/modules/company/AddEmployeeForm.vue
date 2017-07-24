<template>
  <div class="add-employee-form" style="margin-bottom: 30px; border-bottom: 1px solid #ccc">
    <form v-show="show" @submit.prevent="store" class="form-horizontal" style="padding-bottom: 10px">
      <div class="form-group" :class="{ 'has-error': errors.name }">
        <label for="new_employee_name" class="col-sm-2 control-label">Name</label>
        <div class="col-sm-10">
          <input type="text" class="form-control" id="new_employee_name" v-model="name" placeholder="Jane Doe">
          <span class="help-block" v-show="errors.name">
            {{ errors.name }}
          </span>
        </div>
      </div>
      <div class="form-group" :class="{ 'has-error': errors.email }">
        <label for="new_employee_email" class="col-sm-2 control-label">Email</label>
        <div class="col-sm-10">
          <input type="email" class="form-control" id="new_employee_email" v-model="email" placeholder="jane.doe@example.com">
          <span class="help-block" v-show="errors.email">
            {{ errors.email }}
          </span>
        </div>
      </div>
      <div class="form-group">
        <div class="col-sm-offset-2 col-sm-10">
          <button type="submit" class="btn btn-default">Add Employee</button>
        </div>
      </div>
    </form>
  </div>
</template>

<script>
import bus from '../bus';

export default {
  data() {
    return {
      name: '',
      email: '',
      errors: {},
    };
  },

  props: ['show', 'officeId'],

  methods: {
    store() {
      this.resetErrors();

      axios.post('/api/v1/employees', {
        name: this.name,
        email: this.email,
        office_id: this.officeId,
      }).then(({ data }) => {
        bus.$emit('add-employee-form:added', data);
        this.resetForm();
      }).catch(({ response }) => {
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
  }
}
</script>
