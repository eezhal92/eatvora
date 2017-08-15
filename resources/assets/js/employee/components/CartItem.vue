<template>
  <div class="cart-items-by-date__item">
    <div>
      <div class="row cart-items-by-date__item-row">
        <div class="col-xs-8">
          <div class="menu-detail">
            <img src="http://lorempixel.com/60/60" alt="meal" class="pull-left" />
            <div class="menu-detail__info">
              <p style="margin-bottom: 4px">{{ meal.name }}
                <button @click="removeItem" class="cart-item__btn-remove">
                  Batalkan
                </button>
              </p>
              <small>{{ meal.vendorName }}</small>
            </div>
          </div>
        </div>
        <div class="col-xs-2">
          <div>
            Qty
            <input class="cart-item-qty" onkeydown="return false;" @input="updateItemQuantity" type="number" min="1" max="5" v-model="qty" />
          </div>
        </div>
        <div class="col-xs-2">
          <div class="menu-price">{{ meal.qty * meal.final_price | rupiah }}</div>
        </div>
      </div>
    </div>
  </div>
</template>

<script>
import { mapActions } from 'vuex';
import debounce from 'lodash/debounce';

export default {
  props: ['meal', 'cartId'],
  data() {
    return { qty: this.meal.qty };
  },
  methods: {
    ...mapActions(['updateCartItemQty', 'removeCartItem']),
    updateItemQuantity: debounce(function() {
      const payload = { qty: Number(this.qty), menu_id: this.meal.id, date: this.meal.date };
      axios.patch(`/api/v1/cart`, payload)
        .then(() => {
          this.updateCartItemQty(payload);
        })
        .catch(() => {
          alert('Sorry, cannot update cart item quantity. Please try again later.');
        });

    }, 600),
    removeItem() {
      const confirmed = confirm(`Are you sure want to remove ${this.meal.name}?`);

      if (!confirmed) {
        return;
      }

      const payload = { _method: 'DELETE', menu_id: this.meal.id, date: this.meal.date };
      axios.post('/api/v1/cart', payload)
        .then(() => {
          this.removeCartItem(payload);
        })
        .catch(err => {
          alert('Sorry, cannot remove cart item. Please try again later.');

          throw err;
        });
    },
  },
};
</script>

<style lang="scss" scoped>
  .cart-items-by-date__item {
    margin: 10px 0;
  }

  .cart-items-by-date__item-row {
    display: flex;
    align-items: flex-start;
  }

  .cart-item__btn-remove {
    background: none;
    border: none;
    color: #f44336;
    font-size: 10px;

    &:focus {
      outline: none;
    }
  }

  .cart-item-qty {
    border: 1px solid #eeeeee;
    padding: 4px;
    border-radius: 4px;
  }

  .menu-detail {
    overflow: hidden;

    &__info {
      display: inline-block;
      margin-left: 10px;
    }
  }

  .menu-price {
    text-align: right;
    padding-top: 6px;
  }
</style>
