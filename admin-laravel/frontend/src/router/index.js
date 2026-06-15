// FILE: src/router/index.js
import { createRouter, createWebHistory } from 'vue-router'
import { useAdminAuthStore } from '@/stores/adminAuth'

const routes = [
  { path: '/', redirect: '/admin/dashboard' },
  {
    path: '/admin/login',
    name: 'admin.login',
    component: () => import('@/views/admin/auth/AdminLogin.vue'),
  },
  {
    path: '/admin',
    component: () => import('@/layouts/AdminLayout.vue'),
    meta: { requiresAdmin: true },
    children: [
      { path: '', redirect: '/admin/dashboard' },
      {
        path: 'dashboard',
        name: 'admin.dashboard',
        component: () => import('@/views/admin/dashboard/AdminDashboard.vue'),
      },
      {
        path: 'products',
        name: 'admin.products',
        component: () => import('@/views/admin/products/AdminProducts.vue'),
      },
      {
        path: 'products/:id',
        name: 'admin.products.show',
        component: () => import('@/views/admin/products/Adminproductdetail.vue'),
      },
      {
        path: 'categories',
        name: 'admin.categories',
        component: () => import('@/views/admin/categories/AdminCategories.vue'),
      },
      {
        path: 'customers',
        name: 'admin.customers',
        component: () => import('@/views/admin/customers/AdminCustomers.vue'),
      },
      {
        path: 'orders',
        name: 'admin.orders',
        component: () => import('@/views/admin/orders/AdminOrders.vue'),
      },
      {
        path: 'flash-deals',
        name: 'admin.flash-deals',
        component: () => import('@/views/admin/marketing/FlashDeals.vue'),
      },
      {
        path: 'coupons',
        name: 'admin.coupons',
        component: () => import('@/views/admin/marketing/Coupons.vue'),
      },
      {
        path: 'newsletters',
        name: 'admin.newsletters',
        component: () => import('@/views/admin/marketing/Newsletters.vue'),
      },
      {
        path: 'shipping',
        name: 'admin.shipping',
        component: () => import('@/views/admin/shipping/Shipping.vue'),
      },
      {
        path: 'files',
        name: 'admin.files',
        component: () => import('@/views/admin/files/FileManager.vue'),
      },
    ],
  },
  { path: '/:pathMatch(.*)*', redirect: '/admin/dashboard' },
]

const router = createRouter({
  history: createWebHistory(),
  routes,
})

router.beforeEach((to) => {
  if (to.meta.requiresAdmin) {
    const auth = useAdminAuthStore()
    if (!auth.isAuthenticated) return { name: 'admin.login' }
  }
  return true
})

export default router