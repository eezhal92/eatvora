<template>
  <div class="meal-tags">
    <button class="meal-tag" @click="setTagAll" :class="{ 'meal-tag--selected': isSelected('all') }">All</button>
    <button type="button" v-for="tag in tags" class="meal-tag" @click="toggleSelect(tag.id)" :class="{ 'meal-tag--selected': isSelected(tag.id) }">{{ tag.name }}</button>
  </div>
</template>

<script>
const tags = [
  { id: 1, name: 'Vegetarian', slug: 'veggie' },
  { id: 2, name: 'Spicy', slug: 'spicy' },
  { id: 3, name: 'Diet', slug: 'dite' },
];

export default {
  props: ['selectedTags'],
  data() {
    return { tags, selected: this.selectedTags };
  },
  methods: {
    isSelected(id) {
      return !!this.selected.find(tagId => tagId === id);
    },
    removeTagAll() {
      this.selected = this.selected.filter(tagId => tagId !== 'all');
    },
    setTagAll() {
      this.selected = ['all'];
    },
    toggleSelect(id) {
      if (this.isSelected(id)) {
        const i = this.selected.indexOf(id);

        this.selected.splice(i, 1);

        if (!this.selected.length) {
          this.setTagAll();
        }
      } else {
        this.removeTagAll();
        this.selected.push(id);
      }
    }
  },
  watch: {
    selected(newValue) {
      this.$emit('update:selected-tags', newValue)
    }
  },
};
</script>

<style lang="scss" scoped>
  .meal-tag {
    display: inline-block;
    margin-right: 10px;
    margin-top: 8px;
    background: transparent;
    border: 1px solid #E8E9F4;
    border-radius: 4px;
    color: #7E8998;
    padding: 6px 8px;

    &:focus {
      outline: none;
    }

    &--selected {
      color: #FD8421;
      border-color: #FD8421;
    }
  }
</style>
