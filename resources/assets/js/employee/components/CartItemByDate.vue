<template>
  <div class="cart-items-by-date">
    <div class="cart-items-by-date__header">
      {{ date | date }} <span style="float: right">{{ count }} Item</span>
    </div>
    <div class="cart-items-by-date__items">
      <div v-for="item in items" style="margin: 10px 0">
        <div>
          <div class="row" style="display: flex; align-items: flex-start;">
            <div class="col-xs-8">
              <div style="overflow: hidden">
                <img src="http://lorempixel.com/60/60" alt="meal" class="pull-left" />
                <div style="display: inline-block;margin-left: 10px;">
                  <p style="margin-bottom: 4px">{{ item.name }}</p>
                  <small>{{ item.vendorName }}</small>
                </div>
              </div>
            </div>
            <div class="col-xs-2">
              <div>Qty <input class="cart-item-qty" type="number" min="1" max="3" :value="item.qty" /></div>
            </div>
            <div class="col-xs-2">
              <div style="text-align: right; padding-top: 6px">{{ item.qty * item.price | rupiah }}</div>
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

<style scoped>
  .cart-items-by-date {
    background: #fff;
    padding: 15px 20px;
    border-radius: 4px;
    box-shadow: 0px 2px 4px rgba(0, 0, 0, 0.05);
    margin-bottom: 20px;
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
</style>
