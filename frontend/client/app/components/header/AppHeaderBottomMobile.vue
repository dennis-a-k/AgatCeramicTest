<template>
    <div class="header-bottom d-lg-none sticky-nav style-1">
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
                <div class="col-lg-3 col">
                    <div class="header-actions">
                        <a href="#offcanvas-cart"
                            class="header-action-btn header-action-btn-cart offcanvas-toggle pr-0">
                            <i class="pe-7s-cart"></i>
                            <span class="header-action-num">01</span>
                        </a>
                        <a href="#offcanvas-mobile-menu"
                            class="header-action-btn header-action-btn-menu offcanvas-toggle d-lg-none">
                            <i class="pe-7s-menu"></i>
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>