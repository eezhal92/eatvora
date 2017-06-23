<template>
  <div class="meal-list">
    <select v-model="date" @change="fetchMeals({ date: date, category: category })">
      <option v-for="day in weekdays" :value="day">{{ day }}</option>
    </select>
    <br />
    <br />
    <div class="row">
      <meal-item :date="date" v-for="meal in meals" :meal="meal" :key="meal.id"></meal-item>
    </div>
    <div v-show="isLoading" class="col-sm-12">Memuat...</div>
    <div v-if="remaining">
      <button @click="loadMore">Load More</button>
    </div>
  </div>
</template>

<script>
import { stringify as qsStringify } from 'querystring';
import bus from './bus';

import MealItem from './MealItem';

export default {
  props: ['weekdays'],
  data() {
    return {
      meals: [],
      isLoading: false,
      category: 'all',
      date: this.weekdays[0],
      currentPage: 1,
      lastPage: 1,
    };
  },
  created() {
    bus.$on('meal-filter:update', (query) => {
      this.category = query.category;
      this.reFetch();
    });
  },
  mounted() {
    this.fetchMeals({ date: this.date });
  },
  methods: {
    fetchMeals(query = {}, push = false) {
      query = qsStringify(query);
      this.isLoading = true;
      axios.get(`/api/v1/meals?${query}`)
        .then(({ data }) => {
          this.isLoading = false;
          if (push) {
            this.meals = this.meals.concat(data.entries);
          } else {
            this.meals = data.entries;
          }

          this.currentPage = +data.query.page;
          this.lastPage = +data.query.lastPage;
        })
        .catch(() => {
          this.isLoading = false;
        });
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