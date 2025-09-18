<template>
  <footer class="footer-area">
    <div class="footer-container">
      <div class="footer-top">
        <div class="container">
          <div class="row">
            <div class="col-md-6 col-lg-6 mb-md-30px mb-lm-30px">
              <div class="single-wedge">
                <div class="footer-logo">
                  <NuxtLink to="/" class="footer-logo-text">AgatCeramic</NuxtLink>
                </div>
                <p class="about-text lh-base" style="text-align: justify; color: #8a8a8a">
                  Интернет-магазин керамической плитки, керамогранита и мозаики в Москве и Московской области. В
                  интернет-магазине Agat Ceramic вы найдете премиальную
                  керамическую плитку, керамогранит и мозаику от ведущих мировых производителей. Мы предлагаем
                  эксклюзивные коллекции для создания уникального дизайна интерьера:
                  напольную и настенную плитку, износостойкий керамогранит для коммерческих и жилых помещений, а также
                  стильную мозаику для акцентных решений.
                </p>
              </div>
            </div>

            <div class="col-md-6 col-lg-3 col-sm-6 mb-lm-30px pl-lg-40px">
              <div class="single-wedge">
                <h4 class="footer-herading">Меню сайта</h4>
                <div class="footer-links">
                  <div class="footer-row">
                    <ul class="align-items-center">
                      <li class="li">
                        <NuxtLink class="single-link" to="/">
                          Керамогранит
                        </NuxtLink>
                      </li>
                      <li class="li">
                        <NuxtLink class="single-link" to="/">
                          Плитка
                        </NuxtLink>
                      </li>
                      <li class="li">
                        <NuxtLink class="single-link" to="/">
                          Мозаика
                        </NuxtLink>
                      </li>
                      <li class="li">
                        <NuxtLink class="single-link" to="/partnerships" target="_blank">
                          Дизайнерам
                        </NuxtLink>
                      </li>
                      <li class="li">
                        <NuxtLink class="single-link" to="/delivery">Доставка</NuxtLink>
                      </li>
                      <li class="li">
                        <NuxtLink class="single-link" to="/'return">Возврат</NuxtLink>
                      </li>
                    </ul>
                  </div>
                </div>
              </div>
            </div>

            <div class="col-md-6 col-lg-3 col-sm-12">
              <div class="single-wedge">
                <h4 class="footer-herading">Контакты</h4>
                <div class="footer-links">
                  <p class="mail">
                    <NuxtLink to="#" class="modal-call" data-link-action="modal-call" data-bs-toggle="modal"
                      data-bs-target="#modalCall">
                      Заказать звонок
                    </NuxtLink>
                  </p>
                  <p class="phone">
                    <i class="fa fa-phone"></i>
                    <NuxtLink to="tel:+79999999999" class="phone-link"> +7 (999) 999-99-99</NuxtLink>
                  </p>
                  <p class="mail">
                    <i class="fa fa-envelope-o"></i>
                    <NuxtLink to="mailto:zakaz@agatceramic.ru">zakaz@agatceramic.ru</NuxtLink>
                  </p>
                </div>
                <ul class="link-follow">
                  <li>
                    <NuxtLink class="m-0" target="_blank" rel="noopener noreferrer" to="https://t.me"><i
                        class="fa fa-telegram" aria-hidden="true"></i></NuxtLink>
                  </li>
                  <li>
                    <NuxtLink target="_blank" rel="noopener noreferrer" to="https://wa.me/"><i class="fa fa-whatsapp"
                        aria-hidden="true"></i>
                    </NuxtLink>
                  </li>
                </ul>
              </div>
            </div>
          </div>
        </div>
      </div>
      <div class="footer-bottom">
        <div class="container">
          <p class="copy-text"> ©
            {{ currentYear }} <strong>AgatCeramic</strong>&nbsp;
            <NuxtLink to="/policy" class="company-name">Политика конфиденциальности</NuxtLink>
          </p>
        </div>
      </div>
    </div>
  </footer>
</template>

<script setup>
const currentYear = ref(new Date().getFullYear())
</script>