<template>
  <div :id="id" v-show="show" class="alert" role="alert" :class="`alert-${type}`">
    <button type="button" class="close" @click="hide" aria-label="Close"><span aria-hidden="true">&times;</span></button>
    {{ message }}
  </div>
</template>

<script>
import bus from './bus';

export default {
  props: ['id', 'type'],
  data() {
    return { show: false, message: '' };
  },
  created() {
    bus.$on('alert:show', (message) => {
      this.message = message;
      this.show = true;
    });

    bus.$on('alert:hide', () => {
      this.message = '';
      this.show = false
    });
  },
  methods: {
    hide() {
      this.show = false;
    }
  }
}
</script>
