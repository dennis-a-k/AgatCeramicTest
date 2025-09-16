<template>
    <div class="header-bottom d-none d-lg-block">
        <div class="container">
            <div class="row justify-content-between align-items-center">
                <div class="col-lg-3 col">
                    <div class="header-logo">
                        <NuxtLink to="/" class="row align-items-center">
                            <NuxtImg src="/images/logo.svg" class="col-3 pe-0" alt="Site Logo" />
                            <p class="col-9">Agat<span>Ceramic</span></p>
                        </NuxtLink>
                    </div>
                </div>
                <div class="col-lg-6 d-none d-lg-block">
                    <UiSearchForm />
                </div>
                <div class="col-lg-3 col">
                    <div class="header-actions">
                        <a href="#offcanvas-cart"
                            class="header-action-btn header-action-btn-cart offcanvas-toggle pr-0">
                            <div>
                                <span>Корзина</span>
                                <i class="pe-7s-cart"></i>
                                <span class="header-action-num">01</span>
                            </div>
                            <span class="header-action-order">Оформить заказ</span>
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>