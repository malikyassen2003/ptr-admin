<script setup>
import { computed, ref, provide } from 'vue'
import { useRouter, useRoute } from 'vue-router'
import { useAdminAuthStore } from '@/stores/adminAuth'
import ToastNotification from '@/components/ToastNotification.vue'
import ConfirmDialog from '@/components/ConfirmDialog.vue'

const router = useRouter()
const route  = useRoute()
const auth   = useAdminAuthStore()
const user   = computed(() => auth.user)
const sidebarOpen = ref(true)

const toast   = ref(null)
const confirm = ref(null)

provide('toast',   { show: (...args) => toast.value?.show(...args) })
provide('confirm', { open: (...args) => confirm.value?.open(...args) })

const navGroups = [
  {
    label: null,
    items: [{ name: 'admin.dashboard', label: 'Dashboard', icon: 'dashboard' }],
  },
  {
    label: 'Catalogue',
    items: [
      { name: 'admin.products',   label: 'Products',   icon: 'products'   },
      { name: 'admin.categories', label: 'Categories', icon: 'categories' },
    ],
  },
  {
    label: 'Customers',
    items: [
      { name: 'admin.customers', label: 'All Customers', icon: 'customers' },
      { name: 'admin.orders',    label: 'Orders',        icon: 'orders'    },
    ],
  },
  {
    label: 'Marketing',
    items: [
      { name: 'admin.flash-deals', label: 'Flash Deals', icon: 'deals'      },
      { name: 'admin.coupons',     label: 'Coupons',     icon: 'coupon'     },
      { name: 'admin.newsletters', label: 'Newsletters', icon: 'newsletter' },
    ],
  },
  {
    label: 'Settings',
    items: [
      { name: 'admin.shipping', label: 'Shipping',     icon: 'shipping' },
      { name: 'admin.files',    label: 'File Manager', icon: 'files'    },
    ],
  },
]

const pageTitles = {
  'admin.dashboard':   'Dashboard',
  'admin.products':    'Products',
  'admin.categories':  'Categories',
  'admin.customers':   'Customers',
  'admin.orders':      'Orders',
  'admin.flash-deals': 'Flash Deals',
  'admin.coupons':     'Coupons',
  'admin.newsletters': 'Newsletters',
  'admin.shipping':    'Shipping',
  'admin.files':       'File Manager',
}
const pageTitle = computed(() => pageTitles[route.name] ?? 'Admin')

function isActive(name) { return route.name === name }

async function logout() {
  await auth.logout()
  router.push({ name: 'admin.login' })
}
</script>

