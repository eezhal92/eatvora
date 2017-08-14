<template>
  <div class="date-selector">
    <button
      v-for="date in weekdays"
      class="date-selector__date"
      :class="{'date-selector__date--selected': date === selectedDate}"
      @click="selectDate(date)"
    >
      <span class="date-day">{{ date | day }}</span>
      <span class="date-day-month">{{ date | dayMonth }}</span>
    </button>
  </div>
</template>

<script>
import bus from './bus';

export default {
  props: ['weekdays'],
  data() {
    return { selectedDate: this.weekdays[0] }
  },
  methods: {
    selectDate(date) {
      this.selectedDate = date;
      this.emitDateChangedEvent();
    },
    emitDateChangedEvent() {
      bus.$emit('date-selector:date-changed', this.selectedDate);
    },
  },
}
</script>

<style lang="scss" scoped>
  .date-selector {
    &__date {
      display: inline-block;
      padding: 10px 16px;
      margin-right: 14px;
      color: #7E8998;
      font-weight: bold;
      background: transparent;
      border: 1px solid #E8E9F4;
      border-radius: 4px;

      &--selected {
        background: #FFA35C;
        color: #fff;
        font-weight: bold;
        border-color: #FFA35C;
      }

      &:focus {
        outline: none;
      }
    }
  }

  .date-day, .date-day-month {
    display: block;
  }

  .date-day-month {
    font-size: 11px;
  }
</style>
