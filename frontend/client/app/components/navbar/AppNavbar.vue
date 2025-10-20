<template>
  <nav class="header-nav-area d-none d-lg-block sticky-nav">
    <div class="container">
      <div class="header-nav">
        <div class="main-menu position-relative">
          <ul>
            <li class="dropdown">
              <a href="#"> Каталог <i class="fa fa-angle-down"></i> </a>
              <ul class="sub-menu">
                <li>
                  <NuxtLink to="/category/klinker">Клинкер</NuxtLink>
                </li>
                <li>
                  <NuxtLink to="/category/stupeni">Ступени</NuxtLink>
                </li>
                <li>
                  <NuxtLink to="/category/zatirka-dlia-plitki">Затирка для плитки</NuxtLink>
                </li>
                <li>
                  <NuxtLink to="/category/kleevye-smesi">Клеевые смеси</NuxtLink>
                </li>
                <li class="dropdown position-static">
                  <NuxtLink to="/category/santexnika">
                    Сантехника
                    <i class="fa fa-angle-right"></i>
                  </NuxtLink>
                  <ul class="sub-menu sub-menu-2">
                    <li v-for="subcategory in store.santexnikaSubcategories" :key="subcategory.id">
                      <NuxtLink :to="`/category/${subcategory.slug}`">{{ subcategory.name }}</NuxtLink>
                    </li>
                  </ul>
                </li>
              </ul>
            </li>
            <li class="dropdown">
              <a href="#">Услуги <i class="fa fa-angle-down"></i></a>
              <ul class="sub-menu">
                <li>
                  <NuxtLink to="/rezka">Резка</NuxtLink>
                </li>
                <li>
                  <NuxtLink to="/rospis">Роспись плитки</NuxtLink>
                </li>
              </ul>
            </li>
            <li>
              <NuxtLink to="/category/keramogranit">Керамогранит</NuxtLink>
            </li>
            <li>
              <NuxtLink to="/category/plitka">Плитка</NuxtLink>
            </li>
            <li>
              <NuxtLink to="/category/mozaika">Мозаика</NuxtLink>
            </li>
            <li>
              <NuxtLink to="/partnerships" target="_blank">Дизайнерам</NuxtLink>
            </li>
            <li>
              <NuxtLink to="/contacts">Контакты</NuxtLink>
            </li>
            <li><NuxtLink to="/sale" class="sale">Распродажа</NuxtLink></li>
          </ul>
        </div>
      </div>
    </div>
  </nav>
</template>
<script setup>
import { onMounted, ref, provide } from 'vue'
import { useCategoryStore } from '~/stores/useCategoryStore'

// Ссылка на компонент модального окна
const modalCallRef = ref(null)

const store = useCategoryStore()

// Функция для открытия модального окна
const openCallModal = () => {
  if (modalCallRef.value) {
    modalCallRef.value.openModal()
  }
}

// Предоставляем функцию для дочерних компонентов
provide('openCallModal', openCallModal)

onMounted(() => {
  const offCanvasOverlay = document.querySelector(".offcanvas-overlay");

  document.querySelectorAll(".offcanvas-overlay").forEach(el => {
    el.addEventListener("click", function (e) {
      e.preventDefault();
      document.body.classList.remove("offcanvas-open");
      document.querySelectorAll('.offcanvas').forEach(el => {
        el.classList.remove("offcanvas-open");
      });
      offCanvasOverlay.style.display = "none";
    });
  });

  // Загружаем подкатегории сантехники при монтировании компонента
  store.fetchSantexnikaSubcategories()
});
</script>

<style scoped lang="scss">
.header-nav-area {
  background-color: $secondary;
}

