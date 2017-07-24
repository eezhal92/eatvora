<template>
  <div>
    <div class="clearfix">
      Employee of <strong>{{ officeName }}</strong> Office
      <button @click="toggleShowAddForm" class="btn btn-default btn-sm pull-right">
        {{ showAddForm ? 'Cancel' : 'Add New Employee' }}
      </button>
    </div>
    <div style="margin-top: 25px">
      <add-employee-form
        :show="showAddForm"
        :office-id="officeId"
      >  </add-employee-form>
      <div class="search" style="margin-bottom: 15px">
        <form @submit.prevent="fetchEmployees()">
          <input type="text" v-model="query" class="form-control" placeholder="Search employee name">
        </form>
      </div>
      <div style="display: none" v-show="!employees.length">No employee was found...</div>
      <div v-show="employees.length">
        <p v-html="recordsCountText"></p>
        <table class="table">
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
        <div class="list-pagination">
          <ul class="pager">
            <li style="margin-right: 10px">Page {{ currentPage }} / {{ pageCount }}</li>
            <li :class="{ disabled: !isPrevAvailable }"><a href="#" @click="getPrevPage()">Previous</a></li>
            <li :class="{ disabled: !isNextAvailable }"><a href="#" @click="getNextPage()">Next</a></li>
          </ul>
        </div>
      </div>
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
      currentPage: 1,
      pageCount: 1,
      totalRecords: 0,
      query: '',
      showAddForm: false,
    }
  },
  created() {
    bus.$on('employee-list-button:clicked', ({ officeId, officeName }) => {
      this.officeId = officeId;
      this.officeName = officeName;

      this.fetchEmployees();
    });

    bus.$on('add-employee-form:added', (employee) => {
      const [, ...employees] = this.employees;
      this.employees = [employee, ...employees];
    });
  },
  mounted() {
    this.fetchEmployees();
  },
  computed: {
    isPrevAvailable() {
      return this.currentPage > 1;
    },
    isNextAvailable() {
      return this.currentPage < this.pageCount;
    },
    recordsCountText() {
      return `<span>Total Record is <b>${this.totalRecords}</b><span>`;
    }
  },
  methods: {
    fetchEmployees({ page = 1 } = {}) {
      const query = this.query;

      axios.get(`/api/v1/employees?office_id=${this.officeId}&query=${query}&page=${page}`)
        .then((res) => {
          const { employees, page_count, current_page, total_records } = res.data;
          this.employees = employees;
          this.pageCount = page_count;
          this.currentPage = current_page;
          this.totalRecords = total_records;
        });
    },
    getNextPage() {
      if (this.currentPage < this.pageCount) {
        this.fetchEmployees({ page: this.currentPage + 1});
      }
    },
    getPrevPage() {
      if (this.currentPage > 1) {
        this.fetchEmployees({ page: this.currentPage - 1});
      }
    },
    toggleShowAddForm() {
      this.showAddForm = !this.showAddForm;
    }
  },
}
</script>
