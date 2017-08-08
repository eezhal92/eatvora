<template>
  <base-modal :id="modalId">
    <span slot="modal-header">Delete Menu</span>
    <div slot="modal-body">Are you sure want to delete <b>{{ menuName }}</b>?</div>
    <div slot="modal-footer">
      <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
      <button type="button" @click="deleteMenu" class="btn btn-primary">Yes</button>
    </div>
  </base-modal>
</template>

<script>
import bus from '../bus';

export default {
  data() {
    return {
      modalId: 'deleteMenuModal',
      menuName: '',
      menuId: null,
    };
  },
  created() {
    bus.$on('menu-delete', (menu) => {
      $(`#${this.modalId}`).modal('show');
      this.menuId = menu.id;
      this.menuName = menu.name;
    });
  },
  methods: {
    hideModal() {
      $(`#${this.modalId}`).modal('hide');
    },
    deleteMenu() {
      axios.delete(`/api/v1/menus/${this.menuId}`)
        .then(() => {
          this.hideModal();

          bus.$emit('delete-menu-modal:menu-deleted');
        })
        .catch(({ response }) => {
          if (response.data && response.data.message) {
            alert(response.data.message);

            this.hideModal();
          }
        })
    },
  }
};
</script>

