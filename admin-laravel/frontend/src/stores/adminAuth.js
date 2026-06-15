import { defineStore } from 'pinia'
import { ref, computed } from 'vue'
import api from '@/api/axios'

export const useAdminAuthStore = defineStore('adminAuth', () => {
  const token = ref(localStorage.getItem('admin_token') ?? null)
  const user  = ref(JSON.parse(localStorage.getItem('admin_user') ?? 'null'))

  const isAuthenticated = computed(() => !!token.value)

  async function login(email, password) {
    try {
      const { data } = await api.post('/api/admin/login', { email, password })
      _persist(data.token, data.user)
      return { success: true }
    } catch (err) {
      const message = err.response?.data?.message ?? 'Login failed'
      return { success: false, message }
    }
  }

  async function logout() {
    try { await api.post('/api/admin/logout') } catch {}
    _clear()
  }

  function _persist(t, u) {
    token.value = t
    user.value  = u
    localStorage.setItem('admin_token', t)
    localStorage.setItem('admin_user', JSON.stringify(u))
  }

  function _clear() {
    token.value = null
    user.value  = null
    localStorage.removeItem('admin_token')
    localStorage.removeItem('admin_user')
  }

  return { token, user, isAuthenticated, login, logout }
})