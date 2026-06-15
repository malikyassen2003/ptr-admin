<script setup>
import { ref, computed } from 'vue'
import { Head } from '@inertiajs/vue3'
import AdminLayout from '@/Layouts/AdminLayout.vue'

const props = defineProps({
  products:   Array,
  categories: Array,
})

// ── Filters ────────────────────────────────────────────────────────
const search           = ref('')
const selectedCategory = ref('')
const selectedBadge    = ref('')
const selectedStock    = ref('')

const filtered = computed(() => {
  return props.products.filter(p => {
    // Text search
    const q = search.value.toLowerCase()
    const matchSearch = !q ||
      p.name.toLowerCase().includes(q) ||
      p.brand.toLowerCase().includes(q)

    // Category
    const matchCategory = !selectedCategory.value ||
      p.category === selectedCategory.value

    // Badge  ('__none__' = products with no badge)
    const matchBadge = !selectedBadge.value ||
      (selectedBadge.value === '__none__' ? !p.badge : p.badge === selectedBadge.value)

    // Stock filter
    let matchStock = true
    if (selectedStock.value === 'out')      matchStock = !p.has_variants && p.stock === 0
    else if (selectedStock.value === 'low') matchStock = !p.has_variants && p.stock > 0 && p.stock <= 5
    else if (selectedStock.value === 'variants') matchStock = p.has_variants

    return matchSearch && matchCategory && matchBadge && matchStock
  })
})

function clearFilters() {
  search.value           = ''
  selectedCategory.value = ''
  selectedBadge.value    = ''
  selectedStock.value    = ''
}

const hasActiveFilters = computed(() =>
  search.value || selectedCategory.value || selectedBadge.value || selectedStock.value
)

// ── Helpers ────────────────────────────────────────────────────────
function stockInfo(p) {
  if (p.has_variants) return { text: 'Variants',     cls: 'bg-blue-100 text-blue-700' }
  if (p.stock === 0)  return { text: 'Out of Stock', cls: 'bg-red-100 text-red-700'   }
  if (p.stock <= 5)   return { text: `Low · ${p.stock}`, cls: 'bg-amber-100 text-amber-700' }
  return               { text: p.stock,             cls: 'bg-green-100 text-green-700' }
}

function badgeStyle(badge) {
  const map = {
    Hot:  'bg-red-100 text-red-700',
    New:  'bg-emerald-100 text-emerald-700',
    Sale: 'bg-amber-100 text-amber-700',
  }
  return map[badge] ?? 'bg-gray-100 text-gray-600'
}

const BADGES = ['Hot', 'New', 'Sale']
</script>

