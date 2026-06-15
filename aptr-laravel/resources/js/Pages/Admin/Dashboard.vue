<script setup>
import { computed } from 'vue'
import { Head } from '@inertiajs/vue3'
import AdminLayout from '@/Layouts/AdminLayout.vue'

const props = defineProps({
  totalProducts:   Number,
  totalCategories: Number,
  featured:        Number,
  bestSelling:     Number,
  lowStock:        Number,
  outOfStock:      Number,
  byCategory:      Array,
})

const maxCount = computed(() =>
  props.byCategory.length ? Math.max(...props.byCategory.map(c => c.count)) : 1
)

const statCards = computed(() => [
  {
    label: 'Total Products',
    value: props.totalProducts,
    bg:    '#EFF6FF',
    color: '#1565C0',
    icon:  'products',
  },
  {
    label: 'Categories',
    value: props.totalCategories,
    bg:    '#F0F9FF',
    color: '#0369A1',
    icon:  'categories',
  },
  {
    label: 'Featured',
    value: props.featured,
    bg:    '#F5F3FF',
    color: '#7C3AED',
    icon:  'star',
  },
  {
    label: 'Best Selling',
    value: props.bestSelling,
    bg:    '#F0FDF4',
    color: '#15803D',
    icon:  'trending',
  },
  {
    label: 'Low Stock',
    value: props.lowStock,
    bg:    '#FFFBEB',
    color: '#B45309',
    icon:  'warning',
  },
  {
    label: 'Out of Stock',
    value: props.outOfStock,
    bg:    '#FFF1F2',
    color: '#BE123C',
    icon:  'error',
  },
])
</script>

