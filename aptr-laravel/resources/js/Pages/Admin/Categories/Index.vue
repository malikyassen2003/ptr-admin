<script setup>
import { ref, computed } from 'vue'
import { Head } from '@inertiajs/vue3'
import AdminLayout from '@/Layouts/AdminLayout.vue'

const props = defineProps({
  categories: Array,
})

const search = ref('')

const filtered = computed(() =>
  props.categories.filter(c =>
    !search.value ||
    c.name.toLowerCase().includes(search.value.toLowerCase()) ||
    c.slug.toLowerCase().includes(search.value.toLowerCase())
  )
)

const totalProducts = computed(() =>
  props.categories.reduce((sum, c) => sum + c.products_count, 0)
)
</script>

<template>
  <Head title="Categories" />
  <AdminLayout title="Categories">

    <!-- ── Stats strip ────────────────────────────────────────── -->
    <div class="grid grid-cols-2 gap-4 mb-5">
      <div class="bg-white rounded-2xl shadow-sm border border-gray-100 px-6 py-4 flex items-center gap-4">
        <div class="w-10 h-10 rounded-xl bg-blue-50 flex items-center justify-center flex-shrink-0">
          <svg class="w-5 h-5 text-blue-600" fill="none" viewBox="0 0 24 24" stroke-width="1.6" stroke="currentColor">
            <path stroke-linecap="round" stroke-linejoin="round" d="M9.568 3H5.25A2.25 2.25 0 0 0 3 5.25v4.318c0 .597.237 1.17.659 1.591l9.581 9.581c.699.699 1.78.872 2.607.33a18.095 18.095 0 0 0 5.223-5.223c.542-.827.369-1.908-.33-2.607L11.16 3.66A2.25 2.25 0 0 0 9.568 3Z" />
            <path stroke-linecap="round" stroke-linejoin="round" d="M6 6h.008v.008H6V6Z" />
          </svg>
        </div>
        <div>
          <p class="text-2xl font-bold text-gray-900">{{ categories.length }}</p>
          <p class="text-sm text-gray-500">Total Categories</p>
        </div>
      </div>
      <div class="bg-white rounded-2xl shadow-sm border border-gray-100 px-6 py-4 flex items-center gap-4">
        <div class="w-10 h-10 rounded-xl bg-blue-50 flex items-center justify-center flex-shrink-0">
          <svg class="w-5 h-5 text-blue-600" fill="none" viewBox="0 0 24 24" stroke-width="1.6" stroke="currentColor">
            <path stroke-linecap="round" stroke-linejoin="round" d="m21 7.5-9-5.25L3 7.5m18 0-9 5.25m9-5.25v9l-9 5.25M3 7.5l9 5.25M3 7.5v9l9 5.25m0-9v9" />
          </svg>
        </div>
        <div>
          <p class="text-2xl font-bold text-gray-900">{{ totalProducts }}</p>
          <p class="text-sm text-gray-500">Products Across All</p>
        </div>
      </div>
    </div>

    <!-- ── Search ─────────────────────────────────────────────── -->
    <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-4 mb-5">
      <div class="relative max-w-sm">
        <svg class="absolute left-3 top-1/2 -translate-y-1/2 w-4 h-4 text-gray-400 pointer-events-none" fill="none" viewBox="0 0 24 24" stroke-width="1.8" stroke="currentColor">
          <path stroke-linecap="round" stroke-linejoin="round" d="m21 21-5.197-5.197m0 0A7.5 7.5 0 1 0 5.196 5.196a7.5 7.5 0 0 0 10.607 10.607Z" />
        </svg>
        <input
          v-model="search"
          type="text"
          placeholder="Filter categories…"
          class="w-full pl-9 pr-4 py-2 text-sm border border-gray-200 rounded-xl focus:outline-none focus:ring-2 transition"
          style="--tw-ring-color: #1565C0;"
        />
      </div>
    </div>

    <!-- ── Table ──────────────────────────────────────────────── -->
    <div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden">
      <table class="w-full text-sm">
        <thead>
          <tr class="border-b border-gray-100 bg-gray-50/80">
            <th class="text-left px-6 py-3.5 text-xs font-semibold text-gray-400 uppercase tracking-wider">#</th>
            <th class="text-left px-4 py-3.5 text-xs font-semibold text-gray-400 uppercase tracking-wider">Name</th>
            <th class="text-left px-4 py-3.5 text-xs font-semibold text-gray-400 uppercase tracking-wider">Slug</th>
            <th class="text-left px-4 py-3.5 text-xs font-semibold text-gray-400 uppercase tracking-wider">Icon</th>
            <th class="text-left px-4 py-3.5 text-xs font-semibold text-gray-400 uppercase tracking-wider">Products</th>
            <th class="px-4 py-3.5 w-10"></th>
          </tr>
        </thead>
        <tbody class="divide-y divide-gray-50">
          <tr
            v-for="(cat, idx) in filtered"
            :key="cat.id"
            class="hover:bg-blue-50/30 transition-colors group"
          >
            <!-- Index -->
            <td class="px-6 py-4 text-xs text-gray-300 font-mono">{{ idx + 1 }}</td>

            <!-- Name -->
            <td class="px-4 py-4">
              <span class="font-semibold text-gray-900">{{ cat.name }}</span>
            </td>

            <!-- Slug -->
            <td class="px-4 py-4">
              <span class="text-xs font-mono text-gray-500 bg-gray-100 px-2 py-0.5 rounded">
                {{ cat.slug }}
              </span>
            </td>

            <!-- Icon -->
            <td class="px-4 py-4">
              <span class="text-xs font-mono text-gray-500 bg-gray-100 px-2 py-0.5 rounded">
                {{ cat.icon ?? '—' }}
              </span>
            </td>

            <!-- Products count -->
            <td class="px-4 py-4">
              <div class="flex items-center gap-2">
                <div class="flex-1 max-w-[80px] bg-gray-100 rounded-full h-1.5">
                  <div
                    class="h-full rounded-full"
                    style="background: #1565C0;"
                    :style="{
                      width: totalProducts > 0
                        ? (cat.products_count / totalProducts) * 100 + '%'
                        : '0%',
                      background: '#1565C0',
                    }"
                  />
                </div>
                <span
                  class="text-xs font-semibold px-2 py-0.5 rounded-full"
                  :class="cat.products_count === 0
                    ? 'bg-gray-100 text-gray-400'
                    : 'bg-blue-100 text-blue-700'"
                >
                  {{ cat.products_count }}
                </span>
              </div>
            </td>

            <!-- Actions -->
            <td class="px-4 py-4">
              <button
                class="p-1.5 rounded-lg text-gray-300 hover:text-gray-600 hover:bg-gray-100 transition-colors opacity-0 group-hover:opacity-100"
                title="Edit category"
              >
                <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                  <path stroke-linecap="round" stroke-linejoin="round" d="m16.862 4.487 1.687-1.688a1.875 1.875 0 1 1 2.652 2.652L10.582 16.07a4.5 4.5 0 0 1-1.897 1.13L6 18l.8-2.685a4.5 4.5 0 0 1 1.13-1.897l8.932-8.931Zm0 0L19.5 7.125" />
                </svg>
              </button>
            </td>
          </tr>
        </tbody>
      </table>

      <!-- Empty state -->
      <div v-if="filtered.length === 0" class="text-center py-12">
        <svg class="w-10 h-10 text-gray-200 mx-auto mb-3" fill="none" viewBox="0 0 24 24" stroke-width="1" stroke="currentColor">
          <path stroke-linecap="round" stroke-linejoin="round" d="M9.568 3H5.25A2.25 2.25 0 0 0 3 5.25v4.318c0 .597.237 1.17.659 1.591l9.581 9.581c.699.699 1.78.872 2.607.33a18.095 18.095 0 0 0 5.223-5.223c.542-.827.369-1.908-.33-2.607L11.16 3.66A2.25 2.25 0 0 0 9.568 3Z" />
        </svg>
        <p class="text-sm text-gray-400">No categories match "{{ search }}"</p>
      </div>
    </div>

  </AdminLayout>
</template>