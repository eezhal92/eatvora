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
            <th>Active?</th>
            <th>Action</th>
          </tr>
        </thead>
        <tbody>
          <tr v-for="employee of employees">
            <td></td>
            <td>{{ employee.name }}</td>
            <td>{{ employee.email }}</td>
            <td>Yes</td>
            <td>
              <a href="#">
                <svg width="32" height="32" viewBox="0 0 32 32" xmlns="http://www.w3.org/2000/svg" aria-hidden="false" style="margin-top: -5px;">
                  <title>table-overflow</title>
                  <g fill="none" fill-rule="evenodd">
                    <g fill="#637282">
                      <circle cx="10.5" cy="16.5" r="1.5"></circle>
                      <circle cx="15.5" cy="16.5" r="1.5"></circle>
                      <circle cx="20.5" cy="16.5" r="1.5"></circle>
                    </g>
                  </g>
                </svg>
              </a>
            </td>
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
