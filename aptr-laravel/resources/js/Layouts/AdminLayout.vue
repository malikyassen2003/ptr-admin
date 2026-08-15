<script setup>
import { computed } from 'vue'
import { Link, usePage, router } from '@inertiajs/vue3'

defineProps({
  title: String,
})

const page = usePage()

// Safer user computed
const user = computed(() => page.props.auth?.user ?? null)

const navItems = [
  { routeName: 'admin.dashboard',        label: 'Dashboard'  },
  { routeName: 'admin.products.index',   label: 'Products'   },
  { routeName: 'admin.categories.index', label: 'Categories' },
]

function isActive(routeName) {
  return route().current(routeName)
}

function logout() {
  router.post(route('logout'))
}
</script>

<template>
  <div class="flex h-screen overflow-hidden bg-gray-50">

    <!-- ── Sidebar ─────────────────────────────────────────────── -->
    <aside
      class="w-64 flex-shrink-0 flex flex-col"
      style="background: #0d1b2a;"
    >
      <!-- Brand -->
      <div class="flex items-center gap-3 px-5 py-5 border-b border-white/10">
        <div
          class="w-9 h-9 rounded-xl flex items-center justify-center flex-shrink-0"
          style="background: #1565C0;"
        >
          <svg class="w-5 h-5 text-white" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
            <path stroke-linecap="round" stroke-linejoin="round"
              d="M13.5 21v-7.5a.75.75 0 0 1 .75-.75h3a.75.75 0 0 1 .75.75V21m-4.5 0H2.36m11.14 0H18m0 0h3.64m-1.39 0V9.349M3.75 21V9.349m0 0a3.001 3.001 0 0 0 3.75-.615A2.993 2.993 0 0 0 9.75 9.75c.896 0 1.7-.393 2.25-1.016a2.993 2.993 0 0 0 2.25 1.016c.896 0 1.7-.393 2.25-1.015a3.001 3.001 0 0 0 3.75.614m-16.5 0a3.004 3.004 0 0 1-.621-4.72l1.189-1.19A1.5 1.5 0 0 1 5.378 3h13.243a1.5 1.5 0 0 1 1.06.44l1.19 1.189a3 3 0 0 1-.621 4.72M6.75 18h3.75a.75.75 0 0 0 .75-.75V13.5a.75.75 0 0 0-.75-.75H6.75a.75.75 0 0 0-.75.75v3.75c0 .414.336.75.75.75Z" />
          </svg>
        </div>
        <div class="min-w-0">
          <p class="text-white text-sm font-bold leading-tight tracking-wide">Malik Electronics</p>
          <p class="text-white/40 text-xs">Admin Panel</p>
        </div>
      </div>

      <!-- Nav -->
      <nav class="flex-1 px-3 py-4 space-y-0.5">

        <!-- Dashboard -->
        <Link
          :href="route('admin.dashboard')"
          class="flex items-center gap-3 px-3 py-2.5 rounded-lg text-sm font-medium transition-all group"
          :class="isActive('admin.dashboard')
            ? 'text-white shadow-sm'
            : 'text-white/50 hover:text-white hover:bg-white/5'"
          :style="isActive('admin.dashboard') ? 'background: #1565C0;' : ''"
        >
          <svg class="w-[18px] h-[18px] flex-shrink-0" fill="none" viewBox="0 0 24 24" stroke-width="1.6" stroke="currentColor">
            <path stroke-linecap="round" stroke-linejoin="round"
              d="M3.75 6A2.25 2.25 0 0 1 6 3.75h2.25A2.25 2.25 0 0 1 10.5 6v2.25a2.25 2.25 0 0 1-2.25 2.25H6a2.25 2.25 0 0 1-2.25-2.25V6ZM3.75 15.75A2.25 2.25 0 0 1 6 13.5h2.25a2.25 2.25 0 0 1 2.25 2.25V18a2.25 2.25 0 0 1-2.25 2.25H6A2.25 2.25 0 0 1 3.75 18v-2.25ZM13.5 6a2.25 2.25 0 0 1 2.25-2.25H18A2.25 2.25 0 0 1 20.25 6v2.25A2.25 2.25 0 0 1 18 10.5h-2.25a2.25 2.25 0 0 1-2.25-2.25V6ZM13.5 15.75a2.25 2.25 0 0 1 2.25-2.25H18a2.25 2.25 0 0 1 2.25 2.25V18A2.25 2.25 0 0 1 18 20.25h-2.25A2.25 2.25 0 0 1 13.5 18v-2.25Z" />
          </svg>
          Dashboard
        </Link>

        <!-- Products -->
        <Link
          :href="route('admin.products.index')"
          class="flex items-center gap-3 px-3 py-2.5 rounded-lg text-sm font-medium transition-all"
          :class="isActive('admin.products.index')
            ? 'text-white shadow-sm'
            : 'text-white/50 hover:text-white hover:bg-white/5'"
          :style="isActive('admin.products.index') ? 'background: #1565C0;' : ''"
        >
          <svg class="w-[18px] h-[18px] flex-shrink-0" fill="none" viewBox="0 0 24 24" stroke-width="1.6" stroke="currentColor">
            <path stroke-linecap="round" stroke-linejoin="round"
              d="m21 7.5-9-5.25L3 7.5m18 0-9 5.25m9-5.25v9l-9 5.25M3 7.5l9 5.25M3 7.5v9l9 5.25m0-9v9" />
          </svg>
          Products
        </Link>

        <!-- Categories -->
        <Link
          :href="route('admin.categories.index')"
          class="flex items-center gap-3 px-3 py-2.5 rounded-lg text-sm font-medium transition-all"
          :class="isActive('admin.categories.index')
            ? 'text-white shadow-sm'
            : 'text-white/50 hover:text-white hover:bg-white/5'"
          :style="isActive('admin.categories.index') ? 'background: #1565C0;' : ''"
        >
          <svg class="w-[18px] h-[18px] flex-shrink-0" fill="none" viewBox="0 0 24 24" stroke-width="1.6" stroke="currentColor">
            <path stroke-linecap="round" stroke-linejoin="round"
              d="M9.568 3H5.25A2.25 2.25 0 0 0 3 5.25v4.318c0 .597.237 1.17.659 1.591l9.581 9.581c.699.699 1.78.872 2.607.33a18.095 18.095 0 0 0 5.223-5.223c.542-.827.369-1.908-.33-2.607L11.16 3.66A2.25 2.25 0 0 0 9.568 3Z" />
            <path stroke-linecap="round" stroke-linejoin="round" d="M6 6h.008v.008H6V6Z" />
          </svg>
          Categories
        </Link>

        <!-- Divider + Coming Soon -->
        <div class="pt-4 pb-1 px-3">
          <p class="text-white/20 text-xs font-semibold uppercase tracking-widest">Coming Soon</p>
        </div>

        <div class="flex items-center gap-3 px-3 py-2.5 rounded-lg text-white/20 text-sm font-medium cursor-not-allowed select-none">
          <svg class="w-[18px] h-[18px] flex-shrink-0" fill="none" viewBox="0 0 24 24" stroke-width="1.6" stroke="currentColor">
            <path stroke-linecap="round" stroke-linejoin="round"
              d="M9 12h3.75M9 15h3.75M9 18h3.75m3 .75H18a2.25 2.25 0 0 0 2.25-2.25V6.108c0-1.135-.845-2.098-1.976-2.192a48.424 48.424 0 0 0-1.123-.08m-5.801 0c-.065.21-.1.433-.1.664 0 .414.336.75.75.75h4.5a.75.75 0 0 0 .75-.75 2.25 2.25 0 0 0-.1-.664m-5.8 0A2.251 2.251 0 0 1 13.5 2.25H15c1.012 0 1.867.668 2.15 1.586m-5.8 0c-.376.023-.75.05-1.124.08C9.095 4.01 8.25 4.973 8.25 6.108V8.25m0 0H4.875c-.621 0-1.125.504-1.125 1.125v11.25c0 .621.504 1.125 1.125 1.125h9.75c.621 0 1.125-.504 1.125-1.125V9.375c0-.621-.504-1.125-1.125-1.125H8.25ZM6.75 12h.008v.008H6.75V12Zm0 3h.008v.008H6.75V15Zm0 3h.008v.008H6.75V18Z" />
          </svg>
          Orders
        </div>

        <div class="flex items-center gap-3 px-3 py-2.5 rounded-lg text-white/20 text-sm font-medium cursor-not-allowed select-none">
          <svg class="w-[18px] h-[18px] flex-shrink-0" fill="none" viewBox="0 0 24 24" stroke-width="1.6" stroke="currentColor">
            <path stroke-linecap="round" stroke-linejoin="round"
              d="M3 13.125C3 12.504 3.504 12 4.125 12h2.25c.621 0 1.125.504 1.125 1.125v6.75C7.5 20.496 6.996 21 6.375 21h-2.25A1.125 1.125 0 0 1 3 19.875v-6.75ZM9.75 8.625c0-.621.504-1.125 1.125-1.125h2.25c.621 0 1.125.504 1.125 1.125v11.25c0 .621-.504 1.125-1.125 1.125h-2.25a1.125 1.125 0 0 1-1.125-1.125V8.625ZM16.5 4.125c0-.621.504-1.125 1.125-1.125h2.25C20.496 3 21 3.504 21 4.125v15.75c0 .621-.504 1.125-1.125 1.125h-2.25a1.125 1.125 0 0 1-1.125-1.125V4.125Z" />
          </svg>
          Analytics
        </div>
      </nav>

      <!-- User + Logout -->
      <div class="px-3 pb-4 border-t border-white/10 pt-3">
        <div class="flex items-center gap-3 px-3 py-2 mb-1">
          <div
            class="w-8 h-8 rounded-full flex items-center justify-center text-xs font-bold text-white flex-shrink-0"
            style="background: #1565C0;"
          >
            {{ user?.name?.charAt(0)?.toUpperCase() ?? 'A' }}
          </div>
          <div class="flex-1 min-w-0">
            <p class="text-white text-xs font-medium truncate">{{ user?.name }}</p>
            <p class="text-white/35 text-xs truncate">{{ user?.email }}</p>
          </div>
        </div>
        <button
          @click="logout"
          class="flex w-full items-center gap-3 px-3 py-2 rounded-lg text-sm text-white/40 hover:text-white hover:bg-white/5 transition-all"
        >
          <svg class="w-[18px] h-[18px] flex-shrink-0" fill="none" viewBox="0 0 24 24" stroke-width="1.6" stroke="currentColor">
            <path stroke-linecap="round" stroke-linejoin="round"
              d="M15.75 9V5.25A2.25 2.25 0 0 0 13.5 3h-6a2.25 2.25 0 0 0-2.25 2.25v13.5A2.25 2.25 0 0 0 7.5 21h6a2.25 2.25 0 0 0 2.25-2.25V15m3 0 3-3m0 0-3-3m3 3H9" />
          </svg>
          Sign Out
        </button>
      </div>
    </aside>

    <!-- ── Main area ────────────────────────────────────────────── -->
    <div class="flex-1 flex flex-col min-w-0 overflow-hidden">

      <!-- Top bar -->
      <header class="flex-shrink-0 bg-white border-b border-gray-200 px-8 h-14 flex items-center justify-between">
        <h1 class="text-lg font-bold text-gray-900 tracking-tight">{{ title }}</h1>
        <span class="text-xs text-gray-400 font-medium">Malik Electronics · Amman, Jordan</span>
      </header>

      <!-- Page content -->
      <main class="flex-1 overflow-y-auto p-8" style="background: #f4f7fb;">
        <slot />
      </main>
    </div>

  </div>
</template>