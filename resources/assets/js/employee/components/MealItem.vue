<template>
  <div class="col-xs-12 col-sm-4">
    <div class="meal-card">
      <a :href="'/meals/' + date + '/' + meal.id" class="meal-card-cover">
        <div class="meal-card-cover_overlay">
          <div class="meal-card-title">
            {{ meal.name }}
          </div>
        </div>
        <img src="https://upload.wikimedia.org/wikipedia/commons/thumb/6/6d/Good_Food_Display_-_NCI_Visuals_Online.jpg/1200px-Good_Food_Display_-_NCI_Visuals_Online.jpg" class="meal-card-cover_img" alt="">
      </a>
      <div class="meal-card__detail">
        <div class="meal-card__point">
          16 Poin
        </div>
        <div class="meal-card-info">
          <div class="meal-card-info__vendor">
            <span class="meal-card-info__vendor-name" :title="meal.vendor.name">
              Oleh {{ meal.vendor.name | limit(9) }}
            </span>
          </div>
          <button @click="addToCart" :disabled="adding" class="btn pull-right btn-default" :class="{'meal-card__button': !isInCart}">
            {{ isInCart ? 'Tambah' : 'Ingin Ini' }}
          </button>
        </div>
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
.meal-card__button {
  border-radius: 3px;
  background: #FD8421;
  color: #fff;
  font-weight: bold;
  border: 1px solid #FD8421;
}

.meal-card {
  overflow: hidden;
  border-radius: 4px;
  box-shadow: 0 3px 5px rgba(0, 0, 0, 0.1);
  margin-bottom: 32px;
}

.meal-card-cover {
  position: relative;
  height: 180px;
  overflow: hidden;
  display: block;
}

.meal-card-cover_overlay {
  position: absolute;
  top: 0;
  right: 0;
  left: 0;
  bottom: 0;
  background: rgba(0, 0, 0, .4);
}

.meal-card-title {
  position: absolute;
  bottom: 0;
  right: 0;
  left: 0;
  color: white;
  padding: 1em;
}

.meal-card-cover_img {
  height: inherit;
  width: 100%;
  object-fit: cover;
}

.meal-card__detail {
  padding: 14px;
  background: #fff;
}

.meal-card__point {
  font-size: 12px;
  color: #7E8998;
  font-weight: bold;
}

.meal-card-info {
  overflow: hidden;
  margin-top: 10px;
  position: relative;
}

.meal-card-info__vendor {
  float: left;
  font-size: 12px;
  font-weight: bold;
  position: absolute;
  bottom: 7px;
}

.meal-card-info__vendor-name {
  cursor: pointer;
}
</style>
