<template>
  <div class="col-xs-12 col-sm-4">
    <div class="meal-card">
      <a
        class="meal-card-cover"
        :href="`/meals/${meal.id}?date=${date}`"
        :style="{ color: 'red', 'background-image': `url(${meal.image_url})` }"
      >
        <div class="meal-card-cover__overlay">
          <div class="meal-card-title">
            {{ meal.name }}
          </div>
        </div>
      </a>
      <div class="meal-card__detail">
        <div class="meal-card__point">
          {{ meal.point }} Poin
        </div>
        <div class="meal-card-info clearfix">
          <div class="meal-card-info__vendor">
            <div class="meal-card-info__tag" v-if="meal.categories.length" :title="meal.categories.join(', ')">
              <i class="fa fa-cutlery"></i> {{ meal.categories.join(', ') | limit(15) }}
            </div>
            <span class="meal-card-info__vendor-name" :title="meal.vendor_name">
              <i class="fa fa-user"></i> {{ meal.vendor_name | limit(15) }}
            </span>
          </div>
          <p v-if="!alreadyPlacedOrder && soldOut" style="color: #f44336; padding: 6px 12px" class="pull-right">Habis</p>
          <button v-if="!alreadyPlacedOrder && !soldOut" @click="addToCart" :disabled="adding" class="btn pull-right meal-card__btn" :class="[isInCart ? 'btn-default' :'btn--primary']">
            <i v-if="adding" class="fa fa-circle-o-notch icon-spin"></i>
            <span v-else>{{ isInCart ? 'Tambah' : 'Ingin Ini' }}</span>
          </button>
        </div>
      </div>
    </div>
  </div>
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
    soldOut() {
      return !this.meal.nextweek_remaining_qty;
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
        const meal = data.find(m => m.id === this.meal.id);

        this.$store.commit('@cart/ADD_CART_ITEM', {
          date: this.date,
          meal,
        });
      }).catch(err => {
        this.adding = false;

        throw err;
      });;
    }
  }
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

.meal-card {
  overflow: hidden;
  border-radius: 4px;
  box-shadow: 0 3px 5px rgba(0, 0, 0, 0.1);
  margin-bottom: 32px;
}

.meal-card__btn {
  min-width: 77px;
}

.meal-card-cover {
  position: relative;
  height: 180px;
  overflow: hidden;
  display: block;
  background-size: cover;
  background-repeat: no-repeat;
  background-position: center center;
}

.meal-card-cover__overlay {
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

.meal-card-cover__img {
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
  color: #4caf50;
  font-weight: bold;
}

.meal-card-info {
  margin-top: 5px;
  position: relative;
  height: 44px;
}

.meal-card-info__vendor {
  float: left;
  font-size: 11px;
  font-weight: bold;
  position: absolute;
  bottom: 7px;
}

.meal-card-info__vendor-name {
  cursor: help;
}

.meal-card-info__tag {
  cursor: help;
}
</style>
