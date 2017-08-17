<template>
  <div class="cart-summary">
    <div v-if="!alreadyPlacedOrder">
      <div class="cart-summary__total">
        <div class="cart-summary__total-heading">Total</div>
        <div class="cart-summary__total-amount">{{ cartTotal | rupiah }}</div>
      </div>
      <br>
      <button @click="checkout" :disabled="processing" class="btn btn--primary btn-block">
        Checkout <i v-show="processing" class="fa fa-circle-o-notch icon-spin"></i>
      </button>
    </div>
  </div>
</template>

<script>
import bus from './bus';
import { mapGetters, mapActions } from 'vuex';

export default {
  data() {
    return { processing: false };
  },
  computed: {
    ...mapGetters(['cartTotal', 'alreadyPlacedOrder'])
  },
  methods: {
    ...mapActions(['setAlreadyPlacedOrder']),
    checkout() {
      bus.$emit('alert:hide');
      this.processing = true;

      axios.post('/api/v1/orders')
        .then(() => {
          this.processing = false;
          this.setAlreadyPlacedOrder(true);
        })
        .catch(({ response }) => {
          this.processing = false;
          if (response.data && response.data.message) {
            bus.$emit('alert:show', response.data.message);
          }
        });
    }
  }
};
</script>

<style lang="scss" scoped>
  @keyframes spin-around {
    from { transform: rotate(0deg); }
    to { transform: rotate(359deg); }
  }

  .icon-spin {
    animation: spin-around 800ms ease-in-out infinite;
  }

  .cart-summary {
    &__total {
      padding: 20px;
      border-radius: 4px;
      box-shadow: 0 3px 6px rgba(0, 0, 0, 0.1);
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
