<template>
  <div>
    <div v-show="!groupsCount">
      <slot name="noMealMessage"></slot>
    </div>
    <meal-group
      v-for="(group, index) in groups"
      :key="index"
      :date="index"
      :meals="groups[index]"
    ></meal-group>
  </div>
</template>

<script>
import groupBy from 'lodash/groupBy';
import MealGroup from './MealGroup';

export default {
  props: ['for'],
  data() {
    return { groups: [] };
  },
  computed: {
    groupsCount() {
      return Object.keys(this.groups).length;
    },
    endpoint() {
      if (this.for === 'this-week') {
        return '/api/v1/my-meals';
      }

      return '/api/v1/my-meals?for=next_week';
    },
  },
  mounted() {
    this.fetchMeals();
  },
  methods: {
    fetchMeals() {
      axios.get(this.endpoint)
        .then(({ data }) => {
          this.groups = groupBy(data, item => item.date);
        });
    }
  },
  components: {
    MealGroup,
  },
};
</script>
