<template>
  <div>
    <div>Employee of <strong>{{ officeName }}</strong></div>
    <div style="margin-top: 25px">
      <div style="display: none" v-show="!employees.length">No Employee Yet</div>
      <table class="table" v-show="employees.length">
        <thead>
          <tr>
            <th></th>
            <th>Name</th>
            <th>Email</th>
            <th>Action</th>
          </tr>
        </thead>
        <tbody>
          <tr v-for="employee of employees">
            <td></td>
            <td>{{ employee.name }}</td>
            <td>{{ employee.email }}</td>
            <td></td>
          </tr>
        </tbody>
      </table>
    </div>
  </div>
</template>

<script>
import bus from '../bus';

export default {
  props: ['defaultOfficeId', 'defaultOfficeName'],
  data() {
    return {
      officeId: this.defaultOfficeId,
      officeName: this.defaultOfficeName,
      employees: [],
    }
  },
  created() {
    bus.$on('employee-list-button:clicked', ({ officeId, officeName }) => {
      this.officeId = officeId;
      this.officeName = officeName;

      this.fetchEmployees();
    });
  },
  mounted() {
    this.fetchEmployees();
  },
  methods: {
    fetchEmployees() {
      axios.get(`/api/v1/employees?office_id=${this.officeId}`)
        .then((res) => {
          this.employees = res.data;
        });
    }
  },
}
</script>
