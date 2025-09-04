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
              <li><a href="#"> Заказать звонок </a></li>
              <li>
                <a href="tel:+79999999999">
                  <i class="fa fa-phone"></i>
                  &nbsp;&nbsp;+7 (999) 999-99-99
                </a>
              </li>
              <li>
                <a href="mailto:zakaz@agatceramic.ru">
                  <i class="fa fa-envelope-o"></i>
                  &nbsp;&nbsp;zakaz@agatceramic.ru
                </a>
              </li>
            </ul>
          </div>
        </div>
      </div>
    </div>
  </div>
</template>
