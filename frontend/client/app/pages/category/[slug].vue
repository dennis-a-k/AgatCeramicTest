<template>

  <main class="shop-category-area pt-100px pb-100px">
    <div class="container">
      <div v-if="!pending && !error && products.value && products.value.data && products.value.data.length === 0" class="text-center mt-2">
        <h1>Список товаров пуст</h1>
      </div>
      <div v-else class="row">

        <div v-if="pending" class="loading">
          Загрузка товаров...
        </div>
        <div v-else-if="error && error.message !== 'Request aborted as another request to the same endpoint was initiated.' && error.name !== 'AbortError'" class="error">
          Ошибка загрузки: {{ error.message }}
        </div>

        <div class="col-lg-9 order-lg-last col-md-12 order-md-first">
          <div class="shop-top-bar d-flex justify-content-between mb-3">
            <div class="category-text">
              <h1 class="h3 m-0">{{ category.name }}</h1>
              <p>{{ category.description }}</p>
            </div>
            <ProductAppSortedGoods @update:sortOption="handleSortChange" />
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
        <FiltersAppCategoryFilters :initialFilters="filters" @update:filters="handleFilterChange" />
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
const filters = ref({ brands: [], min_price: '', max_price: '', colors: [], patterns: [], weights: [], subcategories: [], glues: [], mixture_types: [], seams: [], textures: [], sizes: [] });

const selectedBrands = ref([]);
const minPrice = ref('');
const maxPrice = ref('');
const sortOption = ref('default');
const currentPage = ref(1);
const selectedColors = ref([]);
const selectedPatterns = ref([]);
const selectedWeights = ref([]);
const selectedSubcategories = ref([]);
const selectedGlues = ref([]);
const selectedMixtureTypes = ref([]);
const selectedSeams = ref([]);
const selectedTextures = ref([]);
const selectedSizes = ref([]);

const queryParams = computed(() => ({
  brands: selectedBrands.value,
  min_price: minPrice.value,
  max_price: maxPrice.value,
  sort: sortOption.value,
  page: currentPage.value,
  colors: selectedColors.value,
  patterns: selectedPatterns.value,
  weights: selectedWeights.value,
  subcategories: selectedSubcategories.value,
  glues: selectedGlues.value,
  mixture_types: selectedMixtureTypes.value,
  seams: selectedSeams.value,
  textures: selectedTextures.value,
  sizes: selectedSizes.value,
}));

const { data, pending, error, refresh } = useFetch(
  `http://localhost/api/category/${slug}/products`,
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
}, { immediate: true });


if (error.value) {
  console.error('Ошибка при загрузке товаров категории:', error.value);
}

const handleFilterChange = (newFilters) => {
  selectedBrands.value = newFilters.brands;
  selectedColors.value = newFilters.colors || [];
  selectedPatterns.value = newFilters.patterns || [];
  selectedWeights.value = newFilters.weights || [];
  selectedSubcategories.value = newFilters.subcategories || [];
  selectedGlues.value = newFilters.glues || [];
  selectedMixtureTypes.value = newFilters.mixture_types || [];
  selectedSeams.value = newFilters.seams || [];
  selectedTextures.value = newFilters.textures || [];
  selectedSizes.value = newFilters.sizes || [];
  // minPrice.value = newFilters.min_price; // Assuming min_price and max_price are handled separately or in filters
  // maxPrice.value = newFilters.max_price; // Assuming min_price and max_price are handled separately or in filters
  currentPage.value = 1;
};

const changePage = (page) => {
  currentPage.value = page;
};

const handleSortChange = (newSortOption) => {
  sortOption.value = newSortOption;
  currentPage.value = 1;
};
</script>

<style scoped>
.category-text {
  line-height: 1em;
}
</style>