<template>
  <Head title="Dashboard" />
  <AdminLayout title="Dashboard">

    <!-- ── Stat Cards ─────────────────────────────────────────── -->
    <div class="grid grid-cols-2 lg:grid-cols-3 gap-4 mb-8">
      <div
        v-for="card in statCards"
        :key="card.label"
        class="bg-white rounded-2xl p-5 shadow-sm border border-gray-100 flex items-center gap-4 transition-shadow hover:shadow-md"
      >
        <!-- Icon bubble -->
        <div
          class="w-12 h-12 rounded-xl flex items-center justify-center flex-shrink-0"
          :style="{ background: card.bg }"
        >
          <!-- Products -->
          <svg v-if="card.icon === 'products'" class="w-6 h-6" :style="{ color: card.color }" fill="none" viewBox="0 0 24 24" stroke-width="1.6" stroke="currentColor">
            <path stroke-linecap="round" stroke-linejoin="round" d="m21 7.5-9-5.25L3 7.5m18 0-9 5.25m9-5.25v9l-9 5.25M3 7.5l9 5.25M3 7.5v9l9 5.25m0-9v9" />
          </svg>
          <!-- Categories -->
          <svg v-else-if="card.icon === 'categories'" class="w-6 h-6" :style="{ color: card.color }" fill="none" viewBox="0 0 24 24" stroke-width="1.6" stroke="currentColor">
            <path stroke-linecap="round" stroke-linejoin="round" d="M9.568 3H5.25A2.25 2.25 0 0 0 3 5.25v4.318c0 .597.237 1.17.659 1.591l9.581 9.581c.699.699 1.78.872 2.607.33a18.095 18.095 0 0 0 5.223-5.223c.542-.827.369-1.908-.33-2.607L11.16 3.66A2.25 2.25 0 0 0 9.568 3Z" />
            <path stroke-linecap="round" stroke-linejoin="round" d="M6 6h.008v.008H6V6Z" />
          </svg>
          <!-- Star -->
          <svg v-else-if="card.icon === 'star'" class="w-6 h-6" :style="{ color: card.color }" fill="none" viewBox="0 0 24 24" stroke-width="1.6" stroke="currentColor">
            <path stroke-linecap="round" stroke-linejoin="round" d="M11.48 3.499a.562.562 0 0 1 1.04 0l2.125 5.111a.563.563 0 0 0 .475.345l5.518.442c.499.04.701.663.321.988l-4.204 3.602a.563.563 0 0 0-.182.557l1.285 5.385a.562.562 0 0 1-.84.61l-4.725-2.885a.562.562 0 0 0-.586 0L6.982 20.54a.562.562 0 0 1-.84-.61l1.285-5.386a.562.562 0 0 0-.182-.557l-4.204-3.602a.562.562 0 0 1 .321-.988l5.518-.442a.563.563 0 0 0 .475-.345L11.48 3.5Z" />
          </svg>
          <!-- Trending -->
          <svg v-else-if="card.icon === 'trending'" class="w-6 h-6" :style="{ color: card.color }" fill="none" viewBox="0 0 24 24" stroke-width="1.6" stroke="currentColor">
            <path stroke-linecap="round" stroke-linejoin="round" d="M2.25 18 9 11.25l4.306 4.306a11.95 11.95 0 0 1 5.814-5.518l2.74-1.22m0 0-5.94-2.281m5.94 2.28-2.28 5.941" />
          </svg>
          <!-- Warning -->
          <svg v-else-if="card.icon === 'warning'" class="w-6 h-6" :style="{ color: card.color }" fill="none" viewBox="0 0 24 24" stroke-width="1.6" stroke="currentColor">
            <path stroke-linecap="round" stroke-linejoin="round" d="M12 9v3.75m-9.303 3.376c-.866 1.5.217 3.374 1.948 3.374h14.71c1.73 0 2.813-1.874 1.948-3.374L13.949 3.378c-.866-1.5-3.032-1.5-3.898 0L2.697 16.126ZM12 15.75h.007v.008H12v-.008Z" />
          </svg>
          <!-- Error -->
          <svg v-else-if="card.icon === 'error'" class="w-6 h-6" :style="{ color: card.color }" fill="none" viewBox="0 0 24 24" stroke-width="1.6" stroke="currentColor">
            <path stroke-linecap="round" stroke-linejoin="round" d="M18.364 18.364A9 9 0 0 0 5.636 5.636m12.728 12.728A9 9 0 0 1 5.636 5.636m12.728 12.728L5.636 5.636" />
          </svg>
        </div>

        <!-- Text -->
        <div>
          <p class="text-2xl font-bold text-gray-900 leading-none mb-1">{{ card.value }}</p>
          <p class="text-sm text-gray-500">{{ card.label }}</p>
        </div>
      </div>
    </div>

    <!-- ── Bottom row ──────────────────────────────────────────── -->
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">

      <!-- Category Breakdown -->
      <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-6">
        <div class="flex items-center justify-between mb-5">
          <h2 class="text-sm font-semibold text-gray-800">Products by Category</h2>
          <span class="text-xs text-gray-400">{{ byCategory.length }} categories</span>
        </div>
        <div class="space-y-3">
          <div v-for="cat in byCategory" :key="cat.name" class="flex items-center gap-3">
            <span class="text-xs text-gray-500 w-28 flex-shrink-0 truncate">{{ cat.name }}</span>
            <div class="flex-1 bg-gray-100 rounded-full h-1.5 overflow-hidden">
              <div
                class="h-full rounded-full transition-all duration-500"
                :style="{
                  width: (cat.count / maxCount) * 100 + '%',
                  background: '#1565C0',
                }"
              />
            </div>
            <span class="text-xs font-semibold text-gray-700 w-5 text-right flex-shrink-0">
              {{ cat.count }}
            </span>
          </div>
          <div v-if="byCategory.length === 0" class="text-center py-6 text-sm text-gray-400">
            No category data available
          </div>
        </div>
      </div>

      <!-- Stock Alerts -->
      <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-6">
        <h2 class="text-sm font-semibold text-gray-800 mb-5">Stock Alerts</h2>
        <div class="space-y-3">
          <!-- Out of stock -->
          <div class="flex items-center justify-between p-3 rounded-xl bg-red-50 border border-red-100">
            <div class="flex items-center gap-3">
              <div class="w-8 h-8 rounded-lg bg-red-100 flex items-center justify-center">
                <svg class="w-4 h-4 text-red-600" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                  <path stroke-linecap="round" stroke-linejoin="round" d="M18.364 18.364A9 9 0 0 0 5.636 5.636m12.728 12.728A9 9 0 0 1 5.636 5.636m12.728 12.728L5.636 5.636" />
                </svg>
              </div>
              <div>
                <p class="text-sm font-medium text-red-800">Out of Stock</p>
                <p class="text-xs text-red-500">Needs immediate attention</p>
              </div>
            </div>
            <span class="text-2xl font-bold text-red-700">{{ outOfStock }}</span>
          </div>
          <!-- Low stock -->
          <div class="flex items-center justify-between p-3 rounded-xl bg-amber-50 border border-amber-100">
            <div class="flex items-center gap-3">
              <div class="w-8 h-8 rounded-lg bg-amber-100 flex items-center justify-center">
                <svg class="w-4 h-4 text-amber-600" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                  <path stroke-linecap="round" stroke-linejoin="round" d="M12 9v3.75m-9.303 3.376c-.866 1.5.217 3.374 1.948 3.374h14.71c1.73 0 2.813-1.874 1.948-3.374L13.949 3.378c-.866-1.5-3.032-1.5-3.898 0L2.697 16.126ZM12 15.75h.007v.008H12v-.008Z" />
                </svg>
              </div>
              <div>
                <p class="text-sm font-medium text-amber-800">Low Stock <span class="text-amber-500 font-normal">(≤ 5 units)</span></p>
                <p class="text-xs text-amber-500">Consider restocking soon</p>
              </div>
            </div>
            <span class="text-2xl font-bold text-amber-700">{{ lowStock }}</span>
          </div>
          <!-- Healthy note -->
          <p class="text-xs text-gray-400 text-center pt-1">
            Variant-based products track stock per variant — not included above.
          </p>
        </div>
      </div>

    </div>

  </AdminLayout>
</template>