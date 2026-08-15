<script setup>
import { ref, computed, onMounted } from 'vue'
import api from '@/api/axios'

const loading = ref(true)
const error   = ref(null)

const stats = ref({
  totalProducts: 0, totalCategories: 0,
  featured: 0, bestSelling: 0,
  lowStock: 0, outOfStock: 0,
})
const byCategory = ref([])

onMounted(async () => {
  try {
    const { data } = await api.get('/api/admin/dashboard')
    stats.value      = data
    byCategory.value = data.byCategory ?? []
  } catch (e) {
    error.value = 'Failed to load dashboard data.'
  } finally {
    loading.value = false
  }
})

const maxCount = computed(() =>
  byCategory.value.length ? Math.max(...byCategory.value.map(c => c.count)) : 1
)

const statCards = computed(() => [
  { label: 'Total Products',   value: stats.value.totalProducts,   icon: 'cube',    color: '#1565C0', light: '#EFF6FF', trend: 'All active listings'    },
  { label: 'Categories',       value: stats.value.totalCategories, icon: 'tag',     color: '#0369A1', light: '#F0F9FF', trend: 'Product groups'          },
  { label: 'Featured',         value: stats.value.featured,        icon: 'star',    color: '#7C3AED', light: '#F5F3FF', trend: 'Highlighted items'       },
  { label: 'Best Selling',     value: stats.value.bestSelling,     icon: 'fire',    color: '#15803D', light: '#F0FDF4', trend: 'Top performers'          },
  { label: 'Low Stock',        value: stats.value.lowStock,        icon: 'warning', color: '#B45309', light: '#FFFBEB', trend: '≤ 5 units remaining'     },
  { label: 'Out of Stock',     value: stats.value.outOfStock,      icon: 'ban',     color: '#BE123C', light: '#FFF1F2', trend: 'Needs restocking'        },
])

const catColors = ['#1565C0','#0369A1','#7C3AED','#15803D','#B45309','#BE123C','#0891B2','#9333EA','#0F766E']
</script>

