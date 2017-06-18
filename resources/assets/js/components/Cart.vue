<template>
  <div class="cart">
    <h3>In cart</h3>
    <p v-for="item in items">{{ item }}</p>
  </div>
</template>

<script>
import bus from './bus';

export default {
  data() {
    return {
      items: [],
    };
  },
  created() {
    bus.$on('cart:adding-item', (meal) => {
      console.log('receiving', meal);
      this.items = this.items.concat(meal);
    });
  }
}
</script>
