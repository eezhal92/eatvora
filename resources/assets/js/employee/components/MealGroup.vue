<template>
  <div class="meal-day">
    <div class="meal-day__date">{{ date | date }}</div>
    <div class="meal-day__items">
      <div class="meal-item" v-for="meal in meals">
        <div class="meal-item__img-container">
          <a :href="`/meals/${meal.id}`"><img src="http://lorempixel.com/110/100/food" class="meal-item__img" /></a>
        </div>
        <div class="meal-item__detail">
          <div class="meal-item__title">{{ meal.name | limit(18) }}</div>
          <div class="meal-item__vendor">Oleh {{ meal.vendor.name }}</div>
          <div class="meal-item__qty">{{ meal.qty }} pcs</div>
        </div>
      </div>
    </div>
  </div>
</template>

<script>
export default {
  props: ['meals', 'date'],
};
</script>
</script>
