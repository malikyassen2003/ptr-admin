<script setup>
import { ref, computed, onMounted, inject, watch } from 'vue'
import { useRouter } from 'vue-router'
import api from '@/api/axios'
import { getSpecTemplate } from '@/data/specTemplates.js'

const router  = useRouter()
const toast   = inject('toast')
const confirm = inject('confirm')

// ── State ──────────────────────────────────────────────────────────
const loading    = ref(true)
const saving     = ref(false)
const products   = ref([])
const categories = ref([])

// ── Filters ────────────────────────────────────────────────────────
const search           = ref('')
const selectedCategory = ref('')
const selectedBadge    = ref('')
const selectedStock    = ref('')
const page             = ref(1)
const perPage          = 12
const BADGES           = ['Hot', 'New', 'Sale']

// ── Modal ──────────────────────────────────────────────────────────
const showModal  = ref(false)
const editingId  = ref(null)
const activeTab  = ref('basic')
const formErrors = ref({})

// Basic info form
const form = ref({
  category_id: '', brand: '', name: '', short_spec: '',
  base_price: '', original_price: '', discount: 0, badge: '',
  description: '', image: '', is_featured: false,
  is_best_selling: false, has_variants: false, stock: 0,
})

// Specs form — array of { key, value, placeholder }
const specs = ref([])

// Colors & Variants form
const colors = ref([])
// color shape: { id?, name, hex, images: [{ url }], variants: [{ label, price, stock }] }

// ── Fetch ──────────────────────────────────────────────────────────
async function fetchProducts() {
  loading.value = true
  try {
    const { data } = await api.get('/api/admin/products')
    products.value   = data.products
    categories.value = data.categories
  } catch {
    toast?.show('Failed to load products.', 'error')
  } finally {
    loading.value = false
  }
}
onMounted(fetchProducts)

// ── Watch category → update spec template ─────────────────────────
watch(() => form.value.category_id, (catId) => {
  if (editingId.value) return // don't reset specs when editing
  const cat  = categories.value.find(c => c.id === Number(catId))
  const slug = cat?.name?.toLowerCase().replace(/\s+/g, '-') ?? ''
  const tmpl = getSpecTemplate(slug)
  specs.value = tmpl.map(t => ({ key: t.key, value: '', placeholder: t.placeholder }))
})

// ── Computed ───────────────────────────────────────────────────────
const filtered = computed(() => products.value.filter(p => {
  const q = search.value.toLowerCase()
  const matchSearch   = !q || p.name.toLowerCase().includes(q) || p.brand.toLowerCase().includes(q)
  const matchCategory = !selectedCategory.value || p.category === selectedCategory.value
  const matchBadge    = !selectedBadge.value || (selectedBadge.value === '__none__' ? !p.badge : p.badge === selectedBadge.value)
  let matchStock = true
  if      (selectedStock.value === 'out')      matchStock = !p.has_variants && p.stock === 0
  else if (selectedStock.value === 'low')      matchStock = !p.has_variants && p.stock > 0 && p.stock <= 5
  else if (selectedStock.value === 'variants') matchStock = p.has_variants
  return matchSearch && matchCategory && matchBadge && matchStock
}))

const totalPages = computed(() => Math.ceil(filtered.value.length / perPage))
const paginated  = computed(() => filtered.value.slice((page.value - 1) * perPage, page.value * perPage))

function clearFilters() {
  search.value = selectedCategory.value = selectedBadge.value = selectedStock.value = ''
  page.value = 1
}
const hasFilters = computed(() => search.value || selectedCategory.value || selectedBadge.value || selectedStock.value)

// ── Modal open/close ───────────────────────────────────────────────
function openCreate() {
  editingId.value  = null
  activeTab.value  = 'basic'
  formErrors.value = {}
  form.value = { category_id:'', brand:'', name:'', short_spec:'', base_price:'', original_price:'', discount:0, badge:'', description:'', image:'', is_featured:false, is_best_selling:false, has_variants:false, stock:0 }
  specs.value  = []
  colors.value = []
  showModal.value = true
}

async function openEdit(p) {
  editingId.value  = p.id
  activeTab.value  = 'basic'
  formErrors.value = {}

  // Load full product detail
  try {
    const { data } = await api.get(`/api/admin/products/${p.id}`)
    const cat = categories.value.find(c => c.name === data.category?.name)

    form.value = {
      category_id:     cat?.id ?? '',
      brand:           data.brand ?? '',
      name:            data.name ?? '',
      short_spec:      data.short_spec ?? '',
      base_price:      data.base_price ?? '',
      original_price:  data.original_price ?? '',
      discount:        data.discount ?? 0,
      badge:           data.badge ?? '',
      description:     data.description ?? '',
      image:           data.image ?? '',
      is_featured:     Boolean(data.is_featured),
      is_best_selling: Boolean(data.is_best_selling),
      has_variants:    Boolean(data.has_variants),
      stock:           data.stock ?? 0,
    }

    // Load specs
    const slug = cat?.name?.toLowerCase().replace(/\s+/g, '-') ?? ''
    const tmpl = getSpecTemplate(slug)
    if (data.specs?.length) {
      specs.value = data.specs.map(s => {
        const tmplItem = tmpl.find(t => t.key === s.key)
        return { key: s.key, value: s.value, placeholder: tmplItem?.placeholder ?? '' }
      })
      // Add any template keys not already in specs
      tmpl.forEach(t => {
        if (!specs.value.find(s => s.key === t.key)) {
          specs.value.push({ key: t.key, value: '', placeholder: t.placeholder })
        }
      })
    } else {
      specs.value = tmpl.map(t => ({ key: t.key, value: '', placeholder: t.placeholder }))
    }

    // Load colors & variants
    colors.value = (data.colors ?? []).map(c => ({
      id:       c.id,
      name:     c.name,
      hex:      c.hex,
      images:   (c.images ?? []).map(img => ({ url: img.url })),
      variants: (c.variants ?? []).map(v => ({
        id:    v.id,
        label: v.label,
        price: v.price,
        stock: v.stock,
      })),
    }))

    showModal.value = true
  } catch {
    toast?.show('Failed to load product details.', 'error')
  }
}

