<script setup>
import { ref, onMounted, inject } from 'vue'
import api from '@/api/axios'

const toast = inject('toast')

const loading      = ref(true)
const orders       = ref([])
const meta         = ref({ current_page: 1, last_page: 1, total: 0 })
const search       = ref('')
const statusFilter = ref('')
const selectedOrder = ref(null)
const showDetail    = ref(false)
const updatingStatus = ref(false)
let   searchTimer  = null

const STATUSES = [
  { value: 'pending',    label: 'Pending',    color: 'bg-amber-100 text-amber-700'   },
  { value: 'processing', label: 'Processing', color: 'bg-blue-100 text-blue-700'     },
  { value: 'shipped',    label: 'Shipped',    color: 'bg-violet-100 text-violet-700' },
  { value: 'delivered',  label: 'Delivered',  color: 'bg-green-100 text-green-700'   },
  { value: 'cancelled',  label: 'Cancelled',  color: 'bg-red-100 text-red-700'       },
]

function statusStyle(status) {
  return STATUSES.find(s => s.value === status)?.color ?? 'bg-gray-100 text-gray-600'
}

function paymentStyle(status) {
  return {
    paid:     'bg-green-100 text-green-700',
    pending:  'bg-amber-100 text-amber-700',
    failed:   'bg-red-100 text-red-700',
    refunded: 'bg-gray-100 text-gray-600',
  }[status] ?? 'bg-gray-100 text-gray-600'
}

async function fetchOrders(page = 1) {
  loading.value = true
  try {
    const { data } = await api.get('/api/admin/orders', {
      params: { search: search.value, status: statusFilter.value, page },
    })
    orders.value = data.data
    meta.value = {
      current_page: data.current_page,
      last_page:    data.last_page,
      total:        data.total,
    }
  } catch {
    toast?.show('Failed to load orders.', 'error')
  } finally {
    loading.value = false
  }
}

onMounted(() => fetchOrders())

function onSearch() {
  clearTimeout(searchTimer)
  searchTimer = setTimeout(() => fetchOrders(1), 400)
}

async function openDetail(order) {
  try {
    const { data } = await api.get(`/api/admin/orders/${order.id}`)
    selectedOrder.value = data
    showDetail.value    = true
  } catch {
    toast?.show('Failed to load order details.', 'error')
  }
}

async function updateStatus(orderId, status) {
  updatingStatus.value = true
  try {
    await api.patch(`/api/admin/orders/${orderId}/status`, { status })
    toast?.show('Order status updated.')
    selectedOrder.value.status = status
    await fetchOrders(meta.value.current_page)
  } catch {
    toast?.show('Failed to update status.', 'error')
  } finally {
    updatingStatus.value = false
  }
}
</script>

