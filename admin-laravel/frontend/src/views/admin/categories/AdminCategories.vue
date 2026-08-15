<script setup>
import { ref, computed, onMounted, inject } from 'vue'
import api from '@/api/axios'

const toast   = inject('toast')
const confirm = inject('confirm')

const loading    = ref(true)
const saving     = ref(false)
const categories = ref([])
const search     = ref('')

const showModal  = ref(false)
const editingId  = ref(null)
const form       = ref({ name: '', icon: '' })
const formErrors = ref({})

// ── Fetch ──────────────────────────────────────────────────────────
async function fetchCategories() {
  loading.value = true
  try {
    const { data } = await api.get('/api/admin/categories')
    categories.value = data.categories
  } catch {
    toast?.show('Failed to load categories.', 'error')
  } finally {
    loading.value = false
  }
}
onMounted(fetchCategories)

// ── Computed ───────────────────────────────────────────────────────
const filtered = computed(() =>
  categories.value.filter(c =>
    !search.value ||
    c.name.toLowerCase().includes(search.value.toLowerCase()) ||
    c.slug.toLowerCase().includes(search.value.toLowerCase())
  )
)

const totalProducts = computed(() =>
  categories.value.reduce((sum, c) => sum + c.products_count, 0)
)

// ── Modal helpers ──────────────────────────────────────────────────
function openCreate() {
  editingId.value  = null
  form.value       = { name: '', icon: '' }
  formErrors.value = {}
  showModal.value  = true
}

function openEdit(cat) {
  editingId.value  = cat.id
  form.value       = { name: cat.name, icon: cat.icon ?? '' }
  formErrors.value = {}
  showModal.value  = true
}

function closeModal() {
  showModal.value = false
}

// ── Save ───────────────────────────────────────────────────────────
async function save() {
  if (saving.value) return
  formErrors.value = {}
  saving.value = true
  try {
    const payload = { name: form.value.name, icon: form.value.icon || null }
    if (editingId.value) {
      await api.put(`/api/admin/categories/${editingId.value}`, payload)
      toast?.show('Category updated successfully.')
    } else {
      await api.post('/api/admin/categories', payload)
      toast?.show('Category created successfully.')
    }
    closeModal()
    await fetchCategories()
  } catch (err) {
    if (err.response?.data?.errors) formErrors.value = err.response.data.errors
    else toast?.show(err.response?.data?.message ?? 'Save failed.', 'error')
  } finally {
    saving.value = false
  }
}

// ── Delete ─────────────────────────────────────────────────────────
async function deleteCategory(cat) {
  const ok = await confirm?.open({
    title:   'Delete Category?',
    message: cat.products_count > 0
      ? `"${cat.name}" has ${cat.products_count} products. Remove them first.`
      : `"${cat.name}" will be permanently deleted.`,
  })
  if (!ok) return
  try {
    await api.delete(`/api/admin/categories/${cat.id}`)
    toast?.show('Category deleted.')
    await fetchCategories()
  } catch (err) {
    toast?.show(err.response?.data?.message ?? 'Failed to delete.', 'error')
  }
}
</script>

