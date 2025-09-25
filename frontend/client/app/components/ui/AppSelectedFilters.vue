<template>
  <div class="selected-filters" v-if="selectedFilters.length">
    <span v-for="filter in selectedFilters" :key="`${filter.type}-${filter.id}`" class="filter-badge">
      {{ filter.name }}
      <button @click="handleRemove(filter.type, filter.id)" class="remove-btn">&times;</button>
    </span>
  </div>
</template>

<script setup>
const props = defineProps({
  selectedFilters: {
    type: Array,
    default: () => []
  }
})

const emit = defineEmits(['removeFilter'])

const handleRemove = (type, id) => {
  emit('removeFilter', type, id)
}
</script>

<style scoped lang="scss">
.selected-filters {
  margin-bottom: 20px;
  display: flex;
  flex-wrap: wrap;
  gap: 10px;
}

.filter-badge {
  display: inline-flex;
  align-items: center;
  background-color: $theme-color;
  color: $white;
  padding: 5px 10px;
  border-radius: 20px;
  font-size: 14px;
  font-weight: 500;
  line-height: 1;

  .remove-btn {
    background: none;
    border: none;
    color: $white;
    font-size: 16px;
    margin-left: 8px;
    cursor: pointer;
    padding: 0;
    line-height: 1;
    opacity: 0.8;
    transition: opacity 0.2s;

    &:hover {
      opacity: 1;
    }
  }
}
</style>