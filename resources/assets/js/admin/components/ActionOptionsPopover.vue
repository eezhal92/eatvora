<template>
  <div class="action-options-popover">
    <a v-click-outside="hide" @click="toggleExpand" aria-haspopup="true" v-bind:aria-expanded="expanded ? 'true' : 'false'">
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
    <div class="popover-content popover-content--right" v-show="expanded">
      <div class="popover-content__menu">
        <slot></slot>
      </div>
    </div>
  </div>
</template>

<script>
export default {
  data() {
    return { expanded: false };
  },
  methods: {
    hide() {
      this.expanded = false;
    },
    toggleExpand() {
      this.expanded = !this.expanded;
    }
  }
};
</script>

<style>
  .action-options-popover {
    position: relative;
    display: inline-block;
    box-sizing: border-box;
  }

  .popover-content {
    position: absolute;
    box-sizing: inherit;
    display: flex;
    left: 0;
    width: 344px;
    z-index: 1;
    top: 100%;
    padding: 0;
  }

  .popover-content--right {
    right: 0;
    left: auto;
    margin-right: -8px;
    justify-content: flex-end;
  }

  .popover-content__menu {
    border-radius: 3px;
    box-shadow: 0 0 0 1px rgba(99, 114, 130, 0.16), 0 8px 16px rgba(27, 39, 51, 0.08);
    width: 100px;
    background: #fff;
    z-index: 5;

  }
</style>
