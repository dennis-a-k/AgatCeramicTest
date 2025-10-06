<template>
    <div class="col-lg-3 order-lg-first col-md-12 order-md-last">
        <div class="shop-sidebar-wrap">
            <div class="sidebar-widget" v-if="showCategoriesFilter && categories && categories.length">
                <h4 class="sidebar-title">Категория</h4>
                <div class="sidebar-widget-category">
                    <ul>
                        <li v-for="category in visibleCategories" :key="category.id">
                            <a href="#" @click.prevent="selectFilter('category', category.id)">
                                {{ category.name }}
                            </a>
                        </li>

                        <div v-show="showAllCategories">
                            <li v-for="category in hiddenCategories" :key="category.id">
                                <a href="#" @click.prevent="selectFilter('category', category.id)">
                                    {{ category.name }}
                                </a>
                            </li>
                        </div>
                        <li v-if="categories.length > 5">
                            <a href="#" class="show-more-link" @click.prevent="toggleShowAllCategories">
                                {{ showAllCategories ? 'Скрыть' : 'Показать все' }}
                            </a>
                        </li>
                    </ul>
                </div>
            </div>

            <div class="sidebar-widget" v-if="subcategories && subcategories.length">
                <h4 class="sidebar-title">Подкатегория</h4>
                <div class="sidebar-widget-category">
                    <ul>
                        <div class="subcategories">
                            <li class="dropdown position-static" v-for="child in subcategories" :key="child.id">
                                <a href="#" @click.prevent="selectFilter('subcategory', child.id)">
                                    {{ child.name }}
                                </a>
                            </li>
                        </div>
                    </ul>
                </div>
            </div>

            <div class="sidebar-widget" v-if="patterns && patterns.length">
                <h4 class="sidebar-title">Рисунок</h4>
                <div class="sidebar-widget-category">
                    <ul>
                        <li class="dropdown position-static" v-for="pattern in patterns" :key="pattern.id">
                            <a href="#" @click.prevent="selectFilter('pattern', pattern.id)">
                                {{ pattern.name }}
                            </a>
                        </li>
                    </ul>
                </div>
            </div>

            <div class="sidebar-widget" v-if="weights && weights.length">
                <h4 class="sidebar-title">Толщина шва (мм)</h4>
                <div class="sidebar-widget-category">
                    <ul>
                        <li class="color-list weight" v-for="weight in weights" :key="weight.id">
                            <a href="#" class="text-white" @click.prevent="selectFilter('weight', weight.id)">
                                {{ weight.name }}
                            </a>
                        </li>
                    </ul>
                </div>
            </div>

            <div class="sidebar-widget" v-if="colors && colors.length">
                <h4 class="sidebar-title">Цвет</h4>
                <div class="sidebar-widget-color">
                    <ul class="d-flex flex-wrap">
                        <li class="color-list" v-for="color in colors" :key="color.id">
                            <a href="#" :style="{ backgroundColor: color.hex }" class="colors-filter" :data-color="color.name" @click.prevent="selectFilter('color', color.id)">
                            </a>
                        </li>
                    </ul>
                </div>
            </div>

            <div class="sidebar-widget" v-if="glues && glues.length">
                <h4 class="sidebar-title">Использовать в качестве клея</h4>
                <div class="sidebar-widget-category">
                    <ul>
                        <li class="color-list weight" v-for="glue in glues" :key="glue.id">
                            <a href="#" class="text-white" @click.prevent="selectFilter('glue', glue.id)">
                                {{ glue.name }}
                            </a>
                        </li>
                    </ul>
                </div>
            </div>

            <div class="sidebar-widget" v-if="mixture_types && mixture_types.length">
                <h4 class="sidebar-title">Тип</h4>
                <div class="sidebar-widget-category">
                    <ul>
                        <li v-for="type in mixture_types" :key="type.id">
                            <a href="#" @click.prevent="selectFilter('mixture_type', type.id)">
                                {{ type.name }}
                            </a>
                        </li>
                    </ul>
                </div>
            </div>

            <div class="sidebar-widget" v-if="seams && seams.length">
                <h4 class="sidebar-title">Ширина шва (мм)</h4>
                <div class="sidebar-widget-category">
                    <ul>
                        <li class="color-list weight" v-for="seam in seams" :key="seam.id">
                            <a href="#" class="text-white" @click.prevent="selectFilter('seam', seam.id)">
                                {{ seam.name }}
                            </a>
                        </li>
                    </ul>
                </div>
            </div>

            <div class="sidebar-widget" v-if="textures && textures.length">
                <h4 class="sidebar-title">Поверхность</h4>
                <div class="sidebar-widget-category">
                    <ul>
                        <li v-for="texture in textures" :key="texture.id">
                            <a href="#" @click.prevent="selectFilter('texture', texture.id)">
                                {{ texture.name }}
                            </a>
                        </li>
                    </ul>
                </div>
            </div>

            <div class="sidebar-widget" v-if="countries && countries.length">
                <h4 class="sidebar-title">Страна</h4>
                <div class="sidebar-widget-category">
                    <ul>
                        <li v-for="country in countries" :key="country.id">
                            <a href="#" @click.prevent="selectFilter('country', country.id)">
                                {{ country.name }}
                            </a>
                        </li>
                    </ul>
                </div>
            </div>

            <div class="sidebar-widget" v-if="sizes && sizes.length">
                <h4 class="sidebar-title">Размер (мм)</h4>
                <div class="sidebar-widget-category">
                    <ul>
                        <li class="color-list weight" v-for="size in sizes" :key="size.id">
                            <a href="#" class="text-white" @click.prevent="selectFilter('size', size.id)">
                                {{ size.name }}
                            </a>
                        </li>
                    </ul>
                </div>
            </div>

            <div class="sidebar-widget" v-if="materials && materials.length">
                <h4 class="sidebar-title">Материал</h4>
                <div class="sidebar-widget-category">
                    <ul>
                        <li v-for="material in materials" :key="material.id">
                            <a href="#" @click.prevent="selectFilter('material', material.id)">
                                {{ material.name }}
                            </a>
                        </li>
                    </ul>
                </div>
            </div>

            <div class="sidebar-widget" v-if="waterproofs && waterproofs.length">
                <h4 class="sidebar-title">Влагостойкость</h4>
                <div class="sidebar-widget-category">
                    <ul>
                        <li class="color-list weight" v-for="waterproof in waterproofs" :key="waterproof.id">
                            <a href="#" class="text-white" @click.prevent="selectFilter('waterproof', waterproof.id)">
                                {{ waterproof.name }}
                            </a>
                        </li>
                    </ul>
                </div>
            </div>

            <div class="sidebar-widget" v-if="collections && collections.length">
                <h4 class="sidebar-title">Коллекция</h4>
                <div class="sidebar-widget-category">
                    <ul>
                        <li v-for="collection in visibleCollections" :key="collection.id">
                            <a href="#" @click.prevent="selectFilter('collection', collection.id)">
                                {{ collection.name }}
                            </a>
                        </li>

                        <div v-show="showAllCollections">
                            <li v-for="collection in hiddenCollections" :key="collection.id">
                                <a href="#" @click.prevent="selectFilter('collection', collection.id)">
                                    {{ collection.name }}
                                </a>
                            </li>
                        </div>
                        <li v-if="collections.length > 10">
                            <a href="#" class="show-more-link" @click.prevent="toggleShowAllCollections">
                                {{ showAllCollections ? 'Скрыть' : 'Показать все' }}
                            </a>
                        </li>
                    </ul>
                </div>
            </div>

            <div class="sidebar-widget" v-if="volumes && volumes.length">
                <h4 class="sidebar-title">Объем (л)</h4>
                <div class="sidebar-widget-category">
                    <ul>
                        <li class="color-list weight" v-for="volume in volumes" :key="volume.id">
                            <a href="#" class="text-white" @click.prevent="selectFilter('volume', volume.id)">
                                {{ volume.name }}
                            </a>
                        </li>
                    </ul>
                </div>
            </div>

            <div class="sidebar-widget" v-if="product_weights && product_weights.length">
                <h4 class="sidebar-title">Вес (кг)</h4>
                <div class="sidebar-widget-category">
                    <ul>
                        <li class="color-list weight" v-for="weight in product_weights" :key="weight.id">
                            <a href="#" class="text-white" @click.prevent="selectFilter('product_weight', weight.id)">
                                {{ weight.name }}
                            </a>
                        </li>
                    </ul>
                </div>
            </div>

            <div class="sidebar-widget" v-if="installation_types && installation_types.length">
                <h4 class="sidebar-title">Тип установки</h4>
                <div class="sidebar-widget-category">
                    <ul>
                        <li v-for="type in installation_types" :key="type.id">
                            <a href="#" @click.prevent="selectFilter('installation_type', type.id)">
                                {{ type.name }}
                            </a>
                        </li>
                    </ul>
                </div>
            </div>

            <div class="sidebar-widget" v-if="shapes && shapes.length">
                <h4 class="sidebar-title">Форма</h4>
                <div class="sidebar-widget-category">
                    <ul>
                        <li v-for="shape in shapes" :key="shape.id">
                            <a href="#" @click.prevent="selectFilter('shape', shape.id)">
                                {{ shape.name }}
                            </a>
                        </li>
                    </ul>
                </div>
            </div>

            <div class="sidebar-widget" v-if="applications && applications.length">
                <h4 class="sidebar-title">Применение</h4>
                <div class="sidebar-widget-category">
                    <ul>
                        <li v-for="application in applications" :key="application.id">
                            <a href="#" @click.prevent="selectFilter('application', application.id)">
                                {{ application.name }}
                            </a>
                        </li>
                    </ul>
                </div>
            </div>

            <div class="sidebar-widget" v-if="drying_times && drying_times.length">
                <h4 class="sidebar-title">Время высыхания</h4>
                <div class="sidebar-widget-category">
                    <ul>
                        <li v-for="time in drying_times" :key="time.id">
                            <a href="#" @click.prevent="selectFilter('drying_time', time.id)">
                                {{ time.name }}
                            </a>
                        </li>
                    </ul>
                </div>
            </div>

            <div class="sidebar-widget" v-if="package_weights && package_weights.length">
                <h4 class="sidebar-title">Вес упаковки (кг)</h4>
                <div class="sidebar-widget-category">
                    <ul>
                        <li class="color-list weight" v-for="weight in package_weights" :key="weight.id">
                            <a href="#" class="text-white" @click.prevent="selectFilter('package_weight', weight.id)">
                                {{ weight.value }}
                            </a>
                        </li>
                    </ul>
                </div>
            </div>

            <div class="sidebar-widget" v-if="min_temps && min_temps.length">
                <h4 class="sidebar-title">Мин. температура эксплуатации (°C)</h4>
                <div class="sidebar-widget-category">
                    <ul>
                        <li class="color-list weight" v-for="temp in min_temps" :key="temp.id">
                            <a href="#" class="text-white" @click.prevent="selectFilter('min_temp', temp.id)">
                                {{ temp.value }}
                            </a>
                        </li>
                    </ul>
                </div>
            </div>

            <div class="sidebar-widget" v-if="max_temps && max_temps.length">
                <h4 class="sidebar-title">Макс. температура эксплуатации (°C)</h4>
                <div class="sidebar-widget-category">
                    <ul>
                        <li class="color-list weight" v-for="temp in max_temps" :key="temp.id">
                            <a href="#" class="text-white" @click.prevent="selectFilter('max_temp', temp.id)">
                                {{ temp.value }}
                            </a>
                        </li>
                    </ul>
                </div>
            </div>

            <div class="sidebar-widget" v-if="consumptions && consumptions.length">
                <h4 class="sidebar-title">Расход</h4>
                <div class="sidebar-widget-category">
                    <ul>
                        <li v-for="consumption in consumptions" :key="consumption.id">
                            <a href="#" @click.prevent="selectFilter('consumption', consumption.id)">
                                {{ consumption.name }}
                            </a>
                        </li>
                    </ul>
                </div>
            </div>

            <div class="sidebar-widget" v-if="brands && brands.length">
                <h4 class="sidebar-title">Производитель</h4>
                <div class="sidebar-widget-category">
                    <ul id="brands-list">
                        <li v-for="brand in visibleBrands" :key="brand.id">
                            <a href="#" @click.prevent="selectFilter('brand', brand.id)">
                                {{ brand.name }}
                            </a>
                        </li>

                        <div id="hidden-brands" v-show="showAllBrands">
                            <li v-for="brand in hiddenBrands" :key="brand.id">
                                <a href="#" @click.prevent="selectFilter('brand', brand.id)">
                                    {{ brand.name }}
                                </a>
                            </li>
                        </div>
                        <li v-if="brands.length > 5">
                            <a href="#" id="show-all-brands" class="show-more-link" @click.prevent="toggleShowAllBrands">
                                {{ showAllBrands ? 'Скрыть' : 'Показать все' }}
                            </a>
                        </li>
                    </ul>
                </div>
            </div>

            <div class="sidebar-widget">
                <div class="">
                    <a href="#" class="btn-filter" @click.prevent="resetFilters">
                        Сбросить фильтры
                    </a>
                </div>
            </div>
        </div>
    </div>
