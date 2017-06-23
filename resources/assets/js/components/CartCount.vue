<template>
  <div style="display: inline; position: relative">
    <i class="glyphicon glyphicon-shopping-cart"></i>
    <div class="cart-count">
      <div class="cart-count__number">
        {{ count }}
      </div>
    </div>
  </div>
</template>

<script>
import bus from './bus';

export default {
  data() {
    return { count: 0 };
  },
  created() {
    bus.$on('cart:item-added', (qty) => {
      this.count += qty;
    });
  },
  mounted() {
    this.fetchCart();
  },
  methods: {
    fetchCart() {
      axios.get('/api/v1/cart').then(({ data }) => {
        const count = Object.keys(data).reduce((total, date) => {
            const subTotal = data[date].reduce((tot, item) => tot + item.qty, 0);

            return subTotal + total;
        }, 0)

        this.count = count;
      });
    }
  }
}
</script>

<style scoped>
  .cart-count {
    background-color: #F44336;
    color: #fff;
    height: 20px;
    width: 20px;
    position: absolute;
    top: -12px;
    left: 5px;
    border-radius: 50%;
    display: -webkit-flex;
    display: -ms-flexbox;
    display: flex;
    -webkit-animation: wiggle 0.25s 2 ease;
    animation: wiggle 0.25s 2 ease;
  }

  .cart-count__number {
    color: #fff;
    font-size: small;
    margin: auto;
  }
</style>