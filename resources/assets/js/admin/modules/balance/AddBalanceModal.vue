<template>
  <base-modal :id="modalId">
    <span slot="modal-header">Top-up Balance For Employee</span>
    <div slot="modal-body">
      <form @submit.prevent="topUp">
        <div class="form-group">
          <label for="totalActiveEmployee">
            Total Active Employee
          </label>
          <p><strong>{{ activeEmployeeCount }} person</strong></p>
        </div>
        <div class="form-group" :class="{ 'has-error': errors.amount_per_employee }">
          <label for="amount-per-employee">Amount / Employee / Week</label>
          <div class="input-group">
            <span class="input-group-addon">Rp.</span>
            <input id="amount-per-employee" type="text" v-model="amountPerEmployee" class="form-control" placeholder="" autofocus="autofocus">
          </div>
          <span class="help-block" v-show="errors.amount_per_employee">
            {{ errors.amount_per_employee }}
          </span>
        </div>
        <div class="form-group">
          The company has paid as much as <strong>{{ paid | rupiah }}</strong>?
        </div>
        <button type="submit" style="display:none">Top Up</button>
      </form>
    </div>
    <div slot="modal-footer">
      <button type="button" class="btn btn-default" data-dismiss="modal">Cancel</button>
      <button @click="topUp" type="button" class="btn btn-primary">Yes, Top Up</button>
    </div>
  </base-modal>
</template>

<script>
import bus from '../bus';

export default {
  props: ['companyId'],
  data() {
    return {
      modalId: 'addBalanceModal',
      amountPerEmployee: 125000,
      activeEmployeeCount: 0,
      processing: false,
      errors: {},
    };
  },
  computed: {
    paid() {
      return this.amountPerEmployee * this.activeEmployeeCount;
    },
  },
  created() {
    bus.$on('add-balance-modal:show', () => {
      $(`#${this.modalId}`).modal('show');
    });
  },
  mounted() {
    axios.get(`/api/v1/employees-count?company_id=${this.companyId}&active=true`)
      .then(({ data }) => {
        this.activeEmployeeCount = data.count;
      });
  },
  methods: {
    topUp() {
      const payload = { company_id: this.companyId, amount_per_employee: this.amountPerEmployee };
      axios.post('/api/v1/balances', payload)
        .then(() => {
          $(`#${this.modalId}`).modal('hide');
        })
        .catch((err) => {
          alert('Error occured')

          throw err;
        });
    }
  },
};
</script>
