<template>
  <div class="cart">
    <p v-if="!cartItemsCount && !alreadyPlacedOrder">
      Ooops! Keranjang Anda Masih Kosong
      <br />
      <br />
      <a href="/meals" class="btn btn--primary btn-sm">
        <i class="glyphicon glyphicon-eye-open"></i> Lihat Menu
      </a>
    </p>
    <p v-if="alreadyPlacedOrder">
      Terima Kasih, Anda telah memesan makan siang untuk minggu depan :)
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
      cartItemsCount: 'cartItemsCount',
      alreadyPlacedOrder: 'alreadyPlacedOrder',
    }),
  },
  components: {
    'cart-item-by-date': CartItemByDate,
  },
}
</script>
