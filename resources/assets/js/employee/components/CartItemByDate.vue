<template>
  <div class="cart-items-by-date">
    <div class="cart-items-by-date__header">
      {{ date | date }} <span class="pull-right">{{ count }} Item</span>
    </div>
    <div class="cart-items-by-date__items">
      <cart-item v-for="meal in items" :key="meal.id" :meal="meal"></cart-item>
    </div>
  </div>
</template>

<script>
import CartItem from './CartItem';

export default {
  props: ['date', 'items'],
  computed: {
    count() {
      if (this.items) {
        return this.items.reduce((total, item) => total + item.qty, 0);
      }

      return 0;
    },
  },
  components: {
    CartItem,
  },
}
</script>

<style lang="scss" scoped>
  .cart-items-by-date {
    background: #fff;
    padding: 15px 20px;
    border-radius: 4px;
    box-shadow: 0px 3px 6px rgba(0, 0, 0, 0.1);
    margin-bottom: 20px;
  }

  .cart-items-by-date__header {
    padding: 10px 0;
    font-weight: bold;
  }
</style>
