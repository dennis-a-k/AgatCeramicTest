<template>
    <div class="search-element">
        <form @submit.prevent="handleSearch">
            <input 
                type="text" 
                placeholder="Поиск" 
                v-model="searchQuery"
                @input="handleInput"
                @keyup.enter="handleSearch"
                @focus="showSuggestions = true"
                @blur="hideSuggestions"
                ref="searchInput"
            >
            <button type="submit">
                <i class="pe-7s-search"></i>
            </button>
            
            <!-- Выпадающие подсказки -->
            <div v-if="showSuggestions && suggestions.length > 0" class="suggestions-dropdown">
                <div 
                    v-for="suggestion in suggestions" 
                    :key="suggestion.id"
                    class="suggestion-item"
                    @mousedown="selectSuggestion(suggestion)"
                >
                    {{ suggestion.display_text }}
                </div>
            </div>
        </form>
    </div>
</template>

<script setup>
import { ref, watch, onMounted } from 'vue'
import { useRouter } from 'vue-router'
import { useRuntimeConfig } from '#imports'

const router = useRouter()

const searchQuery = ref('')
const suggestions = ref([])
const showSuggestions = ref(false)
const searchInput = ref(null)
const config = useRuntimeConfig()

// Реализация дебаунса
const debounce = (func, wait) => {
    let timeout
    return function executedFunction(...args) {
        const later = () => {
            clearTimeout(timeout)
            func(...args)
        }
        clearTimeout(timeout)
        timeout = setTimeout(later, wait)
    }
}

// Дебаунс для быстрого поиска
const fetchSuggestions = debounce(async (query) => {
    if (query.length < 2) {
        suggestions.value = []
        return
    }

    try {
        const response = await $fetch(`${config.public.apiBase}/api/search/quick?query=${encodeURIComponent(query)}`)
        suggestions.value = response.results
    } catch (error) {
        console.error('Ошибка при получении подсказок:', error)
        suggestions.value = []
    }
}, 300)

const handleInput = () => {
    fetchSuggestions(searchQuery.value)
}

const selectSuggestion = (suggestion) => {
    searchQuery.value = suggestion.display_text
    showSuggestions.value = false
    handleSearch()
}

const hideSuggestions = () => {
    // Небольшая задержка для обработки клика по подсказке
    setTimeout(() => {
        showSuggestions.value = false
    }, 150)
}

const handleSearch = async () => {
    if (!searchQuery.value.trim()) return

    // Скрываем подсказки
    showSuggestions.value = false

    // Навигация на страницу результатов поиска
    router.push({
        path: '/search',
        query: { q: searchQuery.value.trim() }
    })
}

// Очистка подсказок при изменении маршрута
watch(() => router.currentRoute.value, () => {
    suggestions.value = []
    showSuggestions.value = false
})

onMounted(() => {
    // Фокус на поле ввода при монтировании
    if (searchInput.value) {
        searchInput.value.focus()
    }
})
</script>

<style scoped lang="scss">
.search-element {
    max-width: 450px;
    margin: auto;
    position: relative;

    @media #{$tablet-device, $large-mobile} {
        padding: 0 0 20px;
    }

    form {
        position: relative;

        input {
            height: 35px;
            width: 100%;
            background-color: $white;
            border: 0;
            border-radius: 5px;
            padding-right: 80px;
            padding-left: 15px;
            font-size: 14px;
            color: $body-color;
        }

        button {
            position: absolute;
            top: 0;
            right: 0;
            width: 55px;
            height: 35px;
            font-size: 25px;
            color: $white;
            display: flex;
            flex-direction: column;
            justify-content: center;
            align-items: center;
            border-radius: 0px 5px 5px 0px;
            background-color: $theme-color;
            border: none;
            cursor: pointer;
            transition: background-color 0.2s ease;

            &:hover {
                background-color: $body-color;
            }
        }
    }

    &.max-width-100 {
        max-width: 100%;
    }
}

.suggestions-dropdown {
    position: absolute;
    top: 100%;
    left: 0;
    right: 0;
    background: $white;
    border: 1px solid $border-color;
    border-radius: 0 0 5px 5px;
    box-shadow: 0 4px 12px rgba(0, 0, 0, 0.1);
    max-height: 300px;
    overflow-y: auto;
    z-index: 1000;
    margin-top: 5px;
}

.suggestion-item {
    padding: 12px 15px;
    cursor: pointer;
    font-size: 14px;
    color: $body-color;
    border-bottom: 1px solid $border-color;
    transition: background-color 0.2s ease;

    &:last-child {
        border-bottom: none;
    }

    &:hover {
        background-color: $secondary;
    }
}
</style>