.main-menu {
  display: flex;
  justify-content: center;
  align-items: center;

  & ul {
    display: flex;
    flex-wrap: wrap;

    margin-bottom: 0;
    padding-left: 0;

    list-style: none;

    & li {
      &+li {
        & {
          margin-left: 50px;
        }

        @media #{$desktop-device} {
          margin-left: 30px;
        }
      }

      & a {
        font-size: 14px;
        font-weight: 400;
        text-decoration: none;
        text-transform: uppercase;
        color: $black;
        display: block;
        position: relative;
        line-height: 50px;
      }

      & .sale {
        color: $red;
      }

      &.dropdown {
        position: relative;

        & ul {
          &.sub-menu {
            position: absolute;
            z-index: 9;
            text-align: left;
            opacity: 0;
            visibility: hidden;
            min-width: 250px;
            left: auto !important;
            background: $white;
            display: block;
            padding: 20px 0;
            box-shadow: -1px 10px 80px -15px rgba(0, 0, 0, 0.3);
            -o-transform-origin: 0% 0%;
            -ms-transform-origin: 0% 0%;
            -moz-transform-origin: 0% 0%;
            -webkit-transform-origin: 0% 0%;
            transform-style: preserve-3d;
            -o-transform-style: preserve-3d;
            -moz-transform-style: preserve-3d;
            -webkit-transform-style: preserve-3d;
            transform: rotateX(-75deg);
            -o-transform: rotateX(-75deg);
            -moz-transform: rotateX(-75deg);
            -webkit-transform: rotateX(-75deg);
            -o-transition: -o-transform 0.3s, opacity 0.3s;
            -ms-transition: -ms-transform 0.3s, opacity 0.3s;
            -moz-transition: -moz-transform 0.3s, opacity 0.3s;
            -webkit-transition: -webkit-transform 0.3s, opacity 0.3s;
          }

          & li {
            padding: 0;
            margin: 0;
            display: block;

            &:hover,
            &.active {
              &>a {
                color: $theme-color !important;
              }
            }

            & a {
              display: block;
              line-height: 20px;
              padding: 12px 0px 12px 20px;
              font-weight: 400;
              font-size: 14px;
              color: $link-secondary-color;
              text-transform: lowercase;

              &::first-letter {
                text-transform: uppercase;
              }

              &:hover {
                padding-left: 25px;
              }
            }
          }
        }

        &:hover {
          & .sub-menu {
            opacity: 1;
            visibility: visible;
            transform: rotateX(0deg);
            -o-transform: rotateX(0deg);
            -moz-transform: rotateX(0deg);
            -webkit-transform: rotateX(0deg);
            -o-transition: -o-transform 0.3s, opacity 0.3s;
            -ms-transition: -ms-transform 0.3s, opacity 0.3s;
            -moz-transition: -moz-transform 0.3s, opacity 0.3s;
            -webkit-transition: -webkit-transform 0.3s, opacity 0.3s;
          }
        }

        &:hover {
          & .mega-menu {
            opacity: 1;
            visibility: visible;
            transform: rotateX(0deg);
            -o-transform: rotateX(0deg);
            -moz-transform: rotateX(0deg);
            -webkit-transform: rotateX(0deg);
            -o-transition: -o-transform 0.3s, opacity 0.3s;
            -ms-transition: -ms-transform 0.3s, opacity 0.3s;
            -moz-transition: -moz-transform 0.3s, opacity 0.3s;
            -webkit-transition: -webkit-transform 0.3s, opacity 0.3s;
          }
        }
      }

      & li {
        &.position-static {
          position: relative !important;

          & i {
            position: absolute;
            right: 20px;
          }

          & ul {
            &.sub-menu.sub-menu-2 {
              & {
                left: 100% !important;
                transform: translateY(-30px);
                opacity: 0;
                visibility: hidden;
              }

              @media #{$laptop-device} {
                left: 95% !important;
                min-width: 230px;
              }

              @media #{$desktop-device} {
                left: auto !important;
                right: 100%;
              }
            }
          }

          &:hover {
            .sub-menu.sub-menu-2 {
              transform: translateY(-185px);
              opacity: 1;
              visibility: visible;
            }
          }
        }
      }
    }
  }
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
  background-color: $theme-color;

  &.style-1 {
    background-color: $secondary;
    padding: 20px 0;
  }

  & a {
    color: $white;
  }

  & .sale {
    color: $black;
  }
}
</style>