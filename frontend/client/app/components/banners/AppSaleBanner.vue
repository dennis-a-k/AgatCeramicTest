<template>
    <section class="fashion-area" :style="{
        backgroundImage: 'linear-gradient(rgba(0, 0, 0, 0.8), rgba(0, 0, 0, 0.5)), url(' + bgImage + ')',
        backgroundRepeat: 'no-repeat',
        backgroundSize: 'cover'
    }">
        <div class="container h-100">
            <div class="row justify-content-center align-items-center h-100">
                <div class="col-12 text-center">
                    <h2 class="title"><span>Распродажа</span>Успейте купить</h2>
                    <NuxtLink to="/sale" class="btn text-capitalize m-auto">
                        Распродажа
                    </NuxtLink>
                </div>
            </div>
        </div>
    </section>
</template>

<script setup>
const bgImage = '/images/page-index/banners/sale.png'
</script>

<style scoped lang="scss">
.fashion-area {
    height: 456px;
    background-position: center;

    @media #{$tablet-device, $large-mobile} {
        height: 356px;
    }

    @media #{$small-mobile} {
        height: 300px;
    }

    .title {
        color: $white;
        font-weight: 700;
        margin-bottom: 45px;

        span {
            font-size: 48px;
            font-weight: 300;
            display: block;

            @media #{$tablet-device, $large-mobile} {
                font-size: 36px;
            }

            @media #{$small-mobile} {
                font-size: 24px;
                line-height: 32px;
            }
        }

        @media #{$tablet-device, $large-mobile} {
            margin-bottom: 32px;
        }

        @media #{$small-mobile} {
            margin-bottom: 25px;
        }
    }

    .btn {
        &:hover {
            color: rgba(0, 0, 0, 0.5);
            background-color: $white;
        }

        &:active {
            border-color: $theme-color;
            background-color: $theme-color;
        }
    }
}
</style>