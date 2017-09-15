<template>
  <button
    v-show="!alreadyPlacedOrder"
    @click="addToCart"
    :disabled="adding"
    type="button"
    class="btn btn-block btn--primary"
  >
    <i v-if="adding" class="fa fa-circle-o-notch icon-spin"></i>
    {{ isInCart ? 'Tambah' : 'Ingin Ini' }}
  </button>
</template>

<script>
import { mapGetters } from 'vuex';

export default {
  data() {
    return { adding: false };
  },
  props: ['meal', 'date'],
  computed: {
    ...mapGetters(['alreadyPlacedOrder']),
    isInCart() {
      return this.$store.getters.allCartItemIds.indexOf(this.meal.id) !== -1;
    },
  },
  methods: {
    addToCart() {
      const item = this.meal.name;
      this.adding = true;

      axios.post('/api/v1/cart', {
        qty: 1,
        menuId: this.meal.id,
        date: this.date,
      }).then(({ data }) => {
        this.adding = false;
        // Currently endpoint response is an array
        // It might be a single object in the future
        // If so, update this code
        const meal = data.find(m => {
          console.log(m.id, this.meal.id)
          return m.id === this.meal.id;
        });

        this.$store.commit('@cart/ADD_CART_ITEM', {
          date: this.date,
          meal,
        })
      }).catch(err => {
        this.adding = false;

        throw err;
      });;
    }
  },
}
</script>

<style lang="scss" scoped>
@keyframes spin-around {
  from { transform: rotate(0deg); }
  to { transform: rotate(359deg); }
}

.icon-spin {
  animation: spin-around 800ms ease-in-out infinite;
}
</style>
