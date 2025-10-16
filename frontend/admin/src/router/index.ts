import { createRouter, createWebHistory } from 'vue-router'

const router = createRouter({
  history: createWebHistory(import.meta.env.BASE_URL + 'admin'),
  scrollBehavior(to, from, savedPosition) {
    return savedPosition || { left: 0, top: 0 }
  },
  routes: [
    {
      path: '/',
      name: 'Dashboard',
      component: () => import('../views/Ecommerce.vue'),
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
      path: '/error-404',
      name: '404 Error',
      component: () => import('../views/Errors/FourZeroFour.vue'),
      meta: {
        title: '404 Error',
      },
    },

    {
      path: '/signin',
      name: 'Signin',
      component: () => import('../views/Auth/Signin.vue'),
      meta: {
        title: 'Signin',
      },
    },
    {
      path: '/signup',
      name: 'Signup',
      component: () => import('../views/Auth/Signup.vue'),
      meta: {
        title: 'Signup',
      },
    },
  ],
})

export default router

router.beforeEach((to, from, next) => {
  document.title = `${to.meta.title} | AgatCeramic Admin`
  next()
})
