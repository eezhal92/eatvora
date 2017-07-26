<template>
  <base-modal :id="modalId">
    <span slot="modal-header">Delete Vendor</span>
    <div slot="modal-body">Are you sure want to delete <b>{{ vendorName }}</b>?</div>
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
      modalId: 'deleteVendorModal',
      vendorName: '',
    };
  },
  created() {
    bus.$on('vendor-delete', (vendor) => {
      $(`#${this.modalId}`).modal('show');
      this.vendorName = vendor.name;
    });
  }
};
</script>

