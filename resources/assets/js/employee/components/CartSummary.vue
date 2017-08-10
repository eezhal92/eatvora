<template>
  <div class="cart-summary">
    <div class="cart-summary__total">
      <div class="cart-summary__total-heading">Total</div>
      <div class="cart-summary__total-amount">{{ cartTotal | rupiah }}</div>
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

<style lang="scss" scoped>
  .cart-summary {
    &__total {
      padding: 20px;
      border-radius: 4px;
      box-shadow: 0 2px 4px rgba(0, 0, 0, 0.05);
      background: #fff;
    }

    &__total-heading {
      font-size: 18px;
      font-weight: bold;
    }

    &__total-amount {
      font-size: 22px;
      font-weight: bold;
    }
  }
</style>
