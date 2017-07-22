<script>
import { stringify as qsStringify } from 'querystring';
import bus from './bus';
import MealItem from './MealItem';
import chunk from 'lodash/chunk';

const DateSelector = {
  props: ['weekdays'],
  data() {
    return { date: this.weekdays[0] };
  },
  template: `
    <select id="meal-date" v-model="date" @change="emitDateChangedEvent">
      <option v-for="day in weekdays" :value="day">{{ day | date }}</option>
    </select>
  `,
  methods: {
    emitDateChangedEvent() {
      bus.$emit('date-selector:date-changed', this.date)
    },
  },
};

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
      return !(this.currentPage === this.lastPage);
    }
  },
  render(h) {
    const chunkedMeals = chunk(this.meals, 3);

    return (
      <div class="meal-list">
        <label for="meal-date">
          Tanggal
        </label>
        <DateSelector weekdays={this.weekdays} />
        <br />
        <br />
        {chunkedMeals.map(meals => (
          <div class="row">
            {meals.map(meal => (
              <MealItem date={this.date} key={meal.id} meal={meal} />
            ))}
          </div>
        ))}
        <div v-show={this.isLoading} class="col-sm-12">Memuat...</div>
        <div v-show={this.remaining} class="text-center">
          <button on-click={this.loadMore} class="btn btn--primary-outline">Muat Lebih Banyak</button>
        </div>
      </div>
    );
  }
}
</script>