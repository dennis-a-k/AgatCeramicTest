import { createRouter, createWebHistory } from 'vue-router'

const router = createRouter({
  history: createWebHistory(import.meta.env.BASE_URL),
  scrollBehavior(to, from, savedPosition) {
    return savedPosition || { left: 0, top: 0 }
  },
  routes: [
    {
      path: '/',
      name: 'Dashboard',
      component: () => import('../views/Dashboard.vue'),
      meta: {
        title: 'Статистика',
      },
    },
    {
      path: '/products',
      name: 'Goods',
      component: () => import('../views/Pages/product/Goods.vue'),
      meta: {
        title: 'Товары',
      },
    },
    {
      path: '/products/create',
      name: 'CreateProduct',
      component: () => import('../views/Pages/product/CreateProduct.vue'),
      meta: {
        title: 'Создание товара',
      },
    },
    {
      path: '/products/edit/:id',
      name: 'EditProduct',
      component: () => import('../views/Pages/product/EditProduct.vue'),
      meta: {
        title: 'Редактирование товара',
      },
    },
    {
      path: '/characteristics',
      name: 'Characteristics',
      component: () => import('../views/Pages/characteristics/Characteristics.vue'),
      meta: {
        title: 'Управление характеричтиками товара',
      },
    },
    {
      path: '/orders',
      name: 'Orders',
      component: () => import('../views/Pages/orders/Orders.vue'),
      meta: {
        title: 'Заказы',
      },
    },
    {
      path: '/calls',
      name: 'Calls',
      component: () => import('../views/Pages/calls/Calls.vue'),
      meta: {
        title: 'Обратные звонки',
      },
    },
    {
      path: '/settings',
      name: 'Calls',
      component: () => import('../views/Pages/settings/Settings.vue'),
      meta: {
        title: 'Настройки сайта',
      },
    },
  ],
})

export default router

router.beforeEach((to, from, next) => {
  document.title = `${to.meta.title} | AgatCeramic Admin`
  next()
})
