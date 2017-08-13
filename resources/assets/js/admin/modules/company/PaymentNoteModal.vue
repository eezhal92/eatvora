<template>
  <base-modal :id="modalId">
    <span slot="modal-header">{{ payment.created_at }} Payment Note</span>
    <div slot="modal-body">
      <div class="form-group">
        <label for="note">Note</label>
        <textarea class="form-control" v-model="payment.note" cols="30" rows="3"></textarea>
      </div>
    </div>
    <div slot="modal-footer">
      <button type="button" class="btn btn-default" data-dismiss="modal">Cancel</button>
      <button @click="updatePaymentNote" type="button" class="btn btn-primary">Update</button>
    </div>
  </base-modal>
</template>

<script>
import bus from '../bus';

export default {
  data() {
    return {
      modalId: 'paymentNoteModal',
      payment: {},
    };
  },
  created() {
    bus.$on('payment-note-modal:show', (payment) => {
      this.payment = payment;
      $(`#${this.modalId}`).modal('show');
    });
  },
  methods: {
    updatePaymentNote() {
      axios.patch(`/api/v1/payments/${this.payment.id}`, { note: this.payment.note })
        .then(() => {
          window.location.reload();
        })
        .catch((err) => {
          alert('Error occured!');

          throw err;
        });
    }
  }
};
</script>
