<script>
export default  {
  props: ['payload', 'eventName'],
  template: `
    <button @click="emitEvent" class="popover-content__menu-item">
      <slot></slot>
    </button>
  `,
  methods: {
    emitEvent() {
      this.$emit(this.eventName, this.eventName, this.payload);
    }
  }
};
</script>

<style>
  .popover-content__menu-item {
    width: 100%;
    box-sizing: border-box;
    padding: 4px 16px;
    font-size: 14px;
    line-height: 24px;
    text-align: left;
    cursor: pointer;
    outline: none;
    background-color: transparent;
    border: none;
    text-decoration: none;
    display: block;
  }

  .popover-content__menu-item:hover {
    background: #f6f9fc;
  }
</style>