function closeModal() {
  showModal.value = false
}

// ── Spec helpers ───────────────────────────────────────────────────
function addSpec() {
  specs.value.push({ key: '', value: '', placeholder: 'Enter value' })
}

function removeSpec(idx) {
  specs.value.splice(idx, 1)
}

// ── Color helpers ──────────────────────────────────────────────────
function addColor() {
  colors.value.push({
    name: '', hex: '#1565C0',
    images: [{ url: '' }],
    variants: [{ label: '', price: '', stock: 0 }],
  })
}

function removeColor(idx) {
  colors.value.splice(idx, 1)
}

function addVariant(colorIdx) {
  colors.value[colorIdx].variants.push({ label: '', price: '', stock: 0 })
}

function removeVariant(colorIdx, varIdx) {
  colors.value[colorIdx].variants.splice(varIdx, 1)
}

function addImage(colorIdx) {
  colors.value[colorIdx].images.push({ url: '' })
}

function removeImage(colorIdx, imgIdx) {
  colors.value[colorIdx].images.splice(imgIdx, 1)
}

// ── Save ───────────────────────────────────────────────────────────
async function save() {
  if (saving.value) return
  formErrors.value = {}
  saving.value = true
  try {
    const payload = {
      ...form.value,
      badge:          form.value.badge || null,
      original_price: form.value.original_price || null,
      specs:  specs.value.filter(s => s.key && s.value).map((s, i) => ({
        key: s.key, value: s.value, sort_order: i,
      })),
      colors: colors.value.map(c => ({
        id:       c.id,
        name:     c.name,
        hex:      c.hex,
        images:   c.images.filter(img => img.url).map((img, i) => ({ url: img.url, sort_order: i })),
        variants: c.variants.filter(v => v.label).map(v => ({
          id:    v.id,
          label: v.label,
          price: v.price,
          stock: v.stock,
        })),
      })),
    }

    if (editingId.value) {
      await api.put(`/api/admin/products/${editingId.value}`, payload)
      toast?.show('Product updated successfully.')
    } else {
      await api.post('/api/admin/products', payload)
      toast?.show('Product created successfully.')
    }
    closeModal()
    await fetchProducts()
  } catch (err) {
    if (err.response?.data?.errors) {
      formErrors.value = err.response.data.errors
      activeTab.value  = 'basic' // show validation errors
    } else {
      toast?.show(err.response?.data?.message ?? 'Save failed.', 'error')
    }
  } finally {
    saving.value = false
  }
}

// ── Delete ─────────────────────────────────────────────────────────
async function deleteProduct(p) {
  const ok = await confirm?.open({
    title: 'Delete Product?',
    message: `"${p.name}" will be permanently deleted.`,
  })
  if (!ok) return
  try {
    await api.delete(`/api/admin/products/${p.id}`)
    toast?.show('Product deleted.')
    await fetchProducts()
  } catch {
    toast?.show('Failed to delete product.', 'error')
  }
}

// ── Helpers ────────────────────────────────────────────────────────
function stockInfo(p) {
  if (p.has_variants) return { text:'Variants',         cls:'bg-blue-100 text-blue-700'   }
  if (p.stock === 0)  return { text:'Out of Stock',     cls:'bg-red-100 text-red-700'     }
  if (p.stock <= 5)   return { text:`Low · ${p.stock}`, cls:'bg-amber-100 text-amber-700' }
  return                     { text: p.stock,           cls:'bg-green-100 text-green-700' }
}
function badgeStyle(b) {
  return { Hot:'bg-red-100 text-red-700', New:'bg-emerald-100 text-emerald-700', Sale:'bg-amber-100 text-amber-700' }[b] ?? 'bg-gray-100 text-gray-600'
}

const tabs = [
  { key:'basic',    label:'Basic Info'        },
  { key:'specs',    label:'Specifications'    },
  { key:'variants', label:'Colors & Variants' },
]
</script>

