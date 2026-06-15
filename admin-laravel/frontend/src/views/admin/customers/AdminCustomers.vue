<script setup>
import { ref, onMounted, inject } from 'vue'
import api from '@/api/axios'

const toast   = inject('toast')
const confirm = inject('confirm')

const loading   = ref(true)
const customers = ref([])
const search    = ref('')
const meta      = ref({ current_page: 1, last_page: 1, total: 0 })
let   searchTimer = null

async function fetchCustomers(page = 1) {
  loading.value = true
  try {
    const { data } = await api.get('/api/admin/customers', {
      params: { search: search.value, page },
    })
    customers.value = data.data
    meta.value = {
      current_page: data.current_page,
      last_page:    data.last_page,
      total:        data.total,
    }
  } catch {
    toast?.show('Failed to load customers.', 'error')
  } finally {
    loading.value = false
  }
}

onMounted(() => fetchCustomers())

function onSearch() {
  clearTimeout(searchTimer)
  searchTimer = setTimeout(() => fetchCustomers(1), 400)
}

async function deleteCustomer(c) {
  const ok = await confirm?.open({
    title:   'Delete Customer?',
    message: `"${c.name}" and all their data will be permanently deleted.`,
  })
  if (!ok) return
  try {
    await api.delete(`/api/admin/customers/${c.id}`)
    toast?.show('Customer deleted.')
    await fetchCustomers(meta.value.current_page)
  } catch (err) {
    toast?.show(err.response?.data?.message ?? 'Failed to delete.', 'error')
  }
}

function avatarLetter(name) {
  return name?.charAt(0)?.toUpperCase() ?? '?'
}

const avatarColors = [
  '#1565C0','#0369A1','#7C3AED','#15803D',
  '#B45309','#BE123C','#0891B2','#9333EA',
]
function avatarColor(id) {
  return avatarColors[id % avatarColors.length]
}
</script>