<template>
  <div>
    <!-- Loading -->
    <div v-if="loading" class="flex items-center justify-center py-24">
      <div class="flex flex-col items-center gap-3">
        <div class="w-10 h-10 rounded-full border-2 animate-spin" style="border-color:#1565C0; border-top-color:transparent;"></div>
        <p class="text-sm text-gray-400">Loading dashboard…</p>
      </div>
    </div>

    <!-- Error -->
    <div v-else-if="error" class="flex items-center justify-center py-24">
      <div class="text-center">
        <div class="w-12 h-12 rounded-2xl bg-red-50 flex items-center justify-center mx-auto mb-3">
          <svg class="w-6 h-6 text-red-400" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
            <path stroke-linecap="round" stroke-linejoin="round" d="M12 9v3.75m9-.75a9 9 0 1 1-18 0 9 9 0 0 1 18 0Zm-9 3.75h.008v.008H12v-.008Z" />
          </svg>
        </div>
        <p class="text-sm text-gray-500">{{ error }}</p>
      </div>
    </div>

    <template v-else>
      <!-- Page header -->
      <div class="mb-8 flex items-end justify-between">
        <div>
          <p class="text-xs font-semibold uppercase tracking-widest mb-1" style="color:#1565C0;">Overview</p>
          <h2 class="text-2xl font-bold text-gray-900">Store Dashboard</h2>
        </div>
        <div class="text-xs text-gray-400 text-right">
          <p class="font-medium text-gray-600">Real-time data</p>
          <p>Updated just now</p>
        </div>
      </div>

      <!-- Stat Cards -->
      <div class="grid grid-cols-2 xl:grid-cols-3 gap-4 mb-8">
        <div
          v-for="(card, i) in statCards"
          :key="card.label"
          class="bg-white rounded-2xl p-5 border border-gray-100 relative overflow-hidden transition-all duration-300 hover:-translate-y-0.5 hover:shadow-md fade-up"
          :style="`animation-delay: ${i * 60}ms;`"
        >
          <div class="absolute top-0 left-0 right-0 h-0.5 rounded-t-2xl" :style="`background:${card.color};`"></div>
          <div class="flex items-start justify-between mb-4">
            <div class="w-10 h-10 rounded-xl flex items-center justify-center" :style="`background:${card.light};`">
              <svg v-if="card.icon==='cube'" class="w-5 h-5" :style="`color:${card.color}`" fill="none" viewBox="0 0 24 24" stroke-width="1.6" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" d="m21 7.5-9-5.25L3 7.5m18 0-9 5.25m9-5.25v9l-9 5.25M3 7.5l9 5.25M3 7.5v9l9 5.25m0-9v9" />
              </svg>
              <svg v-else-if="card.icon==='tag'" class="w-5 h-5" :style="`color:${card.color}`" fill="none" viewBox="0 0 24 24" stroke-width="1.6" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" d="M9.568 3H5.25A2.25 2.25 0 0 0 3 5.25v4.318c0 .597.237 1.17.659 1.591l9.581 9.581c.699.699 1.78.872 2.607.33a18.095 18.095 0 0 0 5.223-5.223c.542-.827.369-1.908-.33-2.607L11.16 3.66A2.25 2.25 0 0 0 9.568 3Z" />
                <path stroke-linecap="round" stroke-linejoin="round" d="M6 6h.008v.008H6V6Z" />
              </svg>
              <svg v-else-if="card.icon==='star'" class="w-5 h-5" :style="`color:${card.color}`" fill="none" viewBox="0 0 24 24" stroke-width="1.6" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" d="M11.48 3.499a.562.562 0 0 1 1.04 0l2.125 5.111a.563.563 0 0 0 .475.345l5.518.442c.499.04.701.663.321.988l-4.204 3.602a.563.563 0 0 0-.182.557l1.285 5.385a.562.562 0 0 1-.84.61l-4.725-2.885a.562.562 0 0 0-.586 0L6.982 20.54a.562.562 0 0 1-.84-.61l1.285-5.386a.562.562 0 0 0-.182-.557l-4.204-3.602a.562.562 0 0 1 .321-.988l5.518-.442a.563.563 0 0 0 .475-.345L11.48 3.5Z" />
              </svg>
              <svg v-else-if="card.icon==='fire'" class="w-5 h-5" :style="`color:${card.color}`" fill="none" viewBox="0 0 24 24" stroke-width="1.6" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" d="M15.362 5.214A8.252 8.252 0 0 1 12 21 8.25 8.25 0 0 1 6.038 7.047 8.287 8.287 0 0 0 9 9.601a8.983 8.983 0 0 1 3.361-6.867 8.21 8.21 0 0 0 3 2.48Z" />
                <path stroke-linecap="round" stroke-linejoin="round" d="M12 18a3.75 3.75 0 0 0 .495-7.468 5.99 5.99 0 0 0-1.925 3.547 5.975 5.975 0 0 1-2.133-1.001A3.75 3.75 0 0 0 12 18Z" />
              </svg>
              <svg v-else-if="card.icon==='warning'" class="w-5 h-5" :style="`color:${card.color}`" fill="none" viewBox="0 0 24 24" stroke-width="1.6" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" d="M12 9v3.75m-9.303 3.376c-.866 1.5.217 3.374 1.948 3.374h14.71c1.73 0 2.813-1.874 1.948-3.374L13.949 3.378c-.866-1.5-3.032-1.5-3.898 0L2.697 16.126ZM12 15.75h.007v.008H12v-.008Z" />
              </svg>
              <svg v-else class="w-5 h-5" :style="`color:${card.color}`" fill="none" viewBox="0 0 24 24" stroke-width="1.6" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" d="M18.364 18.364A9 9 0 0 0 5.636 5.636m12.728 12.728A9 9 0 0 1 5.636 5.636m12.728 12.728L5.636 5.636" />
              </svg>
            </div>
            <span class="text-3xl font-bold text-gray-900">{{ card.value }}</span>
          </div>
          <p class="text-sm font-semibold text-gray-800">{{ card.label }}</p>
          <p class="text-xs text-gray-400 mt-0.5">{{ card.trend }}</p>
        </div>
      </div>

      <!-- Bottom row -->
      <div class="grid grid-cols-1 xl:grid-cols-5 gap-6">

        <!-- Category chart -->
        <div class="xl:col-span-3 bg-white rounded-2xl border border-gray-100 p-6">
          <div class="flex items-center justify-between mb-6">
            <div>
              <h3 class="text-sm font-semibold text-gray-900">Products by Category</h3>
              <p class="text-xs text-gray-400 mt-0.5">Distribution across {{ byCategory.length }} categories</p>
            </div>
            <div class="text-xs font-semibold px-3 py-1.5 rounded-lg" style="background:#EFF6FF; color:#1565C0;">
              {{ stats.totalProducts }} total
            </div>
          </div>
          <div class="space-y-3">
            <div v-for="(cat, idx) in byCategory" :key="cat.name" class="flex items-center gap-3">
              <div class="w-6 h-6 rounded-md flex items-center justify-center flex-shrink-0 text-white text-xs font-bold" :style="`background:${catColors[idx % catColors.length]};`">
                {{ cat.name.charAt(0) }}
              </div>
              <span class="text-xs text-gray-600 w-28 flex-shrink-0 truncate font-medium">{{ cat.name }}</span>
              <div class="flex-1 h-2 bg-gray-100 rounded-full overflow-hidden">
                <div class="h-full rounded-full transition-all duration-700" :style="`width:${(cat.count/maxCount)*100}%; background:${catColors[idx % catColors.length]};`"></div>
              </div>
              <span class="text-xs font-bold text-gray-700 w-6 text-right flex-shrink-0">{{ cat.count }}</span>
            </div>
          </div>
        </div>

        <!-- Stock alerts -->
        <div class="xl:col-span-2 flex flex-col gap-4">
          <div class="bg-white rounded-2xl border border-gray-100 p-5 flex-1">
            <div class="flex items-center gap-3 mb-4">
              <div class="w-9 h-9 rounded-xl flex items-center justify-center" style="background:#FFF1F2;">
                <svg class="w-5 h-5" style="color:#BE123C;" fill="none" viewBox="0 0 24 24" stroke-width="1.8" stroke="currentColor">
                  <path stroke-linecap="round" stroke-linejoin="round" d="M18.364 18.364A9 9 0 0 0 5.636 5.636m12.728 12.728A9 9 0 0 1 5.636 5.636m12.728 12.728L5.636 5.636" />
                </svg>
              </div>
              <div>
                <p class="text-sm font-semibold text-gray-900">Out of Stock</p>
                <p class="text-xs text-gray-400">Immediate attention needed</p>
              </div>
            </div>
            <div class="flex items-end justify-between">
              <span class="text-5xl font-bold" style="color:#BE123C;">{{ stats.outOfStock }}</span>
              <span class="text-xs px-2 py-1 rounded-lg font-medium" style="background:#FFF1F2; color:#BE123C;">products</span>
            </div>
          </div>

          <div class="bg-white rounded-2xl border border-gray-100 p-5 flex-1">
            <div class="flex items-center gap-3 mb-4">
              <div class="w-9 h-9 rounded-xl flex items-center justify-center" style="background:#FFFBEB;">
                <svg class="w-5 h-5" style="color:#B45309;" fill="none" viewBox="0 0 24 24" stroke-width="1.8" stroke="currentColor">
                  <path stroke-linecap="round" stroke-linejoin="round" d="M12 9v3.75m-9.303 3.376c-.866 1.5.217 3.374 1.948 3.374h14.71c1.73 0 2.813-1.874 1.948-3.374L13.949 3.378c-.866-1.5-3.032-1.5-3.898 0L2.697 16.126ZM12 15.75h.007v.008H12v-.008Z" />
                </svg>
              </div>
              <div>
                <p class="text-sm font-semibold text-gray-900">Low Stock</p>
                <p class="text-xs text-gray-400">≤ 5 units remaining</p>
              </div>
            </div>
            <div class="flex items-end justify-between">
              <span class="text-5xl font-bold" style="color:#B45309;">{{ stats.lowStock }}</span>
              <span class="text-xs px-2 py-1 rounded-lg font-medium" style="background:#FFFBEB; color:#B45309;">products</span>
            </div>
          </div>

          <div class="rounded-xl px-4 py-3 border" style="background:#F8FAFF; border-color:#DBEAFE;">
            <p class="text-xs" style="color:#3B82F6;">
              <span class="font-semibold">Note:</span> Variant-based products track stock per variant and are not included in alerts above.
            </p>
          </div>
        </div>
      </div>
    </template>
  </div>
</template>

<style scoped>
.fade-up {
  animation: fadeUp 0.4s ease forwards;
  opacity: 0;
}
@keyframes fadeUp {
  from { opacity: 0; transform: translateY(12px); }
  to   { opacity: 1; transform: translateY(0); }
}
</style>