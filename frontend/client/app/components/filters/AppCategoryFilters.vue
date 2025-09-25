<template>
    <div class="col-lg-3 order-lg-first col-md-12 order-md-last">
        <div class="shop-sidebar-wrap">
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
                <h4 class="sidebar-title">Толщина шва</h4>
                <div class="sidebar-widget-category">
                    <ul>
                        <li class="color-list weight" v-for="weight in weights" :key="weight.id">
                            <a href="#" class="text-white" @click.prevent="selectFilter('weight', weight.id)">
                                {{ weight.value }}
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
                <h4 class="sidebar-title">Размер</h4>
                <div class="sidebar-widget-size">
                    <ul>
                        <li v-for="size in sizes" :key="size.id">
                            <a href="#" @click.prevent="selectFilter('size', size.id)">
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
                        <li v-for="waterproof in waterproofs" :key="waterproof.id">
                            <a href="#" @click.prevent="selectFilter('waterproof', waterproof.id)">
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
                        <li v-for="collection in collections" :key="collection.id">
                            <a href="#" @click.prevent="selectFilter('collection', collection.id)">
                                {{ collection.name }}
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
                                {{ volume.value }}
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
                                {{ weight.value }}
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
import { ref, watch, computed } from 'vue';

const props = defineProps({
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

const emit = defineEmits(['update:filters']);

const selectedBrands = ref([]);
const selectedColors = ref([]);
const selectedPatterns = ref([]);
const selectedWeights = ref([]);
const selectedSubcategories = ref([]);
const selectedGlues = ref([]);
const selectedMixtureTypes = ref([]);
const selectedSeams = ref([]);
const selectedTextures = ref([]);
const selectedSizes = ref([]);
const selectedMaterials = ref([]);
const selectedWaterproofs = ref([]);
const selectedCountries = ref([]);
const selectedCollections = ref([]);
const selectedVolumes = ref([]);
const selectedProductWeights = ref([]);
const selectedInstallationTypes = ref([]);
const selectedShapes = ref([]);
const selectedApplications = ref([]);
const selectedDryingTimes = ref([]);
const selectedPackageWeights = ref([]);
const selectedMinTemps = ref([]);
const selectedMaxTemps = ref([]);
const selectedConsumptions = ref([]);

const showAllBrands = ref(false);

const visibleBrands = computed(() => props.brands.slice(0, 5));
const hiddenBrands = computed(() => props.brands.slice(5));

const toggleShowAllBrands = () => {
    showAllBrands.value = !showAllBrands.value;
};

const selectFilter = (type, value) => {
    if (type === 'brand') {
        const index = selectedBrands.value.indexOf(value);
        if (index === -1) {
            selectedBrands.value.push(value);
        } else {
            selectedBrands.value.splice(index, 1);
        }
    } else if (type === 'color') {
        const index = selectedColors.value.indexOf(value);
        if (index === -1) {
            selectedColors.value.push(value);
        } else {
            selectedColors.value.splice(index, 1);
        }
    } else if (type === 'pattern') {
        const index = selectedPatterns.value.indexOf(value);
        if (index === -1) {
            selectedPatterns.value.push(value);
        } else {
            selectedPatterns.value.splice(index, 1);
        }
    } else if (type === 'weight') {
        const index = selectedWeights.value.indexOf(value);
        if (index === -1) {
            selectedWeights.value.push(value);
        } else {
            selectedWeights.value.splice(index, 1);
        }
    } else if (type === 'subcategory') {
        const index = selectedSubcategories.value.indexOf(value);
        if (index === -1) {
            selectedSubcategories.value.push(value);
        } else {
            selectedSubcategories.value.splice(index, 1);
        }
    } else if (type === 'glue') {
        const index = selectedGlues.value.indexOf(value);
        if (index === -1) {
            selectedGlues.value.push(value);
        } else {
            selectedGlues.value.splice(index, 1);
        }
    } else if (type === 'mixture_type') {
        const index = selectedMixtureTypes.value.indexOf(value);
        if (index === -1) {
            selectedMixtureTypes.value.push(value);
        } else {
            selectedMixtureTypes.value.splice(index, 1);
        }
    } else if (type === 'seam') {
        const index = selectedSeams.value.indexOf(value);
        if (index === -1) {
            selectedSeams.value.push(value);
        } else {
            selectedSeams.value.splice(index, 1);
        }
    } else if (type === 'texture') {
        const index = selectedTextures.value.indexOf(value);
        if (index === -1) {
            selectedTextures.value.push(value);
        } else {
            selectedTextures.value.splice(index, 1);
        }
    } else if (type === 'size') {
        const index = selectedSizes.value.indexOf(value);
        if (index === -1) {
            selectedSizes.value.push(value);
        } else {
            selectedSizes.value.splice(index, 1);
        }
    } else if (type === 'material') {
        const index = selectedMaterials.value.indexOf(value);
        if (index === -1) {
            selectedMaterials.value.push(value);
        } else {
            selectedMaterials.value.splice(index, 1);
        }
    } else if (type === 'waterproof') {
        const index = selectedWaterproofs.value.indexOf(value);
        if (index === -1) {
            selectedWaterproofs.value.push(value);
        } else {
            selectedWaterproofs.value.splice(index, 1);
        }
    } else if (type === 'country') {
        const index = selectedCountries.value.indexOf(value);
        if (index === -1) {
            selectedCountries.value.push(value);
        } else {
            selectedCountries.value.splice(index, 1);
        }
    } else if (type === 'collection') {
        const index = selectedCollections.value.indexOf(value);
        if (index === -1) {
            selectedCollections.value.push(value);
        } else {
            selectedCollections.value.splice(index, 1);
        }
    } else if (type === 'volume') {
        const index = selectedVolumes.value.indexOf(value);
        if (index === -1) {
            selectedVolumes.value.push(value);
        } else {
            selectedVolumes.value.splice(index, 1);
        }
    } else if (type === 'product_weight') {
        const index = selectedProductWeights.value.indexOf(value);
        if (index === -1) {
            selectedProductWeights.value.push(value);
        } else {
            selectedProductWeights.value.splice(index, 1);
        }
    } else if (type === 'installation_type') {
        const index = selectedInstallationTypes.value.indexOf(value);
        if (index === -1) {
            selectedInstallationTypes.value.push(value);
        } else {
            selectedInstallationTypes.value.splice(index, 1);
        }
    } else if (type === 'shape') {
        const index = selectedShapes.value.indexOf(value);
        if (index === -1) {
            selectedShapes.value.push(value);
        } else {
            selectedShapes.value.splice(index, 1);
        }
    } else if (type === 'application') {
        const index = selectedApplications.value.indexOf(value);
        if (index === -1) {
            selectedApplications.value.push(value);
        } else {
            selectedApplications.value.splice(index, 1);
        }
    } else if (type === 'drying_time') {
        const index = selectedDryingTimes.value.indexOf(value);
        if (index === -1) {
            selectedDryingTimes.value.push(value);
        } else {
            selectedDryingTimes.value.splice(index, 1);
        }
    } else if (type === 'package_weight') {
        const index = selectedPackageWeights.value.indexOf(value);
        if (index === -1) {
            selectedPackageWeights.value.push(value);
        } else {
            selectedPackageWeights.value.splice(index, 1);
        }
    } else if (type === 'min_temp') {
        const index = selectedMinTemps.value.indexOf(value);
        if (index === -1) {
            selectedMinTemps.value.push(value);
        } else {
            selectedMinTemps.value.splice(index, 1);
        }
    } else if (type === 'max_temp') {
        const index = selectedMaxTemps.value.indexOf(value);
        if (index === -1) {
            selectedMaxTemps.value.push(value);
        } else {
            selectedMaxTemps.value.splice(index, 1);
        }
    } else if (type === 'consumption') {
        const index = selectedConsumptions.value.indexOf(value);
        if (index === -1) {
            selectedConsumptions.value.push(value);
        } else {
            selectedConsumptions.value.splice(index, 1);
        }
    }
    emitFilters();
};

const emitFilters = () => {
    emit('update:filters', {
        brands: selectedBrands.value,
        colors: selectedColors.value,
        patterns: selectedPatterns.value,
        weights: selectedWeights.value,
        subcategories: selectedSubcategories.value,
        glues: selectedGlues.value,
        mixture_types: selectedMixtureTypes.value,
        seams: selectedSeams.value,
        textures: selectedTextures.value,
        countries: selectedCountries.value,
        sizes: selectedSizes.value,
        materials: selectedMaterials.value,
        waterproofs: selectedWaterproofs.value,
        collections: selectedCollections.value,
        volumes: selectedVolumes.value,
        product_weights: selectedProductWeights.value,
        installation_types: selectedInstallationTypes.value,
        shapes: selectedShapes.value,
        applications: selectedApplications.value,
        drying_times: selectedDryingTimes.value,
        package_weights: selectedPackageWeights.value,
        min_temps: selectedMinTemps.value,
        max_temps: selectedMaxTemps.value,
        consumptions: selectedConsumptions.value,
    });
};

const resetFilters = () => {
    selectedBrands.value = [];
    selectedColors.value = [];
    selectedPatterns.value = [];
    selectedWeights.value = [];
    selectedSubcategories.value = [];
    selectedGlues.value = [];
    selectedMixtureTypes.value = [];
    selectedSeams.value = [];
    selectedTextures.value = [];
    selectedCountries.value = [];
    selectedSizes.value = [];
    selectedMaterials.value = [];
    selectedWaterproofs.value = [];
    selectedCollections.value = [];
    selectedVolumes.value = [];
    selectedProductWeights.value = [];
    selectedInstallationTypes.value = [];
    selectedShapes.value = [];
    selectedApplications.value = [];
    selectedDryingTimes.value = [];
    selectedPackageWeights.value = [];
    selectedMinTemps.value = [];
    selectedMaxTemps.value = [];
    selectedConsumptions.value = [];
    emitFilters();
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
                margin-top: 10px;
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
                margin: 0 0 0 10px;
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