<template>
  <div>
    <select v-model="date" @change="fetchMeals({ date: date })">
      <option v-for="day in weekdays" :value="day">{{ day }}</option>
    </select>
    <br />
    <select v-model="category">
      <option value="all">Semua</option>
      <option value="diet">Diet</option>
      <option value="veggie">Veggie</option>
    </select>
    <button @click="reFetch">Cari</button>
    <p>meal list</p>
    <hr />
    <meal-item v-for="meal in meals" :meal="meal" :key="meal.id"></meal-item>
    <div v-if="remaining">
      <button @click="loadMore">Load More</button>
    </div>
  </div>
</template>

<script>
// import axios from 'axios';
import { stringify as qsStringify } from 'querystring';

import MealItem from './MealItem';

export default {
  props: ['weekdays'],
  data() {
    return {
      meals: [],
      category: 'all',
      date: this.weekdays[0],
      currentPage: 1,
      lastPage: 1,
    };
  },
  mounted() {
    this.fetchMeals({ date: this.date });
  },
  methods: {
    fetchMeals(query = {}, push = false) {
      query = qsStringify(query);
      axios.get(`/something?${query}`)
        .then(({ data }) => {
          if (push) {
            this.meals = this.meals.concat(data.entries);
          } else {
            this.meals = data.entries;
          }
          this.currentPage = +data.query.page;
          this.lastPage = +data.query.lastPage;
        })
    },
    reFetch() {
      this.resetPagination();
      const query = this.nextQuery();
      this.fetchMeals(query);
    },
    resetPagination() {
      this.currentPage = 1;
      this.lastPage = 1;
    },
    nextQuery() {
      return {
        category: this.category,
        date: this.date,
        page: this.nextPage,
      };
    },
    loadMore() {
      const query = this.nextQuery();

      this.fetchMeals(query, true);
    }
  },
  computed: {
    nextPage() {
      if (this.currentPage === this.lastPage) {
        return this.currentPage;
      }

      return this.currentPage + 1;
    },
    remaining() {
      return !(this.currentPage === this.lastPage);
    }
  },
  components: {
    'meal-item': MealItem,
  }
}
</script>