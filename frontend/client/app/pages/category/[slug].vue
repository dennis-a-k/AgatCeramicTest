<template>

  <main class="shop-category-area pt-100px pb-100px">
    <div class="container">
      <div v-if="products.length === 0" class="text-center mt-2">
        <h1>Список товаров пуст</h1>
      </div>
      <div v-else class="row">

        <div v-if="pending" class="loading">
          Загрузка товаров...
        </div>
        <div v-else-if="error" class="error">
          Ошибка загрузки: {{ error.message }}
        </div>

        <div class="col-lg-9 order-lg-last col-md-12 order-md-first">
          <div class="shop-top-bar d-flex justify-content-between mb-3">
            <div class="category-text">
              <h1 class="h3 m-0">{{ category.name }}</h1>
              <p>{{ category.description }}</p>
            </div>
            <ProductAppSortedGoods />
          </div>
          <div class="shop-bottom-area">
            <div class="row">
              <div class="col">
                <div class="tab-content">
                  <div class="tab-pane fade show active" id="shop-grid">
                    <div class="row mb-n-30px">
                      <ProductAppProductCard v-for="product in products.data" :key="product.id" :product="product" />
                    </div>
                  </div>
                </div>
              </div>
            </div>

            <nav v-if="products.last_page > 1">
              <ul class="pagination">
                <li v-for="page in products.last_page" :key="page"
                  :class="['page-item', { active: products.current_page === page }]">
                  <a class="page-link" href="#" @click.prevent="changePage(page)">{{ page }}</a>
                </li>
              </ul>
            </nav>

          </div>
        </div>
        <FiltersAppCategoryFilters />
      </div>
    </div> 
  </main>
</template>

<script setup>
import { ref, watch, computed } from 'vue';
import { useRoute } from 'vue-router';

const route = useRoute();
const slug = route.params.slug;

const category = ref({});
const products = ref({ data: [] });
const filters = ref({ brands: [], min_price: 0, max_price: 0 });

const selectedBrands = ref([]);
const minPrice = ref('');
const maxPrice = ref('');
const sortOption = ref('newest');
const currentPage = ref(1);

const queryParams = computed(() => ({
  brands: selectedBrands.value,
  min_price: minPrice.value,
  max_price: maxPrice.value,
  sort: sortOption.value,
  page: currentPage.value
}));

const { data, error, refresh } = useFetch(
  `http://127.0.0.1:8000/api/category/${slug}/products`,
  {  
    query: queryParams,
    watch: [queryParams]
  }
);

watch(data, (newData) => {
  if (newData) {
    category.value = newData.category;
    products.value = newData.products;
    filters.value = newData.filters;
  }
});

if (error.value) {
  console.error('Ошибка при загрузке товаров категории:', error.value);
}

const handleFilterChange = (newFilters) => {
  selectedBrands.value = newFilters.brands;
  minPrice.value = newFilters.min_price;
  maxPrice.value = newFilters.max_price;
  currentPage.value = 1;
};

const changePage = (page) => {
  currentPage.value = page;
};
</script>

<style scoped>
.category-text {
  line-height: 1em;
}
</style>