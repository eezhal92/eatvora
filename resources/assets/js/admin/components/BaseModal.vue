<template>
  <div class="modal fade" :id="id" tabindex="-1" role="dialog" :aria-labelledby="label">
    <div class="modal-dialog" role="document">
      <div class="modal-content">
        <div class="modal-header">
          <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
          <h4 class="modal-title" id="label">
            <slot name="modal-header"></slot>
          </h4>
        </div>
        <div class="modal-body">
          <slot name="modal-body"></slot>
        </div>
        <div class="modal-footer">
          <slot name="modal-footer"></slot>
        </div>
      </div>
    </div>
  </div>
</template>

<script>

export default {
  props: ['id'],
  computed: {
    label() {
      return `${this.id}Label`;
    }
  }
}
</script>