<template>
  <div class="flex h-screen overflow-hidden" style="background:#f0f4f9;">

    <!-- Sidebar -->
    <aside class="flex-shrink-0 flex flex-col transition-all duration-300 relative z-10 shadow-xl"
      :style="{ width: sidebarOpen ? '248px' : '68px', background: '#0d1b2a' }">

      <div class="absolute inset-0 opacity-[0.025] pointer-events-none"
        style="background-image:linear-gradient(rgba(255,255,255,1) 1px,transparent 1px),linear-gradient(90deg,rgba(255,255,255,1) 1px,transparent 1px);background-size:24px 24px;"></div>

      <!-- Brand -->
      <div class="relative flex items-center px-4 h-16 border-b border-white/5 flex-shrink-0">
        <div class="w-9 h-9 rounded-xl flex items-center justify-center flex-shrink-0"
          style="background:linear-gradient(135deg,#1565C0,#1976D2);">
          <svg class="w-5 h-5 text-white" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
            <path stroke-linecap="round" stroke-linejoin="round" d="M13.5 21v-7.5a.75.75 0 0 1 .75-.75h3a.75.75 0 0 1 .75.75V21m-4.5 0H2.36m11.14 0H18m0 0h3.64m-1.39 0V9.349M3.75 21V9.349m0 0a3.001 3.001 0 0 0 3.75-.615A2.993 2.993 0 0 0 9.75 9.75c.896 0 1.7-.393 2.25-1.016a2.993 2.993 0 0 0 2.25 1.016c.896 0 1.7-.393 2.25-1.015a3.001 3.001 0 0 0 3.75.614m-16.5 0a3.004 3.004 0 0 1-.621-4.72l1.189-1.19A1.5 1.5 0 0 1 5.378 3h13.243a1.5 1.5 0 0 1 1.06.44l1.19 1.189a3 3 0 0 1-.621 4.72" />
          </svg>
        </div>
        <div v-if="sidebarOpen" class="ml-3 min-w-0">
          <p class="text-white text-sm font-semibold whitespace-nowrap">Malik Electronics</p>
          <p class="text-xs whitespace-nowrap" style="color:rgba(255,255,255,0.3);">Admin Panel</p>
        </div>
        <button @click="sidebarOpen=!sidebarOpen"
          class="ml-auto p-1.5 rounded-lg hover:bg-white/5 transition-colors"
          style="color:rgba(255,255,255,0.3);"
          :class="!sidebarOpen?'mx-auto':''">
          <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
            <path stroke-linecap="round" stroke-linejoin="round" d="M3.75 6.75h16.5M3.75 12h16.5M3.75 17.25h16.5" />
          </svg>
        </button>
      </div>

      <!-- Nav -->
      <nav class="relative flex-1 overflow-y-auto px-2 py-3 space-y-0.5">
        <template v-for="group in navGroups" :key="group.label ?? 'main'">
          <p v-if="group.label && sidebarOpen"
            class="text-xs font-semibold uppercase px-3 pt-4 pb-1"
            style="color:rgba(255,255,255,0.2);letter-spacing:0.1em;">
            {{ group.label }}
          </p>
          <div v-else-if="group.label && !sidebarOpen" class="pt-3 pb-1 px-2">
            <div class="h-px bg-white/10"></div>
          </div>

          <button
            v-for="item in group.items" :key="item.label"
            @click="item.name ? $router.push({ name: item.name }) : null"
            class="w-full flex items-center rounded-xl transition-all duration-150 relative cursor-pointer"
            :class="[sidebarOpen ? 'px-3 py-2.5 gap-3' : 'py-2.5 justify-center']"
            :style="isActive(item.name) ? 'background:rgba(21,101,192,0.25);' : ''"
          >
            <div class="absolute left-0 top-1/2 -translate-y-1/2 w-0.5 h-5 rounded-r transition-all"
              :style="isActive(item.name) ? 'background:#1565C0;' : 'background:transparent;'"></div>

            <div class="flex-shrink-0 w-8 h-8 rounded-lg flex items-center justify-center transition-all"
              :style="isActive(item.name) ? 'background:#1565C0;color:white;' : 'color:rgba(255,255,255,0.4);'">
              <svg v-if="item.icon==='dashboard'" class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke-width="1.8" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M3.75 6A2.25 2.25 0 0 1 6 3.75h2.25A2.25 2.25 0 0 1 10.5 6v2.25a2.25 2.25 0 0 1-2.25 2.25H6a2.25 2.25 0 0 1-2.25-2.25V6ZM3.75 15.75A2.25 2.25 0 0 1 6 13.5h2.25a2.25 2.25 0 0 1 2.25 2.25V18a2.25 2.25 0 0 1-2.25 2.25H6A2.25 2.25 0 0 1 3.75 18v-2.25ZM13.5 6a2.25 2.25 0 0 1 2.25-2.25H18A2.25 2.25 0 0 1 20.25 6v2.25A2.25 2.25 0 0 1 18 10.5h-2.25a2.25 2.25 0 0 1-2.25-2.25V6ZM13.5 15.75a2.25 2.25 0 0 1 2.25-2.25H18a2.25 2.25 0 0 1 2.25 2.25V18A2.25 2.25 0 0 1 18 20.25h-2.25A2.25 2.25 0 0 1 13.5 18v-2.25Z" /></svg>
              <svg v-else-if="item.icon==='products'" class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke-width="1.8" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="m21 7.5-9-5.25L3 7.5m18 0-9 5.25m9-5.25v9l-9 5.25M3 7.5l9 5.25M3 7.5v9l9 5.25m0-9v9" /></svg>
              <svg v-else-if="item.icon==='categories'" class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke-width="1.8" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M9.568 3H5.25A2.25 2.25 0 0 0 3 5.25v4.318c0 .597.237 1.17.659 1.591l9.581 9.581c.699.699 1.78.872 2.607.33a18.095 18.095 0 0 0 5.223-5.223c.542-.827.369-1.908-.33-2.607L11.16 3.66A2.25 2.25 0 0 0 9.568 3Z" /><path stroke-linecap="round" stroke-linejoin="round" d="M6 6h.008v.008H6V6Z" /></svg>
              <svg v-else-if="item.icon==='customers'" class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke-width="1.8" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M15 19.128a9.38 9.38 0 0 0 2.625.372 9.337 9.337 0 0 0 4.121-.952 4.125 4.125 0 0 0-7.533-2.493M15 19.128v-.003c0-1.113-.285-2.16-.786-3.07M15 19.128v.106A12.318 12.318 0 0 1 8.624 21c-2.331 0-4.512-.645-6.374-1.766l-.001-.109a6.375 6.375 0 0 1 11.964-3.07M12 6.375a3.375 3.375 0 1 1-6.75 0 3.375 3.375 0 0 1 6.75 0Zm8.25 2.25a2.625 2.625 0 1 1-5.25 0 2.625 2.625 0 0 1 5.25 0Z" /></svg>
              <svg v-else-if="item.icon==='orders'" class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke-width="1.8" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M9 12h3.75M9 15h3.75M9 18h3.75m3 .75H18a2.25 2.25 0 0 0 2.25-2.25V6.108c0-1.135-.845-2.098-1.976-2.192a48.424 48.424 0 0 0-1.123-.08m-5.801 0c-.065.21-.1.433-.1.664 0 .414.336.75.75.75h4.5a.75.75 0 0 0 .75-.75 2.25 2.25 0 0 0-.1-.664m-5.8 0A2.251 2.251 0 0 1 13.5 2.25H15c1.012 0 1.867.668 2.15 1.586m-5.8 0c-.376.023-.75.05-1.124.08C9.095 4.01 8.25 4.973 8.25 6.108V8.25m0 0H4.875c-.621 0-1.125.504-1.125 1.125v11.25c0 .621.504 1.125 1.125 1.125h9.75c.621 0 1.125-.504 1.125-1.125V9.375c0-.621-.504-1.125-1.125-1.125H8.25Z" /></svg>
              <svg v-else-if="item.icon==='deals'" class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke-width="1.8" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M3.75 13.5l10.5-11.25L12 10.5h8.25L9.75 21.75 12 13.5H3.75z" /></svg>
              <svg v-else-if="item.icon==='coupon'" class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke-width="1.8" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M9 14.25l6-6m4.5-3.493V21.75l-3.75-1.5-3.75 1.5-3.75-1.5-3.75 1.5V4.757c0-1.108.806-2.057 1.907-2.185a48.507 48.507 0 0 1 11.186 0c1.1.128 1.907 1.077 1.907 2.185Z" /></svg>
              <svg v-else-if="item.icon==='newsletter'" class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke-width="1.8" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M21.75 6.75v10.5a2.25 2.25 0 0 1-2.25 2.25h-15a2.25 2.25 0 0 1-2.25-2.25V6.75m19.5 0A2.25 2.25 0 0 0 19.5 4.5h-15a2.25 2.25 0 0 0-2.25 2.25m19.5 0v.243a2.25 2.25 0 0 1-1.07 1.916l-7.5 4.615a2.25 2.25 0 0 1-2.36 0L3.32 8.91a2.25 2.25 0 0 1-1.07-1.916V6.75" /></svg>
              <svg v-else-if="item.icon==='shipping'" class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke-width="1.8" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M8.25 18.75a1.5 1.5 0 0 1-3 0m3 0a1.5 1.5 0 0 0-3 0m3 0h6m-9 0H3.375a1.125 1.125 0 0 1-1.125-1.125V14.25m17.25 4.5a1.5 1.5 0 0 1-3 0m3 0a1.5 1.5 0 0 0-3 0m3 0h1.125c.621 0 1.129-.504 1.09-1.124a17.902 17.902 0 0 0-3.213-9.193 2.056 2.056 0 0 0-1.58-.86H14.25M16.5 18.75h-2.25m0-11.177v-.958c0-.568-.422-1.048-.987-1.106a48.554 48.554 0 0 0-10.026 0 1.106 1.106 0 0 0-.987 1.106v7.635m12-6.677v6.677m0 4.5v-4.5m0 0h-12" /></svg>
              <svg v-else class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke-width="1.8" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M2.25 12.75V12A2.25 2.25 0 0 1 4.5 9.75h15A2.25 2.25 0 0 1 21.75 12v.75m-8.69-6.44-2.12-2.12a1.5 1.5 0 0 0-1.061-.44H4.5A2.25 2.25 0 0 0 2.25 6v12a2.25 2.25 0 0 0 2.25 2.25h15A2.25 2.25 0 0 0 21.75 18V9a2.25 2.25 0 0 0-2.25-2.25h-5.379a1.5 1.5 0 0 1-1.06-.44Z" /></svg>
            </div>

            <span v-if="sidebarOpen" class="text-sm font-medium whitespace-nowrap"
              :style="isActive(item.name) ? 'color:white;' : 'color:rgba(255,255,255,0.45);'">
              {{ item.label }}
            </span>
            <div v-if="isActive(item.name) && sidebarOpen" class="ml-auto w-1.5 h-1.5 rounded-full" style="background:#1565C0;"></div>
          </button>
        </template>
      </nav>

      <!-- User -->
      <div class="relative border-t border-white/5 p-3 flex-shrink-0">
        <div class="flex items-center gap-3 p-2 rounded-xl" style="background:rgba(255,255,255,0.04);">
          <div class="w-8 h-8 rounded-lg flex items-center justify-center text-xs font-bold text-white flex-shrink-0"
            style="background:linear-gradient(135deg,#1565C0,#0d47a1);">
            {{ user?.name?.charAt(0)?.toUpperCase() ?? 'A' }}
          </div>
          <div v-if="sidebarOpen" class="flex-1 min-w-0">
            <p class="text-white text-xs font-semibold truncate">{{ user?.name ?? 'Admin' }}</p>
            <p class="text-xs truncate" style="color:rgba(255,255,255,0.3);">{{ user?.email }}</p>
          </div>
          <button v-if="sidebarOpen" @click="logout"
            class="p-1.5 rounded-lg hover:bg-white/10 transition-colors flex-shrink-0"
            style="color:rgba(255,255,255,0.3);" title="Sign out">
            <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke-width="1.8" stroke="currentColor">
              <path stroke-linecap="round" stroke-linejoin="round" d="M15.75 9V5.25A2.25 2.25 0 0 0 13.5 3h-6a2.25 2.25 0 0 0-2.25 2.25v13.5A2.25 2.25 0 0 0 7.5 21h6a2.25 2.25 0 0 0 2.25-2.25V15m3 0 3-3m0 0-3-3m3 3H9" />
            </svg>
          </button>
        </div>
      </div>
    </aside>

    <!-- Main -->
    <div class="flex-1 flex flex-col min-w-0 overflow-hidden">
      <header class="flex-shrink-0 h-16 flex items-center justify-between px-8 bg-white border-b border-gray-100 shadow-sm">
        <div>
          <h1 class="text-base font-bold text-gray-900">{{ pageTitle }}</h1>
          <p class="text-xs text-gray-400">Malik Electronics · Amman, Jordan</p>
        </div>
        <div class="flex items-center gap-3">
          <span class="text-xs px-3 py-1.5 rounded-lg font-medium" style="background:#EFF6FF;color:#1565C0;">
            {{ new Date().toLocaleDateString('en-US',{weekday:'short',month:'short',day:'numeric'}) }}
          </span>
          <a href="/" class="text-xs px-3 py-1.5 rounded-lg font-medium text-gray-500 border border-gray-200 hover:bg-gray-50 transition-colors">← Store</a>
        </div>
      </header>
      <main class="flex-1 overflow-y-auto p-8" style="background:#f0f4f9;">
        <router-view />
      </main>
    </div>

    <!-- Global toast + confirm -->
    <ToastNotification ref="toast" />
    <ConfirmDialog ref="confirm" />
  </div>
</template>