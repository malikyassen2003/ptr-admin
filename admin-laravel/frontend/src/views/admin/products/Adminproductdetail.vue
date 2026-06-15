<script setup>
import { ref, onMounted } from 'vue'
import { useRoute, useRouter } from 'vue-router'
import api from '@/api/axios'

const route  = useRoute()
const router = useRouter()

const loading = ref(true)
const error   = ref(null)
const product = ref(null)
const selectedColor = ref(null)
const lightboxImg   = ref(null)

onMounted(async () => {
  try {
    const { data } = await api.get(`/api/admin/products/${route.params.id}`)
    product.value = data
    if (data.colors?.length) selectedColor.value = data.colors[0]
  } catch {
    error.value = 'Product not found.'
  } finally {
    loading.value = false
  }
})

function selectColor(color) {
  selectedColor.value = color
}

function statusColor(stock) {
  if (stock === 0)  return 'bg-red-100 text-red-700'
  if (stock <= 5)   return 'bg-amber-100 text-amber-700'
  return 'bg-green-100 text-green-700'
}

function badgeStyle(b) {
  return { Hot:'bg-red-100 text-red-700', New:'bg-emerald-100 text-emerald-700', Sale:'bg-amber-100 text-amber-700' }[b] ?? 'bg-gray-100 text-gray-600'
}
</script>

