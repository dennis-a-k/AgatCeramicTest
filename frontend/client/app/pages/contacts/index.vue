<template>
  <main class="shop-category-area pt-100px pb-100px">
    <div class="container">
      <div class="contact-area">
        <div class="contact-wrapper">
          <div class="row">
            <div class="col-12">
              <div class="contact-title mb-30 text-center">
                <h1 class="title">Контактная информация</h1>
              </div>
              <section class="contact-info" itemscope itemtype="http://schema.org/Organization">
                <link itemprop="url" href="/" />
                <div class="single-contact">
                  <div class="icon-box">
                    <i class="fa fa-phone"></i>
                  </div>
                  <div class="info-box">
                    <h5 class="title">Телефон</h5>
                    <p>
                      <a href="tel:+79999999999" itemprop="telephone">+7 (999) 999-99-99</a>
                    </p>
                    <small>Москва (Пн.-Пт. 10:00-18:00)</small>
                  </div>
                </div>
                <div class="single-contact">
                  <div class="icon-box">
                    <i class="fa fa-telegram"></i> /
                    <i class="fa fa-whatsapp"></i>
                  </div>
                  <div class="info-box">
                    <h5 class="title">Telegram/WhatsApp</h5>
                    <p>
                      <a itemprop="sameAs" href="https://t.me/" target="_blanc">@agatceramic</a>
                    </p>
                    <p>
                      <a itemprop="sameAs" href="https://wa.me/" target="_blanc">https://wa.me/79999999999</a>
                    </p>
                  </div>
                </div>
                <div class="single-contact m-0">
                  <div class="icon-box">
                    <i class="fa fa-envelope-o"></i>
                  </div>
                  <div class="info-box">
                    <h5 class="title">Почта</h5>
                    <p>
                      <a href="mailto:zakaz@agatceramic.ru" itemprop="email">zakaz@agatceramic.ru</a>
                    </p>
                  </div>
                </div>
              </section>
            </div>
          </div>
        </div>
      </div>
    </div>
  </main>
</template>

<script setup>
import { useRuntimeConfig } from '#imports'

const config = useRuntimeConfig()

useHead({
  title: 'Контакты - AgatCeramic',
  meta: [
    {
      name: 'description',
      content: 'Контактная информация интернет-магазина AgatCeramic. Телефон, email, адрес. Свяжитесь с нами для консультации по выбору плитки и сантехники.'
    },
    {
      name: 'keywords',
      content: 'контакты, телефон, email, адрес, AgatCeramic, керамическая плитка, сантехника'
    }
  ],
  link: [
    {
      rel: 'canonical',
      href: `${config.public.siteUrl}/contacts`
    }
  ]
})
</script>

<style scoped lang="scss">
.contact-wrapper {
  background-color: $secondary;
  padding: 110px 120px;

  @media #{$desktop-device} {
    padding: 70px 80px;
  }

  @media #{$tablet-device, $large-mobile} {
    padding: 50px 60px 60px 60px;
  }

  @media #{$small-mobile} {
    padding: 50px 30px 60px 30px;
  }

  @media #{$extra-small-mobile} {
    padding: 50px 20px 60px 20px;
  }

  .contact-info {
    display: flex;
    flex-direction: row;
    justify-content: space-between;
    margin-top: 120px;
    position: relative;
    z-index: 1;

    @media #{$desktop-device} {
      margin-top: 80px;
    }

    @media #{$tablet-device, $large-mobile} {
      margin-top: 60px;
      flex-direction: column;
    }

    .single-contact {
      display: flex;
      flex-direction: column;
      justify-content: center;
      align-items: center;
      width: 270px;
      height: 264px;
      box-shadow: 4px 0px 20px rgba(0, 0, 0, 0.1);
      border-radius: 10px;
      background-color: $white;

      @media #{$desktop-device} {
        width: 240px;
        height: 250px;
      }

      @media #{$tablet-device, $large-mobile} {
        width: auto;
        height: auto;
        margin-bottom: 50px;
        padding: 40px 0;
      }

      .icon-box {
        font-size: xxx-large;
      }

      .info-box {
        text-align: center;
        line-height: 1;

        .title {
          font-size: 24px;
          color: $black;
          font-weight: 500;
          margin: 21px 0 18px 0;

          @media #{$extra-small-mobile} {
            font-size: 20px;
          }
        }

        p {
          font-size: 16px;
          color: $black;
          font-weight: 400;
          line-height: 26px;
        }

        a {
          color: $black;

          &:hover {
            color: $theme-color;
          }
        }
      }
    }
  }
}

.contact-title {
  .title {
    font-size: 48px;
    color: $black;
    font-weight: 500;
    margin: 0;
    line-height: 1;

    @media #{$desktop-device} {
      font-size: 36px;
    }

    @media #{$tablet-device} {
      font-size: 32px;
    }

    @media #{$large-mobile} {
      font-size: 28px;
    }
  }
}
</style>