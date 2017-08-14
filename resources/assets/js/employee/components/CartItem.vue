<template>
  <div class="cart-items-by-date__item">
    <div>
      <div class="row cart-items-by-date__item-row">
        <div class="col-xs-8">
          <div class="menu-detail">
            <img src="http://lorempixel.com/60/60" alt="meal" class="pull-left" />
            <div class="menu-detail__info">
              <p style="margin-bottom: 4px">{{ meal.name }}
                <button class="cart-item__btn-remove">
                  Batalkan
                </button>
              </p>
              <small>{{ meal.vendorName }}</small>
            </div>
          </div>
        </div>
        <div class="col-xs-2">
          <div>Qty <input class="cart-item-qty" @input="updateQuantity" type="number" min="1" max="5" :value="meal.qty" /></div>
        </div>
        <div class="col-xs-2">
          <div class="menu-price">{{ meal.qty * meal.price | rupiah }}</div>
        </div>
      </div>
    </div>
  </div>
</template>

<script>
import debounce from 'lodash/debounce';

export default {
  props: ['meal'],
  methods: {
    updateQuantity: debounce(() => {
      console.log('updating...')
    }, 600),
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
