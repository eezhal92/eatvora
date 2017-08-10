<template>
  <div class="cart-items-by-date">
    <div class="cart-items-by-date__header">
      {{ date | date }} <span class="pull-right">{{ count }} Item</span>
    </div>
    <div class="cart-items-by-date__items">
      <div v-for="item in items" class="cart-items-by-date__item">
        <div>
          <div class="row cart-items-by-date__item-row">
            <div class="col-xs-8">
              <div class="menu-detail">
                <img src="http://lorempixel.com/60/60" alt="meal" class="pull-left" />
                <div class="menu-detail__info">
                  <p style="margin-bottom: 4px">{{ item.name }}</p>
                  <small>{{ item.vendorName }}</small>
                </div>
              </div>
            </div>
            <div class="col-xs-2">
              <div>Qty <input class="cart-item-qty" type="number" min="1" max="3" :value="item.qty" /></div>
            </div>
            <div class="col-xs-2">
              <div class="menu-price">{{ item.qty * item.price | rupiah }}</div>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
</template>

<script>
export default {
  props: ['date', 'items'],
  computed: {
    count() {
      if (this.items) {
        return this.items.reduce((total, item) => total + item.qty, 0);
      }

      return 0;
    },
  }
}
</script>

<style lang="scss" scoped>
  .cart-items-by-date {
    background: #fff;
    padding: 15px 20px;
    border-radius: 4px;
    box-shadow: 0px 2px 4px rgba(0, 0, 0, 0.05);
    margin-bottom: 20px;
  }

  .cart-items-by-date__item {
    margin: 10px 0;
  }

  .cart-items-by-date__item-row {
    display: flex;
    align-items: flex-start;
  }

  .cart-items-by-date__header {
    border-bottom: 1px solid #e6e6e6;
    padding: 10px 0;
    font-weight: bold;
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