<template>
  <div>
    <!-- Loading -->
    <div v-if="loading" class="flex items-center justify-center py-24">
      <div class="flex flex-col items-center gap-3">
        <div class="w-10 h-10 rounded-full border-2 animate-spin" style="border-color:#1565C0;border-top-color:transparent;"></div>
        <p class="text-sm text-gray-400">Loading products…</p>
      </div>
    </div>

    <template v-else>
      <!-- Header -->
      <div class="mb-6 flex items-center justify-between">
        <div>
          <p class="text-xs font-semibold uppercase tracking-widest mb-1" style="color:#1565C0;">Catalogue</p>
          <h2 class="text-2xl font-bold text-gray-900">Products</h2>
        </div>
        <button @click="openCreate"
          class="flex items-center gap-2 px-4 py-2.5 rounded-xl text-sm font-semibold text-white transition-all hover:brightness-110 active:scale-95 shadow-sm"
          style="background:linear-gradient(135deg,#1565C0,#1976D2);">
          <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke-width="2.5" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M12 4.5v15m7.5-7.5h-15" /></svg>
          Add Product
        </button>
      </div>

      <!-- Filters -->
      <div class="bg-white rounded-2xl border border-gray-100 p-4 mb-5 shadow-sm">
        <div class="flex flex-wrap gap-3 items-center">
          <div class="relative flex-1 min-w-[180px]">
            <svg class="absolute left-3 top-1/2 -translate-y-1/2 w-4 h-4 text-gray-400 pointer-events-none" fill="none" viewBox="0 0 24 24" stroke-width="1.8" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="m21 21-5.197-5.197m0 0A7.5 7.5 0 1 0 5.196 5.196a7.5 7.5 0 0 0 10.607 10.607Z" /></svg>
            <input v-model="search" @input="page=1" type="text" placeholder="Search name or brand…"
              class="w-full pl-9 pr-4 py-2.5 text-sm border border-gray-200 rounded-xl focus:outline-none focus:ring-2 focus:ring-blue-600/20 focus:border-blue-400 transition-all bg-gray-50 focus:bg-white" />
          </div>
          <select v-model="selectedCategory" @change="page=1" class="px-3 py-2.5 text-sm border border-gray-200 rounded-xl focus:outline-none bg-gray-50 transition-all">
            <option value="">All Categories</option>
            <option v-for="cat in categories" :key="cat.id" :value="cat.name">{{ cat.name }}</option>
          </select>
          <select v-model="selectedBadge" @change="page=1" class="px-3 py-2.5 text-sm border border-gray-200 rounded-xl focus:outline-none bg-gray-50 transition-all">
            <option value="">All Badges</option>
            <option v-for="b in BADGES" :key="b" :value="b">{{ b }}</option>
            <option value="__none__">No Badge</option>
          </select>
          <select v-model="selectedStock" @change="page=1" class="px-3 py-2.5 text-sm border border-gray-200 rounded-xl focus:outline-none bg-gray-50 transition-all">
            <option value="">All Stock</option>
            <option value="out">Out of Stock</option>
            <option value="low">Low Stock (≤5)</option>
            <option value="variants">Has Variants</option>
          </select>
          <div class="flex items-center gap-2 ml-auto">
            <span class="text-xs text-gray-400 whitespace-nowrap">{{ filtered.length }} / {{ products.length }}</span>
            <button v-if="hasFilters" @click="clearFilters" class="text-xs px-2.5 py-1.5 rounded-lg text-gray-500 hover:text-gray-700 border border-gray-200 hover:bg-gray-50 transition-colors">Clear</button>
          </div>
        </div>
      </div>

      <!-- Table -->
      <div class="bg-white rounded-2xl border border-gray-100 overflow-hidden shadow-sm mb-5">
        <div class="overflow-x-auto">
          <table class="w-full text-sm">
            <thead>
              <tr class="border-b border-gray-100" style="background:#F8FAFF;">
                <th class="text-left px-5 py-4 text-xs font-semibold text-gray-400 uppercase tracking-wider">Product</th>
                <th class="text-left px-4 py-4 text-xs font-semibold text-gray-400 uppercase tracking-wider">Category</th>
                <th class="text-left px-4 py-4 text-xs font-semibold text-gray-400 uppercase tracking-wider">Price</th>
                <th class="text-left px-4 py-4 text-xs font-semibold text-gray-400 uppercase tracking-wider">Badge</th>
                <th class="text-left px-4 py-4 text-xs font-semibold text-gray-400 uppercase tracking-wider">Stock</th>
                <th class="text-left px-4 py-4 text-xs font-semibold text-gray-400 uppercase tracking-wider">Tags</th>
                <th class="px-4 py-4 w-24"></th>
              </tr>
            </thead>
            <tbody class="divide-y divide-gray-50">
              <tr v-for="p in paginated" :key="p.id"
                class="hover:bg-blue-50/20 transition-colors group cursor-pointer"
                @click="router.push({ name: 'admin.products.show', params: { id: p.id } })">
                <td class="px-5 py-3.5">
                  <div class="flex items-center gap-3">
                    <div class="w-10 h-10 rounded-xl bg-gray-100 overflow-hidden flex-shrink-0 border border-gray-100">
                      <img v-if="p.image" :src="p.image" :alt="p.name" class="w-full h-full object-cover" />
                      <div v-else class="w-full h-full flex items-center justify-center text-gray-300">
                        <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="m2.25 15.75 5.159-5.159a2.25 2.25 0 0 1 3.182 0l5.159 5.159m-1.5-1.5 1.409-1.409a2.25 2.25 0 0 1 3.182 0l2.909 2.909m-18 3.75h16.5a1.5 1.5 0 0 0 1.5-1.5V6a1.5 1.5 0 0 0-1.5-1.5H3.75A1.5 1.5 0 0 0 2.25 6v12a1.5 1.5 0 0 0 1.5 1.5Z" /></svg>
                      </div>
                    </div>
                    <div class="min-w-0">
                      <p class="font-semibold text-gray-900 leading-tight truncate max-w-[180px]">{{ p.name }}</p>
                      <p class="text-xs text-gray-400 mt-0.5">{{ p.brand }}</p>
                    </div>
                  </div>
                </td>
                <td class="px-4 py-3.5"><span class="text-xs text-gray-600 bg-gray-100 px-2 py-1 rounded-lg font-medium">{{ p.category }}</span></td>
                <td class="px-4 py-3.5 font-semibold text-gray-800 whitespace-nowrap">{{ Number(p.base_price).toFixed(2) }} <span class="text-xs font-normal text-gray-400">JOD</span></td>
                <td class="px-4 py-3.5">
                  <span v-if="p.badge" class="inline-flex px-2 py-0.5 rounded-lg text-xs font-semibold" :class="badgeStyle(p.badge)">{{ p.badge }}</span>
                  <span v-else class="text-gray-200 text-xs">—</span>
                </td>
                <td class="px-4 py-3.5"><span class="inline-flex px-2 py-0.5 rounded-lg text-xs font-semibold" :class="stockInfo(p).cls">{{ stockInfo(p).text }}</span></td>
                <td class="px-4 py-3.5">
                  <div class="flex flex-wrap gap-1">
                    <span v-if="p.is_featured"     class="px-1.5 py-0.5 rounded-md text-xs bg-violet-100 text-violet-700 font-medium">Featured</span>
                    <span v-if="p.is_best_selling" class="px-1.5 py-0.5 rounded-md text-xs bg-teal-100 text-teal-700 font-medium">Top Seller</span>
                  </div>
                </td>
                <td class="px-4 py-3.5" @click.stop>
                  <div class="flex items-center gap-1 opacity-0 group-hover:opacity-100 transition-opacity">
                    <button @click="openEdit(p)" class="p-1.5 rounded-lg text-gray-400 hover:text-blue-600 hover:bg-blue-50 transition-colors" title="Edit">
                      <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="m16.862 4.487 1.687-1.688a1.875 1.875 0 1 1 2.652 2.652L10.582 16.07a4.5 4.5 0 0 1-1.897 1.13L6 18l.8-2.685a4.5 4.5 0 0 1 1.13-1.897l8.932-8.931Z" /></svg>
                    </button>
                    <button @click="deleteProduct(p)" class="p-1.5 rounded-lg text-gray-400 hover:text-red-600 hover:bg-red-50 transition-colors" title="Delete">
                      <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="m14.74 9-.346 9m-4.788 0L9.26 9m9.968-3.21c.342.052.682.107 1.022.166m-1.022-.165L18.16 19.673a2.25 2.25 0 0 1-2.244 2.077H8.084a2.25 2.25 0 0 1-2.244-2.077L4.772 5.79m14.456 0a48.108 48.108 0 0 0-3.478-.397m-12 .562c.34-.059.68-.114 1.022-.165m0 0a48.11 48.11 0 0 1 3.478-.397m7.5 0v-.916c0-1.18-.91-2.164-2.09-2.201a51.964 51.964 0 0 0-3.32 0c-1.18.037-2.09 1.022-2.09 2.201v.916m7.5 0a48.667 48.667 0 0 0-7.5 0" /></svg>
                    </button>
                  </div>
                </td>
              </tr>
            </tbody>
          </table>
        </div>
        <div v-if="filtered.length === 0" class="text-center py-16">
          <p class="text-sm text-gray-400 mb-2">No products match your filters</p>
          <button @click="clearFilters" class="text-xs text-blue-600 hover:underline">Clear filters</button>
        </div>
      </div>

      <!-- Pagination -->
      <div v-if="totalPages > 1" class="flex items-center justify-between">
        <p class="text-xs text-gray-400">Showing {{ (page-1)*perPage+1 }}–{{ Math.min(page*perPage,filtered.length) }} of {{ filtered.length }}</p>
        <div class="flex items-center gap-1">
          <button @click="page--" :disabled="page===1" class="w-8 h-8 rounded-lg flex items-center justify-center border border-gray-200 transition-colors" :class="page===1?'text-gray-200 cursor-not-allowed':'text-gray-600 hover:bg-gray-50'">
            <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M15.75 19.5 8.25 12l7.5-7.5" /></svg>
          </button>
          <button v-for="n in totalPages" :key="n" @click="page=n" class="w-8 h-8 rounded-lg flex items-center justify-center text-xs font-semibold transition-colors border" :style="page===n?'background:#1565C0;color:white;border-color:#1565C0;':'border-color:#e5e7eb;color:#6b7280;'">{{ n }}</button>
          <button @click="page++" :disabled="page===totalPages" class="w-8 h-8 rounded-lg flex items-center justify-center border border-gray-200 transition-colors" :class="page===totalPages?'text-gray-200 cursor-not-allowed':'text-gray-600 hover:bg-gray-50'">
            <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="m8.25 4.5 7.5 7.5-7.5 7.5" /></svg>
          </button>
        </div>
      </div>
    </template>

    <!-- ══════════════════════════════════════════════════════════════
         MODAL — Tabbed Product Form
    ══════════════════════════════════════════════════════════════ -->
    <teleport to="body">
      <div v-if="showModal" class="fixed inset-0 z-50 flex items-center justify-center p-4" @click.self="closeModal">
        <div class="absolute inset-0" style="background:rgba(13,27,42,0.65);backdrop-filter:blur(6px);"></div>

        <div class="relative bg-white rounded-2xl shadow-2xl w-full max-w-3xl flex flex-col" style="max-height:92vh;" @click.stop>

          <!-- Modal header -->
          <div class="flex items-center justify-between px-6 py-4 border-b border-gray-100 flex-shrink-0">
            <h3 class="text-base font-bold text-gray-900">{{ editingId ? 'Edit Product' : 'Add New Product' }}</h3>
            <button @click="closeModal" class="p-1.5 rounded-lg text-gray-400 hover:bg-gray-100 transition-colors">
              <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M6 18 18 6M6 6l12 12" /></svg>
            </button>
          </div>

          <!-- Tabs -->
          <div class="flex border-b border-gray-100 flex-shrink-0 px-6">
            <button v-for="tab in tabs" :key="tab.key" @click="activeTab = tab.key"
              class="px-4 py-3 text-sm font-semibold border-b-2 transition-all mr-2"
              :style="activeTab === tab.key
                ? 'border-color:#1565C0;color:#1565C0;'
                : 'border-color:transparent;color:#9ca3af;'">
              {{ tab.label }}
            </button>
          </div>

          <!-- Tab content -->
          <div class="flex-1 overflow-y-auto p-6">

            <!-- ── Tab 1: Basic Info ──────────────────────────────── -->
            <div v-show="activeTab === 'basic'" class="space-y-5">

              <div class="grid grid-cols-2 gap-4">
                <div>
                  <label class="block text-xs font-semibold text-gray-500 mb-1.5 uppercase tracking-wide">Category *</label>
                  <select v-model="form.category_id" class="w-full px-3 py-2.5 text-sm border rounded-xl focus:outline-none focus:ring-2 focus:ring-blue-600/20 focus:border-blue-400 bg-gray-50 focus:bg-white transition-all" :class="formErrors.category_id?'border-red-300':'border-gray-200'">
                    <option value="">Select category</option>
                    <option v-for="cat in categories" :key="cat.id" :value="cat.id">{{ cat.name }}</option>
                  </select>
                  <p v-if="formErrors.category_id" class="text-xs text-red-500 mt-1">{{ formErrors.category_id[0] }}</p>
                </div>
                <div>
                  <label class="block text-xs font-semibold text-gray-500 mb-1.5 uppercase tracking-wide">Brand *</label>
                  <input v-model="form.brand" type="text" placeholder="e.g. ASUS, HP, Dell" class="w-full px-3 py-2.5 text-sm border rounded-xl focus:outline-none focus:ring-2 focus:ring-blue-600/20 focus:border-blue-400 bg-gray-50 focus:bg-white transition-all" :class="formErrors.brand?'border-red-300':'border-gray-200'" />
                  <p v-if="formErrors.brand" class="text-xs text-red-500 mt-1">{{ formErrors.brand[0] }}</p>
                </div>
              </div>

              <div>
                <label class="block text-xs font-semibold text-gray-500 mb-1.5 uppercase tracking-wide">Product Name *</label>
                <input v-model="form.name" type="text" placeholder="e.g. ROG Strix SCAR 16" class="w-full px-3 py-2.5 text-sm border rounded-xl focus:outline-none focus:ring-2 focus:ring-blue-600/20 focus:border-blue-400 bg-gray-50 focus:bg-white transition-all" :class="formErrors.name?'border-red-300':'border-gray-200'" />
                <p v-if="formErrors.name" class="text-xs text-red-500 mt-1">{{ formErrors.name[0] }}</p>
              </div>

              <div>
                <label class="block text-xs font-semibold text-gray-500 mb-1.5 uppercase tracking-wide">Short Spec *</label>
                <input v-model="form.short_spec" type="text" placeholder="e.g. Intel i9 | RTX 4090 | 32GB RAM" class="w-full px-3 py-2.5 text-sm border rounded-xl focus:outline-none focus:ring-2 focus:ring-blue-600/20 focus:border-blue-400 bg-gray-50 focus:bg-white transition-all" :class="formErrors.short_spec?'border-red-300':'border-gray-200'" />
              </div>

              <div class="grid grid-cols-4 gap-4">
                <div>
                  <label class="block text-xs font-semibold text-gray-500 mb-1.5 uppercase tracking-wide">Base Price *</label>
                  <input v-model="form.base_price" type="number" step="0.01" min="0" placeholder="0.00" class="w-full px-3 py-2.5 text-sm border rounded-xl focus:outline-none focus:ring-2 focus:ring-blue-600/20 bg-gray-50 focus:bg-white transition-all" :class="formErrors.base_price?'border-red-300':'border-gray-200'" />
                </div>
                <div>
                  <label class="block text-xs font-semibold text-gray-500 mb-1.5 uppercase tracking-wide">Original Price</label>
                  <input v-model="form.original_price" type="number" step="0.01" min="0" placeholder="0.00" class="w-full px-3 py-2.5 text-sm border border-gray-200 rounded-xl focus:outline-none focus:ring-2 focus:ring-blue-600/20 bg-gray-50 focus:bg-white transition-all" />
                </div>
                <div>
                  <label class="block text-xs font-semibold text-gray-500 mb-1.5 uppercase tracking-wide">Discount %</label>
                  <input v-model="form.discount" type="number" min="0" max="100" placeholder="0" class="w-full px-3 py-2.5 text-sm border border-gray-200 rounded-xl focus:outline-none focus:ring-2 focus:ring-blue-600/20 bg-gray-50 focus:bg-white transition-all" />
                </div>
                <div>
                  <label class="block text-xs font-semibold text-gray-500 mb-1.5 uppercase tracking-wide">Badge</label>
                  <select v-model="form.badge" class="w-full px-3 py-2.5 text-sm border border-gray-200 rounded-xl focus:outline-none focus:ring-2 focus:ring-blue-600/20 bg-gray-50 focus:bg-white transition-all">
                    <option value="">None</option>
                    <option v-for="b in BADGES" :key="b" :value="b">{{ b }}</option>
                  </select>
                </div>
              </div>

              <div>
                <label class="block text-xs font-semibold text-gray-500 mb-1.5 uppercase tracking-wide">Main Image URL</label>
                <input v-model="form.image" type="text" placeholder="https://…" class="w-full px-3 py-2.5 text-sm border border-gray-200 rounded-xl focus:outline-none focus:ring-2 focus:ring-blue-600/20 bg-gray-50 focus:bg-white transition-all" />
                <div v-if="form.image" class="mt-2 w-16 h-16 rounded-xl overflow-hidden border border-gray-200 bg-gray-100">
                  <img :src="form.image" class="w-full h-full object-cover" />
                </div>
              </div>

              <div>
                <label class="block text-xs font-semibold text-gray-500 mb-1.5 uppercase tracking-wide">Description</label>
                <textarea v-model="form.description" rows="3" placeholder="Product description…" class="w-full px-3 py-2.5 text-sm border border-gray-200 rounded-xl focus:outline-none focus:ring-2 focus:ring-blue-600/20 bg-gray-50 focus:bg-white transition-all resize-none"></textarea>
              </div>

              <div class="grid grid-cols-2 gap-4">
                <div>
                  <label class="block text-xs font-semibold text-gray-500 mb-1.5 uppercase tracking-wide">Stock (simple products)</label>
                  <input v-model="form.stock" type="number" min="0" placeholder="0" :disabled="form.has_variants"
                    class="w-full px-3 py-2.5 text-sm border border-gray-200 rounded-xl focus:outline-none focus:ring-2 focus:ring-blue-600/20 transition-all"
                    :class="form.has_variants ? 'bg-gray-100 text-gray-400 cursor-not-allowed' : 'bg-gray-50 focus:bg-white focus:border-blue-400'" />
                  <p class="text-xs text-gray-400 mt-1">Ignored when Has Variants is enabled</p>
                </div>
                <div class="space-y-3 pt-1">
                  <label v-for="field in ['is_featured','is_best_selling','has_variants']" :key="field" class="flex items-center gap-3 cursor-pointer">
                    <div class="relative w-9 h-5 flex-shrink-0 cursor-pointer" @click="form[field]=!form[field]">
                      <div class="absolute inset-0 rounded-full transition-colors" :style="form[field]?'background:#1565C0;':'background:#d1d5db;'"></div>
                      <div class="absolute top-0.5 left-0.5 w-4 h-4 bg-white rounded-full shadow transition-transform" :style="form[field]?'transform:translateX(16px);':''"></div>
                    </div>
                    <span class="text-sm text-gray-700 font-medium capitalize">{{ field.replace(/_/g,' ').replace('is ','') }}</span>
                  </label>
                </div>
              </div>
            </div>

            <!-- ── Tab 2: Specifications ──────────────────────────── -->
            <div v-show="activeTab === 'specs'">
              <div v-if="!form.category_id" class="text-center py-12">
                <div class="w-12 h-12 rounded-2xl mx-auto mb-3 flex items-center justify-center" style="background:#EFF6FF;">
                  <svg class="w-6 h-6" style="color:#1565C0;" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M9.568 3H5.25A2.25 2.25 0 0 0 3 5.25v4.318c0 .597.237 1.17.659 1.591l9.581 9.581c.699.699 1.78.872 2.607.33a18.095 18.095 0 0 0 5.223-5.223c.542-.827.369-1.908-.33-2.607L11.16 3.66A2.25 2.25 0 0 0 9.568 3Z" /></svg>
                </div>
                <p class="text-sm font-semibold text-gray-700 mb-1">Select a category first</p>
                <p class="text-xs text-gray-400">Specs are auto-loaded based on the selected category</p>
                <button @click="activeTab='basic'" class="mt-3 text-xs text-blue-600 hover:underline">Go to Basic Info →</button>
              </div>

              <template v-else>
                <div class="flex items-center justify-between mb-4">
                  <p class="text-xs text-gray-400">
                    Showing specs for <span class="font-semibold text-gray-700">{{ categories.find(c=>c.id===Number(form.category_id))?.name }}</span>
                  </p>
                  <button @click="addSpec"
                    class="flex items-center gap-1.5 px-3 py-1.5 rounded-lg text-xs font-semibold border transition-colors"
                    style="border-color:#1565C0;color:#1565C0;">
                    <svg class="w-3.5 h-3.5" fill="none" viewBox="0 0 24 24" stroke-width="2.5" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M12 4.5v15m7.5-7.5h-15" /></svg>
                    Add Custom Spec
                  </button>
                </div>

                <div class="space-y-3">
                  <div v-for="(spec, idx) in specs" :key="idx"
                    class="flex items-center gap-3 p-3 rounded-xl border border-gray-100 bg-gray-50">
                    <input v-model="spec.key" type="text" placeholder="Spec name"
                      class="w-36 flex-shrink-0 px-3 py-2 text-sm border border-gray-200 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-600/20 bg-white font-medium" />
                    <input v-model="spec.value" type="text" :placeholder="spec.placeholder || 'Value'"
                      class="flex-1 px-3 py-2 text-sm border border-gray-200 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-600/20 bg-white" />
                    <button @click="removeSpec(idx)" class="p-1.5 rounded-lg text-gray-300 hover:text-red-500 hover:bg-red-50 transition-colors flex-shrink-0">
                      <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M6 18 18 6M6 6l12 12" /></svg>
                    </button>
                  </div>
                  <div v-if="specs.length === 0" class="text-center py-6 text-sm text-gray-400">
                    No specs yet. Click "Add Custom Spec" to add one.
                  </div>
                </div>
              </template>
            </div>

            <!-- ── Tab 3: Colors & Variants ──────────────────────── -->
            <div v-show="activeTab === 'variants'">
              <div class="flex items-center justify-between mb-5">
                <div>
                  <p class="text-sm font-bold text-gray-900">Colors & Variants</p>
                  <p class="text-xs text-gray-400 mt-0.5">Each color can have its own images and multiple variants with price + stock</p>
                </div>
                <button @click="addColor"
                  class="flex items-center gap-1.5 px-3 py-2 rounded-xl text-sm font-semibold text-white transition-all hover:brightness-110"
                  style="background:linear-gradient(135deg,#1565C0,#1976D2);">
                  <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke-width="2.5" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M12 4.5v15m7.5-7.5h-15" /></svg>
                  Add Color
                </button>
              </div>

              <div v-if="colors.length === 0" class="text-center py-12 border-2 border-dashed border-gray-200 rounded-2xl">
                <div class="w-12 h-12 rounded-2xl mx-auto mb-3 flex items-center justify-center" style="background:#EFF6FF;">
                  <svg class="w-6 h-6" style="color:#1565C0;" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M4.098 19.902a3.75 3.75 0 0 0 5.304 0l6.401-6.402M6.75 21A3.75 3.75 0 0 1 3 17.25V4.125C3 3.504 3.504 3 4.125 3h5.25c.621 0 1.125.504 1.125 1.125v4.072M6.75 21a3.75 3.75 0 0 0 3.75-3.75V8.197M6.75 21h13.125c.621 0 1.125-.504 1.125-1.125v-5.25c0-.621-.504-1.125-1.125-1.125h-4.072M10.5 8.197l2.88-2.88c.438-.439 1.15-.439 1.59 0l3.712 3.713c.44.44.44 1.152 0 1.59l-2.879 2.88M6.75 17.25h.008v.008H6.75v-.008Z" />
                  </svg>
                </div>
                <p class="text-sm font-semibold text-gray-700 mb-1">No colors added yet</p>
                <p class="text-xs text-gray-400 mb-3">Add colors to enable variant-based stock and pricing</p>
                <button @click="addColor" class="text-xs text-blue-600 hover:underline">+ Add first color</button>
              </div>

              <div v-else class="space-y-5">
                <div v-for="(color, ci) in colors" :key="ci"
                  class="border border-gray-200 rounded-2xl overflow-hidden">

                  <!-- Color header -->
                  <div class="flex items-center gap-3 px-4 py-3 border-b border-gray-100" style="background:#F8FAFF;">
                    <input type="color" v-model="color.hex"
                      class="w-9 h-9 rounded-lg border border-gray-200 cursor-pointer p-0.5 bg-white" />
                    <input v-model="color.name" type="text" placeholder="Color name (e.g. Midnight Black)"
                      class="flex-1 px-3 py-2 text-sm border border-gray-200 rounded-xl focus:outline-none focus:ring-2 focus:ring-blue-600/20 bg-white font-medium" />
                    <button @click="removeColor(ci)" class="p-1.5 rounded-lg text-gray-300 hover:text-red-500 hover:bg-red-50 transition-colors flex-shrink-0">
                      <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M6 18 18 6M6 6l12 12" /></svg>
                    </button>
                  </div>

                  <div class="p-4 space-y-4">

                    <!-- Images for this color -->
                    <div>
                      <div class="flex items-center justify-between mb-2">
                        <p class="text-xs font-semibold text-gray-500 uppercase tracking-wide">Images for this color</p>
                        <button @click="addImage(ci)" class="text-xs text-blue-600 hover:underline">+ Add image</button>
                      </div>
                      <div class="space-y-2">
                        <div v-for="(img, ii) in color.images" :key="ii" class="flex items-center gap-2">
                          <div class="w-10 h-10 rounded-lg overflow-hidden bg-gray-100 border border-gray-200 flex-shrink-0">
                            <img v-if="img.url" :src="img.url" class="w-full h-full object-cover" />
                            <div v-else class="w-full h-full flex items-center justify-center text-gray-300">
                              <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="m2.25 15.75 5.159-5.159a2.25 2.25 0 0 1 3.182 0l5.159 5.159m-1.5-1.5 1.409-1.409a2.25 2.25 0 0 1 3.182 0l2.909 2.909" /></svg>
                            </div>
                          </div>
                          <input v-model="img.url" type="text" placeholder="Image URL https://…"
                            class="flex-1 px-3 py-2 text-sm border border-gray-200 rounded-xl focus:outline-none focus:ring-2 focus:ring-blue-600/20 bg-gray-50 focus:bg-white" />
                          <button @click="removeImage(ci,ii)" class="p-1.5 rounded-lg text-gray-300 hover:text-red-500 hover:bg-red-50 transition-colors flex-shrink-0">
                            <svg class="w-3.5 h-3.5" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M6 18 18 6M6 6l12 12" /></svg>
                          </button>
                        </div>
                      </div>
                    </div>

                    <!-- Variants for this color -->
                    <div>
                      <div class="flex items-center justify-between mb-2">
                        <p class="text-xs font-semibold text-gray-500 uppercase tracking-wide">Variants (label / price / stock)</p>
                        <button @click="addVariant(ci)" class="text-xs text-blue-600 hover:underline">+ Add variant</button>
                      </div>

                      <!-- Header row -->
                      <div class="grid grid-cols-12 gap-2 px-1 mb-1">
                        <span class="col-span-5 text-xs text-gray-400 font-medium">Label</span>
                        <span class="col-span-3 text-xs text-gray-400 font-medium">Price (JOD)</span>
                        <span class="col-span-3 text-xs text-gray-400 font-medium">Stock</span>
                        <span class="col-span-1"></span>
                      </div>

                      <div class="space-y-2">
                        <div v-for="(variant, vi) in color.variants" :key="vi" class="grid grid-cols-12 gap-2 items-center">
                          <input v-model="variant.label" type="text" placeholder="e.g. 16GB / 512GB SSD"
                            class="col-span-5 px-3 py-2 text-sm border border-gray-200 rounded-xl focus:outline-none focus:ring-2 focus:ring-blue-600/20 bg-gray-50 focus:bg-white" />
                          <input v-model="variant.price" type="number" step="0.01" min="0" placeholder="0.00"
                            class="col-span-3 px-3 py-2 text-sm border border-gray-200 rounded-xl focus:outline-none focus:ring-2 focus:ring-blue-600/20 bg-gray-50 focus:bg-white" />
                          <input v-model="variant.stock" type="number" min="0" placeholder="0"
                            class="col-span-3 px-3 py-2 text-sm border border-gray-200 rounded-xl focus:outline-none focus:ring-2 focus:ring-blue-600/20 bg-gray-50 focus:bg-white" />
                          <button @click="removeVariant(ci,vi)" class="col-span-1 p-1.5 rounded-lg text-gray-300 hover:text-red-500 hover:bg-red-50 transition-colors flex justify-center">
                            <svg class="w-3.5 h-3.5" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M6 18 18 6M6 6l12 12" /></svg>
                          </button>
                        </div>
                      </div>
                    </div>
                  </div>
                </div>
              </div>
            </div>

          </div><!-- end tab content -->

          <!-- Modal footer -->
          <div class="flex items-center justify-between px-6 py-4 border-t border-gray-100 flex-shrink-0 bg-white">
            <div class="flex gap-2">
              <button v-for="tab in tabs" :key="tab.key" @click="activeTab=tab.key"
                class="px-3 py-1.5 rounded-lg text-xs font-semibold transition-all border"
                :style="activeTab===tab.key?'background:#EFF6FF;color:#1565C0;border-color:#BFDBFE;':'border-color:#e5e7eb;color:#9ca3af;'">
                {{ tab.label }}
              </button>
            </div>
            <div class="flex gap-3">
              <button @click="closeModal" class="px-5 py-2.5 rounded-xl text-sm font-semibold text-gray-600 border border-gray-200 hover:bg-gray-50 transition-colors">Cancel</button>
              <button @click="save" :disabled="saving"
                class="px-5 py-2.5 rounded-xl text-sm font-semibold text-white transition-all hover:brightness-110 active:scale-95 flex items-center gap-2"
                style="background:linear-gradient(135deg,#1565C0,#1976D2);"
                :class="saving?'opacity-70 cursor-not-allowed':''">
                <svg v-if="saving" class="w-4 h-4 animate-spin" fill="none" viewBox="0 0 24 24">
                  <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"/>
                  <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8v8z"/>
                </svg>
                {{ saving ? 'Saving…' : editingId ? 'Update Product' : 'Create Product' }}
              </button>
            </div>
          </div>

        </div>
      </div>
    </teleport>
  </div>
</template>

<style scoped>
.fade-enter-active, .fade-leave-active { transition: opacity 0.2s ease; }
.fade-enter-from, .fade-leave-to { opacity: 0; }
</style>