<template>
  <Head title="Products" />
  <AdminLayout title="Products">

    <!-- ── Filters bar ────────────────────────────────────────── -->
    <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-4 mb-5">
      <div class="flex flex-wrap gap-3 items-center">

        <!-- Search -->
        <div class="relative flex-1 min-w-[200px]">
          <svg class="absolute left-3 top-1/2 -translate-y-1/2 w-4 h-4 text-gray-400 pointer-events-none" fill="none" viewBox="0 0 24 24" stroke-width="1.8" stroke="currentColor">
            <path stroke-linecap="round" stroke-linejoin="round" d="m21 21-5.197-5.197m0 0A7.5 7.5 0 1 0 5.196 5.196a7.5 7.5 0 0 0 10.607 10.607Z" />
          </svg>
          <input
            v-model="search"
            type="text"
            placeholder="Search by name or brand…"
            class="w-full pl-9 pr-4 py-2 text-sm border border-gray-200 rounded-xl focus:outline-none focus:ring-2 focus:border-transparent transition"
            style="--tw-ring-color: #1565C0;"
          />
        </div>

        <!-- Category -->
        <select
          v-model="selectedCategory"
          class="px-3 py-2 text-sm border border-gray-200 rounded-xl focus:outline-none focus:ring-2 transition"
          style="--tw-ring-color: #1565C0;"
        >
          <option value="">All Categories</option>
          <option v-for="cat in categories" :key="cat.id" :value="cat.name">{{ cat.name }}</option>
        </select>

        <!-- Badge -->
        <select
          v-model="selectedBadge"
          class="px-3 py-2 text-sm border border-gray-200 rounded-xl focus:outline-none focus:ring-2 transition"
          style="--tw-ring-color: #1565C0;"
        >
          <option value="">All Badges</option>
          <option v-for="b in BADGES" :key="b" :value="b">{{ b }}</option>
          <option value="__none__">No Badge</option>
        </select>

        <!-- Stock -->
        <select
          v-model="selectedStock"
          class="px-3 py-2 text-sm border border-gray-200 rounded-xl focus:outline-none focus:ring-2 transition"
          style="--tw-ring-color: #1565C0;"
        >
          <option value="">All Stock</option>
          <option value="out">Out of Stock</option>
          <option value="low">Low Stock (≤5)</option>
          <option value="variants">Has Variants</option>
        </select>

        <!-- Count + clear -->
        <div class="flex items-center gap-2 ml-auto">
          <span class="text-xs text-gray-400 whitespace-nowrap">
            {{ filtered.length }} / {{ products.length }} products
          </span>
          <button
            v-if="hasActiveFilters"
            @click="clearFilters"
            class="text-xs px-2.5 py-1.5 rounded-lg text-gray-500 hover:text-gray-700 border border-gray-200 hover:bg-gray-50 transition"
          >
            Clear
          </button>
        </div>
      </div>
    </div>

    <!-- ── Table ──────────────────────────────────────────────── -->
    <div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden">
      <div class="overflow-x-auto">
        <table class="w-full text-sm">
          <thead>
            <tr class="border-b border-gray-100 bg-gray-50/80">
              <th class="text-left px-5 py-3.5 text-xs font-semibold text-gray-400 uppercase tracking-wider">Product</th>
              <th class="text-left px-4 py-3.5 text-xs font-semibold text-gray-400 uppercase tracking-wider">Category</th>
              <th class="text-left px-4 py-3.5 text-xs font-semibold text-gray-400 uppercase tracking-wider">Price</th>
              <th class="text-left px-4 py-3.5 text-xs font-semibold text-gray-400 uppercase tracking-wider">Badge</th>
              <th class="text-left px-4 py-3.5 text-xs font-semibold text-gray-400 uppercase tracking-wider">Stock</th>
              <th class="text-left px-4 py-3.5 text-xs font-semibold text-gray-400 uppercase tracking-wider">Tags</th>
              <th class="px-4 py-3.5 w-10"></th>
            </tr>
          </thead>
          <tbody class="divide-y divide-gray-50">
            <tr
              v-for="p in filtered"
              :key="p.id"
              class="hover:bg-blue-50/30 transition-colors group"
            >
              <!-- Product image + name -->
              <td class="px-5 py-3">
                <div class="flex items-center gap-3">
                  <div class="w-10 h-10 rounded-xl bg-gray-100 overflow-hidden flex-shrink-0">
                    <img
                      v-if="p.image"
                      :src="p.image"
                      :alt="p.name"
                      class="w-full h-full object-cover"
                    />
                    <div v-else class="w-full h-full flex items-center justify-center text-gray-300">
                      <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" d="m2.25 15.75 5.159-5.159a2.25 2.25 0 0 1 3.182 0l5.159 5.159m-1.5-1.5 1.409-1.409a2.25 2.25 0 0 1 3.182 0l2.909 2.909m-18 3.75h16.5a1.5 1.5 0 0 0 1.5-1.5V6a1.5 1.5 0 0 0-1.5-1.5H3.75A1.5 1.5 0 0 0 2.25 6v12a1.5 1.5 0 0 0 1.5 1.5Zm10.5-11.25h.008v.008h-.008V8.25Zm.375 0a.375.375 0 1 1-.75 0 .375.375 0 0 1 .75 0Z" />
                      </svg>
                    </div>
                  </div>
                  <div class="min-w-0">
                    <p class="font-medium text-gray-900 leading-tight truncate max-w-[200px]">{{ p.name }}</p>
                    <p class="text-xs text-gray-400 mt-0.5">{{ p.brand }}</p>
                  </div>
                </div>
              </td>

              <!-- Category -->
              <td class="px-4 py-3 text-gray-600 text-xs">{{ p.category }}</td>

              <!-- Price -->
              <td class="px-4 py-3 font-semibold text-gray-800 whitespace-nowrap">
                {{ Number(p.base_price).toFixed(2) }} <span class="text-xs font-normal text-gray-400">JOD</span>
              </td>

              <!-- Badge -->
              <td class="px-4 py-3">
                <span
                  v-if="p.badge"
                  class="inline-flex items-center px-2 py-0.5 rounded-md text-xs font-semibold"
                  :class="badgeStyle(p.badge)"
                >
                  {{ p.badge }}
                </span>
                <span v-else class="text-gray-200 text-sm">—</span>
              </td>

              <!-- Stock -->
              <td class="px-4 py-3">
                <span
                  class="inline-flex items-center px-2 py-0.5 rounded-md text-xs font-semibold"
                  :class="stockInfo(p).cls"
                >
                  {{ stockInfo(p).text }}
                </span>
              </td>

              <!-- Flags -->
              <td class="px-4 py-3">
                <div class="flex flex-wrap gap-1">
                  <span
                    v-if="p.is_featured"
                    class="inline-flex px-1.5 py-0.5 rounded text-xs bg-violet-100 text-violet-700 font-medium"
                  >Featured</span>
                  <span
                    v-if="p.is_best_selling"
                    class="inline-flex px-1.5 py-0.5 rounded text-xs bg-teal-100 text-teal-700 font-medium"
                  >Top Seller</span>
                </div>
              </td>

              <!-- Actions -->
              <td class="px-4 py-3">
                <button
                  class="p-1.5 rounded-lg text-gray-300 hover:text-gray-600 hover:bg-gray-100 transition-colors opacity-0 group-hover:opacity-100"
                  title="Edit product"
                >
                  <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" d="m16.862 4.487 1.687-1.688a1.875 1.875 0 1 1 2.652 2.652L10.582 16.07a4.5 4.5 0 0 1-1.897 1.13L6 18l.8-2.685a4.5 4.5 0 0 1 1.13-1.897l8.932-8.931Zm0 0L19.5 7.125" />
                  </svg>
                </button>
              </td>
            </tr>
          </tbody>
        </table>
      </div>

      <!-- Empty state -->
      <div v-if="filtered.length === 0" class="text-center py-14">
        <svg class="w-10 h-10 text-gray-200 mx-auto mb-3" fill="none" viewBox="0 0 24 24" stroke-width="1" stroke="currentColor">
          <path stroke-linecap="round" stroke-linejoin="round" d="m21 21-5.197-5.197m0 0A7.5 7.5 0 1 0 5.196 5.196a7.5 7.5 0 0 0 10.607 10.607Z" />
        </svg>
        <p class="text-sm text-gray-400 mb-2">No products match your filters</p>
        <button @click="clearFilters" class="text-xs text-blue-600 hover:underline">Clear all filters</button>
      </div>
    </div>

  </AdminLayout>
</template>