</template>

<script setup>
import { ref, computed } from 'vue';

const props = defineProps({
    store: {
        type: Object,
        required: true
    },
    showCategoriesFilter: { type: Boolean, default: false },
    categories: { type: Array, default: () => [] },
    subcategories: { type: Array, default: () => [] },
    patterns: { type: Array, default: () => [] },
    weights: { type: Array, default: () => [] },
    colors: { type: Array, default: () => [] },
    glues: { type: Array, default: () => [] },
    mixture_types: { type: Array, default: () => [] },
    seams: { type: Array, default: () => [] },
    textures: { type: Array, default: () => [] },
    countries: { type: Array, default: () => [] },
    sizes: { type: Array, default: () => [] },
    materials: { type: Array, default: () => [] },
    waterproofs: { type: Array, default: () => [] },
    collections: { type: Array, default: () => [] },
    volumes: { type: Array, default: () => [] },
    product_weights: { type: Array, default: () => [] },
    installation_types: { type: Array, default: () => [] },
    shapes: { type: Array, default: () => [] },
    applications: { type: Array, default: () => [] },
    drying_times: { type: Array, default: () => [] },
    package_weights: { type: Array, default: () => [] },
    min_temps: { type: Array, default: () => [] },
    max_temps: { type: Array, default: () => [] },
    consumptions: { type: Array, default: () => [] },
    brands: { type: Array, default: () => [] },
    initialFilters: { type: Object, default: () => ({ brands: [], min_price: '', max_price: '', colors: [], patterns: [], weights: [], subcategories: [], glues: [], mixture_types: [], seams: [], textures: [], countries: [], sizes: [], materials: [], waterproofs: [], collections: [], volumes: [], product_weights: [], installation_types: [], shapes: [], applications: [], drying_times: [], package_weights: [], min_temps: [], max_temps: [], consumptions: [] }) }
});

