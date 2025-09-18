<template>
    <section class="fashion-area"
        :style="{
                backgroundImage: 'linear-gradient(rgba(0, 0, 0, 0.8), rgba(0, 0, 0, 0.5)), url(' + bgImage + ')',
                backgroundRepeat: 'no-repeat',
                backgroundSize: 'cover'
            }">
        <div class="container h-100">
            <div class="row justify-content-center align-items-center h-100">
                <div class="col-12 text-center">
                    <h2 class="title"><span>Распродажа</span>Успейти купить</h2>
                    <a href="/" class="btn btn-primary text-capitalize m-auto">
                        Распродажа
                    </a>
                </div>
            </div>
        </div>
    </section>
</template>

<script setup>
const bgImage = '/images/page-index/banners/sale.png'
</script>