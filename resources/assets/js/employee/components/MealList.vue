<script>
import { stringify } from 'qs';

import bus from './bus';
import MealItem from './MealItem';
import chunk from 'lodash/chunk';
import DateSelector from './DateSelector.vue';

export default {
  props: ['weekdays'],
  data() {
    return {
      meals: [],
      total: 0,
      isLoading: false,
      category: ['all'],
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

    bus.$on('date-selector:date-changed', (date) => {
      this.date = date;
      this.fetchMeals({ date, category: this.category })
    })
  },
  mounted() {
    this.fetchMeals({ date: this.date });
  },
  methods: {
    fetchMeals(query = {}, push = false) {
      query = stringify(query);
      this.isLoading = true;
      axios.get(`/api/v1/meals?${query}`)
        .then(({ data }) => {
          this.isLoading = false;
          if (push) {
            this.meals = this.meals.concat(data.items);
          } else {
            this.meals = data.items;
          }

          this.currentPage = +data.query.page;
          this.total = +data.total;
          this.lastPage = +data.query.last_page;
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
    },
    changeDate(date) {
      this.date = date;
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
      if (!this.total) {
        return false;
      }

      return !(this.currentPage === this.lastPage);
    }
  },
  render(h) {
    const chunkedMeals = chunk(this.meals, 3);

    return (
      <div class="meal-list">
        <DateSelector weekdays={this.weekdays} />
        <br />
        <br />
        {!this.total && <div>Makan siang untuk hari ini telah habis atau tidak tersedia :(</div>}
        {chunkedMeals.map(meals => (
          <div class="row">
            {meals.map(meal => (
              <MealItem date={this.date} key={meal.id} meal={meal} />
            ))}
          </div>
        ))}
        <div v-show={this.isLoading} class="text-center">Memuat...</div>
        <div v-show={this.remaining && !this.isLoading} class="text-center">
          <button on-click={this.loadMore} class="btn btn--primary-outline">Muat Lebih Banyak</button>
        </div>
      </div>
    );
  }
}
</script>
