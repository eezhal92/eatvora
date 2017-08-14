<template>
  <form @submit.prevent="updateFilter">
    <div class="form-group">
      <label for="category">Kategori</label>
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