<template>
  <div>
    <!-- Header -->
    <div class="mb-6 flex items-center justify-between">
      <div>
        <p class="text-xs font-semibold uppercase tracking-widest mb-1" style="color:#1565C0;">Customers</p>
        <h2 class="text-2xl font-bold text-gray-900">Orders</h2>
      </div>
      <div class="text-xs font-semibold px-3 py-1.5 rounded-lg" style="background:#EFF6FF;color:#1565C0;">
        {{ meta.total }} total
      </div>
    </div>

    <!-- Filters -->
    <div class="bg-white rounded-2xl border border-gray-100 p-4 mb-5 shadow-sm">
      <div class="flex flex-wrap gap-3 items-center">
        <div class="relative flex-1 min-w-[200px]">
          <svg class="absolute left-3 top-1/2 -translate-y-1/2 w-4 h-4 text-gray-400 pointer-events-none"
            fill="none" viewBox="0 0 24 24" stroke-width="1.8" stroke="currentColor">
            <path stroke-linecap="round" stroke-linejoin="round" d="m21 21-5.197-5.197m0 0A7.5 7.5 0 1 0 5.196 5.196a7.5 7.5 0 0 0 10.607 10.607Z" />
          </svg>
          <input
            v-model="search" @input="onSearch"
            type="text" placeholder="Search order # or customer…"
            class="w-full pl-9 pr-4 py-2.5 text-sm border border-gray-200 rounded-xl focus:outline-none focus:ring-2 focus:ring-blue-600/20 focus:border-blue-400 transition-all bg-gray-50 focus:bg-white"
          />
        </div>
        <select v-model="statusFilter" @change="fetchOrders(1)"
          class="px-3 py-2.5 text-sm border border-gray-200 rounded-xl focus:outline-none focus:ring-2 focus:ring-blue-600/20 bg-gray-50 transition-all">
          <option value="">All Statuses</option>
          <option v-for="s in STATUSES" :key="s.value" :value="s.value">{{ s.label }}</option>
        </select>
      </div>
    </div>

    <!-- Empty state — no orders yet -->
    <div v-if="!loading && orders.length === 0 && meta.total === 0"
      class="bg-white rounded-2xl border border-gray-100 shadow-sm p-16 text-center">
      <div class="w-16 h-16 rounded-2xl mx-auto mb-4 flex items-center justify-center" style="background:#EFF6FF;">
        <svg class="w-8 h-8" style="color:#1565C0;" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
          <path stroke-linecap="round" stroke-linejoin="round" d="M9 12h3.75M9 15h3.75M9 18h3.75m3 .75H18a2.25 2.25 0 0 0 2.25-2.25V6.108c0-1.135-.845-2.098-1.976-2.192a48.424 48.424 0 0 0-1.123-.08m-5.801 0c-.065.21-.1.433-.1.664 0 .414.336.75.75.75h4.5a.75.75 0 0 0 .75-.75 2.25 2.25 0 0 0-.1-.664m-5.8 0A2.251 2.251 0 0 1 13.5 2.25H15c1.012 0 1.867.668 2.15 1.586m-5.8 0c-.376.023-.75.05-1.124.08C9.095 4.01 8.25 4.973 8.25 6.108V8.25m0 0H4.875c-.621 0-1.125.504-1.125 1.125v11.25c0 .621.504 1.125 1.125 1.125h9.75c.621 0 1.125-.504 1.125-1.125V9.375c0-.621-.504-1.125-1.125-1.125H8.25Z" />
        </svg>
      </div>
      <h3 class="text-base font-semibold text-gray-900 mb-2">No Orders Yet</h3>
      <p class="text-sm text-gray-400 max-w-sm mx-auto">Orders will appear here once customers start placing them through the storefront.</p>
    </div>

    <!-- Loading -->
    <div v-else-if="loading" class="flex items-center justify-center py-20">
      <div class="flex flex-col items-center gap-3">
        <div class="w-10 h-10 rounded-full border-2 animate-spin" style="border-color:#1565C0;border-top-color:transparent;"></div>
        <p class="text-sm text-gray-400">Loading orders…</p>
      </div>
    </div>

    <template v-else>
      <!-- Table -->
      <div class="bg-white rounded-2xl border border-gray-100 overflow-hidden shadow-sm mb-5">
        <div class="overflow-x-auto">
          <table class="w-full text-sm">
            <thead>
              <tr class="border-b border-gray-100" style="background:#F8FAFF;">
                <th class="text-left px-5 py-4 text-xs font-semibold text-gray-400 uppercase tracking-wider">Order</th>
                <th class="text-left px-4 py-4 text-xs font-semibold text-gray-400 uppercase tracking-wider">Customer</th>
                <th class="text-left px-4 py-4 text-xs font-semibold text-gray-400 uppercase tracking-wider">Status</th>
                <th class="text-left px-4 py-4 text-xs font-semibold text-gray-400 uppercase tracking-wider">Payment</th>
                <th class="text-left px-4 py-4 text-xs font-semibold text-gray-400 uppercase tracking-wider">Items</th>
                <th class="text-left px-4 py-4 text-xs font-semibold text-gray-400 uppercase tracking-wider">Total</th>
                <th class="text-left px-4 py-4 text-xs font-semibold text-gray-400 uppercase tracking-wider">Date</th>
                <th class="px-4 py-4 w-16"></th>
              </tr>
            </thead>
            <tbody class="divide-y divide-gray-50">
              <tr v-for="o in orders" :key="o.id" class="hover:bg-blue-50/20 transition-colors group cursor-pointer" @click="openDetail(o)">
                <td class="px-5 py-3.5">
                  <span class="font-mono text-xs font-semibold text-gray-700">{{ o.order_number }}</span>
                </td>
                <td class="px-4 py-3.5">
                  <p class="font-medium text-gray-900 text-sm">{{ o.customer_name }}</p>
                  <p class="text-xs text-gray-400">{{ o.customer_email }}</p>
                </td>
                <td class="px-4 py-3.5">
                  <span class="inline-flex px-2 py-0.5 rounded-lg text-xs font-semibold capitalize" :class="statusStyle(o.status)">
                    {{ o.status }}
                  </span>
                </td>
                <td class="px-4 py-3.5">
                  <span class="inline-flex px-2 py-0.5 rounded-lg text-xs font-semibold capitalize" :class="paymentStyle(o.payment_status)">
                    {{ o.payment_status }}
                  </span>
                </td>
                <td class="px-4 py-3.5 text-gray-600 text-sm">{{ o.items_count }} item{{ o.items_count !== 1 ? 's' : '' }}</td>
                <td class="px-4 py-3.5 font-semibold text-gray-900">
                  {{ Number(o.total).toFixed(2) }} <span class="text-xs font-normal text-gray-400">JOD</span>
                </td>
                <td class="px-4 py-3.5 text-gray-400 text-xs">{{ o.created_at }}</td>
                <td class="px-4 py-3.5">
                  <button class="p-1.5 rounded-lg text-gray-300 hover:text-blue-600 hover:bg-blue-50 transition-colors opacity-0 group-hover:opacity-100">
                    <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                      <path stroke-linecap="round" stroke-linejoin="round" d="M2.036 12.322a1.012 1.012 0 0 1 0-.639C3.423 7.51 7.36 4.5 12 4.5c4.638 0 8.573 3.007 9.963 7.178.07.207.07.431 0 .639C20.577 16.49 16.64 19.5 12 19.5c-4.638 0-8.573-3.007-9.963-7.178Z" />
                      <path stroke-linecap="round" stroke-linejoin="round" d="M15 12a3 3 0 1 1-6 0 3 3 0 0 1 6 0Z" />
                    </svg>
                  </button>
                </td>
              </tr>
            </tbody>
          </table>

          <div v-if="orders.length === 0" class="text-center py-12">
            <p class="text-sm text-gray-400">No orders match your filters</p>
          </div>
        </div>
      </div>

      <!-- Pagination -->
      <div v-if="meta.last_page > 1" class="flex items-center justify-between">
        <p class="text-xs text-gray-400">Page {{ meta.current_page }} of {{ meta.last_page }}</p>
        <div class="flex items-center gap-1">
          <button @click="fetchOrders(meta.current_page - 1)" :disabled="meta.current_page === 1"
            class="w-8 h-8 rounded-lg flex items-center justify-center text-sm border border-gray-200 transition-colors"
            :class="meta.current_page === 1 ? 'text-gray-200 cursor-not-allowed' : 'text-gray-600 hover:bg-gray-50'">
            <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M15.75 19.5 8.25 12l7.5-7.5" /></svg>
          </button>
          <button v-for="p in meta.last_page" :key="p" @click="fetchOrders(p)"
            class="w-8 h-8 rounded-lg flex items-center justify-center text-xs font-semibold transition-colors border"
            :style="meta.current_page === p ? 'background:#1565C0;color:white;border-color:#1565C0;' : 'border-color:#e5e7eb;color:#6b7280;'">
            {{ p }}
          </button>
          <button @click="fetchOrders(meta.current_page + 1)" :disabled="meta.current_page === meta.last_page"
            class="w-8 h-8 rounded-lg flex items-center justify-center text-sm border border-gray-200 transition-colors"
            :class="meta.current_page === meta.last_page ? 'text-gray-200 cursor-not-allowed' : 'text-gray-600 hover:bg-gray-50'">
            <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="m8.25 4.5 7.5 7.5-7.5 7.5" /></svg>
          </button>
        </div>
      </div>
    </template>

    <!-- Order Detail Modal -->
    <teleport to="body">
      <div v-if="showDetail" class="fixed inset-0 z-50 flex items-center justify-center p-4" @click.self="showDetail=false">
        <div class="absolute inset-0" style="background:rgba(13,27,42,0.6);backdrop-filter:blur(4px);"></div>
        <div class="relative bg-white rounded-2xl shadow-2xl w-full max-w-2xl max-h-[90vh] overflow-y-auto" @click.stop>

          <!-- Header -->
          <div class="flex items-center justify-between px-6 py-5 border-b border-gray-100 sticky top-0 bg-white z-10">
            <div>
              <h3 class="text-base font-bold text-gray-900">Order {{ selectedOrder?.order_number }}</h3>
              <p class="text-xs text-gray-400 mt-0.5">{{ selectedOrder?.created_at }}</p>
            </div>
            <button @click="showDetail=false" class="p-1.5 rounded-lg text-gray-400 hover:bg-gray-100 transition-colors">
              <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M6 18 18 6M6 6l12 12" /></svg>
            </button>
          </div>

          <div class="p-6 space-y-5">
            <!-- Customer info -->
            <div class="grid grid-cols-2 gap-4">
              <div class="bg-gray-50 rounded-xl p-4">
                <p class="text-xs font-semibold text-gray-400 uppercase tracking-wide mb-2">Customer</p>
                <p class="text-sm font-semibold text-gray-900">{{ selectedOrder?.customer_name }}</p>
                <p class="text-xs text-gray-500">{{ selectedOrder?.customer_email }}</p>
                <p v-if="selectedOrder?.customer_phone" class="text-xs text-gray-500">{{ selectedOrder?.customer_phone }}</p>
              </div>
              <div class="bg-gray-50 rounded-xl p-4">
                <p class="text-xs font-semibold text-gray-400 uppercase tracking-wide mb-2">Shipping Address</p>
                <p class="text-sm text-gray-700">{{ selectedOrder?.shipping_address ?? 'Not provided' }}</p>
              </div>
            </div>

            <!-- Status update -->
            <div class="bg-gray-50 rounded-xl p-4">
              <p class="text-xs font-semibold text-gray-400 uppercase tracking-wide mb-3">Update Status</p>
              <div class="flex flex-wrap gap-2">
                <button
                  v-for="s in STATUSES" :key="s.value"
                  @click="updateStatus(selectedOrder.id, s.value)"
                  :disabled="updatingStatus || selectedOrder?.status === s.value"
                  class="px-3 py-1.5 rounded-lg text-xs font-semibold transition-all border"
                  :class="[
                    selectedOrder?.status === s.value ? s.color + ' border-transparent' : 'border-gray-200 text-gray-600 hover:bg-gray-100',
                    updatingStatus ? 'opacity-50 cursor-not-allowed' : ''
                  ]"
                >
                  {{ s.label }}
                </button>
              </div>
            </div>

            <!-- Order items -->
            <div>
              <p class="text-xs font-semibold text-gray-400 uppercase tracking-wide mb-3">Items</p>
              <div class="bg-white border border-gray-100 rounded-xl overflow-hidden">
                <table class="w-full text-sm">
                  <thead>
                    <tr style="background:#F8FAFF;" class="border-b border-gray-100">
                      <th class="text-left px-4 py-3 text-xs font-semibold text-gray-400 uppercase">Product</th>
                      <th class="text-left px-4 py-3 text-xs font-semibold text-gray-400 uppercase">Variant</th>
                      <th class="text-right px-4 py-3 text-xs font-semibold text-gray-400 uppercase">Price</th>
                      <th class="text-right px-4 py-3 text-xs font-semibold text-gray-400 uppercase">Qty</th>
                      <th class="text-right px-4 py-3 text-xs font-semibold text-gray-400 uppercase">Subtotal</th>
                    </tr>
                  </thead>
                  <tbody class="divide-y divide-gray-50">
                    <tr v-for="item in selectedOrder?.items" :key="item.id">
                      <td class="px-4 py-3">
                        <p class="font-medium text-gray-900">{{ item.product_name }}</p>
                        <p class="text-xs text-gray-400">{{ item.product_brand }}</p>
                      </td>
                      <td class="px-4 py-3 text-xs text-gray-500">{{ item.variant_label ?? '—' }}</td>
                      <td class="px-4 py-3 text-right text-sm text-gray-700">{{ Number(item.price).toFixed(2) }}</td>
                      <td class="px-4 py-3 text-right text-sm text-gray-700">{{ item.quantity }}</td>
                      <td class="px-4 py-3 text-right font-semibold text-gray-900">{{ Number(item.subtotal).toFixed(2) }}</td>
                    </tr>
                  </tbody>
                </table>
              </div>
            </div>

            <!-- Totals -->
            <div class="bg-gray-50 rounded-xl p-4 space-y-2">
              <div class="flex justify-between text-sm text-gray-600">
                <span>Subtotal</span><span>{{ Number(selectedOrder?.subtotal).toFixed(2) }} JOD</span>
              </div>
              <div class="flex justify-between text-sm text-gray-600">
                <span>Shipping</span><span>{{ Number(selectedOrder?.shipping_cost).toFixed(2) }} JOD</span>
              </div>
              <div v-if="selectedOrder?.discount > 0" class="flex justify-between text-sm text-green-600">
                <span>Discount</span><span>-{{ Number(selectedOrder?.discount).toFixed(2) }} JOD</span>
              </div>
              <div class="flex justify-between text-base font-bold text-gray-900 pt-2 border-t border-gray-200">
                <span>Total</span><span>{{ Number(selectedOrder?.total).toFixed(2) }} JOD</span>
              </div>
            </div>
          </div>
        </div>
      </div>
    </teleport>
  </div>
</template>