const showAllCategories = ref(false);
const showAllBrands = ref(false);
const showAllCollections = ref(false);

const visibleCategories = computed(() => props.categories.slice(0, 5));
const hiddenCategories = computed(() => props.categories.slice(5));

const visibleBrands = computed(() => props.brands.slice(0, 5));
const hiddenBrands = computed(() => props.brands.slice(5));

const visibleCollections = computed(() => props.collections.slice(0, 10));
const hiddenCollections = computed(() => props.collections.slice(10));

const toggleShowAllCategories = () => {
    showAllCategories.value = !showAllCategories.value;
};

const toggleShowAllBrands = () => {
    showAllBrands.value = !showAllBrands.value;
};

const toggleShowAllCollections = () => {
    showAllCollections.value = !showAllCollections.value;
};

const selectFilter = (type, value) => {
    props.store.selectFilter(type, value);
};

const resetFilters = () => {
    props.store.resetFilters();
};


</script>

<style scoped lang="scss">
.shop-sidebar-wrap {
    border: 2px solid $border-color;
    border-radius: 5px;
    padding: 10px 30px 40px 30px;

    @media #{$desktop-device} {
        padding: 10px 10px 40px 20px;
    }

    & .sidebar-widget-category,
    .sidebar-widget-color,
    .sidebar-widget-size {
        & li {
            & a {
                color: $logo-secondary-color;
                font-size: 16px;
                display: inline-block;
                margin: 0 0 0 10px;
                font-weight: 300;
                line-height: 1;

                &:hover {
                    color: $theme-color;
                }
            }
        }
    }

    .sidebar-widget-color {
        & li {
            & a {
                width: 24px;
                height: 24px;
                border-radius: 50%;
            }
        }
    }

    .sidebar-widget {
        & h4 {
            font-size: 18px;
            display: block;
            font-weight: 600;
            color: $link-secondary-color;
            position: relative;
            margin: 35px 0 20px 0px;
            padding: 0 0 20px;

            &::after {
                position: absolute;
                top: auto;
                bottom: 0;
                width: 150px;
                height: 2px;
                background-color: $border-color;
                content: "";
                left: 0;
            }
        }
    }
}

