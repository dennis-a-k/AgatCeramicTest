<template>
    <div class="search-page">
        <div class="container">
            <div class="row">
                <div class="col-12">
                    <div class="search-header">
                        <h1>Результаты поиска</h1>
                        <p v-if="searchQuery">По запросу: "{{ searchQuery }}"</p>
                    </div>

                    <div class="search-results">
                        <div v-if="loading" class="loading-spinner">
                            <div class="spinner"></div>
                            <p>Загрузка...</p>
                        </div>

                        <div v-else-if="products.length === 0 && !loading" class="no-results">
                            <div class="no-results-content">
                                <i class="pe-7s-search"></i>
                                <h3>Ничего не найдено</h3>
                                <p>Попробуйте изменить поисковый запрос</p>
                            </div>
                        </div>

                        <div v-else class="products-grid">
                            <div class="row">
                                <div
                                    v-for="product in products"
                                    :key="product.id"
                                    class="col-xl-3 col-lg-4 col-md-6 col-sm-6"
                                >
                                    <div class="product-card">
                                        <div class="product-image">
                                            <img :src="product.image || '/images/stock/stock-image.png'" :alt="product.name" />
                                        </div>
                                        <div class="product-info">
                                            <h3>{{ product.name }}</h3>
                                            <p class="price">{{ formatPrice(product.price) }}</p>
                                            <p class="article">Артикул: {{ product.article }}</p>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Пагинация -->
                        <div v-if="products.length > 0 && meta.last_page > 1" class="pagination-wrapper">
                            <nav aria-label="Page navigation">
                                <ul class="pagination">
                                    <li class="page-item" :class="{ disabled: meta.current_page === 1 }">
                                        <a class="page-link" href="#" @click.prevent="changePage(meta.current_page - 1)">
                                            <i class="pe-7s-angle-left"></i>
                                        </a>
                                    </li>
                                    
                                    <li
                                        v-for="page in pageRange"
                                        :key="page"
                                        class="page-item"
                                        :class="{ active: page === meta.current_page }"
                                    >
                                        <a class="page-link" href="#" @click.prevent="changePage(page)">
                                            {{ page }}
                                        </a>
                                    </li>

                                    <li class="page-item" :class="{ disabled: meta.current_page === meta.last_page }">
                                        <a class="page-link" href="#" @click.prevent="changePage(meta.current_page + 1)">
                                            <i class="pe-7s-angle-right"></i>
                                        </a>
                                    </li>
                                </ul>
                            </nav>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>

<script setup>
import { ref, computed, onMounted, watch } from 'vue'
import { useRoute, useRouter } from 'vue-router'
import { useRuntimeConfig } from '#imports'

const formatPrice = (price) => {
    return new Intl.NumberFormat('ru-RU', {
        style: 'currency',
        currency: 'RUB'
    }).format(price);
};

const route = useRoute()
const router = useRouter()
const config = useRuntimeConfig()

const searchQuery = ref('')
const products = ref([])
const loading = ref(false)
const meta = ref({
    total: 0,
    current_page: 1,
    per_page: 40,
    last_page: 1
})

// Вычисляемый диапазон страниц для пагинации
const pageRange = computed(() => {
    const current = meta.value.current_page
    const last = meta.value.last_page
    const delta = 2
    const range = []
    const rangeWithDots = []

    for (let i = 1; i <= last; i++) {
        if (i === 1 || i === last || (i >= current - delta && i <= current + delta)) {
            range.push(i)
        }
    }

    let prev = 0
    for (let i of range) {
        if (prev) {
            if (i - prev === 2) {
                rangeWithDots.push(prev + 1)
            } else if (i - prev !== 1) {
                rangeWithDots.push('...')
            }
        }
        rangeWithDots.push(i)
        prev = i
    }

    return rangeWithDots
})

