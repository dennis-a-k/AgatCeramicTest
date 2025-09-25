<template>
    <div class="pro-pagination-style text-center text-lg-end" data-aos="fade-up" data-aos-delay="200">
        <div class="pages">
            <ul>
                <li class="page-item li" :class="{ disabled: currentPage === 1 }">
                    <a class="page-link" href="#" @click.prevent="currentPage > 1 && $emit('change-page', currentPage - 1)">
                        <i class="fa fa-angle-left"></i>
                    </a>
                </li>
                <li v-for="page in pages" :key="page" class="page-item li">
                    <a class="page-link" :class="{ active: page === currentPage }" href="#" @click.prevent="$emit('change-page', page)">{{ page }}</a>
                </li>
                <li class="page-item li" :class="{ disabled: currentPage === totalPages }">
                    <a class="page-link" href="#" @click.prevent="currentPage < totalPages && $emit('change-page', currentPage + 1)">
                        <i class="fa fa-angle-right"></i>
                    </a>
                </li>
            </ul>
        </div>
    </div>
</template>

<script setup>
import { computed } from 'vue'

const props = defineProps({
  currentPage: {
    type: Number,
    required: true
  },
  totalPages: {
    type: Number,
    required: true
  }
})

const emit = defineEmits(['change-page'])

const pages = computed(() => {
  const start = Math.max(1, props.currentPage - 3)
  const end = Math.min(props.totalPages, props.currentPage + 3)
  const result = []
  for (let i = start; i <= end; i++) {
    result.push(i)
  }
  return result
})
</script>

<style scoped lang="scss">
.pro-pagination-style {
    margin-top: 60px;

    @media #{$tablet-device, $large-mobile} {
        margin-bottom: 60px;
    }

    & li {
        display: inline-block;

        &+li {
            margin-left: 10px;
        }
    }

    & a {
        font-weight: 400;
        color: $logo-secondary-color;
        padding: 0;
        height: 40px;
        background: $white;
        display: inline-block;
        width: 40px;
        border-radius: 5px;
        text-align: center;
        vertical-align: top;
        font-size: 12px;
        transition: $baseTransition;
        border-color: $border-color;
        font-weight: 600;
        line-height: 40px;
        outline: none;
        border-width: 2px;
        border-style: solid;

        @media #{$large-mobile} {
            height: 40px;
            width: 40px;
            line-height: 40px;
            font-size: 16px;
        }

        & .fa {
            @media #{$large-mobile} {
                font-size: 20px;
            }
        }

        &.active {
            color: $theme-color;
            border-color: $theme-color;
            background: $white;
        }

        &:hover {
            color: $theme-color;
            border-color: $theme-color;
            background: $white;
        }
    }

    .page-link {
        border-radius: 5px;
        &:focus {
            z-index: 3;
            color: $theme-color;
            background-color: $white;
            outline: 0;
            box-shadow: none;
        }
    }

    .disabled {
        pointer-events: none;
    }

    .disabled a {
        background-color: $border-color;
    }
}
</style>