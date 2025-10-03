<template>
  <div class="header-top">
    <div class="container">
      <div class="row justify-content-between align-items-center">
        <div class="col-auto">
          <div class="welcome-text">
            <p>Москва (Пн.-Пт. 10:00-18:00)</p>
          </div>
        </div>
        <div class="col d-none d-lg-block ps-0">
          <div class="top-nav">
            <ul>
              <li>
                <NuxtLink to="/delivery">Оплата и доставка</NuxtLink>
              </li>
              <li>
                <NuxtLink to="/return">Возврат и замена</NuxtLink>
              </li>
              <li><a href="#" class="modal-call" @click.prevent="openCallModal">Заказать звонок</a></li>
              <li>
                <a href="tel:+79999999999">
                  <i class="fa fa-phone"></i>
                  +7 (999) 999-99-99
                </a>
              </li>
              <li>
                <a href="mailto:zakaz@agatceramic.ru">
                  <i class="fa fa-envelope-o"></i>
                  zakaz@agatceramic.ru
                </a>
              </li>
            </ul>
          </div>
        </div>
      </div>
    </div>
  </div>
</template>

<style scoped lang="scss">
$font-size-base: 14px;
$font-size-small: 12px;
$line-height: 38px;
$spacing-between-items: 30px;
$spacing-between-items-mobile: 10px;

.header-top {
  background-color: $secondary;

  .welcome-text {
    display: flex;
    align-items: flex-start;

    @media #{$tablet-device, $large-mobile} {
      justify-content: center;
    }

    p {
      font-size: $font-size-base;
      color: $black;
      line-height: $line-height;

      @media #{$extra-small-mobile} {
        font-size: $font-size-small;
      }
    }
  }

  .top-nav {
    display: flex;
    flex-direction: column;
    align-items: flex-end;
    line-height: 1;

    ul {
      align-items: flex-end;
      display: flex;
      padding-left: 0;
      margin-bottom: 0;

      li {
        &:not(:last-child) {
          margin-right: $spacing-between-items;

          @media #{$desktop-device} {
            margin-right: $spacing-between-items-mobile;
          }
        }

        a {
          font-size: $font-size-base;
          color: $black;
          display: flex;
          flex-direction: row;
          justify-content: center;
          line-height: 1;
          text-decoration: none;

          &:hover {
            color: $theme-color;
          }

          i {
            font-size: 15px;
            color: $theme-color;
            margin-right: 5px;
          }
        }

        &.modal-call {
          color: $theme-color !important;
          font-weight: 500;

          &:hover {
            text-decoration: underline !important;
          }
        }
      }
    }
  }
}
</style>

<script setup>
import { inject } from 'vue'

const openCallModal = inject('openCallModal')
</script>