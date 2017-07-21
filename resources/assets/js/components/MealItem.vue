<template>
  <div class="col-xs-12 col-sm-4 meal-item">
    <div class="meal-card">
      <a :href="'/meals/' + date + '/' + meal.id" class="meal-card__image">
        <img class="img-responsive" src="http://lorempixel.com/375/240" alt="">
      </a>
      <div class="meal-card__container">
        <div class="meal-card__detail">
          <h3 class="meal-card__meal-name">{{ meal.name }}</h3>
          <div class="meal-card__meal-price">{{ meal.price | rupiah }}</div>
          <div class="meal-card__meal-vendor">Oleh Someone</div>
          <div class="meal-card__meal-category">
            <i class="glyphicon glyphicon-tag"></i> Diet
          </div>
        </div>
        <button @click="addToCart" :disabled="adding" class="btn btn-default meal-card__button">
          Ingin Ini
        </button>
        <br>
        <small v-show="isInCart">Sudah Ada di Keranjang</small>
      </div>
    </div>
  </div>
</template>

<script>
export default {
  data() {
    return { adding: false };
  },
  props: ['meal', 'date'],
  computed: {
    isInCart() {
      return this.$store.getters.allCartItemIds.indexOf(this.meal.id) !== -1;
    },
  },
  methods: {
    addToCart() {
      const item = this.meal.name;

      axios.post('/api/v1/cart', {
        qty: 1,
        menuId: this.meal.id,
        date: this.date,
      }).then(({ data }) => {
        // Currently endpoint response is an array
        // It might be a single object in the future
        // If so, update this code
        const meal = data.find(m => m.id === this.meal.id);

        this.$store.commit('@cart/ADD_CART_ITEM', {
          date: this.date,
          meal,
        });
      });
    }
  }
}
</script>

<style scoped>
  .meal-item {
    position: relative;
  }

  .meal-card {
    display: inline-block;
    margin-bottom: 20px;
    width: 100%;
    border-radius: 4px;
    box-shadow: 0 2px 4px rgba(0, 0, 0, 0.05);
    background: #fff;
  }

  .meal-card__image {
    width: 375px;
    height: 220px;
    min-width: 375px;
    min-height: 220px;
    background: #ddd;
  }

  .meal-card__image img {
    width: 100%;
  }

  .meal-card__container {
    margin: 10px;
    position: relative;
  }

  .meal-card__detail {
    margin-bottom: 10px;
  }

  .meal-card__meal-name {
    font-size: 16px;
    font-weight: bold;
  }

  .meal-card__meal-price {
    font-weight: bold;
  }

  .meal-card__meal-vendor, .meal-card__meal-category {
    font-size: 12px;
  }

  .meal-card__button {
    border-radius: 3px;
    background: #FD8421;
    color: #fff;
    font-weight: bold;
    border: none;
  }
</style>
