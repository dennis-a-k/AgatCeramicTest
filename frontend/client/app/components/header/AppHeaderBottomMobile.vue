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

<style scoped lang="scss">
.header-bottom {
    padding: 20px 0;
    background-color: $secondary;

    @media #{$tablet-device, $large-mobile} {
        padding: 15px 0;
    }

    .header-logo {
        & a {
            font-family: $heading-font;
            color: $black;
            text-decoration: none;

            & img {
                @media #{$tablet-device} {
                    width: 18%;
                }
            }

            & p {
                font-weight: 600;
                font-size: 1.75rem;

                @media #{$desktop-device, $large-mobile} {
                    font-size: 1.5rem;
                }
            }
        }

        & span {
            color: $logo-secondary-color;
        }
    }

    //header action
    .header-actions {
        display: flex;
        justify-content: flex-end;
        align-items: center;

        &>* {
            &+* {
                margin-left: 15px;

                @media #{$desktop-device} {}

                @media #{$tablet-device, $large-mobile} {}

                @media #{$small-mobile} {}

                @media #{$extra-small-mobile} {}
            }
        }

        & .header-action-btn {
            position: relative;
            display: flex;
            flex-direction: column;
            text-decoration: none;
            color: #000;
            transition: $baseTransition;

            &:hover {
                color: $theme-color;
            }

            & i {
                transition: 0s;
                font-size: 27px;

                @media #{$small-mobile} {}

                @media #{$extra-small-mobile} {}

                &:hover {
                    color: $theme-color;
                }
            }

            & .header-action-num {
                font-size: 14px;
                font-weight: 400;
                position: absolute;
                z-index: 2;
                top: -10px;
                right: -7px;
                display: flex;
                flex-direction: column;
                justify-content: center;
                align-items: center;
                color: #fff;
                border-radius: 50%;
                background-color: $theme-color;
                width: 21px;
                height: 21px;
            }

            & .header-action-order {
                font-size: 13px;
                color: $theme-color;
                line-height: 1px;
            }

            & .cart-amount {
                display: block;
                font-size: 16px;
                font-weight: 700;
                text-align: center;
                padding-left: 15px;
                color: #0f0f0f;
                transition: $baseTransition;
            }

            &:hover {
                .cart-amount {
                    color: $theme-color;
                }
            }
        }
    }

    //header action
}

.sticky-nav.menu_fixed {
    position: fixed;
    top: 0;
    left: 0;
    width: 100%;
    z-index: 99;
    -webkit-animation: sticky 1s;
    -moz-animation: sticky 1s;
    -o-animation: sticky 1s;
    animation: sticky 1s;
    -webkit-box-shadow: 2px 4px 8px rgba(51, 51, 51, 0.25);
    -moz-box-shadow: 2px 4px 8px rgba(51, 51, 51, 0.25);
    box-shadow: 2px 4px 8px rgba(51, 51, 51, 0.25);
    background-color: #f2f6f9;

    &.style-1 {
        background-color: #f2f6f9;
        padding: 20px 0;
    }
}
</style>