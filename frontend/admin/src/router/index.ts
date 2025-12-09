import { createRouter, createWebHistory } from 'vue-router'

const router = createRouter({
  history: createWebHistory(import.meta.env.BASE_URL),
  scrollBehavior(to, from, savedPosition) {
    return savedPosition || { left: 0, top: 0 }
  },
  routes: [
    {
      path: '/login',
      name: 'Auth',
      component: () => import('@/components/auth/LoginForm.vue'),
      meta: {
        title: 'Авторизация',
        requiresGuest: true
      },
    },
    {
      path: '/',
      name: 'Dashboard',
      component: () => import('../views/Dashboard.vue'),
      meta: {
        title: 'Статистика',
        requiresAuth: true
      },
    },
    {
      path: '/products',
      name: 'Goods',
      component: () => import('../views/Pages/product/Goods.vue'),
      meta: {
        title: 'Товары',
        requiresAuth: true
      },
    },
    {
      path: '/products/create',
      name: 'CreateProduct',
      component: () => import('../views/Pages/product/CreateProduct.vue'),
      meta: {
        title: 'Создание товара',
        requiresAuth: true
      },
    },
    {
      path: '/products/edit/:id',
      name: 'EditProduct',
      component: () => import('../views/Pages/product/EditProduct.vue'),
      meta: {
        title: 'Редактирование товара',
        requiresAuth: true
      },
    },
    {
      path: '/characteristics',
      name: 'Characteristics',
      component: () => import('../views/Pages/characteristics/Characteristics.vue'),
      meta: {
        title: 'Управление характеричтиками товара',
        requiresAuth: true
      },
    },
    {
      path: '/orders',
      name: 'Orders',
      component: () => import('../views/Pages/orders/Orders.vue'),
      meta: {
        title: 'Заказы',
        requiresAuth: true
      },
    },
    {
      path: '/calls',
      name: 'Calls',
      component: () => import('../views/Pages/calls/Calls.vue'),
      meta: {
        title: 'Обратные звонки',
        requiresAuth: true
      },
    },
    {
      path: '/settings',
      name: 'Settings',
      component: () => import('../views/Pages/settings/Settings.vue'),
      meta: {
        title: 'Настройки сайта',
        requiresAuth: true
      },
    },
  ],
})

export default router

router.beforeEach((to, from, next) => {
  // Установка заголовка страницы
  document.title = `${to.meta?.title || 'AgatCeramic Admin'} | AgatCeramic Admin`

  // Проверка авторизации
  const isAuthenticated = !!localStorage.getItem('auth_token') && localStorage.getItem('auth_token') !== 'undefined'

  // Если маршрут не найден, перенаправляем в зависимости от статуса авторизации
  if (to.matched.length === 0) {
    if (!isAuthenticated) {
      next({ name: 'Auth' })
    } else {
      next({ name: 'Dashboard' })
    }
    return
  }

  if (to.meta.requiresAuth && !isAuthenticated) {
    next({ name: 'Auth' })
  } else if (to.meta.requiresGuest && isAuthenticated) {
    next({ name: 'Dashboard' })
  } else {
    next()
  }
})
