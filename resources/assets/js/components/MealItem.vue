<template>
  <div class="meal-item">
    Name of meal: {{ meal.name }}
    <button @click="addToCart">Add to Cart</button>
  </div>
</template>

<script>
import bus from './bus';

export default {
  props: ['meal'],
  methods: {
    addToCart() {
      const item = this.meal.name;
      console.log(item);
      bus.$emit('cart:adding-item', item);
    }
  }
}
</script>

<style scoped>
  .meal-item {
    display: inline-block;
    width: 300px;
  }
</style>