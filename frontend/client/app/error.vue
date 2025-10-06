<template>
  <main class="page container">
    <div class="content-404">
      <h1 class="title">{{ error.statusCode || 500 }}</h1>
      <h2 class="sub-title">{{ error.statusCode === 404 ? 'Упс! Страница не найдена' : 'Упс! Что-то пошло не так' }}
      </h2>
      <p>{{ error.statusCode === 404
        ? 'К сожалению, запрашиваемая страница не существует или была перемещена.'
        : 'Произошла внутренняя ошибка сервера. Мы уже работаем над её устранением.' }}
      </p>
      <div class="buttons">
        <a class="btn btn-primary btn-outline-hover-dark" href="javascript:history.back()">
          <i class="fa fa-arrow-left"></i>&nbsp;Назад
        </a>
        <NuxtLink class="btn btn-dark btn-outline-hover-dark" to="/">
          <i class="fa fa-home"></i>&nbsp;На главную
        </NuxtLink>
      </div>
    </div>
  </main>
</template>

<script setup>
const props = defineProps({
  error: {
    type: Object,
    required: true
  }
})

useHead({
  title: `${props.error.statusCode} | AgatCeramic`,
})
</script>

<style lang="scss" scoped>
.page {
  min-height: 100vh;
  display: flex;
  flex-direction: column;
}

.content {
  flex: 1;
}

.content-404 {
  padding: 250px 0;

  @media #{$desktop-device} {
    padding: 100px 0;
  }

  @media #{$tablet-device, $large-mobile} {
    padding: 50px 0;
  }

  .title {
    font-size: 100px;
    font-weight: 400;
    line-height: 1;
    margin: 0;

    @media #{$desktop-device} {
      font-size: 60px;
    }

    @media #{$tablet-device, $large-mobile} {
      font-size: 60px;
    }
  }

  .sub-title {
    font-size: 64px;
    font-weight: 400;
    line-height: 1.2;
    margin-bottom: 30px;

    @media #{$desktop-device} {
      font-size: 50px;
      margin-bottom: 20px;
    }

    @media #{$tablet-device, $large-mobile} {
      font-size: 40px;
      margin-bottom: 20px;
    }
  }

  p {
    margin-bottom: 40px;

    @media #{$desktop-device} {
      margin-bottom: 30px;
    }

    @media #{$tablet-device, $large-mobile} {
      margin-bottom: 20px;
    }
  }

  .buttons {
    display: -webkit-box;
    display: -webkit-flex;
    display: -ms-flexbox;
    display: flex;
    -webkit-flex-wrap: wrap;
    -ms-flex-wrap: wrap;
    flex-wrap: wrap;
    margin-right: -20px;
    margin-bottom: -20px;

    .buttons .btn {
      margin-right: 20px;
      margin-bottom: 20px;
    }

    .btn-primary {
      border-color: $theme-color;
      background-color: $theme-color;
      color: $white;
      height: auto;
      width: auto;
      margin-right: 20px;
      margin-bottom: 20px;
    }

    .btn {
      font-weight: 400;
      line-height: 24px;
      padding: 15px 40px;
      text-align: center;
      white-space: nowrap;
      letter-spacing: 1px;
      text-transform: uppercase;
      border-radius: 500px;
      height: auto;
      width: auto;

      @media #{$tablet-device, $large-mobile} {
        padding: 10px 30px;
        font-size: 16px;
      }
    }

    .btn-dark {
      border-color: $black;
      background-color: $black;
      color: $white;
      margin-bottom: 20px;
    }

    .btn-outline-hover-dark:hover,
    .btn-outline-hover-dark:not(:disabled):not(.disabled).active {
      color: $black;
      border-color: $black;
      background-color: transparent;
    }
  }
}
</style>