<template>
  <div class="cart-summary">
    <div style="padding: 20px; border-radius: 4px; box-shadow: 0 2px 4px rgba(0, 0, 0, 0.05); background: #fff">
      <div style="font-size: 18px; font-weight: bold">Total</div>
      <div style="font-size: 22px; font-weight: bold">{{ cartTotal | rupiah }}</div>
    </div>
    <br>
    <button class="btn btn--primary btn-block">
      Checkout
    </button>
  </div>
</template>

<script>
import { mapGetters } from 'vuex';

export default {
  computed: {
    ...mapGetters(['cartTotal'])
  },
};
</script>

<style scoped>

</style>