<template>
  <div>
    <!-- Loading -->
    <div v-if="loading" class="flex items-center justify-center py-24">
      <div class="flex flex-col items-center gap-3">
        <div class="w-10 h-10 rounded-full border-2 animate-spin" style="border-color:#1565C0;border-top-color:transparent;"></div>
        <p class="text-sm text-gray-400">Loading product…</p>
      </div>
    </div>

    <!-- Error -->
    <div v-else-if="error" class="flex items-center justify-center py-24">
      <p class="text-sm text-gray-500">{{ error }}</p>
    </div>

    <template v-else-if="product">
      <!-- Back + Header -->
      <div class="mb-6">
        <button @click="router.push({ name: 'admin.products' })"
          class="flex items-center gap-2 text-sm text-gray-400 hover:text-gray-700 transition-colors mb-4">
          <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
            <path stroke-linecap="round" stroke-linejoin="round" d="M15.75 19.5 8.25 12l7.5-7.5" />
          </svg>
          Back to Products
        </button>
        <div class="flex items-start justify-between">
          <div>
            <p class="text-xs font-semibold uppercase tracking-widest mb-1" style="color:#1565C0;">{{ product.category?.name }}</p>
            <h2 class="text-2xl font-bold text-gray-900">{{ product.name }}</h2>
            <p class="text-sm text-gray-400 mt-1">{{ product.brand }} · {{ product.short_spec }}</p>
          </div>
          <div class="flex items-center gap-2">
            <span v-if="product.badge" class="px-3 py-1 rounded-lg text-xs font-semibold" :class="badgeStyle(product.badge)">{{ product.badge }}</span>
            <button @click="router.push({ name: 'admin.products' })"
              class="px-4 py-2 rounded-xl text-sm font-semibold text-white transition-all hover:brightness-110"
              style="background:linear-gradient(135deg,#1565C0,#1976D2);">
              Edit Product
            </button>
          </div>
        </div>
      </div>

      <div class="grid grid-cols-1 xl:grid-cols-3 gap-6">

        <!-- Left: Main info -->
        <div class="xl:col-span-2 space-y-5">

          <!-- Main image + gallery -->
          <div class="bg-white rounded-2xl border border-gray-100 shadow-sm overflow-hidden">
            <!-- Main image -->
            <div class="relative bg-gray-50 flex items-center justify-center" style="height:280px;">
              <img
                v-if="selectedColor?.images?.[0]?.url || product.image"
                :src="selectedColor?.images?.[0]?.url ?? product.image"
                :alt="product.name"
                class="max-h-full max-w-full object-contain cursor-zoom-in"
                @click="lightboxImg = selectedColor?.images?.[0]?.url ?? product.image"
              />
              <div v-else class="text-gray-200">
                <svg class="w-16 h-16" fill="none" viewBox="0 0 24 24" stroke-width="1" stroke="currentColor">
                  <path stroke-linecap="round" stroke-linejoin="round" d="m2.25 15.75 5.159-5.159a2.25 2.25 0 0 1 3.182 0l5.159 5.159m-1.5-1.5 1.409-1.409a2.25 2.25 0 0 1 3.182 0l2.909 2.909m-18 3.75h16.5a1.5 1.5 0 0 0 1.5-1.5V6a1.5 1.5 0 0 0-1.5-1.5H3.75A1.5 1.5 0 0 0 2.25 6v12a1.5 1.5 0 0 0 1.5 1.5Zm10.5-11.25h.008v.008h-.008V8.25Zm.375 0a.375.375 0 1 1-.75 0 .375.375 0 0 1 .75 0Z" />
                </svg>
              </div>
            </div>

            <!-- Gallery thumbnails -->
            <div v-if="selectedColor?.images?.length > 1" class="flex gap-2 p-4 border-t border-gray-100 overflow-x-auto">
              <div
                v-for="(img, idx) in selectedColor.images"
                :key="idx"
                class="w-16 h-16 rounded-xl overflow-hidden flex-shrink-0 cursor-pointer border-2 transition-all"
                :class="idx === 0 ? 'border-blue-500' : 'border-transparent hover:border-gray-300'"
                @click="lightboxImg = img.url"
              >
                <img :src="img.url" class="w-full h-full object-cover" />
              </div>
            </div>
          </div>

          <!-- Colors & Variants -->
          <div v-if="product.colors?.length" class="bg-white rounded-2xl border border-gray-100 shadow-sm p-6">
            <h3 class="text-sm font-bold text-gray-900 mb-4">Colors & Variants</h3>

            <!-- Color selector -->
            <div class="flex flex-wrap gap-2 mb-5">
              <button
                v-for="color in product.colors"
                :key="color.id"
                @click="selectColor(color)"
                class="flex items-center gap-2 px-3 py-2 rounded-xl text-xs font-semibold border transition-all"
                :class="selectedColor?.id === color.id
                  ? 'border-blue-500 text-blue-700 bg-blue-50'
                  : 'border-gray-200 text-gray-600 hover:border-gray-300'"
              >
                <div class="w-3.5 h-3.5 rounded-full border border-white shadow-sm" :style="`background:${color.hex};`"></div>
                {{ color.name }}
              </button>
            </div>

            <!-- Variants table -->
            <div v-if="selectedColor?.variants?.length" class="overflow-hidden rounded-xl border border-gray-100">
              <table class="w-full text-sm">
                <thead>
                  <tr style="background:#F8FAFF;">
                    <th class="text-left px-4 py-3 text-xs font-semibold text-gray-400 uppercase tracking-wider">Variant</th>
                    <th class="text-left px-4 py-3 text-xs font-semibold text-gray-400 uppercase tracking-wider">Price</th>
                    <th class="text-left px-4 py-3 text-xs font-semibold text-gray-400 uppercase tracking-wider">Stock</th>
                  </tr>
                </thead>
                <tbody class="divide-y divide-gray-50">
                  <tr v-for="v in selectedColor.variants" :key="v.id">
                    <td class="px-4 py-3 font-medium text-gray-900">{{ v.label }}</td>
                    <td class="px-4 py-3 font-semibold text-gray-800">{{ Number(v.price).toFixed(2) }} <span class="text-xs font-normal text-gray-400">JOD</span></td>
                    <td class="px-4 py-3">
                      <span class="px-2 py-0.5 rounded-lg text-xs font-semibold" :class="statusColor(v.stock)">
                        {{ v.stock === 0 ? 'Out of Stock' : v.stock <= 5 ? `Low · ${v.stock}` : v.stock }}
                      </span>
                    </td>
                  </tr>
                </tbody>
              </table>
            </div>
          </div>

          <!-- Specs -->
          <div v-if="product.specs?.length" class="bg-white rounded-2xl border border-gray-100 shadow-sm p-6">
            <h3 class="text-sm font-bold text-gray-900 mb-4">Specifications</h3>
            <div class="divide-y divide-gray-50">
              <div v-for="spec in product.specs" :key="spec.id" class="flex py-2.5">
                <span class="text-xs font-semibold text-gray-400 uppercase tracking-wide w-36 flex-shrink-0">{{ spec.key }}</span>
                <span class="text-sm text-gray-700 font-medium">{{ spec.value }}</span>
              </div>
            </div>
          </div>

          <!-- Description -->
          <div v-if="product.description" class="bg-white rounded-2xl border border-gray-100 shadow-sm p-6">
            <h3 class="text-sm font-bold text-gray-900 mb-3">Description</h3>
            <p class="text-sm text-gray-600 leading-relaxed">{{ product.description }}</p>
          </div>
        </div>

        <!-- Right: Summary -->
        <div class="space-y-5">

          <!-- Price card -->
          <div class="bg-white rounded-2xl border border-gray-100 shadow-sm p-6">
            <h3 class="text-xs font-semibold text-gray-400 uppercase tracking-wide mb-4">Pricing</h3>
            <div class="space-y-3">
              <div class="flex justify-between items-center">
                <span class="text-sm text-gray-500">Base Price</span>
                <span class="text-lg font-bold text-gray-900">{{ Number(product.base_price).toFixed(2) }} JOD</span>
              </div>
              <div v-if="product.original_price" class="flex justify-between items-center">
                <span class="text-sm text-gray-500">Original Price</span>
                <span class="text-sm text-gray-400 line-through">{{ Number(product.original_price).toFixed(2) }} JOD</span>
              </div>
              <div v-if="product.discount > 0" class="flex justify-between items-center">
                <span class="text-sm text-gray-500">Discount</span>
                <span class="text-sm font-semibold text-green-600">{{ product.discount }}% OFF</span>
              </div>
            </div>
          </div>

          <!-- Stock card (simple products) -->
          <div v-if="!product.has_variants" class="bg-white rounded-2xl border border-gray-100 shadow-sm p-6">
            <h3 class="text-xs font-semibold text-gray-400 uppercase tracking-wide mb-4">Stock</h3>
            <div class="flex items-center justify-between">
              <span class="text-3xl font-bold text-gray-900">{{ product.stock }}</span>
              <span class="px-3 py-1.5 rounded-xl text-xs font-semibold" :class="statusColor(product.stock)">
                {{ product.stock === 0 ? 'Out of Stock' : product.stock <= 5 ? 'Low Stock' : 'In Stock' }}
              </span>
            </div>
          </div>

          <!-- Tags -->
          <div class="bg-white rounded-2xl border border-gray-100 shadow-sm p-6">
            <h3 class="text-xs font-semibold text-gray-400 uppercase tracking-wide mb-4">Tags & Flags</h3>
            <div class="flex flex-wrap gap-2">
              <span v-if="product.is_featured"     class="px-3 py-1.5 rounded-xl text-xs font-semibold bg-violet-100 text-violet-700">Featured</span>
              <span v-if="product.is_best_selling" class="px-3 py-1.5 rounded-xl text-xs font-semibold bg-teal-100 text-teal-700">Top Seller</span>
              <span v-if="product.has_variants"    class="px-3 py-1.5 rounded-xl text-xs font-semibold bg-blue-100 text-blue-700">Has Variants</span>
              <span v-if="product.badge"           class="px-3 py-1.5 rounded-xl text-xs font-semibold" :class="badgeStyle(product.badge)">{{ product.badge }}</span>
              <span v-if="!product.is_featured && !product.is_best_selling && !product.has_variants && !product.badge"
                class="text-xs text-gray-400">No tags</span>
            </div>
          </div>

          <!-- Meta -->
          <div class="bg-white rounded-2xl border border-gray-100 shadow-sm p-6">
            <h3 class="text-xs font-semibold text-gray-400 uppercase tracking-wide mb-4">Info</h3>
            <div class="space-y-2.5">
              <div class="flex justify-between">
                <span class="text-xs text-gray-400">ID</span>
                <span class="text-xs font-mono text-gray-600">#{{ product.id }}</span>
              </div>
              <div class="flex justify-between">
                <span class="text-xs text-gray-400">Colors</span>
                <span class="text-xs font-semibold text-gray-700">{{ product.colors?.length ?? 0 }}</span>
              </div>
              <div class="flex justify-between">
                <span class="text-xs text-gray-400">Gallery images</span>
                <span class="text-xs font-semibold text-gray-700">{{ product.images?.length ?? 0 }}</span>
              </div>
              <div class="flex justify-between">
                <span class="text-xs text-gray-400">Specs</span>
                <span class="text-xs font-semibold text-gray-700">{{ product.specs?.length ?? 0 }}</span>
              </div>
            </div>
          </div>
        </div>
      </div>
    </template>

    <!-- Lightbox -->
    <teleport to="body">
      <div v-if="lightboxImg" class="fixed inset-0 z-50 flex items-center justify-center p-8"
        style="background:rgba(0,0,0,0.85);" @click="lightboxImg = null">
        <img :src="lightboxImg" class="max-h-full max-w-full rounded-2xl object-contain" @click.stop />
        <button @click="lightboxImg = null" class="absolute top-4 right-4 p-2 rounded-full bg-white/10 text-white hover:bg-white/20 transition-colors">
          <svg class="w-6 h-6" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
            <path stroke-linecap="round" stroke-linejoin="round" d="M6 18 18 6M6 6l12 12" />
          </svg>
        </button>
      </div>
    </teleport>
  </div>
</template>