<template>
    <div id="offcanvas-mobile-menu" class="offcanvas offcanvas-mobile-menu">
        <button class="offcanvas-close"></button>
        <div class="user-panel">
            <ul>
                <li>
                    <a href="#" class="modal-call" data-link-action="modal-call" data-bs-toggle="modal"
                        data-bs-target="#modalCall">
                        Заказать звонок
                    </a>
                </li>
                <li><a href="tel:+79999999999" class="phone-link"><i class="fa fa-phone"></i> +7 (999) 999-99-99</a>
                </li>
                <li>
                    <a href="mailto:zakaz@agatceramic.ru">
                        <i class="fa fa-envelope-o"></i>
                        zakaz@agatceramic.ru
                    </a>
                </li>
            </ul>
        </div>
        <div class="inner customScroll">
            <nav class="offcanvas-menu mb-4">
                <ul>
                    <li>
                        <a href="#">
                            Керамогранит
                        </a>
                    </li>

                    <li>
                        <a href="/">
                            Плитка
                        </a>
                    </li>

                    <li>
                        <a href="/">
                            Мозаика
                        </a>
                    </li>

                    <li>
                        <a href="#"><span class="menu-text">Услуги</span></a>
                        <ul class="sub-menu">
                            <li>
                                <NuxtLink to="/rezka"><span class="menu-text">Резка</span></NuxtLink>
                            </li>
                            <li>
                                <NuxtLink to="/rospis"><span class="menu-text">Роспись плитки</span></NuxtLink>
                            </li>
                        </ul>
                    </li>

                    <li>
                        <a href="#"><span class="menu-text">Каталог</span></a>
                        <ul class="sub-menu">
                            <li>
                                <a href="/">
                                    <span class="menu-text">Клинкер</span>
                                </a>
                            </li>
                            <li>
                                <a href="/">
                                    <span class="menu-text">Ступени</span>
                                </a>
                            </li>
                            <li>
                                <a href="/">
                                    <span class="menu-text">Затирка для плитки</span>
                                </a>
                            </li>
                            <li>
                                <a href="/">
                                    <span class="menu-text">Клеевые смеси</span>
                                </a>
                            </li>
                            <li>
                                <a href="#"><span class="menu-text">Сантехника</span></a>
                                <ul class="sub-menu">

                                    <li><a href="/">Ванны</a></li>
                                    <li><a href="/">Смесители</a></li>
                                    <li><a href="/">Унитазы</a></li>

                                </ul>
                            </li>
                        </ul>
                    </li>

                    <li><NuxtLink to="/partnerships" target="_blank">Дизайнерам</NuxtLink></li>
                    <li><NuxtLink to="/delivery">Оплата и доставка</NuxtLink></li>
                    <li><NuxtLink to="/return">Возврат и замена</NuxtLink></li>
                    <li><NuxtLink to="/contacts">Контакты</NuxtLink></li>
                    <li><NuxtLink to="/" class="text-danger">Распродажа</NuxtLink></li>
                </ul>
            </nav>

            <div class="offcanvas-social">
                <ul>
                    <li>
                        <a href="https://t.me/{{ $appData->telegram ?? '---' }}" target="_blanc"><i
                                class="fa fa-telegram"></i></a>
                    </li>
                    <li>
                        <a href="https://wa.me/{{ $appData->whatsapp ?? '---' }}" target="_blanc"><i
                                class="fa fa-whatsapp"></i></a>
                    </li>
                </ul>
            </div>
        </div>
    </div>
</template>