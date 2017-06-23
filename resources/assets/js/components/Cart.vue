<template>
  <div class="cart">
    <cart-item-by-date 
      v-for="group in groupedItems"
      :items="group.items"
      :date="group.date"
      :key="group.date"
    ></cart-item-by-date>
  </div>
</template>

<script>
import CartItemByDate from './CartItemByDate';

export default {
  data() {
    return {
      groupedItems: [],
    };
  },
  mounted() {
    axios.get('/api/v1/cart')
      .then(({ data }) => {
        const groupedItems = Object.keys(data).map(date => ({
          date,
          items: data[date],
        }))

        this.groupedItems = groupedItems;
      });
  },
  components: {
    'cart-item-by-date': CartItemByDate,
  },
}
</script>