// Загрузка результатов поиска
const fetchSearchResults = async () => {
    if (!searchQuery.value) return

    loading.value = true
    try {
        const response = await $fetch(`${config.public.apiBase}/api/products/search/${encodeURIComponent(searchQuery.value)}`, {
            method: 'GET',
            query: {
                page: meta.value.current_page,
                per_page: meta.value.per_page
            }
        })

        products.value = response.products.data || []
        meta.value = {
            total: response.products.total || 0,
            current_page: response.products.current_page || 1,
            per_page: response.products.per_page || 40,
            last_page: response.products.last_page || 1
        }
    } catch (error) {
        console.error('Ошибка при поиске товаров:', error)
        products.value = []
    } finally {
        loading.value = false
    }
}

// Смена страницы
const changePage = (page) => {
    if (page < 1 || page > meta.value.last_page || page === meta.value.current_page) return
    
    meta.value.current_page = page
    router.push({
        query: { 
            ...route.query,
            page: page
        }
    })
}

// Наблюдаем за изменениями параметров маршрута
watch(() => route.query, (newQuery) => {
    searchQuery.value = newQuery.q || ''
    meta.value.current_page = parseInt(newQuery.page) || 1
    if (searchQuery.value) {
        fetchSearchResults()
    }
}, { immediate: true })

// Инициализация при монтировании
onMounted(() => {
    searchQuery.value = route.query.q || ''
    meta.value.current_page = parseInt(route.query.page) || 1
    if (searchQuery.value) {
        fetchSearchResults()
    }
})
</script>

<style scoped lang="scss">
.search-page {
    padding: 40px 0;
    min-height: 60vh;
}

.search-header {
    text-align: center;
    margin-bottom: 30px;

    h1 {
        font-size: 28px;
        font-weight: 600;
        color: $heading-color;
        margin-bottom: 10px;
    }

    p {
        font-size: 16px;
        color: $body-color;
        margin: 0;
    }
}

.loading-spinner {
    display: flex;
    justify-content: center;
    align-items: center;
    min-height: 200px;
}

.no-results {
    text-align: center;
    padding: 60px 0;

    .no-results-content {
        i {
            font-size: 60px;
            color: $border-color;
            margin-bottom: 20px;
            display: block;
        }

        h3 {
            font-size: 20px;
            color: $heading-color;
            margin-bottom: 10px;
        }

        p {
            color: $body-color;
            margin: 0;
        }
    }
}

.products-grid {
    margin-bottom: 40px;
}

.pagination-wrapper {
    display: flex;
    justify-content: center;
    margin-top: 40px;

    .pagination {
        .page-item {
            margin: 0 5px;

            &.active {
                .page-link {
                    background-color: $theme-color;
                    border-color: $theme-color;
                    color: $white;
                }
            }

            &.disabled {
                opacity: 0.6;
                pointer-events: none;
            }

            .page-link {
                min-width: 40px;
                height: 40px;
                display: flex;
                align-items: center;
                justify-content: center;
                border: 1px solid $border-color;
                border-radius: 5px;
                color: $body-color;
                text-decoration: none;
                transition: all 0.2s ease;

                &:hover {
                    background-color: $theme-color;
                    border-color: $theme-color;
                    color: $white;
                }
            }
        }
    }
}

@media #{$tablet-device} {
    .search-page {
        padding: 30px 0;
    }

    .search-header {
        margin-bottom: 20px;
        
        h1 {
            font-size: 24px;
        }
    }
}

@media #{$large-mobile} {
    .search-page {
        padding: 20px 0;
    }

    .search-header {
        margin-bottom: 15px;
        
        h1 {
            font-size: 20px;
        }
        
        p {
            font-size: 14px;
        }
    }

    .no-results {
        padding: 40px 0;
        
        .no-results-content {
            i {
                font-size: 40px;
                margin-bottom: 15px;
            }
            
            h3 {
                font-size: 18px;
            }
        }
    }
}
</style>