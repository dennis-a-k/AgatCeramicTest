<template>
  <footer class="footer-area">
    <div class="footer-container">
      <div class="footer-top">
        <div class="container">
          <div class="footer-grid">
            <!-- Навигация -->
            <div class="footer-column footer-nav">
              <div class="single-wedge">
                <h4 class="footer-heading">Меню сайта</h4>
                <div class="footer-links">
                  <ul class="footer-menu">
                    <li>
                      <NuxtLink class="single-link" to="/category/keramogranit">
                        Керамогранит
                      </NuxtLink>
                    </li>
                    <li>
                      <NuxtLink class="single-link" to="/category/plitka">
                        Плитка
                      </NuxtLink>
                    </li>
                    <li>
                      <NuxtLink class="single-link" to="/category/mozaika">
                        Мозаика
                      </NuxtLink>
                    </li>
                    <li>
                      <NuxtLink class="single-link" to="/partnerships" target="_blank">
                        Дизайнерам
                      </NuxtLink>
                    </li>
                    <li>
                      <NuxtLink class="single-link" to="/delivery">Доставка</NuxtLink>
                    </li>
                    <li>
                      <NuxtLink class="single-link" to="/return">Возврат</NuxtLink>
                    </li>
                  </ul>
                </div>
              </div>
            </div>

            <!-- Контакты -->
            <div class="footer-column footer-contacts">
              <div class="single-wedge">
                <h4 class="footer-heading">Контакты</h4>
                <div class="footer-links">
                  <p class="contact-item">
                    <NuxtLink to="#" class="modal-call" data-link-action="modal-call" data-bs-toggle="modal"
                      data-bs-target="#modalCall">
                      Заказать звонок
                    </NuxtLink>
                  </p>
                  <p class="contact-item">
                    <i class="icon fa fa-phone"></i>
                    <NuxtLink to="tel:+79999999999" class="phone-link"> +7 (999) 999-99-99</NuxtLink>
                  </p>
                  <p class="contact-item">
                    <i class="icon fa fa-envelope-o"></i>
                    <NuxtLink to="mailto:zakaz@agatceramic.ru">zakaz@agatceramic.ru</NuxtLink>
                  </p>
                </div>
                <div class="social-links">
                  <NuxtLink class="social-link" target="_blank" rel="noopener noreferrer" to="https://t.me">
                    <i class="fa fa-telegram" aria-hidden="true"></i>
                  </NuxtLink>
                  <NuxtLink class="social-link" target="_blank" rel="noopener noreferrer" to="https://wa.me/">
                    <i class="fa fa-whatsapp" aria-hidden="true"></i>
                  </NuxtLink>
                </div>
              </div>
            </div>

            <!-- Блок с логотипом и описанием -->
            <div class="footer-column footer-about">
              <div class="single-wedge">
                <div class="footer-logo">
                  <NuxtLink to="/" class="footer-logo-text">Agat<span>Ceramic</span></NuxtLink>
                </div>
                <p class="about-text">
                  Интернет-магазин керамической плитки, керамогранита и мозаики в Москве и Московской области.
                  В интернет-магазине AgatCeramic вы найдете премиальную керамическую плитку, керамогранит
                  и мозаику от ведущих мировых производителей.
                </p>
              </div>
            </div>
          </div>
        </div>
      </div>
      <div class="footer-bottom">
        <div class="container">
          <div class="footer-bottom-content">
            <p class="copy-text">© {{ currentYear }} <strong>AgatCeramic</strong></p>
            <NuxtLink to="/policy" class="policy-link">Политика конфиденциальности</NuxtLink>
          </div>
        </div>
      </div>
    </div>
  </footer>
</template>

<script setup>
const currentYear = ref(new Date().getFullYear())
</script>

<style scoped lang="scss">
// Переменные
$bg-color: #1a1a1a;
$text-color: #e0e0e0;
$border-color: #2c2c2c;
$social-bg: #2c2c2c;

// Основные стили футера
.footer-area {
  background-color: $bg-color;
  color: $text-color;
  padding: 50px 0 0;

  .footer-container {
    width: 100%;
  }

  .container {
    max-width: 1200px;
    margin: 0 auto;
    padding: 0 15px;
  }
}

// Сетка для колонок
.footer-grid {
  display: grid;
  grid-template-columns: repeat(1, 1fr);
  gap: 30px;
  margin-bottom: 40px;

  @media (min-width: 768px) {
    grid-template-columns: 1fr 1fr 2fr;
  }

  @media (min-width: 992px) {
    grid-template-columns: 1fr 1fr 1fr;
  }
}

.footer-column {
  padding: 0 15px;
}

// Логотип
.footer-logo-text {
  font-size: 28px;
  font-weight: 700;
  color: $white;
  text-decoration: none;
  display: inline-block;
  margin-bottom: 20px;
  transition: color 0.3s;

  &:hover {
    color: $theme-color;
  }
}

// Заголовки секций
.footer-heading {
  font-size: 18px;
  font-weight: 600;
  color: $white;
  margin-bottom: 20px;
  position: relative;
  padding-bottom: 10px;

  &::after {
    content: '';
    position: absolute;
    bottom: 0;
    left: 0;
    width: 40px;
    height: 2px;
    background-color: $theme-color;
  }
}

// Текст описания
.about-text {
  line-height: 1.6;
  color: $logo-secondary-color;
  text-align: justify;
  margin-bottom: 20px;
}

// Навигационное меню
.footer-menu {
  list-style: none;
  padding: 0;
  margin: 0;

  li {
    margin-bottom: 12px;
  }
}

.single-link {
  color: $logo-secondary-color;
  text-decoration: none;
  transition: all 0.3s ease;
  position: relative;
  padding-left: 0;

  &:hover {
    color: $theme-color;
    padding-left: 5px;
  }
}

// Контакты
.contact-item {
  display: flex;
  align-items: center;
  margin-bottom: 15px;
  color: $logo-secondary-color;

  a {
    color: $logo-secondary-color;
    text-decoration: none;
    transition: color 0.3s;

    &:hover {
      color: $theme-color;
    }
  }

  .icon {
    margin-right: 10px;
    color: $theme-color;
    min-width: 16px;
  }
}

.modal-call {
  color: $theme-color !important;
  font-weight: 500;

  &:hover {
    text-decoration: underline !important;
  }
}

// Социальные сети
.social-links {
  display: flex;
  margin-top: 20px;
}

.social-link {
  display: flex;
  align-items: center;
  justify-content: center;
  background-color: $social-bg;
  color: $white;
  margin-right: 10px;
  text-decoration: none;
  transition: all 0.3s ease;
  font-size: 25px;
  height: 36px;
  width: 36px;
  border-radius: 5px;

  &:hover {
    background-color: $theme-color;
    transform: translateY(-3px);
  }
}

// Нижняя часть футера
.footer-bottom {
  border-top: 1px solid $border-color;
  padding: 20px 0;

  .footer-bottom-content {
    display: flex;
    flex-direction: column;
    align-items: center;
    justify-content: space-between;

    @media (min-width: 768px) {
      flex-direction: row;
    }
  }
}

.copy-text {
  color: $logo-secondary-color;
  margin: 0;

  strong {
    color: $white;
  }
}

.policy-link {
  color: $logo-secondary-color;
  text-decoration: none;
  transition: color 0.3s;
  margin-top: 10px;

  @media (min-width: 768px) {
    margin-top: 0;
  }

  &:hover {
    color: $theme-color;
    text-decoration: underline;
  }
}
</style>