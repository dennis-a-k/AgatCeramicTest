<template>
  <div class="select-shoing-wrap d-flex align-items-center col-sm-12 col-md-6">
    <div class="shot-product">
      <p>Сортировать:</p>
    </div>
    <div class="header-bottom-set dropdown">
      <button
        class="dropdown-toggle header-action-btn"
        data-bs-toggle="dropdown"
      >
        {{ currentSortText }}
        <i class="fa fa-angle-down"></i>
      </button>
      <ul class="dropdown-menu dropdown-menu-right">
        <li>
          <a
            class="dropdown-item"
            href="#"
            @click.prevent="selectSortOption('alphabetical')"
          >
            по алфавиту</a
          >
        </li>
        <li>
          <a
            class="dropdown-item"
            href="#"
            @click.prevent="selectSortOption('price_asc')"
          >
            по низкой цене
          </a>
        </li>
        <li>
          <a
            class="dropdown-item"
            href="#"
            @click.prevent="selectSortOption('price_desc')"
          >
            по высокой цене
          </a>
        </li>
        <li>
          <a
            class="dropdown-item"
            href="#"
            @click.prevent="selectSortOption('default')"
          >
            по умолчанию
          </a>
        </li>
      </ul>
    </div>
  </div>
</template>

<script setup>
import { ref, computed } from 'vue';

const emit = defineEmits(['update:sortOption']);
const selectedSort = ref('default');

const sortOptionsMap = {
  alphabetical: 'по алфавиту',
  price_asc: 'по низкой цене',
  price_desc: 'по высокой цене',
  default: 'по умолчанию',
};

const currentSortText = computed(() => sortOptionsMap[selectedSort.value]);

const selectSortOption = (option) => {
  selectedSort.value = option;
  emit('update:sortOption', option);
};
</script>

<style lang="scss">
.select-shoing-wrap {
  width: 280px;
  height: 50px;
  border: 2px solid $border-color;
  border-radius: 5px;
  padding: 0 10px;
  color: $logo-secondary-color;

  .shot-product {
    margin-right: 5px;
  }
}

.header-bottom-set {
  width: 55%;

  & .dropdown-toggle {
    display: flex;
    align-items: center;
    transition: $baseTransition;
    border: none;
    background-color: transparent;
    padding: 0;
    color: $link-secondary-color;
    width: 100%;

    &::after {
      display: none;
    }

    i {
      font-size: 18px;
      position: absolute;
      right: 0;
      color: $theme-color;
    }
  }

  & .dropdown-menu {
    margin: 0;
    top: 40px !important;
    left: auto !important;
    right: 0;
    min-width: 200px;
    overflow: hidden;
    padding: 0 15px;
    background: $white;
    border-radius: 0;
    border: none;
    box-shadow: 0 3px 25.5px 4.5px rgba(0, 0, 0, 0.06);
    transform: translate3d(0, 0, 0) !important;
    inset: 40px 0 auto -45px !important;

    @media #{$tablet-device} {
      top: 48px !important;
      inset: 48px 0 auto -45px !important;
    }

    @media #{$large-mobile} {
      top: 46px !important;
      inset: 46px 0 auto -45px !important;
    }

    & li {
      border-bottom: 1px solid $border-color;

      &:last-child {
        border: none;
      }
    }

    & .dropdown-item {
      padding: 10px;
      color: $link-secondary-color;
      line-height: 25px;
      font-size: 14px;
      background: transparent;

      &:hover {
        color: $theme-color;
      }
    }
  }
}
</style>
