<template>
  <div class="cart">
    <p v-if="!cartItemsCount">
      Ooops! Keranjang Anda Masih Kosong
    </p>
    <cart-item-by-date
      v-for="group in groupedItems"
      :items="group.items"
      :date="group.date"
      :key="group.date"
    ></cart-item-by-date>
  </div>
</template>

<script>
import { mapGetters } from 'vuex';
import CartItemByDate from './CartItemByDate';

export default {
  computed: {
    ...mapGetters({
      groupedItems: 'groupedCartItems',
      cartItemsCount:  'cartItemsCount',
    }),
  },
  components: {
    'cart-item-by-date': CartItemByDate,
  },
}
</script>
