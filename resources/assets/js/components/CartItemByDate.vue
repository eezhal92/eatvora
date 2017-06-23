<template>
  <div class="cart-items-by-date">
    <div class="cart-items-by-date__header">
      {{ date | date }} <span style="float: right">{{ count }} Item</span>
    </div>
    <div class="cart-items-by-date__items">
      <div v-for="item in items" style="margin: 10px 0">
        <div style="display: inline-block; width: 20%">
          <img src="http://lorempixel.com/60/60" alt="meal" />
        </div>
        <div style="display: inline-block; width: 70%; text-align: right">
          <span class="pull-left">{{ item.name }}</span>
          <span class="pull-right">
            Qty <input type="number" min="1" max="3" :value="item.qty" />
            {{ item.qty * item.price | rupiah }}
          </span>
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
    border-radius: 3px;
    margin-bottom: 20px;
  }

  .cart-items-by-date__header {
    border-bottom: 1px solid #e6e6e6;
    padding: 10px 0;
    font-weight: bold;
  }
</style>