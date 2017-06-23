<template>
  <form @submit.prevent="updateFilter">
    <div class="form-group">
      <label for="category">Kategori</label>
      <select id="category" class="form-control" v-model="category">
        <option value="all">Semua</option>
        <option value="diet">Diet</option>
        <option value="veggie">Veggie</option>
      </select>
    </div>
    
    <button class="btn btn-primary" type="submit">
      Cari
    </button>
  </form>
</template>

<script>
import bus from './bus';

export default {
  data() {
    return { category: 'all' };
  },
  methods: {
    updateFilter() {
      const payload = { category: this.category };

      bus.$emit('meal-filter:update', payload);
    }
  }
}
</script>