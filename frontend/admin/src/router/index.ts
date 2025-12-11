import { createRouter, createWebHistory } from 'vue-router'

declare module 'vue-router' {
  interface RouteMeta {
    title?: string
    requiresAuth?: boolean
    requiresGuest?: boolean
    roles?: string[]
  }
}

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
        requiresAuth: true,
        roles: ['admin', 'moderator', 'user']
      },
    },
    {
      path: '/products',
      name: 'Goods',
      component: () => import('../views/Pages/product/Goods.vue'),
      meta: {
        title: 'Товары',
        requiresAuth: true,
        roles: ['admin', 'moderator']
      },
    },
    {
      path: '/products/create',
      name: 'CreateProduct',
      component: () => import('../views/Pages/product/CreateProduct.vue'),
      meta: {
        title: 'Создание товара',
        requiresAuth: true,
        roles: ['admin', 'moderator']
      },
    },
    {
      path: '/products/edit/:id',
      name: 'EditProduct',
      component: () => import('../views/Pages/product/EditProduct.vue'),
      meta: {
        title: 'Редактирование товара',
        requiresAuth: true,
        roles: ['admin', 'moderator']
      },
    },
    {
      path: '/characteristics',
      name: 'Characteristics',
      component: () => import('../views/Pages/characteristics/Characteristics.vue'),
      meta: {
        title: 'Управление характеричтиками товара',
        requiresAuth: true,
        roles: ['admin']
      },
    },
    {
      path: '/orders',
      name: 'Orders',
      component: () => import('../views/Pages/orders/Orders.vue'),
      meta: {
        title: 'Заказы',
        requiresAuth: true,
        roles: ['admin', 'moderator']
      },
    },
    {
      path: '/calls',
      name: 'Calls',
      component: () => import('../views/Pages/calls/Calls.vue'),
      meta: {
        title: 'Обратные звонки',
        requiresAuth: true,
        roles: ['admin', 'moderator']
      },
    },
    {
      path: '/settings',
      name: 'Settings',
      component: () => import('../views/Pages/settings/Settings.vue'),
      meta: {
        title: 'Настройки сайта',
        requiresAuth: true,
        roles: ['admin']
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

  // Получение роли пользователя
  const getUserRole = () => {
    const user = localStorage.getItem('auth_user')
    if (user) {
      try {
        const userData = JSON.parse(user)
        return userData.role
      } catch {
        return null
      }
    }
    return null
  }

  const userRole = getUserRole()

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
  } else if (to.meta.requiresAuth && to.meta.roles && !to.meta.roles.includes(userRole)) {
    // Если пользователь не имеет нужной роли, перенаправляем на Dashboard
    next({ name: 'Dashboard' })
  } else {
    next()
  }
})
