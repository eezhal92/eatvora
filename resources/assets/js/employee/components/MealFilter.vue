<template>
  <form class="meal-filter" @submit.prevent="updateFilter">
    <div class="form-group">
      <label for="category" class="meal-filter__label">Kategori</label>
      <meal-tag></meal-tag>
    </div>
    <button class="btn btn-sm btn--primary" type="submit">
      <i class="glyphicon glyphicon-search"></i> Perbarui &amp; Dapatkan
    </button>
  </form>
</template>

<script>
import bus from './bus';
import store from '../vuex/store';
import MealTag from './MealTag';

export default {
  data() {
    return { category: 'all' };
  },
  methods: {
    updateFilter() {
      const payload = { category: this.category };

      bus.$emit('meal-filter:update', payload);
    }
  },
  components: {
    MealTag,
  }
}
</script>

<style lang="scss" scoped>
.meal-filter {
  &__label {
    color: #86919F;
    font-size: 16px;
    font-weight: bold;
  }
}
</style>
