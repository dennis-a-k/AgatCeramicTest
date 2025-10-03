<template>
    <main class="thank-you-area">
        <div class="container">
            <div class="row justify-content-center align-items-center">
                <div class="col-md-8">
                    <div class="inner_complated">
                        <div class="img_cmpted">
                            <img src="/images/stock/checkmark.png" alt="check" />
                        </div>
                        <p class="dsc_cmpted">
                            Спасибо за заказ в нашем магазине.<br>Скоро вы получите письмо с подтверждением.
                        </p>
                        <div class="btn_cmpted">
                            <NuxtLink to="/" class="shop-btn" title="Продолжить покупки">
                                Продолжить покупки
                            </NuxtLink>
                        </div>
                    </div>
                    <div class="main_quickorder text-align-center">
                        <h3 class="title">Ваш номер заказа:</h3>
                        <div class="cntct typewriter-effect">
                            <span class="call_desk">
                                <p class="order">{{order}}</p>
                            </span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </main>
</template>

<script setup>
import { computed } from 'vue';
import { useRuntimeConfig, useRoute } from '#imports';

const config = useRuntimeConfig();
const route = useRoute();

const order = route.params.order;

const structuredData = computed(() => {
  if (!order) return null
  return {
    '@context': 'https://schema.org',
    '@type': 'Order',
    orderNumber: order,
  }
})

useHead(computed(() => ({
  title: `Заказ №${order} - AgatCeramic`,
  meta: [
    {
      name: 'description',
      content: `Ваш заказ №${order} успешно оформлен в интернет-магазине AgatCeramic`
    },
    {
      name: 'keywords',
      content: `заказ ${order}, подтверждение заказа, AgatCeramic`
    },
    {
      name: 'robots',
      content: 'noindex, nofollow'
    }
  ],
  link: [
    {
      rel: 'canonical',
      href: `${config.public.siteUrl}/order`
    }
  ],
  script: structuredData.value ? [{ type: 'application/ld+json', children: JSON.stringify(structuredData.value) }] : []
})))
</script>

<style scoped lang="scss">
.thank-you-area {
    height: 80vh;
    display: flex;
    flex-direction: column;
    align-items: center;
    justify-content: center;
}

.inner_complated {
    text-align: center;

    & img {
        max-width: 100%;
    }
}

.img_cmpted {
    width: 15%;

    justify-self: center;
    margin: 0 0 30px;
}

.dsc_cmpted {
    margin: 0 0 30px;
    line-height: 24px;
}

.btn_cmpted {
    & .shop-btn {
        width: 210px;
        background: $theme-color;
        height: 50px;
        line-height: 50px;
        text-align: center;
        display: inline-block;
        color: $white;
        font-weight: 500;
        border-radius: 5px;
        font-size: 16px;

        &:hover {
            background-color: $body-color;
        }
    }
}

.main_quickorder {
    display: flex;
    flex-direction: column;
    justify-content: center;
    align-items: center;
    margin: 60px 0 0;

    & .title {
        font-size: 32px;

        @media #{$small-mobile} {
            font-size: 26px;
        }
    }
}

.order {
    font-size: 20px;
    color: $red;
    margin: 15px 0 0;
    display: inline-block;
    font-weight: 600;
}
</style>