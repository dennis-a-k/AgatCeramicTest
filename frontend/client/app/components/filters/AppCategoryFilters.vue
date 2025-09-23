<template>
    <div class="col-lg-3 order-lg-first col-md-12 order-md-last">
        <div class="shop-sidebar-wrap">
            @if (isset($subcategories) && $subcategories->count())
            <div class="sidebar-widget">
                <h4 class="sidebar-title">Тип</h4>
                <div class="sidebar-widget-category">
                    <ul>
                        <div class="subcategories">
                            @foreach ($subcategories as $child)
                            <li class="dropdown position-static">
                                <a href="/">
                                    подкатегория
                                </a>
                            </li>
                            @endforeach
                        </div>
                    </ul>
                </div>
            </div>
            @endif

            @if (isset($patterns) && $patterns->count())
            <div class="sidebar-widget">
                <h4 class="sidebar-title">Рисунок</h4>
                <div class="sidebar-widget-category">
                    <ul>
                        @foreach ($patterns as $pattern)
                        <li class="dropdown position-static">
                            <a href="/">
                                рисунок
                            </a>
                        </li>
                        @endforeach
                    </ul>
                </div>
            </div>
            @endif

            @if (isset($weights) && $weights->count())
            <div class="sidebar-widget">
                <h4 class="sidebar-title">Вес (кг)</h4>
                <div class="sidebar-widget-category">
                    <ul>
                        @foreach ($weights as $weight)
                        <li class="color-list weight">
                            <a href="/" class="text-white">
                                вес
                            </a>
                        </li>
                        @endforeach
                    </ul>
                </div>
            </div>
            @endif

            @if (isset($colors) && $colors->count())
            <div class="sidebar-widget">
                <h4 class="sidebar-title">Цвет</h4>
                <div class="sidebar-widget-color">
                    <ul class="d-flex flex-wrap">
                        @foreach ($colors as $color)
                        <li class="color-list">
                            <a href="/" style="background-color: #FFFF00;" class="colors-filter" data-color="жёлтый">
                            </a>
                        </li>
                        @endforeach
                    </ul>
                </div>
            </div>
            @endif

            @if (isset($glues) && $glues->count())
            <div class="sidebar-widget">
                <h4 class="sidebar-title">Использовать в качестве клея</h4>
                <div class="sidebar-widget-category">
                    <ul>
                        @foreach ($glues as $glue)
                        <li class="color-list weight">
                            <a href="/" class="text-white">
                                да
                            </a>
                        </li>
                        @endforeach
                    </ul>
                </div>
            </div>
            @endif

            @if (isset($mixture_types) && $mixture_types->count())
            <div class="sidebar-widget">
                <h4 class="sidebar-title">Тип смеси</h4>
                <div class="sidebar-widget-category">
                    <ul>
                        @foreach ($mixture_types as $type)
                        <li>
                            <a href="/">
                                тип
                            </a>
                        </li>
                        @endforeach
                    </ul>
                </div>
            </div>
            @endif

            @if (isset($seams) && $seams->count())
            <div class="sidebar-widget">
                <h4 class="sidebar-title">Ширина шва (мм)</h4>
                <div class="sidebar-widget-category">
                    <ul>
                        @foreach ($seams as $seam)
                        <li class="color-list weight">
                            <a href="/" class="text-white">
                                77
                            </a>
                        </li>
                        @endforeach
                    </ul>
                </div>
            </div>
            @endif

            @if (isset($textures) && $textures->count())
            <div class="sidebar-widget">
                <h4 class="sidebar-title">Поверхность</h4>
                <div class="sidebar-widget-category">
                    <ul>
                        @foreach ($textures as $texture)
                        <li>
                            <a href="/">
                                глянцевая
                            </a>
                        </li>
                        @endforeach
                    </ul>
                </div>
            </div>
            @endif

            @if (isset($sizes) && $sizes->count())
            <div class="sidebar-widget">
                <h4 class="sidebar-title">Размер</h4>
                <div class="sidebar-widget-size">
                    <ul>
                        @foreach ($sizes as $size)
                        <li>
                            <a href="/">
                                120х120
                            </a>
                        </li>
                        @endforeach
                    </ul>
                </div>
            </div>
            @endif

            @if (isset($brands) && $brands->count())
            <div class="sidebar-widget">
                <h4 class="sidebar-title">Производитель</h4>
                <div class="sidebar-widget-category">
                    <ul id="brands-list">
                        @foreach ($brands->take(5) as $brand)
                        <li>
                            <a href="/">
                                бренд
                            </a>
                        </li>
                        @endforeach

                        @if ($brands->count() > 5)
                        <div id="hidden-brands" style="display: none;">
                            @foreach ($brands->slice(5) as $brand)
                            <li>
                                <a href="/">
                                    список скрытых брендов
                                </a>
                            </li>
                            @endforeach
                        </div>
                        <li>
                            <a href="#" id="show-all-brands" class="show-more-link">Показать все</a>
                        </li>
                        @endif
                    </ul>
                </div>
            </div>
            @endif

            <div class="sidebar-widget">
                <div class="">
                    <a href="/" class="btn-filter">
                        Сбросить фильтры
                    </a>
                </div>
            </div>
        </div>
    </div>
</template>

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
                background-color: #e1e1e1;
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
    border: 1px solid #e1e1e1;
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