<template>
  <div>
    <!-- Loading -->
    <div v-if="loading" class="flex items-center justify-center py-24">
      <div class="flex flex-col items-center gap-3">
        <div class="w-10 h-10 rounded-full border-2 animate-spin" style="border-color:#1565C0;border-top-color:transparent;"></div>
        <p class="text-sm text-gray-400">Loading categories…</p>
      </div>
    </div>

    <template v-else>
      <!-- Header -->
      <div class="mb-6 flex items-center justify-between">
        <div>
          <p class="text-xs font-semibold uppercase tracking-widest mb-1" style="color:#1565C0;">Catalogue</p>
          <h2 class="text-2xl font-bold text-gray-900">Categories</h2>
        </div>
        <button @click="openCreate"
          class="flex items-center gap-2 px-4 py-2.5 rounded-xl text-sm font-semibold text-white transition-all hover:brightness-110 active:scale-95 shadow-sm"
          style="background:linear-gradient(135deg,#1565C0,#1976D2);">
          <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke-width="2.5" stroke="currentColor">
            <path stroke-linecap="round" stroke-linejoin="round" d="M12 4.5v15m7.5-7.5h-15" />
          </svg>
          Add Category
        </button>
      </div>

      <!-- Stats -->
      <div class="grid grid-cols-2 gap-4 mb-6">
        <div class="bg-white rounded-2xl border border-gray-100 px-6 py-4 flex items-center gap-4 shadow-sm">
          <div class="w-10 h-10 rounded-xl flex items-center justify-center flex-shrink-0" style="background:#EFF6FF;">
            <svg class="w-5 h-5" style="color:#1565C0;" fill="none" viewBox="0 0 24 24" stroke-width="1.6" stroke="currentColor">
              <path stroke-linecap="round" stroke-linejoin="round" d="M9.568 3H5.25A2.25 2.25 0 0 0 3 5.25v4.318c0 .597.237 1.17.659 1.591l9.581 9.581c.699.699 1.78.872 2.607.33a18.095 18.095 0 0 0 5.223-5.223c.542-.827.369-1.908-.33-2.607L11.16 3.66A2.25 2.25 0 0 0 9.568 3Z" />
              <path stroke-linecap="round" stroke-linejoin="round" d="M6 6h.008v.008H6V6Z" />
            </svg>
          </div>
          <div>
            <p class="text-2xl font-bold text-gray-900">{{ categories.length }}</p>
            <p class="text-sm text-gray-500">Total Categories</p>
          </div>
        </div>
        <div class="bg-white rounded-2xl border border-gray-100 px-6 py-4 flex items-center gap-4 shadow-sm">
          <div class="w-10 h-10 rounded-xl flex items-center justify-center flex-shrink-0" style="background:#EFF6FF;">
            <svg class="w-5 h-5" style="color:#1565C0;" fill="none" viewBox="0 0 24 24" stroke-width="1.6" stroke="currentColor">
              <path stroke-linecap="round" stroke-linejoin="round" d="m21 7.5-9-5.25L3 7.5m18 0-9 5.25m9-5.25v9l-9 5.25M3 7.5l9 5.25M3 7.5v9l9 5.25m0-9v9" />
            </svg>
          </div>
          <div>
            <p class="text-2xl font-bold text-gray-900">{{ totalProducts }}</p>
            <p class="text-sm text-gray-500">Products Across All</p>
          </div>
        </div>
      </div>

      <!-- Search -->
      <div class="bg-white rounded-2xl border border-gray-100 p-4 mb-5 shadow-sm">
        <div class="relative max-w-sm">
          <svg class="absolute left-3 top-1/2 -translate-y-1/2 w-4 h-4 text-gray-400 pointer-events-none" fill="none" viewBox="0 0 24 24" stroke-width="1.8" stroke="currentColor">
            <path stroke-linecap="round" stroke-linejoin="round" d="m21 21-5.197-5.197m0 0A7.5 7.5 0 1 0 5.196 5.196a7.5 7.5 0 0 0 10.607 10.607Z" />
          </svg>
          <input v-model="search" type="text" placeholder="Filter categories…"
            class="w-full pl-9 pr-4 py-2.5 text-sm border border-gray-200 rounded-xl focus:outline-none focus:ring-2 focus:ring-blue-600/20 focus:border-blue-400 transition-all bg-gray-50 focus:bg-white" />
        </div>
      </div>

      <!-- Table -->
      <div class="bg-white rounded-2xl border border-gray-100 overflow-hidden shadow-sm">
        <table class="w-full text-sm">
          <thead>
            <tr class="border-b border-gray-100" style="background:#F8FAFF;">
              <th class="text-left px-6 py-4 text-xs font-semibold text-gray-400 uppercase tracking-wider">#</th>
              <th class="text-left px-4 py-4 text-xs font-semibold text-gray-400 uppercase tracking-wider">Name</th>
              <th class="text-left px-4 py-4 text-xs font-semibold text-gray-400 uppercase tracking-wider">Slug</th>
              <th class="text-left px-4 py-4 text-xs font-semibold text-gray-400 uppercase tracking-wider">Icon</th>
              <th class="text-left px-4 py-4 text-xs font-semibold text-gray-400 uppercase tracking-wider">Products</th>
              <th class="px-4 py-4 w-20"></th>
            </tr>
          </thead>
          <tbody class="divide-y divide-gray-50">
            <tr v-for="(cat, idx) in filtered" :key="cat.id" class="hover:bg-blue-50/20 transition-colors group">
              <td class="px-6 py-4 text-xs text-gray-300 font-mono">{{ idx + 1 }}</td>
              <td class="px-4 py-4 font-semibold text-gray-900">{{ cat.name }}</td>
              <td class="px-4 py-4">
                <span class="text-xs font-mono text-gray-500 bg-gray-100 px-2 py-1 rounded-lg">{{ cat.slug }}</span>
              </td>
              <td class="px-4 py-4">
                <span class="text-xs font-mono text-gray-500 bg-gray-100 px-2 py-1 rounded-lg">{{ cat.icon ?? '—' }}</span>
              </td>
              <td class="px-4 py-4">
                <div class="flex items-center gap-2">
                  <div class="flex-1 max-w-[80px] bg-gray-100 rounded-full h-1.5">
                    <div class="h-full rounded-full transition-all duration-700"
                      :style="{ width: totalProducts > 0 ? (cat.products_count / totalProducts) * 100 + '%' : '0%', background:'#1565C0' }">
                    </div>
                  </div>
                  <span class="text-xs font-semibold px-2 py-0.5 rounded-full"
                    :class="cat.products_count === 0 ? 'bg-gray-100 text-gray-400' : 'bg-blue-100 text-blue-700'">
                    {{ cat.products_count }}
                  </span>
                </div>
              </td>
              <td class="px-4 py-4">
                <div class="flex items-center gap-1 opacity-0 group-hover:opacity-100 transition-opacity">
                  <button @click.stop="openEdit(cat)" class="p-1.5 rounded-lg text-gray-400 hover:text-blue-600 hover:bg-blue-50 transition-colors" title="Edit">
                    <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                      <path stroke-linecap="round" stroke-linejoin="round" d="m16.862 4.487 1.687-1.688a1.875 1.875 0 1 1 2.652 2.652L10.582 16.07a4.5 4.5 0 0 1-1.897 1.13L6 18l.8-2.685a4.5 4.5 0 0 1 1.13-1.897l8.932-8.931Z" />
                    </svg>
                  </button>
                  <button @click.stop="deleteCategory(cat)" class="p-1.5 rounded-lg text-gray-400 hover:text-red-600 hover:bg-red-50 transition-colors" title="Delete">
                    <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                      <path stroke-linecap="round" stroke-linejoin="round" d="m14.74 9-.346 9m-4.788 0L9.26 9m9.968-3.21c.342.052.682.107 1.022.166m-1.022-.165L18.16 19.673a2.25 2.25 0 0 1-2.244 2.077H8.084a2.25 2.25 0 0 1-2.244-2.077L4.772 5.79m14.456 0a48.108 48.108 0 0 0-3.478-.397m-12 .562c.34-.059.68-.114 1.022-.165m0 0a48.11 48.11 0 0 1 3.478-.397m7.5 0v-.916c0-1.18-.91-2.164-2.09-2.201a51.964 51.964 0 0 0-3.32 0c-1.18.037-2.09 1.022-2.09 2.201v.916m7.5 0a48.667 48.667 0 0 0-7.5 0" />
                    </svg>
                  </button>
                </div>
              </td>
            </tr>
          </tbody>
        </table>

        <div v-if="filtered.length === 0" class="text-center py-12">
          <p class="text-sm text-gray-400">No categories match "{{ search }}"</p>
        </div>
      </div>
    </template>

    <!-- ── Modal ──────────────────────────────────────────────────── -->
    <teleport to="body">
      <div v-if="showModal" class="fixed inset-0 z-50 flex items-center justify-center p-4" @click.self="closeModal">
        <div style="background:rgba(13,27,42,0.6);backdrop-filter:blur(4px);" class="absolute inset-0"></div>

        <!-- Modal box -->
        <div class="relative bg-white rounded-2xl shadow-2xl w-full max-w-md" @click.stop>

          <!-- Header -->
          <div class="flex items-center justify-between px-6 py-5 border-b border-gray-100">
            <h3 class="text-base font-bold text-gray-900">{{ editingId ? 'Edit Category' : 'Add New Category' }}</h3>
            <button @click="closeModal" class="p-1.5 rounded-lg text-gray-400 hover:bg-gray-100 transition-colors">
              <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" d="M6 18 18 6M6 6l12 12" />
              </svg>
            </button>
          </div>

          <!-- Form -->
          <div class="p-6 space-y-4">
            <div>
              <label class="block text-xs font-semibold text-gray-500 mb-1.5 uppercase tracking-wide">Category Name *</label>
              <input v-model="form.name" type="text" placeholder="e.g. Gaming Laptop"
                class="w-full px-3 py-2.5 text-sm border rounded-xl focus:outline-none focus:ring-2 focus:ring-blue-600/20 focus:border-blue-400 transition-all bg-gray-50 focus:bg-white"
                :class="formErrors.name ? 'border-red-300' : 'border-gray-200'"
                @keyup.enter="save" />
              <p v-if="formErrors.name" class="text-xs text-red-500 mt-1">{{ formErrors.name[0] }}</p>
            </div>
            <div>
              <label class="block text-xs font-semibold text-gray-500 mb-1.5 uppercase tracking-wide">Icon (MDI class)</label>
              <input v-model="form.icon" type="text" placeholder="e.g. mdi-laptop"
                class="w-full px-3 py-2.5 text-sm border border-gray-200 rounded-xl focus:outline-none focus:ring-2 focus:ring-blue-600/20 focus:border-blue-400 transition-all bg-gray-50 focus:bg-white"
                @keyup.enter="save" />
              <p class="text-xs text-gray-400 mt-1">Optional — used in the storefront category icons</p>
            </div>
          </div>

          <!-- Footer -->
          <div class="flex items-center justify-end gap-3 px-6 py-4 border-t border-gray-100">
            <button @click="closeModal" class="px-5 py-2.5 rounded-xl text-sm font-semibold text-gray-600 border border-gray-200 hover:bg-gray-50 transition-colors">
              Cancel
            </button>
            <button @click="save" :disabled="saving"
              class="px-5 py-2.5 rounded-xl text-sm font-semibold text-white transition-all hover:brightness-110 active:scale-95 flex items-center gap-2"
              style="background:linear-gradient(135deg,#1565C0,#1976D2);"
              :class="saving ? 'opacity-70 cursor-not-allowed' : ''">
              <svg v-if="saving" class="w-4 h-4 animate-spin" fill="none" viewBox="0 0 24 24">
                <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"/>
                <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8v8z"/>
              </svg>
              {{ saving ? 'Saving…' : editingId ? 'Update Category' : 'Create Category' }}
            </button>
          </div>
        </div>
      </div>
    </teleport>
  </div>
</template>