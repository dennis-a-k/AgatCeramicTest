<template>
  <nav class="header-nav-area d-none d-lg-block sticky-nav">
    <div class="container">
      <div class="header-nav">
        <div class="main-menu position-relative">
          <ul>
            <li class="dropdown">
              <a href="#"> Каталог <i class="fa fa-angle-down"></i> </a>
              <ul class="sub-menu">
                <li><a href="index.html">Клинкер</a></li>
                <li><a href="index.html">Ступени</a></li>
                <li><a href="index.html">Затирка для плитки</a></li>
                <li><a href="index.html">Клеевые смеси</a></li>
                <li class="dropdown position-static">
                  <a href="blog-grid-left-sidebar.html">
                    Сантехника
                    <i class="fa fa-angle-right"></i>
                  </a>
                  <ul class="sub-menu sub-menu-2">
                    <li><a href="blog-grid.html">Ванны</a></li>
                    <li><a href="blog-grid-left-sidebar.html">Смесители</a></li>
                    <li><a href="blog-grid-right-sidebar.html">Унитазы</a></li>
                  </ul>
                </li>
              </ul>
            </li>
            <li class="dropdown">
              <a href="#">Услуги <i class="fa fa-angle-down"></i></a>
              <ul class="sub-menu">
                <li><NuxtLink to="/rezka">Резка</NuxtLink></li>
                <li><NuxtLink to="/rospis">Роспись плитки</NuxtLink></li>
              </ul>
            </li>
            <li><a href="about.html">Керамогранит</a></li>
            <li><a href="about.html">Плитка</a></li>
            <li><a href="about.html">Мозаика</a></li>
            <li><a href="about.html">Дизайнерам</a></li>
            <li><NuxtLink to="/contacts">Контакты</NuxtLink></li>
            <li><a href="about.html" class="sail">Распродажа</a></li>
          </ul>
        </div>
      </div>
    </div>
  </nav>
</template>