<template>
  <div>
    <!-- Header -->
    <div class="mb-6 flex items-center justify-between">
      <div>
        <p class="text-xs font-semibold uppercase tracking-widest mb-1" style="color:#1565C0;">Users</p>
        <h2 class="text-2xl font-bold text-gray-900">Customers</h2>
      </div>
      <div class="text-xs font-semibold px-3 py-1.5 rounded-lg" style="background:#EFF6FF;color:#1565C0;">
        {{ meta.total }} total
      </div>
    </div>

    <!-- Search -->
    <div class="bg-white rounded-2xl border border-gray-100 p-4 mb-5 shadow-sm">
      <div class="relative max-w-sm">
        <svg class="absolute left-3 top-1/2 -translate-y-1/2 w-4 h-4 text-gray-400 pointer-events-none"
          fill="none" viewBox="0 0 24 24" stroke-width="1.8" stroke="currentColor">
          <path stroke-linecap="round" stroke-linejoin="round" d="m21 21-5.197-5.197m0 0A7.5 7.5 0 1 0 5.196 5.196a7.5 7.5 0 0 0 10.607 10.607Z" />
        </svg>
        <input
          v-model="search"
          @input="onSearch"
          type="text"
          placeholder="Search by name or email…"
          class="w-full pl-9 pr-4 py-2.5 text-sm border border-gray-200 rounded-xl focus:outline-none focus:ring-2 focus:ring-blue-600/20 focus:border-blue-400 transition-all bg-gray-50 focus:bg-white"
        />
      </div>
    </div>

    <!-- Loading -->
    <div v-if="loading" class="flex items-center justify-center py-20">
      <div class="flex flex-col items-center gap-3">
        <div class="w-10 h-10 rounded-full border-2 animate-spin" style="border-color:#1565C0;border-top-color:transparent;"></div>
        <p class="text-sm text-gray-400">Loading customers…</p>
      </div>
    </div>

    <template v-else>
      <!-- Table -->
      <div class="bg-white rounded-2xl border border-gray-100 overflow-hidden shadow-sm mb-5">
        <table class="w-full text-sm">
          <thead>
            <tr class="border-b border-gray-100" style="background:#F8FAFF;">
              <th class="text-left px-5 py-4 text-xs font-semibold text-gray-400 uppercase tracking-wider">Customer</th>
              <th class="text-left px-4 py-4 text-xs font-semibold text-gray-400 uppercase tracking-wider">Email</th>
              <th class="text-left px-4 py-4 text-xs font-semibold text-gray-400 uppercase tracking-wider">Joined</th>
              <th class="px-4 py-4 w-16"></th>
            </tr>
          </thead>
          <tbody class="divide-y divide-gray-50">
            <tr v-for="c in customers" :key="c.id" class="hover:bg-blue-50/20 transition-colors group">
              <td class="px-5 py-3.5">
                <div class="flex items-center gap-3">
                  <div
                    class="w-9 h-9 rounded-xl flex items-center justify-center text-white text-sm font-bold flex-shrink-0"
                    :style="`background:${avatarColor(c.id)};`"
                  >
                    {{ avatarLetter(c.name) }}
                  </div>
                  <span class="font-semibold text-gray-900">{{ c.name }}</span>
                </div>
              </td>
              <td class="px-4 py-3.5 text-gray-500 text-sm">{{ c.email }}</td>
              <td class="px-4 py-3.5 text-gray-400 text-xs">{{ c.created_at }}</td>
              <td class="px-4 py-3.5">
                <button
                  @click.stop="deleteCustomer(c)"
                  class="p-1.5 rounded-lg text-gray-300 hover:text-red-600 hover:bg-red-50 transition-colors opacity-0 group-hover:opacity-100"
                  title="Delete"
                >
                  <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" d="m14.74 9-.346 9m-4.788 0L9.26 9m9.968-3.21c.342.052.682.107 1.022.166m-1.022-.165L18.16 19.673a2.25 2.25 0 0 1-2.244 2.077H8.084a2.25 2.25 0 0 1-2.244-2.077L4.772 5.79m14.456 0a48.108 48.108 0 0 0-3.478-.397m-12 .562c.34-.059.68-.114 1.022-.165m0 0a48.11 48.11 0 0 1 3.478-.397m7.5 0v-.916c0-1.18-.91-2.164-2.09-2.201a51.964 51.964 0 0 0-3.32 0c-1.18.037-2.09 1.022-2.09 2.201v.916m7.5 0a48.667 48.667 0 0 0-7.5 0" />
                  </svg>
                </button>
              </td>
            </tr>
          </tbody>
        </table>

        <!-- Empty -->
        <div v-if="customers.length === 0" class="text-center py-16">
          <svg class="w-10 h-10 text-gray-200 mx-auto mb-3" fill="none" viewBox="0 0 24 24" stroke-width="1" stroke="currentColor">
            <path stroke-linecap="round" stroke-linejoin="round" d="M15 19.128a9.38 9.38 0 0 0 2.625.372 9.337 9.337 0 0 0 4.121-.952 4.125 4.125 0 0 0-7.533-2.493M15 19.128v-.003c0-1.113-.285-2.16-.786-3.07M15 19.128v.106A12.318 12.318 0 0 1 8.624 21c-2.331 0-4.512-.645-6.374-1.766l-.001-.109a6.375 6.375 0 0 1 11.964-3.07M12 6.375a3.375 3.375 0 1 1-6.75 0 3.375 3.375 0 0 1 6.75 0Zm8.25 2.25a2.625 2.625 0 1 1-5.25 0 2.625 2.625 0 0 1 5.25 0Z" />
          </svg>
          <p class="text-sm text-gray-400">No customers found</p>
        </div>
      </div>

      <!-- Pagination -->
      <div v-if="meta.last_page > 1" class="flex items-center justify-between">
        <p class="text-xs text-gray-400">Page {{ meta.current_page }} of {{ meta.last_page }}</p>
        <div class="flex items-center gap-1">
          <button
            @click="fetchCustomers(meta.current_page - 1)"
            :disabled="meta.current_page === 1"
            class="w-8 h-8 rounded-lg flex items-center justify-center text-sm border border-gray-200 transition-colors"
            :class="meta.current_page === 1 ? 'text-gray-200 cursor-not-allowed' : 'text-gray-600 hover:bg-gray-50'"
          >
            <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
              <path stroke-linecap="round" stroke-linejoin="round" d="M15.75 19.5 8.25 12l7.5-7.5" />
            </svg>
          </button>
          <button
            v-for="p in meta.last_page" :key="p"
            @click="fetchCustomers(p)"
            class="w-8 h-8 rounded-lg flex items-center justify-center text-xs font-semibold transition-colors border"
            :style="meta.current_page === p ? 'background:#1565C0;color:white;border-color:#1565C0;' : 'border-color:#e5e7eb;color:#6b7280;'"
          >
            {{ p }}
          </button>
          <button
            @click="fetchCustomers(meta.current_page + 1)"
            :disabled="meta.current_page === meta.last_page"
            class="w-8 h-8 rounded-lg flex items-center justify-center text-sm border border-gray-200 transition-colors"
            :class="meta.current_page === meta.last_page ? 'text-gray-200 cursor-not-allowed' : 'text-gray-600 hover:bg-gray-50'"
          >
            <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
              <path stroke-linecap="round" stroke-linejoin="round" d="m8.25 4.5 7.5 7.5-7.5 7.5" />
            </svg>
          </button>
        </div>
      </div>
    </template>
  </div>
</template>