.btn-filter {
    display: block;
    text-transform: none;
    font-weight: 500;
    font-size: 16px;
    color: $logo-secondary-color;
    box-shadow: none;
    padding: 10px 15px;
    line-height: 26px;
    border: 1px solid $border-color;
    background: $white;
    border-radius: 0;
    width: auto;
    height: auto;
    text-align: center;
    margin: 35px 0 0 0;
}

.btn-filter:hover {
    background-color: $theme-color;
    border: 1px solid $theme-color;
    color: $white;
}

/* Стили для ссылки */
.colors-filter {
    filter: brightness(95%);
}

/* Стили тултипа */
.colors-filter::after {
    content: attr(data-color);
    position: absolute;
    top: -130%;
    left: 50%;
    transform: translateX(-50%);
    background-color: $theme-color;
    color: $white;
    padding: 5px 10px;
    border-radius: 4px;
    white-space: nowrap;
    display: none;
    transition: opacity 0.3s ease;
    pointer-events: none;
}

/* Треугольник под тултипом */
.colors-filter::before {
    content: "";
    position: absolute;
    top: -25%;
    left: 50%;
    transform: translateX(-50%);
    border-width: 5px;
    border-style: solid;
    border-color: $theme-color transparent transparent transparent;
    display: none;
    transition: opacity 0.3s ease;
}

/* Показ тултипа и треугольника при наведении */
.colors-filter:hover::after,
.colors-filter:hover::before {
    display: block;
}

.color-list {
    position: relative;
    display: inline-block;
}

.show-more-link {
    color: $theme-color;
    text-decoration: underline;
    cursor: pointer;
    font-weight: 500;
}

.show-more-link:hover {
    color: $body-color;
    text-decoration: none;
}

.weight a {
    border: 1px solid $theme-color;
    padding: 3px;
    background-color: $theme-color;
    border-radius: 4px;
}
</style>